<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceType;
use App\Services\WebexSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'driver', 'porter', 'porters', 'vehicle']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $drivers = User::where('role', 'driver')->where('status', 'active')->get();
        $porters = User::where('role', 'porter')->where('status', 'active')->get();
        $vehicles = Vehicle::where('status', 'available')->get();
        $services = ServiceType::get();
        $companies = \App\Models\Company::orderBy('name')->get();
        $leadSources = \App\Models\LeadSource::active()->ordered()->get();

        return view('admin.bookings.create', compact('customers', 'drivers', 'porters', 'vehicles', 'services', 'companies', 'leadSources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug logging
        \Log::info('BookingController@store called', [
            'user_id' => auth()->id(),
            'status' => $request->status,
            'customer_id' => $request->customer_id,
        ]);
        
        // For not_converted status, allow any date (inquiry can be from past)
        $bookingDateRule = $request->status === 'not_converted' 
            ? 'required|date' 
            : 'required|date|after_or_equal:today';
        
        // For not_converted, manual_amount can be 0 (no quote given)
        $manualAmountRule = $request->status === 'not_converted'
            ? 'nullable|numeric|min:0'
            : 'required|numeric|min:0';
        
        // For not_converted, booking_type is not critical
        $bookingTypeRule = $request->status === 'not_converted'
            ? 'nullable|in:fixed,hourly'
            : 'required|in:fixed,hourly';
            
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_date' => $bookingDateRule,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'pickup_address' => 'required|string|max:1000',
            'delivery_address' => 'required|string|max:1000',
            'via_address' => 'nullable|string|max:1000',
            'pickup_postcode' => 'nullable|string|max:10',
            'delivery_postcode' => 'nullable|string|max:10',
            'job_description' => 'required|string|max:2000',
            'special_instructions' => 'nullable|string|max:2000',
            'driver_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'porter_ids' => 'nullable|array',
            'porter_ids.*' => 'nullable|exists:users,id',
            'manual_amount' => $manualAmountRule,
            'is_company_booking' => 'nullable|boolean',
            'company_id' => 'nullable|exists:companies,id',
            'company_commission_amount' => 'nullable|numeric|min:0',
            'extra_hours' => 'nullable|integer|min:0',
            'extra_hours_rate' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:service_types,id',
            'services.*.qty' => 'nullable|integer|min:1',
            // New enhanced fields validation
            'source' => 'nullable|string|max:255',
            'booking_type' => $bookingTypeRule,
            'booked_hours' => 'nullable|integer|min:1',
            'helpers_count' => 'nullable|integer|min:1',
            'deposit' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'details_shared_with_customer' => 'nullable|string|max:2000',
            'total_fare' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,card,bank_transfer',
            'discount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
            'status' => 'nullable|in:pending,confirmed,in_progress,completed,cancelled,not_converted',
            'lead_source' => 'nullable|in:website,phone,email,whatsapp,facebook,instagram,referral,walk_in,other',
            // Survey validation
            'survey_type' => 'nullable|in:video_call,video_recording,list',
            'schedule_date' => 'nullable|date',
            'schedule_time' => 'nullable|date_format:H:i,H:i:s',
            'survey_status' => 'nullable|in:done,pending,not_agreed',
            'survey_list_content' => 'nullable|string|max:5000',
            'survey_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max
            'survey_notes' => 'nullable|string|max:2000',
        ]);

        // Generate sequential booking reference starting from TBR-001000
        // Get all bookings with TBR- prefix and extract numeric references
        $allBookings = Booking::where('booking_reference', 'like', 'TBR-%')
            ->pluck('booking_reference')
            ->toArray();
        
        $maxNumber = 999; // Start from 1000, so max should be 999 to get 1000
        
        foreach ($allBookings as $ref) {
            if (preg_match('/TBR-(\d+)/', $ref, $matches)) {
                $number = (int) $matches[1];
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
        }
        
        // Next number will be maxNumber + 1, starting from 1000
        $nextNumber = $maxNumber + 1;
        $bookingReference = 'TBR-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // Calculate total fare based on booking type
        $totalFare = 0;
        if ($request->booking_type === 'fixed') {
            $totalFare = $request->total_fare ?? 0;
        } elseif ($request->booking_type === 'hourly' && $request->hourly_rate && $request->booked_hours) {
            $totalFare = $request->hourly_rate * $request->booked_hours;
        }

        $booking = Booking::create([
            'customer_id' => $request->customer_id,
            'created_by' => auth()->id(),
            'driver_id' => $request->driver_id,
            'porter_id' => $request->porter_ids && is_array($request->porter_ids) ? ($request->porter_ids[0] ?? null) : null,
            'porter_ids' => $request->porter_ids,
            'vehicle_id' => $request->vehicle_id,
            'booking_reference' => $bookingReference,
            'booking_date' => $request->booking_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'estimated_hours' => $request->estimated_hours,
            'pickup_address' => $request->pickup_address,
            'delivery_address' => $request->delivery_address,
            'via_address' => $request->via_address,
            'pickup_postcode' => $request->pickup_postcode,
            'delivery_postcode' => $request->delivery_postcode,
            'job_description' => $request->job_description,
            'special_instructions' => $request->special_instructions,
            'status' => $request->status ?? (($request->driver_id && $request->porter_ids && $request->vehicle_id) ? 'confirmed' : 'pending'),
            'total_amount' => $request->manual_amount ?? 0,
            'is_manual_amount' => true,
            'manual_amount' => $request->manual_amount ?? 0,
            'is_company_booking' => $request->boolean('is_company_booking'),
            'company_id' => $request->company_id,
            'company_commission_amount' => $request->company_commission_amount,
            'extra_hours' => $request->extra_hours,
            'extra_hours_rate' => $request->extra_hours_rate,
            // New enhanced fields
            'source' => $request->source,
            'booking_type' => $request->booking_type ?? 'fixed',
            'booked_hours' => $request->booked_hours,
            'helpers_count' => $request->helpers_count ?? 1,
            'deposit' => $request->deposit ?? 0,
            'hourly_rate' => $request->hourly_rate,
            'details_shared_with_customer' => $request->details_shared_with_customer,
            'total_fare' => $totalFare,
            'payment_method' => $request->payment_method,
            'discount' => $request->discount ?? 0,
            'discount_reason' => $request->discount_reason,
            'notes' => $request->notes,
            'review_link' => $request->review_link,
            'week_start' => $request->week_start,
            'lead_source' => $request->lead_source ?? 'phone',
        ]);

        // Attach multiple porters if provided
        if ($request->filled('porter_ids')) {
            $booking->porters()->sync($request->porter_ids);
        }

        // Attach services if provided
        if ($request->filled('services')) {
            foreach ($request->services as $serviceData) {
                if (isset($serviceData['service_id'])) {
                    $booking->services()->attach($serviceData['service_id'], [
                        'quantity' => 1,
                        'unit_rate' => 0,
                        'total_amount' => 0,
                    ]);
                }
            }
        }

        // Calculate company commission if applicable
        if ($booking->is_company_booking && $booking->company_commission_rate) {
            $booking->company_commission_amount = $booking->calculateCompanyCommission();
        }

        // Calculate extra hours amount if applicable
        if ($booking->extra_hours && $booking->extra_hours_rate) {
            $booking->extra_hours_amount = $booking->calculateExtraHoursAmount();
        }

        // Calculate remaining amount
        $booking->remaining_amount = $booking->calculateRemainingAmount();
        $booking->total_earning_inc_deposit = $booking->calculateTotalEarning();
        $booking->save();

        // Update vehicle status if assigned
        if ($request->vehicle_id) {
            Vehicle::find($request->vehicle_id)->update(['status' => 'in_use']);
        }

        // Handle Video Survey if enabled
        if ($request->filled('survey_type')) {
            $surveyData = [
                'booking_id' => $booking->id,
                'survey_type' => $request->survey_type,
                'schedule_date' => $request->schedule_date,
                'schedule_time' => $request->schedule_time,
                'status' => $request->survey_status,
                'list_content' => $request->survey_list_content,
                'notes' => $request->survey_notes,
            ];

            // Handle video file upload for video recording type
            if ($request->survey_type === 'video_recording' && $request->hasFile('survey_video')) {
                $videoPath = $request->file('survey_video')->store('surveys/videos', 'public');
                $surveyData['video_path'] = $videoPath;
            }

            $booking->survey()->create($surveyData);
        }

        // Send SMS notification if booking is confirmed
        if ($booking->status === 'confirmed') {
            try {
                $smsService = new WebexSmsService();
                $smsService->sendBookingConfirmation($booking);
            } catch (\Exception $e) {
                \Log::error('Failed to send booking confirmation SMS', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Add extra hours to a completed booking
     */
    public function addExtraHours(Request $request, Booking $booking)
    {
        $request->validate([
            'extra_hours' => 'required|integer|min:1',
            'extra_hours_rate' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'extra_hours' => $request->extra_hours,
            'extra_hours_rate' => $request->extra_hours_rate,
            'extra_hours_amount' => $request->extra_hours * $request->extra_hours_rate,
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Extra hours added successfully!');
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,not_converted',
            'notes' => 'nullable|string|max:1000',
        ]);

        $newStatus = $request->status;
        $notes = $request->notes;

        // Check if transition is valid
        // if (!$booking->canTransitionTo($newStatus)) {
        //     return redirect()->back()
        //         ->with('error', "Cannot transition from {$booking->status} to {$newStatus}");
        // }

        // Check requirements for specific statuses
        $requirements = $booking->getStatusRequirements($newStatus);
        $missingRequirements = [];

        foreach ($requirements as $field => $message) {
            if ($field === 'porter_ids') {
                if (!$booking->porter_ids || count($booking->porter_ids) === 0) {
                    $missingRequirements[] = $message;
                }
            } elseif (empty($booking->$field)) {
                $missingRequirements[] = $message;
            }
        }

        if (!empty($missingRequirements)) {
            return redirect()->back()
                ->with('error', 'Cannot update status. Missing requirements: ' . implode(', ', $missingRequirements));
        }

        // Update status and handle special cases
        $oldStatus = $booking->status;
        $booking->status = $newStatus;

        // Handle special status transitions
        if ($newStatus === 'in_progress' && $oldStatus === 'confirmed') {
            $booking->started_at = now();
        } elseif ($newStatus === 'completed' && $oldStatus === 'in_progress') {
            $booking->completed_at = now();
            // Calculate actual hours if started_at exists
            if ($booking->started_at) {
                $booking->actual_hours = $booking->started_at->diffInHours($booking->completed_at);
            }
        }

        $booking->save();

        // Send SMS notification if booking status changed to confirmed
        if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
            try {
                $smsService = new WebexSmsService();
                $smsService->sendBookingConfirmation($booking);
            } catch (\Exception $e) {
                \Log::error('Failed to send booking confirmation SMS', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Log status change (optional - you can create a status_logs table)
        // StatusLog::create([
        //     'booking_id' => $booking->id,
        //     'old_status' => $oldStatus,
        //     'new_status' => $newStatus,
        //     'changed_by' => auth()->id(),
        //     'notes' => $notes,
        // ]);

        $statusMessages = [
            'confirmed' => 'Booking confirmed successfully!',
            'in_progress' => 'Booking started successfully!',
            'completed' => 'Booking completed successfully!',
            'cancelled' => 'Booking cancelled successfully!',
            'pending' => 'Booking reactivated successfully!',
        ];

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', $statusMessages[$newStatus] ?? 'Status updated successfully!');
    }

    /**
     * Start a booking (set to in_progress)
     */
    public function start(Booking $booking)
    {
        if (!$booking->canStart()) {
            return redirect()->back()
                ->with('error', 'Cannot start booking. Please ensure driver, porter(s), and vehicle are assigned.');
        }

        $booking->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking started successfully!');
    }

    /**
     * Complete a booking
     */
    public function complete(Booking $booking)
    {
        if (!$booking->canComplete()) {
            return redirect()->back()
                ->with('error', 'Cannot complete booking. Booking must be in progress.');
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
            'actual_hours' => $booking->started_at ? $booking->started_at->diffInHours(now()) : null,
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking completed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['customer', 'driver', 'porter', 'porters', 'vehicle', 'services', 'invoice', 'survey']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show print view for booking
     */
    public function print(Booking $booking)
    {
        $booking->load(['customer', 'driver', 'porter', 'porters', 'vehicle', 'services', 'expenses.paidToUser']);

        return view('admin.bookings.print', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $customers = Customer::orderBy('name')->get();
        $drivers = User::where('role', 'driver')->where('status', 'active')->get();
        $porters = User::where('role', 'porter')->where('status', 'active')->get();
        $vehicles = Vehicle::whereIn('status', ['available', 'in_use'])->get();
        $services = ServiceType::get();
        $companies = \App\Models\Company::orderBy('name')->get();
        $leadSources = \App\Models\LeadSource::active()->ordered()->get();

        $booking->load(['services', 'porters']);

        return view('admin.bookings.create', compact('booking', 'customers', 'drivers', 'porters', 'vehicles', 'services', 'companies', 'leadSources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Log that we've reached the update method
        \Log::info('BookingController@update called', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'request_all' => $request->all(),
        ]);
        
        // For not_converted status, no date restrictions
        $bookingDateRule = $request->status === 'not_converted' 
            ? 'required|date' 
            : 'required|date';
            
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_date' => $bookingDateRule,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'pickup_address' => 'required|string|max:1000',
            'delivery_address' => 'required|string|max:1000',
            'via_address' => 'nullable|string|max:1000',
            'pickup_postcode' => 'nullable|string|max:10',
            'delivery_postcode' => 'nullable|string|max:10',
            'job_description' => 'required|string|max:2000',
            'special_instructions' => 'nullable|string|max:2000',
            'driver_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'porter_ids' => 'nullable|array',
            'porter_ids.*' => 'nullable|exists:users,id',
            'manual_amount' => 'nullable|numeric|min:0',
            'is_company_booking' => 'nullable|boolean',
            'company_id' => 'nullable|exists:companies,id',
            'company_commission_amount' => 'nullable|numeric|min:0',
            'extra_hours' => 'nullable|integer|min:0',
            'extra_hours_rate' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:service_types,id',
            // New enhanced fields validation
            'source' => 'nullable|string|max:255',
            'booking_type' => 'required|in:fixed,hourly',
            'booked_hours' => 'nullable|integer|min:1',
            'helpers_count' => 'nullable|integer|min:1',
            'deposit' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'details_shared_with_customer' => 'nullable|string|max:2000',
            'total_fare' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,card,bank_transfer',
            'discount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
            // Survey validation
            'survey_type' => 'nullable|in:video_call,video_recording,list',
            'schedule_date' => 'nullable|date',
            'schedule_time' => 'nullable|date_format:H:i,H:i:s',
            'survey_status' => 'nullable|in:done,pending,not_agreed',
            'survey_list_content' => 'nullable|string|max:5000',
            'survey_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max
            'survey_notes' => 'nullable|string|max:2000',
        ]);

        // Store old status and vehicle ID to update its status
        $oldStatus = $booking->status;
        $oldVehicleId = $booking->vehicle_id;

        // Calculate total fare based on booking type
        $totalFare = 0;
        if ($request->booking_type === 'fixed') {
            $totalFare = $request->total_fare ?? 0;
        } elseif ($request->booking_type === 'hourly' && $request->hourly_rate && $request->booked_hours) {
            $totalFare = $request->hourly_rate * $request->booked_hours;
        }

        $booking->update([
            'customer_id' => $request->customer_id,
            'driver_id' => $request->driver_id,
            'porter_id' => $request->porter_ids && is_array($request->porter_ids) ? ($request->porter_ids[0] ?? null) : null,
            'porter_ids' => $request->porter_ids,
            'vehicle_id' => $request->vehicle_id,
            'booking_date' => $request->booking_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'pickup_address' => $request->pickup_address,
            'delivery_address' => $request->delivery_address,
            'via_address' => $request->via_address,
            'pickup_postcode' => $request->pickup_postcode,
            'delivery_postcode' => $request->delivery_postcode,
            'job_description' => $request->job_description,
            'special_instructions' => $request->special_instructions,
            'total_amount' => $request->manual_amount ?? $booking->total_amount,
            'manual_amount' => $request->manual_amount ?? $booking->manual_amount,
            'is_company_booking' => $request->boolean('is_company_booking'),
            'company_id' => $request->company_id,
            'company_commission_amount' => $request->company_commission_amount,
            'extra_hours' => $request->extra_hours,
            'extra_hours_rate' => $request->extra_hours_rate,
            // New enhanced fields
            'source' => $request->source,
            'booking_type' => $request->booking_type ?? 'fixed',
            'booked_hours' => $request->booked_hours,
            'helpers_count' => $request->helpers_count ?? 1,
            'deposit' => $request->deposit ?? 0,
            'hourly_rate' => $request->hourly_rate,
            'details_shared_with_customer' => $request->details_shared_with_customer,
            'total_fare' => $totalFare,
            'payment_method' => $request->payment_method,
            'discount' => $request->discount ?? 0,
            'discount_reason' => $request->discount_reason,
            'notes' => $request->notes,
            'lead_source' => $request->lead_source ?? $booking->lead_source ?? 'phone',
            'status' => $request->status ?? $booking->status,
        ]);

        // Handle porters
        if ($request->filled('porter_ids')) {
            $booking->porters()->sync($request->porter_ids);
        } else {
            $booking->porters()->sync([]);
        }

        // Update services
        $booking->services()->detach();
        
        // Log services data for debugging
        \Log::info('Services data received:', [
            'services' => $request->services,
            'has_services' => $request->has('services'),
            'filled_services' => $request->filled('services'),
        ]);
        
        if ($request->filled('services') && is_array($request->services)) {
            foreach ($request->services as $serviceData) {
                if (isset($serviceData['service_id']) && !empty($serviceData['service_id'])) {
                    $service = ServiceType::find($serviceData['service_id']);
                    
                    if ($service) {
                        $booking->services()->attach($service->id, [
                            'quantity' => 1,
                            'unit_rate' => 0,
                            'total_amount' => 0,
                        ]);
                        \Log::info('Service attached:', ['service_id' => $service->id, 'service_name' => $service->name]);
                    }
                }
            }
        }

        // Calculate company commission if applicable
        if ($booking->is_company_booking && $booking->company_commission_amount) {
            // Commission is already set from the form
        }

        // Calculate extra hours amount if applicable
        if ($booking->extra_hours && $booking->extra_hours_rate) {
            $booking->extra_hours_amount = $booking->calculateExtraHoursAmount();
        }

        // Calculate remaining amount
        $booking->remaining_amount = $booking->calculateRemainingAmount();
        $booking->total_earning_inc_deposit = $booking->calculateTotalEarning();
        $booking->save();

        // Handle Video Survey
        if ($request->filled('survey_type')) {
            $surveyData = [
                'survey_type' => $request->survey_type,
                'schedule_date' => $request->schedule_date,
                'schedule_time' => $request->schedule_time,
                'status' => $request->survey_status,
                'list_content' => $request->survey_list_content,
                'notes' => $request->survey_notes,
            ];

            // Handle video file upload for video recording type
            if ($request->survey_type === 'video_recording' && $request->hasFile('survey_video')) {
                $videoPath = $request->file('survey_video')->store('surveys/videos', 'public');
                $surveyData['video_path'] = $videoPath;
            }

            // Update or create survey
            $booking->survey()->updateOrCreate(
                ['booking_id' => $booking->id],
                $surveyData
            );
        } else {
            // Delete survey if no survey type selected
            $booking->survey()->delete();
        }

        // Update vehicle statuses
        if ($oldVehicleId && $oldVehicleId != $request->vehicle_id) {
            Vehicle::find($oldVehicleId)->update(['status' => 'available']);
        }
        if ($request->vehicle_id) {
            Vehicle::find($request->vehicle_id)->update(['status' => 'in_use']);
        }

        // Send SMS notification if booking status changed to confirmed
        // Reload booking to get the latest status
        $booking->refresh();
        if ($booking->status === 'confirmed' && $oldStatus !== 'confirmed') {
            try {
                $smsService = new WebexSmsService();
                $smsService->sendBookingConfirmation($booking);
            } catch (\Exception $e) {
                \Log::error('Failed to send booking confirmation SMS', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Update vehicle status if it was assigned
        if ($booking->vehicle_id) {
            Vehicle::find($booking->vehicle_id)->update(['status' => 'available']);
        }

        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }
}

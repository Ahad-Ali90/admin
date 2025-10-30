<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceType;
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
            'services.*.service_id' => 'required_with:services|exists:service_types,id',
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
        ]);

        // Generate booking reference
        $bookingReference = 'TBR-' . strtoupper(Str::random(8));

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
        if (!$booking->canTransitionTo($newStatus)) {
            return redirect()->back()
                ->with('error', "Cannot transition from {$booking->status} to {$newStatus}");
        }

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
        $booking->load(['customer', 'driver', 'porter', 'porters', 'vehicle', 'services', 'invoice']);

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
            'is_company_booking' => 'nullable|boolean',
            'company_id' => 'nullable|exists:companies,id',
            'company_commission_amount' => 'nullable|numeric|min:0',
            'extra_hours' => 'nullable|integer|min:0',
            'extra_hours_rate' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required_with:services|exists:service_types,id',
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
        ]);

        // Store old vehicle ID to update its status
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
        if ($request->services) {
            foreach ($request->services as $serviceData) {
                $service = ServiceType::find($serviceData['service_id']);

                $booking->services()->attach($service->id, [
                    'quantity' => 1, // Default quantity
                    'unit_rate' => 0, // No pricing in service types anymore
                    'total_amount' => 0, // No pricing in service types anymore
                ]);
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

        // Update vehicle statuses
        if ($oldVehicleId && $oldVehicleId != $request->vehicle_id) {
            Vehicle::find($oldVehicleId)->update(['status' => 'available']);
        }
        if ($request->vehicle_id) {
            Vehicle::find($request->vehicle_id)->update(['status' => 'in_use']);
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

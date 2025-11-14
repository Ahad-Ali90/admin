<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadSourceController extends Controller
{
    /**
     * Display the management page
     */
    public function manage()
    {
        return view('admin.lead-sources.manage');
    }

    /**
     * Get all lead sources (JSON)
     */
    public function index()
    {
        $sources = LeadSource::ordered()->get()->map(function($source) {
            $bookingsCount = \App\Models\Booking::where('lead_source', $source->slug)->count();
            $source->bookings_count = $bookingsCount;
            return $source;
        });
        return response()->json($sources);
    }

    /**
     * Store a new lead source
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_sources,name',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['color'] = $validated['color'] ?? '#4f46e5';

        $source = LeadSource::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead source created successfully!',
            'data' => $source
        ], 201);
    }

    /**
     * Get a single lead source
     */
    public function show($id)
    {
        $source = LeadSource::findOrFail($id);
        return response()->json($source);
    }

    /**
     * Update a lead source
     */
    public function update(Request $request, $id)
    {
        $source = LeadSource::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_sources,name,' . $id,
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $source->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead source updated successfully!',
            'data' => $source
        ]);
    }

    /**
     * Delete a lead source
     */
    public function destroy($id)
    {
        $source = LeadSource::findOrFail($id);
        
        // Check if this source is being used by any bookings
        $bookingsCount = $source->bookings()->count();
        
        if ($bookingsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this lead source. It is being used by {$bookingsCount} booking(s)."
            ], 422);
        }

        $source->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead source deleted successfully!'
        ]);
    }

    /**
     * Display lead source details page with statistics
     */
    public function details($id)
    {
        $source = LeadSource::findOrFail($id);
        
        // Get bookings for this source (using slug - bookings table has lead_source as string/slug)
        $bookingsQuery = \App\Models\Booking::where('lead_source', $source->slug)
            ->with(['customer', 'driver', 'vehicle', 'company', 'expenses']);
        
        // Apply filters
        $status = request('status');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        
        if ($status) {
            $bookingsQuery->where('status', $status);
        }
        
        if ($dateFrom) {
            $bookingsQuery->whereDate('booking_date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $bookingsQuery->whereDate('booking_date', '<=', $dateTo);
        }
        
        $bookings = $bookingsQuery->orderBy('booking_date', 'desc')->paginate(20);
        
        // Calculate statistics from all bookings (not filtered) - using same method as dashboard
        $allBookings = \App\Models\Booking::where('lead_source', $source->slug)
            ->with('expenses')
            ->get();
        
        // Calculate revenue, expenses, and profit using same method as dashboard
        $totalRevenue = 0;
        $totalExpenses = 0;
        $totalProfit = 0;
        $totalCommission = 0;
        
        foreach($allBookings as $booking) {
            // Revenue calculation (same as dashboard)
            $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
            $totalRevenue += $revenue;
            
            // Expenses calculation
            $expenses = $booking->getTotalExpenses();
            $totalExpenses += $expenses;
            
            // Commission calculation
            $companyCommission = $booking->is_company_booking ? ($booking->company_commission_amount ?? 0) : 0;
            $totalCommission += $companyCommission;
            
            // Profit calculation (same as Booking model getNetProfit method)
            $totalProfit += ($revenue - $expenses - $companyCommission);
        }
        
        $completedBookings = $allBookings->where('status', 'completed');
        
        $stats = [
            'total_bookings' => $allBookings->count(),
            'completed_bookings' => $completedBookings->count(),
            'pending_bookings' => $allBookings->where('status', 'pending')->count(),
            'confirmed_bookings' => $allBookings->where('status', 'confirmed')->count(),
            'in_progress_bookings' => $allBookings->where('status', 'in_progress')->count(),
            'cancelled_bookings' => $allBookings->where('status', 'cancelled')->count(),
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'total_profit' => $totalProfit,
            'total_commission_paid' => $totalCommission,
            'average_booking_value' => $completedBookings->count() > 0 
                ? $completedBookings->avg(function($booking) {
                    return $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                }) 
                : 0,
        ];
        
        return view('admin.lead-sources.details', compact('source', 'bookings', 'stats'));
    }
}

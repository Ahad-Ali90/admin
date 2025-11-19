<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Overview Statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::whereIn('status', ['pending', 'confirmed'])->count(),
            'in_progress_bookings' => Booking::where('status', 'in_progress')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'total_companies' => Company::count(),
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'total_drivers' => User::where('role', 'driver')->where('status', 'active')->count(),
            'total_porters' => User::where('role', 'porter')->where('status', 'active')->count(),
        ];

        // Revenue Calculations
        $totalRevenue = 0;
        $monthlyRevenue = 0;
        $todayRevenue = 0;
        
        foreach(Booking::with('expenses')->where('status', 'completed')->get() as $booking) {
            // Calculate revenue: base fare + extra hours - expenses - company commission
            $bookingExpenses = $booking->expenses->sum('amount');
            $companyCommission = $booking->is_company_booking ? ($booking->company_commission_amount ?? 0) : 0;
            $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0) - $bookingExpenses - $companyCommission;
            $totalRevenue += $revenue;
            
            if($booking->booking_date->isCurrentMonth()) {
                $monthlyRevenue += $revenue;
            }
            
            if($booking->booking_date->isToday()) {
                $todayRevenue += $revenue;
            }
        }
        
        $stats['total_revenue'] = $totalRevenue;
        $stats['monthly_revenue'] = $monthlyRevenue;
        $stats['today_revenue'] = $todayRevenue;

        // Recent bookings
        $recent_bookings = Booking::with(['customer', 'driver', 'vehicle'])
            ->latest('booking_date')
            ->limit(8)
            ->get();

        // Today's bookings
        $today_bookings = Booking::with(['customer', 'driver'])
            ->whereDate('booking_date', today())
            ->orderBy('start_time')
            ->get();

        // Upcoming bookings (next 7 days)
        $upcoming_bookings = Booking::with(['customer', 'driver'])
            ->whereBetween('booking_date', [now(), now()->addDays(7)])
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('booking_date')
            ->limit(5)
            ->get();

        // Vehicle maintenance alerts
        $maintenance_alerts = Vehicle::where(function($query) {
            $query->where('mot_expiry_date', '<=', now()->addDays(30))
                  ->orWhere('insurance_expiry_date', '<=', now()->addDays(30))
                  ->orWhere('next_service_due', '<=', now());
        })->get();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenueData = [];
        for($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = 0;
            
            foreach(Booking::whereYear('booking_date', $month->year)
                           ->whereMonth('booking_date', $month->month)
                           ->get() as $booking) {
                $revenue += $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
            }
            
            $monthlyRevenueData[] = [
                'month' => $month->format('M'),
                'revenue' => $revenue
            ];
        }

        // Booking status distribution
        $booking_status = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Top customers (by total spent)
        $topCustomers = Customer::withCount('bookings')
            ->having('bookings_count', '>', 0)
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_bookings',
            'today_bookings',
            'upcoming_bookings',
            'maintenance_alerts',
            'monthlyRevenueData',
            'booking_status',
            'topCustomers'
        ));
    }
    public function tinymce(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        // store to public disk: storage/app/public/uploads/tinymce/...
        $path = $request->file('file')->store('uploads/tinymce', 'public');

        // return absolute URL for editor
        return response()->json([
            'location' => asset('storage/'.$path),
        ]);
    }
}

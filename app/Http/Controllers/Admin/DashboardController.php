<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'in_progress_bookings' => Booking::where('status', 'in_progress')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'total_staff' => User::whereIn('role', ['driver', 'porter'])->count(),
            'active_staff' => User::whereIn('role', ['driver', 'porter'])->where('status', 'active')->count(),
        ];

        // Revenue statistics
        $stats['total_revenue'] = Invoice::where('status', 'paid')->sum('total_amount');
        $stats['pending_revenue'] = Invoice::where('status', 'sent')->sum('balance_due');
        $stats['monthly_revenue'] = Invoice::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        // Recent bookings
        $recent_bookings = Booking::with(['customer', 'driver', 'porter', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get();

        // Upcoming bookings
        $upcoming_bookings = Booking::with(['customer', 'driver', 'porter', 'vehicle'])
            ->where('booking_date', '>=', now())
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

        // Monthly revenue chart data (last 12 months)
        $monthly_revenue = Invoice::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_amount) as revenue')
        )
        ->where('status', 'paid')
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
    
        // Booking status distribution
        $booking_status = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_bookings',
            'upcoming_bookings',
            'maintenance_alerts',
            'monthly_revenue',
            'booking_status'
        ));
    }
}

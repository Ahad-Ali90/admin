<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'monthly'); // monthly or weekly
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // Get only completed bookings for calculations
        $allBookings = Booking::with(['expenses', 'company'])
            ->where('status', 'completed')
            ->get();
        
        // Overall Statistics
        $totalRevenue = 0;
        $totalExpenses = 0;
        $totalProfit = 0;
        
        foreach($allBookings as $booking) {
            $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
            $expenses = $booking->getTotalExpenses();
            $companyCommission = $booking->is_company_booking ? ($booking->company_commission_amount ?? 0) : 0;
            
            $totalRevenue += $revenue;
            $totalExpenses += $expenses + $companyCommission;
            $totalProfit += ($revenue - $expenses - $companyCommission);
        }
        
        $stats = [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'total_profit' => $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
        ];
        
        if ($view === 'monthly') {
            // Monthly Analysis (Last 12 months)
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthBookings = Booking::with(['expenses', 'company'])
                    ->where('status', 'completed')
                    ->whereYear('start_date', $date->year)
                    ->whereMonth('start_date', $date->month)
                    ->get();
                
                $revenue = 0;
                $expenses = 0;
                $profit = 0;
                
                foreach($monthBookings as $booking) {
                    $bookingRevenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                    $bookingExpenses = $booking->getTotalExpenses();
                    $companyCommission = $booking->is_company_booking ? ($booking->company_commission_amount ?? 0) : 0;
                    
                    $revenue += $bookingRevenue;
                    $expenses += $bookingExpenses + $companyCommission;
                    $profit += ($bookingRevenue - $bookingExpenses - $companyCommission);
                }
                
                $monthlyData[] = [
                    'month' => $date->format('M Y'),
                    'month_short' => $date->format('M'),
                    'year' => $date->year,
                    'month_num' => $date->month,
                    'revenue' => $revenue,
                    'expenses' => $expenses,
                    'profit' => $profit,
                    'bookings_count' => $monthBookings->count(),
                ];
            }
            
            return view('admin.finance.index', compact('stats', 'monthlyData', 'view', 'year', 'month'));
        } else {
            // Weekly Analysis for selected month
            $selectedDate = Carbon::create($year, $month, 1);
            $startOfMonth = $selectedDate->copy()->startOfMonth();
            $endOfMonth = $selectedDate->copy()->endOfMonth();
            
            $weeklyData = [];
            $currentWeekStart = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
            $weekNumber = 1;
            
            while ($currentWeekStart <= $endOfMonth) {
                $weekEnd = $currentWeekStart->copy()->endOfWeek(Carbon::SATURDAY);
                
                // Only include weeks that overlap with the selected month
                if ($weekEnd >= $startOfMonth) {
                    $weekBookings = Booking::with(['expenses', 'company'])
                        ->where('status', 'completed')
                        ->whereBetween('start_date', [$currentWeekStart, $weekEnd])
                        ->get();
                    
                    $revenue = 0;
                    $expenses = 0;
                    $profit = 0;
                    
                    foreach($weekBookings as $booking) {
                        $bookingRevenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                        $bookingExpenses = $booking->getTotalExpenses();
                        $companyCommission = $booking->is_company_booking ? ($booking->company_commission_amount ?? 0) : 0;
                        
                        $revenue += $bookingRevenue;
                        $expenses += $bookingExpenses + $companyCommission;
                        $profit += ($bookingRevenue - $bookingExpenses - $companyCommission);
                    }
                    
                    $weeklyData[] = [
                        'week' => "Week $weekNumber",
                        'date_range' => $currentWeekStart->format('M d') . ' - ' . $weekEnd->format('M d'),
                        'revenue' => $revenue,
                        'expenses' => $expenses,
                        'profit' => $profit,
                        'bookings_count' => $weekBookings->count(),
                    ];
                    
                    $weekNumber++;
                }
                
                $currentWeekStart->addWeek();
                
                // Break if we've gone past the end of the month
                if ($currentWeekStart > $endOfMonth->copy()->addWeek()) {
                    break;
                }
            }
            
            return view('admin.finance.index', compact('stats', 'weeklyData', 'view', 'year', 'month', 'selectedDate'));
        }
    }
}

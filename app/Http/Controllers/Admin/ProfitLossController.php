<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $week = $request->get('week', null);
        
        // Build base query for bookings
        $bookingsQuery = Booking::whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->whereNotIn('status', ['not_converted', 'cancelled']);
        
        // If week is selected, filter by that week
        if ($week) {
            $weekStart = Carbon::create($year, $month, 1)->addWeeks($week - 1)->startOfWeek(Carbon::SUNDAY);
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SATURDAY);
            $bookingsQuery->whereBetween('booking_date', [$weekStart, $weekEnd]);
        }
        
        $bookings = $bookingsQuery->with(['customer', 'expenses'])->get();
        
        // Calculate totals
        $totalRevenue = 0;
        $totalExpenses = 0;
        
        foreach ($bookings as $booking) {
            $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
            $totalRevenue += $revenue;
            $totalExpenses += $booking->getTotalExpenses();
            
            // Deduct company commission if applicable
            if ($booking->is_company_booking && $booking->company_commission_amount) {
                $totalExpenses += $booking->company_commission_amount;
            }
        }
        
        $netProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
        
        // Expenses breakdown by type
        $expensesQuery = BookingExpense::whereIn('booking_id', $bookings->pluck('id'));
        $expensesByType = $expensesQuery->select('expense_type', DB::raw('SUM(amount) as total'))
            ->groupBy('expense_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->expense_type => $item->total];
            });
        
        // Get all expenses with booking details
        $allExpenses = BookingExpense::whereIn('booking_id', $bookings->pluck('id'))
            ->with('booking.customer')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Company commission total
        $totalCommission = $bookings->where('is_company_booking', true)->sum('company_commission_amount');
        
        // Weekly breakdown for the selected month
        $weeks = [];
        $monthStart = Carbon::create($year, $month, 1);
        $monthEnd = $monthStart->copy()->endOfMonth();
        
        $currentWeekStart = $monthStart->copy()->startOfWeek(Carbon::SUNDAY);
        $weekNumber = 1;
        
        while ($currentWeekStart <= $monthEnd) {
            $weekEnd = $currentWeekStart->copy()->endOfWeek(Carbon::SATURDAY);
            if ($weekEnd > $monthEnd) {
                $weekEnd = $monthEnd;
            }
            
            $weekBookings = Booking::whereBetween('booking_date', [$currentWeekStart, $weekEnd])
                ->whereNotIn('status', ['not_converted', 'cancelled'])
                ->with('expenses')
                ->get();
            
            $weekRevenue = 0;
            $weekExpenses = 0;
            
            foreach ($weekBookings as $booking) {
                $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                $weekRevenue += $revenue;
                $weekExpenses += $booking->getTotalExpenses();
                
                if ($booking->is_company_booking && $booking->company_commission_amount) {
                    $weekExpenses += $booking->company_commission_amount;
                }
            }
            
            $weeks[] = [
                'number' => $weekNumber,
                'start' => $currentWeekStart->format('M d'),
                'end' => $weekEnd->format('M d'),
                'revenue' => $weekRevenue,
                'expenses' => $weekExpenses,
                'profit' => $weekRevenue - $weekExpenses,
                'bookings_count' => $weekBookings->count(),
            ];
            
            $currentWeekStart->addWeek();
            $weekNumber++;
        }
        
        // Monthly data for the selected year
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthBookings = Booking::whereYear('booking_date', $year)
                ->whereMonth('booking_date', $m)
                ->whereNotIn('status', ['not_converted', 'cancelled'])
                ->with('expenses')
                ->get();
            
            $monthRevenue = 0;
            $monthExpenses = 0;
            
            foreach ($monthBookings as $booking) {
                $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                $monthRevenue += $revenue;
                $monthExpenses += $booking->getTotalExpenses();
                
                if ($booking->is_company_booking && $booking->company_commission_amount) {
                    $monthExpenses += $booking->company_commission_amount;
                }
            }
            
            $months[] = [
                'name' => Carbon::create($year, $m, 1)->format('F'),
                'number' => $m,
                'revenue' => $monthRevenue,
                'expenses' => $monthExpenses,
                'profit' => $monthRevenue - $monthExpenses,
                'bookings_count' => $monthBookings->count(),
            ];
        }
        
        return view('admin.profit-loss.index', compact(
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'profitMargin',
            'expensesByType',
            'allExpenses',
            'totalCommission',
            'bookings',
            'weeks',
            'months',
            'year',
            'month',
            'week'
        ));
    }
}


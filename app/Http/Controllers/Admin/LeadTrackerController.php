<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeadTrackerController extends Controller
{
    /**
     * Display lead tracker dashboard with reports
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // month, week, year
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Build query for filtered leads
        $leadsQuery = Booking::with(['customer', 'creator']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $leadsQuery->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('source')) {
            $leadsQuery->where('lead_source', $request->source);
        }

        if ($request->filled('status')) {
            $leadsQuery->where('status', $request->status);
        }

        // Filter by month and year
        if ($request->filled('month') && $request->filled('year')) {
            $leadsQuery->whereYear('booking_date', $request->year)
                      ->whereMonth('booking_date', $request->month);
        } elseif ($request->filled('year')) {
            $leadsQuery->whereYear('booking_date', $request->year);
        }

        // Filter by specific week
        if ($request->filled('week') && $request->filled('month') && $request->filled('year')) {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            
            $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $weekStart = $startOfMonth->copy()->addWeeks($week - 1);
            $weekEnd = $weekStart->copy()->addWeek()->subDay();
            
            $leadsQuery->whereBetween('booking_date', [$weekStart, $weekEnd]);
        }

        // Get paginated leads
        $allLeads = $leadsQuery->orderBy('booking_date', 'desc')->paginate(20);

        // Build query for statistics based on same filters
        $statsQuery = Booking::query();

        // Apply same filters for stats
        if ($request->filled('month') && $request->filled('year')) {
            $statsQuery->whereYear('booking_date', $request->year)
                      ->whereMonth('booking_date', $request->month);
        } elseif ($request->filled('year')) {
            $statsQuery->whereYear('booking_date', $request->year);
        }

        if ($request->filled('week') && $request->filled('month') && $request->filled('year')) {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            
            $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $weekStart = $startOfMonth->copy()->addWeeks($week - 1);
            $weekEnd = $weekStart->copy()->addWeek()->subDay();
            
            $statsQuery->whereBetween('booking_date', [$weekStart, $weekEnd]);
        }

        if ($request->filled('source')) {
            $statsQuery->where('lead_source', $request->source);
        }

        // Calculate statistics
        $totalLeads = $statsQuery->count();
        
        // Not Converted - customers who inquired but didn't book
        $notConvertedLeads = (clone $statsQuery)->where('status', 'not_converted')->count();
        
        // Converted - bookings that are pending, confirmed, in_progress, or completed
        $convertedLeads = (clone $statsQuery)->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])->count();
        
        // Conversion rate
        $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;

        // Lead Source Breakdown - with filters applied
        $sourceQuery = clone $statsQuery;
        $leadsBySource = $sourceQuery->select('lead_source', DB::raw('count(*) as total'))
            ->groupBy('lead_source')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function($item) use ($statsQuery) {
                $sourceStatsQuery = clone $statsQuery;
                return [
                    'source' => $item->lead_source,
                    'total' => $item->total,
                    'converted' => $sourceStatsQuery->where('lead_source', $item->lead_source)
                        ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
                        ->count(),
                ];
            });


        $stats = [
            'total_leads' => $totalLeads,
            'converted_leads' => $convertedLeads,
            'not_converted_leads' => $notConvertedLeads,
            'conversion_rate' => round($conversionRate, 1),
        ];

        return view('admin.leads.index', compact(
            'stats',
            'leadsBySource',
            'allLeads',
            'period',
            'year',
            'month'
        ));
    }
}

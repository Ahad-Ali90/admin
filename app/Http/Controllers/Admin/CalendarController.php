<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $currentDate = Carbon::create($year, $month, 1);
        
        // Get all bookings for the current month (using start_date)
        $bookings = Booking::with(['customer', 'driver', 'vehicle'])
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get();
        
        // Group bookings by start_date
        $bookingsByDate = $bookings->groupBy(function($booking) {
            return $booking->start_date ? $booking->start_date->format('Y-m-d') : $booking->booking_date->format('Y-m-d');
        });
        
        // Statistics
        $stats = [
            'total_month' => $bookings->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'in_progress' => $bookings->where('status', 'in_progress')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];
        
        return view('admin.calendar.index', compact('currentDate', 'bookingsByDate', 'stats'));
    }
    
    public function getBookings(Request $request)
    {
        $date = $request->get('date');
        
        $bookings = Booking::with(['customer', 'driver', 'vehicle'])
            ->whereDate('start_date', $date)
            ->orderBy('start_time')
            ->get();
        
        return response()->json([
            'bookings' => $bookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'reference' => $booking->booking_reference,
                    'customer' => $booking->customer->name ?? 'N/A',
                    'driver' => $booking->driver->name ?? 'Not Assigned',
                    'vehicle' => $booking->vehicle->registration_number ?? 'N/A',
                    'time' => $booking->start_time ? Carbon::parse($booking->start_time)->format('H:i') : '--:--',
                    'pickup' => $booking->pickup_address,
                    'dropoff' => $booking->delivery_address,
                    'status' => $booking->status,
                    'status_label' => str_replace('_', ' ', ucfirst($booking->status)),
                    'amount' => number_format($booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0), 2),
                    'view_url' => route('admin.bookings.show', $booking),
                ];
            })
        ]);
    }
}

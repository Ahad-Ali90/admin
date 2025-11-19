<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    /**
     * Show the public booking details page
     *
     * @param string $reference Booking reference number
     * @return \Illuminate\View\View
     */
    public function show($reference)
    {
        // Find booking by reference
        $booking = Booking::where('booking_reference', $reference)
            ->with(['customer', 'driver', 'vehicle', 'porters', 'services'])
            ->first();

        if (!$booking) {
            abort(404, 'Booking not found. Please check your booking reference number.');
        }

        // Load appropriate terms based on booking type
        $termsType = $booking->is_company_booking ? 'company' : 'customer';
        $terms = TermsAndCondition::active()
            ->where('type', $termsType)
            ->ordered()
            ->get();

        return view('public.booking-details', compact('booking', 'terms'));
    }

    /**
     * Show the booking lookup form
     *
     * @return \Illuminate\View\View
     */
    public function lookup()
    {
        return view('public.booking-lookup');
    }

    /**
     * Handle booking lookup form submission
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'booking_reference' => 'required|string'
        ]);

        $reference = strtoupper(trim($request->booking_reference));

        // Check if booking exists
        $booking = Booking::where('booking_reference', $reference)->first();

        if (!$booking) {
            return back()
                ->withInput()
                ->with('error', 'Booking not found. Please check your booking reference number.');
        }

        return redirect()->route('public.booking.show', $reference);
    }
}


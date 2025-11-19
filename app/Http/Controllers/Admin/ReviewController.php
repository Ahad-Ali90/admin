<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show the management page (single page CRUD)
     */
    public function manage()
    {
        return view('admin.reviews.manage');
    }

    /**
     * Display a listing of the resource (AJAX endpoint)
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            $query = Review::with('booking:id,booking_reference');

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('review', 'like', "%{$search}%");
                });
            }

            if ($request->filled('rating')) {
                $query->where('rating', $request->rating);
            }

            if ($request->filled('source')) {
                $query->where('source', $request->source);
            }

            // Order and paginate
            $reviews = $query->orderBy('created_at', 'desc')
                           ->paginate(10);

            return response()->json($reviews);
        }

        return redirect()->route('admin.reviews.manage');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'source' => 'required|in:Google,Trustpilot',
            'booking_id' => 'nullable|exists:bookings,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = 1; // Always active

        $review = Review::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            $review->load('booking:id,booking_reference');
            return response()->json(['success' => true, 'data' => $review]);
        }

        return redirect()->route('admin.reviews.manage')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified resource (AJAX endpoint)
     */
    public function show(Request $request, Review $review)
    {
        if ($request->wantsJson() || $request->ajax()) {
            $review->load('booking:id,booking_reference');
            return response()->json($review);
        }

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'source' => 'required|in:Google,Trustpilot',
            'booking_id' => 'nullable|exists:bookings,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = 1; // Always active

        $review->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            $review->load('booking:id,booking_reference');
            return response()->json(['success' => true, 'data' => $review]);
        }

        return redirect()->route('admin.reviews.manage')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Review $review)
    {
        $review->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.reviews.manage')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Get bookings for dropdown (AJAX endpoint)
     */
    public function getBookings(Request $request)
    {
        $search = $request->get('search', '');
        
        $bookings = Booking::where('booking_reference', 'like', "%{$search}%")
            ->orWhereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->with('customer:id,name')
            ->limit(10)
            ->get(['id', 'booking_reference', 'customer_id']);

        return response()->json($bookings);
    }
}

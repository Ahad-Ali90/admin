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
        $sources = LeadSource::ordered()->get();
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
}

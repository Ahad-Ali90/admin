<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class TermsAndConditionController extends Controller
{
    /**
     * Show the management page (single page CRUD)
     */
    public function manage()
    {
        return view('admin.terms.manage');
    }

    /**
     * Display a listing of the resource (AJAX endpoint)
     */
    public function index(Request $request)
    {
        // Check if it's an AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            $query = TermsAndCondition::query();

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('title', 'like', "%{$search}%");
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            // Order and paginate
            $terms = $query->orderBy('type')
                          ->orderBy('display_order')
                          ->paginate(10);

            return response()->json($terms);
        }

        // For non-AJAX requests, redirect to manage page
        return redirect()->route('admin.terms.manage');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.terms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:customer,company',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->input('is_active', 0);
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $term = TermsAndCondition::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'data' => $term]);
        }

        return redirect()->route('admin.terms.manage')
            ->with('success', 'Terms and Conditions created successfully.');
    }

    /**
     * Display the specified resource (AJAX endpoint)
     */
    public function show(Request $request, TermsAndCondition $term)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($term);
        }
        
        return view('admin.terms.show', compact('term'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TermsAndCondition $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TermsAndCondition $term)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:customer,company',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->input('is_active', 0);
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $term->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'data' => $term]);
        }

        return redirect()->route('admin.terms.manage')
            ->with('success', 'Terms and Conditions updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TermsAndCondition $term)
    {
        $term->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.terms.manage')
            ->with('success', 'Terms and Conditions deleted successfully.');
    }
}

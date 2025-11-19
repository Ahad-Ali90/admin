<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ColorScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ColorSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colorSchemes = ColorScheme::orderBy('category')
            ->orderBy('key')
            ->get()
            ->groupBy('category');
        
        $categories = ColorScheme::distinct('category')->pluck('category');
        
        return view('admin.color-schemes.index', compact('colorSchemes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'buttons' => 'Buttons',
            'cards' => 'Cards',
            'tables' => 'Tables',
            'inputs' => 'Inputs & Forms',
            'text' => 'Text Colors',
            'backgrounds' => 'Backgrounds',
            'borders' => 'Borders',
            'alerts' => 'Alerts & Notifications',
            'navigation' => 'Navigation',
            'other' => 'Other',
        ];
        
        return view('admin.color-schemes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:color_schemes,key',
            'value' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        ColorScheme::create($validated);
        
        // Clear cache
        Cache::forget('color_schemes');
        
        return redirect()->route('admin.color-schemes.index')
            ->with('success', 'Color scheme created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ColorScheme $colorScheme)
    {
        return view('admin.color-schemes.show', compact('colorScheme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ColorScheme $colorScheme)
    {
        $categories = [
            'buttons' => 'Buttons',
            'cards' => 'Cards',
            'tables' => 'Tables',
            'inputs' => 'Inputs & Forms',
            'text' => 'Text Colors',
            'backgrounds' => 'Backgrounds',
            'borders' => 'Borders',
            'alerts' => 'Alerts & Notifications',
            'navigation' => 'Navigation',
            'other' => 'Other',
        ];
        
        return view('admin.color-schemes.edit', compact('colorScheme', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ColorScheme $colorScheme)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:color_schemes,key,' . $colorScheme->id,
            'value' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $colorScheme->update($validated);
        
        // Clear cache
        Cache::forget('color_schemes');
        
        return redirect()->route('admin.color-schemes.index')
            ->with('success', 'Color scheme updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ColorScheme $colorScheme)
    {
        $colorScheme->delete();
        
        // Clear cache
        Cache::forget('color_schemes');
        
        return redirect()->route('admin.color-schemes.index')
            ->with('success', 'Color scheme deleted successfully!');
    }

    /**
     * Bulk update colors (for real-time updates)
     */
    public function bulkUpdate(Request $request)
    {
        $colors = $request->input('colors', []);
        
        foreach ($colors as $key => $value) {
            ColorScheme::where('key', $key)
                ->update(['value' => $value]);
        }
        
        // Clear cache
        Cache::forget('color_schemes');
        
        return response()->json([
            'success' => true,
            'message' => 'Colors updated successfully!'
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(ColorScheme $colorScheme)
    {
        $colorScheme->update([
            'is_active' => !$colorScheme->is_active
        ]);
        
        Cache::forget('color_schemes');
        Cache::forget('color_schemes_css');
        
        return response()->json([
            'success' => true,
            'is_active' => $colorScheme->is_active
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display the company management page
     */
    public function manage(): View
    {
        return view('admin.companies.manage');
    }

    /**
     * Get all companies (AJAX)
     */
    public function index(): JsonResponse
    {
        $companies = Company::orderBy('name')->get();
        return response()->json($companies);
    }

    /**
     * Store a new company (AJAX)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:subcontractor,commercial_client',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $company = Company::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Company created successfully!',
            'company' => $company
        ]);
    }

    /**
     * Display company details page
     */
    public function details(Company $company): View
    {
        $company->load(['bookings' => function($query) {
            $query->with(['customer', 'driver', 'vehicle'])->latest('booking_date');
        }]);

        // Calculate statistics
        $stats = [
            'total_bookings' => $company->total_bookings,
            'completed_bookings' => $company->completed_bookings,
            'pending_bookings' => $company->pending_bookings,
            'cancelled_bookings' => $company->cancelled_bookings,
            'total_revenue' => $company->total_revenue,
            'total_commission_paid' => $company->total_commission_paid,
            'total_commission_pending' => $company->total_commission_pending,
            'net_profit' => $company->net_profit,
            'average_commission' => $company->average_commission,
        ];

        return view('admin.companies.details', compact('company', 'stats'));
    }

    /**
     * Get a single company (AJAX)
     */
    public function show($id): JsonResponse
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    /**
     * Update a company (AJAX)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:subcontractor,commercial_client',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully!',
            'company' => $company
        ]);
    }

    /**
     * Delete a company (AJAX)
     */
    public function destroy($id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully!'
        ]);
    }
}

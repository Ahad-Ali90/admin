<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleExpenseController extends Controller
{
    /**
     * Display vehicle details with expenses
     */
    public function index(Request $request, Vehicle $vehicle): View
    {
        // Get filter parameters
        $month = $request->get('month');
        $year = $request->get('year');
        $expenseType = $request->get('expense_type');

        // Build expenses query with filters
        $expensesQuery = $vehicle->vehicleExpenses();

        if ($month && $year) {
            $expensesQuery->whereYear('expense_date', $year)
                         ->whereMonth('expense_date', $month);
        } elseif ($year) {
            $expensesQuery->whereYear('expense_date', $year);
        }

        if ($expenseType) {
            $expensesQuery->where('expense_type', $expenseType);
        }

        $filteredExpenses = $expensesQuery->orderBy('expense_date', 'desc')->get();

        // Load all expenses for overall stats
        $vehicle->load(['vehicleExpenses', 'bookings']);

        // Calculate filtered statistics
        $filteredTotal = $filteredExpenses->sum('amount');
        $expensesByType = $filteredExpenses->groupBy('expense_type')->map(function($items) {
            return $items->sum('amount');
        })->toArray();

        // Calculate statistics
        $stats = [
            'total_expenses' => $vehicle->total_expenses,
            'filtered_total' => $filteredTotal,
            'monthly_average' => $vehicle->monthly_expense_average,
            'expenses_by_type' => $expensesByType,
            'total_bookings' => $vehicle->total_bookings,
            'total_revenue' => $vehicle->total_revenue,
            'net_profit' => $vehicle->net_profit,
        ];

        // Get available months for filter dropdown
        $availableMonths = $vehicle->vehicleExpenses()
            ->selectRaw('YEAR(expense_date) as year, MONTH(expense_date) as month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.vehicle.expenses', compact('vehicle', 'stats', 'filteredExpenses', 'availableMonths'));
    }

    /**
     * Store a new expense
     */
    public function store(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'expense_type' => 'required|in:fuel,maintenance,repair,insurance,mot,tax,cleaning,parking,toll,fine,service,parts,tyres,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'mileage_at_expense' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['vehicle_id'] = $vehicle->id;
        VehicleExpense::create($validated);

        return redirect()->route('admin.vehicles.expenses', $vehicle)
            ->with('success', 'Expense added successfully!');
    }

    /**
     * Update an expense
     */
    public function update(Request $request, Vehicle $vehicle, VehicleExpense $expense)
    {
        $validated = $request->validate([
            'expense_type' => 'required|in:fuel,maintenance,repair,insurance,mot,tax,cleaning,parking,toll,fine,service,parts,tyres,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'mileage_at_expense' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('admin.vehicles.expenses', $vehicle)
            ->with('success', 'Expense updated successfully!');
    }

    /**
     * Delete an expense
     */
    public function destroy(Vehicle $vehicle, VehicleExpense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.vehicles.expenses', $vehicle)
            ->with('success', 'Expense deleted successfully!');
    }
}

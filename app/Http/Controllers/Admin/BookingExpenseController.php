<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingExpense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingExpenseController extends Controller
{
    public function index(Booking $booking): View
    {
        $booking->load(['expenses.paidToUser']);
        $users = User::where('status', 'active')->get();
        
        return view('admin.bookings.expenses', compact('booking', 'users'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'expense_type' => 'required|in:driver_payment,porter_payment,congestion_charge,ulez_charge,toll_charge,extra_waiting,fuel,other',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'paid_to_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'expense_date' => 'required|date'
        ]);

        $booking->expenses()->create($request->all());

        return redirect()->route('admin.bookings.expenses', $booking)
                        ->with('success', 'Expense added successfully.');
    }

    public function update(Request $request, Booking $booking, BookingExpense $expense): RedirectResponse
    {
        $request->validate([
            'expense_type' => 'required|in:driver_payment,porter_payment,congestion_charge,ulez_charge,toll_charge,extra_waiting,fuel,other',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'paid_to_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'expense_date' => 'required|date'
        ]);

        $expense->update($request->all());

        return redirect()->route('admin.bookings.expenses', $booking)
                        ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Booking $booking, BookingExpense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('admin.bookings.expenses', $booking)
                        ->with('success', 'Expense deleted successfully.');
    }
}

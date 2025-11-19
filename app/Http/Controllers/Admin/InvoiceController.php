<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Show the invoice creation/edit form
     */
    public function create(Booking $booking)
    {
        // Check if booking is a company booking
        if (!$booking->is_company_booking || !$booking->company) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Invoices can only be created for company bookings.');
        }

        // Check if invoice already exists
        $invoice = $booking->invoice;

        if (!$invoice) {
            // Create new invoice
            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'invoice_number' => $booking->booking_reference,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'status' => 'draft',
            ]);
        }

        return view('admin.invoices.create', compact('booking', 'invoice'));
    }

    /**
     * Store or update invoice items
     */
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.rate' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            'company_attendant' => 'nullable|string|max:255',
        ]);

        // Update company with attendant name if provided
        if ($request->filled('company_attendant') && $booking->company) {
            $booking->company->update([
                'attendant' => $request->company_attendant,
            ]);
        }

        $invoice = $booking->invoice;

        if (!$invoice) {
            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'invoice_number' => $booking->booking_reference,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'status' => 'draft',
            ]);
        }

        // Delete existing items
        $invoice->items()->delete();

        // Create new items and calculate total
        $total = 0;
        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
            ]);
            $total += $item['amount'];
        }

        // Update invoice totals
        $invoice->update([
            'subtotal' => $total,
            'total_amount' => $total,
            'balance_due' => $total,
        ]);

        return redirect()->route('admin.invoices.show', $booking)
            ->with('success', 'Invoice saved successfully.');
    }

    /**
     * Show the invoice (printable view)
     */
    public function show(Booking $booking)
    {
        // Refresh booking to get latest data (including company_attendant)
        $booking->refresh();
        $booking->load('company');
        
        $invoice = $booking->invoice()->with('items')->first();

        if (!$invoice) {
            return redirect()->route('admin.invoices.create', $booking)
                ->with('info', 'Please add invoice items first.');
        }

        // Update invoice date to today and due date to tomorrow whenever invoice is opened
        $invoice->update([
            'invoice_date' => now(),
            'due_date' => now()->addDay(),
        ]);

        // Refresh the invoice to get updated dates
        $invoice->refresh();

        // Get company terms and conditions
        $terms = TermsAndCondition::active()
            ->where('type', 'company')
            ->ordered()
            ->get();

        return view('admin.invoices.show', compact('booking', 'invoice', 'terms'));
    }
}

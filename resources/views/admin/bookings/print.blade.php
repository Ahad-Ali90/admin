<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $booking->booking_reference }} - Print</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 40px;
            max-width: 210mm;
            margin: 0 auto;
        }

        .print-container {
            background: white;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 4px solid #2563eb;
        }

        .header h1 {
            font-size: 32px;
            color: #1e40af;
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 15px;
        }

        .header .meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 15px;
        }

        .header .meta-item {
            font-size: 14px;
            color: #475569;
        }

        .header .meta-item strong {
            color: #1e293b;
        }

        .status-badge {
            display: inline-block;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending { color: #92400e; }
        .status-confirmed { color: #1e40af; }
        .status-in_progress { color: #3730a3; }
        .status-completed { color: #065f46; }
        .status-cancelled { color: #991b1b; }

        /* Section */
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 500;
        }

        /* Full Width Info */
        .info-full {
            grid-column: 1 / -1;
        }

        /* Text Box */
        .text-box {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #3b82f6;
            margin-top: 10px;
        }

        .text-box.warning {
            background: #fef3c7;
            border-left-color: #f59e0b;
        }

        /* Financial Box */
        .financial-box {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #cbd5e1;
            margin-top: 15px;
        }

        .financial-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #cbd5e1;
        }

        .financial-row:last-child {
            border-bottom: none;
        }

        .financial-row.total {
            border-top: 3px solid #1e40af;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #1e40af;
        }

        .financial-row.highlight {
            background: #dbeafe;
            padding: 12px;
            border-radius: 6px;
            margin: 5px 0;
            border-bottom: none;
        }

        .financial-label {
            font-weight: 600;
            color: #475569;
        }

        .financial-value {
            font-weight: 700;
            color: #1e293b;
        }

        .financial-value.positive {
            color: #059669;
        }

        .financial-value.negative {
            color: #dc2626;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .data-table th {
            background: #1e40af;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tfoot td {
            background: #f1f5f9;
            font-weight: 700;
            color: #1e293b;
            border-top: 2px solid #cbd5e1;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }

        .footer p {
            margin: 5px 0;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 20px;
            }

            .no-print {
                display: none !important;
            }

            .section {
                page-break-inside: avoid;
            }

            @page {
                margin: 15mm;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .print-button:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        .print-button i {
            margin-right: 8px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">
        <i class="bi bi-printer-fill"></i> Print Document
    </button>

    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <h1>BOOKING RECEIPT</h1>
            <div class="subtitle">{{ $booking->booking_reference }}</div>
            <div class="meta">
                <div class="meta-item">
                    <strong>Date:</strong> {{ now()->format('F d, Y') }}
                </div>
                <div class="meta-item">
                    <strong>Status:</strong> 
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Customer Name</div>
                    <div class="info-value">{{ $booking->customer->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $booking->customer->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">{{ $booking->customer->phone }}</div>
                </div>
                @if($booking->customer->address)
                <div class="info-item">
                    <div class="info-label">Address</div>
                    <div class="info-value">{{ $booking->customer->address }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Company Information -->
        @if($booking->is_company_booking)
        <div class="section">
            <div class="section-title">Company Information</div>
            <div class="info-grid">
                @if($booking->company_name)
                <div class="info-item">
                    <div class="info-label">Company Name</div>
                    <div class="info-value">{{ $booking->company_name }}</div>
                </div>
                @endif
                @if($booking->company_phone)
                <div class="info-item">
                    <div class="info-label">Company Phone</div>
                    <div class="info-value">{{ $booking->company_phone }}</div>
                </div>
                @endif
                @if($booking->company_commission_amount)
                <div class="info-item">
                    <div class="info-label">Commission Amount</div>
                    <div class="info-value">£{{ number_format($booking->company_commission_amount, 2) }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Booking Details -->
        <div class="section">
            <div class="section-title">Booking Details</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Booking Date</div>
                    <div class="info-value">{{ $booking->booking_date->format('F d, Y') }}</div>
                </div>
                @if($booking->start_date)
                <div class="info-item">
                    <div class="info-label">Start Date</div>
                    <div class="info-value">{{ $booking->start_date->format('F d, Y') }}</div>
                </div>
                @endif
                @if($booking->end_date)
                <div class="info-item">
                    <div class="info-label">End Date</div>
                    <div class="info-value">{{ $booking->end_date->format('F d, Y') }}</div>
                </div>
                @endif
                @if($booking->start_time)
                <div class="info-item">
                    <div class="info-label">Start Time</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-label">Booking Type</div>
                    <div class="info-value">{{ $booking->getBookingTypeLabel() }}</div>
                </div>
                @if($booking->source)
                <div class="info-item">
                    <div class="info-label">Source</div>
                    <div class="info-value">{{ $booking->source }}</div>
                </div>
                @endif
                @if($booking->helpers_count)
                <div class="info-item">
                    <div class="info-label">Helpers</div>
                    <div class="info-value">{{ $booking->helpers_count }} helper(s)</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Location Details -->
        <div class="section">
            <div class="section-title">Location Details</div>
            <div class="info-grid">
                <div class="info-item info-full">
                    <div class="info-label">Pickup Address</div>
                    <div class="info-value">{{ $booking->pickup_address }}@if($booking->pickup_postcode), {{ $booking->pickup_postcode }}@endif</div>
                </div>
                @if($booking->via_address)
                <div class="info-item info-full">
                    <div class="info-label">Via Address</div>
                    <div class="info-value">{{ $booking->via_address }}</div>
                </div>
                @endif
                <div class="info-item info-full">
                    <div class="info-label">Delivery Address</div>
                    <div class="info-value">{{ $booking->delivery_address }}@if($booking->delivery_postcode), {{ $booking->delivery_postcode }}@endif</div>
                </div>
            </div>
        </div>

        <!-- Job Description -->
        @if($booking->job_description)
        <div class="section">
            <div class="section-title">Job Description</div>
            <div class="text-box">
                {{ $booking->job_description }}
            </div>
        </div>
        @endif

        <!-- Special Instructions -->
        @if($booking->special_instructions)
        <div class="section">
            <div class="section-title">Special Instructions</div>
            <div class="text-box warning">
                {{ $booking->special_instructions }}
            </div>
        </div>
        @endif

        <!-- Staff Assigned -->
        <div class="section">
            <div class="section-title">Staff Assigned</div>
            <div class="info-grid">
                @if($booking->driver)
                <div class="info-item">
                    <div class="info-label">Driver</div>
                    <div class="info-value">{{ $booking->driver->name }}</div>
                </div>
                @endif
                @if($booking->porter_names)
                <div class="info-item">
                    <div class="info-label">Porter(s)</div>
                    <div class="info-value">{{ $booking->porter_names }}</div>
                </div>
                @endif
                @if($booking->vehicle)
                <div class="info-item info-full">
                    <div class="info-label">Vehicle</div>
                    <div class="info-value">{{ $booking->vehicle->registration_number }} ({{ $booking->vehicle->make }} {{ $booking->vehicle->model }})</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="section">
            <div class="section-title">Financial Summary</div>
            <div class="financial-box">
                <div class="financial-row">
                    <span class="financial-label">Original Total:</span>
                    <span class="financial-value">£{{ number_format($booking->total_fare, 2) }}</span>
                </div>
                @if($booking->discount > 0)
                <div class="financial-row">
                    <span class="financial-label">Discount:</span>
                    <span class="financial-value negative">-£{{ number_format($booking->discount, 2) }}</span>
                </div>
                @endif
                <div class="financial-row highlight">
                    <span class="financial-label">Final Total:</span>
                    <span class="financial-value">£{{ number_format($booking->getFinalTotalFare(), 2) }}</span>
                </div>
                @if($booking->extra_hours && $booking->extra_hours > 0)
                <div class="financial-row">
                    <span class="financial-label">Extra Hours ({{ $booking->extra_hours }} hrs @ £{{ number_format($booking->extra_hours_rate, 2) }}):</span>
                    <span class="financial-value positive">+£{{ number_format($booking->extra_hours_amount, 2) }}</span>
                </div>
                @endif
                @php
                    $totalRevenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                @endphp
                <div class="financial-row highlight">
                    <span class="financial-label">Total Revenue:</span>
                    <span class="financial-value positive">£{{ number_format($totalRevenue, 2) }}</span>
                </div>
                @if($booking->deposit > 0)
                <div class="financial-row">
                    <span class="financial-label">Deposit Paid:</span>
                    <span class="financial-value positive">£{{ number_format($booking->deposit, 2) }}</span>
                </div>
                @endif
                @if($booking->remaining_amount > 0)
                <div class="financial-row total">
                    <span class="financial-label">Remaining Amount:</span>
                    <span class="financial-value">£{{ number_format($booking->remaining_amount, 2) }}</span>
                </div>
                @endif
                @if($booking->payment_method)
                <div class="financial-row" style="border-top: 1px solid #cbd5e1; margin-top: 10px; padding-top: 10px;">
                    <span class="financial-label">Payment Method:</span>
                    <span class="financial-value">{{ $booking->getPaymentMethodLabel() }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Expenses -->
        @php
            $totalExpenses = $booking->getTotalExpenses();
        @endphp
        @if($totalExpenses > 0)
        <div class="section">
            <div class="section-title">Expenses Breakdown</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td>{{ $expense->expense_type_label }}</td>
                        <td>{{ $expense->description ?? '-' }}</td>
                        <td style="text-align: right;">£{{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Total Expenses:</td>
                        <td style="text-align: right;">£{{ number_format($totalExpenses, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Net Profit -->
        @php
            $netProfit = $booking->getNetProfit();
        @endphp
        @if($totalExpenses > 0 || $booking->is_company_booking)
        <div class="section">
            <div class="section-title">Net Profit Analysis</div>
            <div class="financial-box">
                <div class="financial-row">
                    <span class="financial-label">Total Revenue:</span>
                    <span class="financial-value positive">£{{ number_format($totalRevenue, 2) }}</span>
                </div>
                @if($totalExpenses > 0)
                <div class="financial-row">
                    <span class="financial-label">Total Expenses:</span>
                    <span class="financial-value negative">-£{{ number_format($totalExpenses, 2) }}</span>
                </div>
                @endif
                @if($booking->is_company_booking && $booking->company_commission_amount > 0)
                <div class="financial-row">
                    <span class="financial-label">Company Commission:</span>
                    <span class="financial-value negative">-£{{ number_format($booking->company_commission_amount, 2) }}</span>
                </div>
                @endif
                <div class="financial-row total">
                    <span class="financial-label">NET PROFIT:</span>
                    <span class="financial-value positive">£{{ number_format($netProfit, 2) }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is a computer-generated document.</strong></p>
            <p>Printed on {{ now()->format('l, F d, Y \a\t g:i A') }}</p>
            <p>© {{ now()->year }} Transport Booking System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>


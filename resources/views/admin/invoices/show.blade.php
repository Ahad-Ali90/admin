<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px 50px;
        }

        .company-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .company-name {
            color: #0066cc;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 14px;
            color: #333;
            margin-bottom: 30px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .invoice-details div {
            margin-bottom: 3px;
            font-size: 14px;
        }

        .invoice-details strong {
            font-weight: bold;
        }

        .bill-to {
            margin-bottom: 30px;
        }

        .bill-to-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .bill-to-name {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .bill-to-attn {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .invoice-table {
            width: 100%;
            border: 2px solid #333;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .invoice-table thead {
            background-color: #d3d3d3;
        }

        .invoice-table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #333;
        }

        .invoice-table td {
            padding: 10px;
            font-size: 14px;
            border-bottom: 1px solid #333;
        }

        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background-color: white;
        }

        .total-row td {
            font-weight: bold;
            padding-top: 15px;
            border-bottom: none !important;
        }

        .total-amount {
            font-size: 16px;
        }

        .bank-details {
            margin-bottom: 30px;
        }

        .bank-details-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .bank-details div {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .footer-note {
            font-size: 14px;
            margin-bottom: 30px;
        }

        .terms-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }

        .terms-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .terms-content {
            font-size: 13px;
            line-height: 1.6;
        }

        .terms-content h4 {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
        }

        .terms-content ul {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .terms-content ul li {
            margin-bottom: 5px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background-color: #0052a3;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .invoice-container {
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            .print-button {
                display: none;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="bi bi-printer"></i> Print Invoice
    </button>

    <div class="invoice-container">
        {{-- Company Header --}}
        <div class="company-header">
            <div class="company-name">{{ env('COMPANY_NAME', 'TBR Transport Ltd') }}</div>
            <div class="company-address">{{ env('COMPANY_ADDRESS', '25 Crown Road, Morden, Surrey, SM4 5DD') }}</div>
        </div>

        {{-- Invoice Details --}}
        <div class="invoice-details">
            <div><strong>Invoice:</strong></div>
            <div><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</div>
            <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</div>
            <div><strong>Due Date:</strong> {{ $invoice->due_date->format('d/m/Y') }}</div>
        </div>

        {{-- Bill To --}}
        <div class="bill-to">
            <div class="bill-to-title"><strong>Bill To:</strong></div>
            <div class="bill-to-name">{{ $booking->company->name }}</div>
            <div class="bill-to-attn"><strong>Attn:</strong> {{ $booking->company->attendant ?? 'Unknown' }}</div>
        </div>

        {{-- Invoice Table --}}
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Rate (£)</th>
                    <th style="text-align: right;">Amount (£)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: right;">{{ $item->rate }}</td>
                    <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;"><strong>Total Amount Due:</strong></td>
                    <td style="text-align: right;" class="total-amount"><strong>£{{ number_format($invoice->total_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        {{-- Bank Details --}}
        <div class="bank-details">
            <div class="bank-details-title"><strong>Bank Details:</strong></div>
            <div><strong>Account Name:</strong> {{ env('BANK_ACCOUNT_NAME', 'TBR Transport Ltd') }}</div>
            <div><strong>Account Number:</strong> {{ env('BANK_ACCOUNT_NUMBER', '38173158') }}</div>
            <div><strong>Sort Code:</strong> {{ env('BANK_SORT_CODE', '60-06-14') }}</div>
        </div>

        <div class="footer-note">
            Thank you for your business. Payment is due today.
        </div>

        {{-- Terms and Conditions --}}
        @if($terms && $terms->count() > 0)
        <div class="terms-section">
            <div class="terms-title">Terms and Conditions</div>
            <div class="terms-content">
                @foreach($terms as $term)
                    <div style="margin-bottom: 20px;">
                        {!! $term->content !!}
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>


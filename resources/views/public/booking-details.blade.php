<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - {{ $booking->booking_reference }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script>
        (function(w,d,s,r,n){w.TrustpilotObject=n;w[n]=w[n]||function(){(w[n].q=w[n].q||[]).push(arguments)};
            a=d.createElement(s);a.async=1;a.src=r;a.type='text/java'+s;f=d.getElementsByTagName(s)[0];
            f.parentNode.insertBefore(a,f)})(window,document,'script', 'https://invitejs.trustpilot.com/tp.min.js', 'tp');
            tp('register', 'Jgm9NPF1jIJAr3WM');
</script>
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .booking-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .booking-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .booking-header {
            background: white;
            padding: 30px;
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .company-logo {
            max-width: 180px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }
        
        .booking-reference {
            font-size: 28px;
            font-weight: 600;
            margin: 10px 0;
            color: #333;
            letter-spacing: 1px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-in_progress { background: #e0e7ff; color: #3730a3; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .booking-body {
            padding: 0;
        }
        
        .info-section {
            padding: 25px 30px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4f46e5;
            display: inline-block;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding: 8px 0;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 140px;
            font-size: 14px;
        }
        
        .info-value {
            color: #333;
            flex: 1;
            font-size: 14px;
        }
        
        .location-box {
            /* background: #f9fafb; */
            /* border: 1px solid #e5e7eb; */
            /* border-left: 3px solid #4f46e5; */
            /* padding: 12px 15px; */
            border-radius: 4px;
            margin-bottom: 12px;
        }
        
        .location-box:last-child {
            margin-bottom: 0;
        }
        
        .location-label {
            font-size: 11px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .location-address {
            color: #333;
            font-size: 14px;
            margin: 0;
            font-weight: 500;
        }
        
        .back-button {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 10px 20px;
            color: #333;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .back-button:hover {
            background: #f9fafb;
            color: #4f46e5;
            border-color: #4f46e5;
        }
        
        .price-box {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            padding: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .price-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .price-amount {
            font-size: 32px;
            font-weight: 700;
            color: #4f46e5;
        }
        
        .contact-info {
            background: #f9fafb;
            border-radius: 4px;
            padding: 20px;
            margin-top: 15px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        
        .contact-info h5 {
            font-size: 15px;
            margin-bottom: 12px;
            color: #333;
            font-weight: 600;
        }
        
        .contact-item {
            margin: 10px 0;
        }
        
        .contact-item a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
        }
        
        .contact-item a:hover {
            text-decoration: underline;
        }
        
        /* Terms and Conditions Styling */
        .terms-content h4 {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        
        .terms-content h4:first-child {
            margin-top: 0;
        }
        
        .terms-content ul {
            padding-left: 20px;
            margin-bottom: 15px;
        }
        
        .terms-content ul li {
            margin-bottom: 8px;
            line-height: 1.8;
            color: #555;
        }
        
        .terms-content strong {
            color: #333;
            font-weight: 600;
        }
        
        .service-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }
        
        .service-item:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .booking-header {
                padding: 20px 15px;
            }
            
            .info-section {
                padding: 20px 15px;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                min-width: auto;
                margin-bottom: 3px;
            }
            
            .booking-reference {
                font-size: 10px !important;
            }
            
            .company-logo {
                max-width: 150px;
            }
        }
        
    </style>
</head>
<body>
    <div class="booking-container">
        <a href="{{ route('public.booking.lookup') }}" class="back-button">
            <i class="bi bi-arrow-left"></i> Track Another Booking
        </a>
        
        <div class="booking-card">
            <div class="booking-header">
                <img src="https://removalplus365.com/wp-content/uploads/2021/12/Removal-365-1000-x-1000-px-2.png" 
                     alt="Company Logo" 
                     class="company-logo"
                     onerror="this.style.display='none'">
                <div class="d-flex align-items-center justify-content-between">
                <div class="booking-reference"><h4 class="mt-2">{{ $booking->booking_reference }}</h4 class="mt-2"></div>
                <span class="status-badge status-{{ $booking->status }}">
                    @if($booking->status === 'pending')
                        <i class="bi bi-clock-history me-1"></i> Pending
                    @elseif($booking->status === 'confirmed')
                        <i class="bi bi-check-circle me-1"></i> Confirmed
                    @elseif($booking->status === 'in_progress')
                        <i class="bi bi-arrow-repeat me-1"></i> In Progress
                    @elseif($booking->status === 'completed')
                        <i class="bi bi-check-all me-1"></i> Completed
                    @elseif($booking->status === 'cancelled')
                        <i class="bi bi-x-circle me-1"></i> Cancelled
                    @else
                        {{ ucfirst($booking->status) }}
                    @endif
                </span>
                </div>
            </div>
            
            <div class="booking-body">
                {{-- Customer Information --}}
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-person-circle me-2"></i>Customer Information
                    </div>
                    <div class="info-row">
                        <div class="info-label">Name:</div>
                        <div class="info-value">{{ $booking->customer->name }}</div>
                    </div>
                    @if($booking->customer->email)
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $booking->customer->email }}</div>
                    </div>
                    @endif
                    @if($booking->customer->phone)
                    <div class="info-row">
                        <div class="info-label">Phone:</div>
                        <div class="info-value">{{ $booking->customer->phone }}</div>
                    </div>
                    @endif
                </div>
                
                {{-- Booking Schedule --}}
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-calendar-check me-2"></i>Booking Schedule
                    </div>
                    <div class="info-row">
                        <div class="info-label">Booking Date:</div>
                        <div class="info-value">{{ $booking->booking_date->format('d-m-Y') }}</div>
                        </div>
                    @if($booking->start_time)
                    <div class="info-row">
                        <div class="info-label">Start Time:</div>
                        <div class="info-value">{{ $booking->start_time->format('h:i A') }}</div>
                    </div>
                    @endif
                    @if($booking->booking_type)
                    <div class="info-row">
                        <div class="info-label">Booking Type:</div>
                        <div class="info-value">{{ ucfirst($booking->booking_type) }}</div>
                    </div>
                    @endif
                </div>
                
                {{-- Location Details --}}
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-geo-alt me-2"></i>Location Details
                    </div>
                    
                    <div class="location-box">
                        <div class="location-label">
                            <i class="bi bi-circle-fill me-1" style="font-size: 8px; color: #10b981;"></i>
                            Pickup Address
                        </div>
                        <p class="location-address">{{ $booking->pickup_address }}</p>
                        @if($booking->pickup_postcode)
                            <small style="color: #666;">Postcode: {{ $booking->pickup_postcode }}</small>
                        @endif
                    </div>
                    
                    @if($booking->via_address)
                    <div class="location-box">
                        <div class="location-label">
                            <i class="bi bi-circle-fill me-1" style="font-size: 8px; color: #f59e0b;"></i>
                            Via Address
                        </div>
                        <p class="location-address">{{ $booking->via_address }}</p>
                    </div>
                    @endif
                    
                    <div class="location-box">
                        <div class="location-label">
                            <i class="bi bi-circle-fill me-1" style="font-size: 8px; color: #ef4444;"></i>
                            Delivery Address
                        </div>
                        <p class="location-address">{{ $booking->delivery_address }}</p>
                        @if($booking->delivery_postcode)
                            <small style="color: #666;">Postcode: {{ $booking->delivery_postcode }}</small>
                        @endif
                    </div>
                </div>
                
                
                {{-- Services --}}
                @if($booking->services && $booking->services->count() > 0)
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-list-check me-2"></i>Services
                    </div>
                    @foreach($booking->services as $service)
                        <div class="service-item">
                            <i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>
                            {{ $service->name }}
                        </div>
                    @endforeach
                </div>
                @endif
                
                {{-- Assignment Details (if confirmed) --}}
                @if($booking->status === 'confirmed' || $booking->status === 'in_progress' || $booking->status === 'completed')
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-people me-2"></i>Assignment Details
                    </div>
                    
                    @if($booking->driver)
                    <div class="info-row">
                        <div class="info-label">Driver:</div>
                        <div class="info-value">{{ $booking->driver->name }}</div>
                    </div>
                    @endif
                    
                    @if($booking->porters && $booking->porters->count() > 0)
                    <div class="info-row">
                        <div class="info-label">Helpers:</div>
                        <div class="info-value">
                            {{ $booking->porters->pluck('name')->join(', ') }}
                        </div>
                    </div>
                    @endif
                    
                    @if($booking->vehicle)
                    <div class="info-row">
                        <div class="info-label">Vehicle:</div>
                        <div class="info-value">
                            {{ $booking->vehicle->registration_number }}
                            ({{ $booking->vehicle->make }} {{ $booking->vehicle->model }})
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                {{-- Pricing Information --}}
                @if($booking->total_fare > 0 || $booking->manual_amount > 0)
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-cash-coin me-2"></i>Pricing Information
                    </div>
                    
                    <div class="price-box">
                        <div class="price-label">Payable Amount</div>
                        <div class="price-amount">£{{ number_format($booking->total_fare > 0 ? ($booking->total_fare - $booking->deposit) : ($booking->manual_amount -$booking->deposit), 2) }}</div>
                    </div>
                    
                    @if($booking->deposit > 0)
                    <div class="info-row mt-3">
                        <div class="info-label">Deposit Paid:</div>
                        <div class="info-value">£{{ number_format($booking->deposit, 2) }}</div>
                    </div>
                    @endif
                    
                    @if($booking->discount > 0)
                    <div class="info-row">
                        <div class="info-label">Discount:</div>
                        <div class="info-value">£{{ number_format($booking->discount, 2) }}</div>
                    </div>
                    @endif
                    
                    @if($booking->payment_method)
                    <div class="info-row">
                        <div class="info-label">Payment Method:</div>
                        <div class="info-value">{{ ucwords(str_replace('_', ' ', $booking->payment_method)) }}</div>
                    </div>
                    @endif
                </div>
                @endif
                
                {{-- Terms and Conditions --}}
                @if($terms && $terms->count() > 0)
                <div class="info-section">
                    <h5><i class="bi bi-file-text me-2"></i>Terms and Conditions</h5>
                    <div class="terms-content">
                        @foreach($terms as $index => $term)
                            <div class="term-item {{ $index > 0 ? 'mt-4 pt-3 border-top' : '' }}">
                                <!-- <h6 style="color: #333; font-weight: 600; margin-bottom: 16px;">
                                    {{ $term->title }}
                                </h6> -->
                                <div style="color: #555; line-height: 1.8; font-size: 14px;">
                                    {!! $term->content !!}
                                </div>
                            </div>
                        @endforeach
                        <div class="alert alert-info mt-3 mb-0" style="background-color: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 12px;">
                            <small style="color: #004085;">
                                <i class="bi bi-info-circle me-1"></i>
                                By proceeding with this booking, you agree to the above terms and conditions.
                            </small>
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Contact Information --}}
                <div class="info-section">
                    <div class="contact-info">
                        <h5><i class="bi bi-headset me-2"></i>Need Help?</h5>
                        <div class="contact-item">
                            <i class="bi bi-telephone-fill me-2"></i>
                            <a href="tel:{{ config('services.webex.admin_number') }}">
                                {{ config('services.webex.admin_number') }}
                            </a>
                        </div>
                        <p class="mt-3 mb-0" style="font-size: 13px; color: #666;">
                            For any questions or concerns about your booking, please don't hesitate to contact us.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

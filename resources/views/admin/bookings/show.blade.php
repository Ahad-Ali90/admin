{{-- resources/views/admin/bookings/show.blade.php --}}
<x-admin.layouts.app>
  @php
    // Soft badge palette (matches earlier pages)
    $badgeClass = match($booking->status){
      'pending'     => 'badge-pending',
      'confirmed'   => 'badge-confirmed',
      'in_progress' => 'badge-inprog',
      'completed'   => 'badge-completed',
      'cancelled'   => 'badge-cancelled',
      default       => 'badge-soft'
    };
  @endphp

  {{-- Page styles for soft badges (reuse across pages if you like) --}}
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-soft{background:rgba(0,0,0,.06)}
    .badge-pending{background:#fff7ed;color:#9a3412}
    .badge-confirmed{background:#ecfeff;color:#155e75}
    .badge-inprog{background:#eef2ff;color:#3730a3}
    .badge-completed{background:#ecfdf5;color:#065f46}
    .badge-cancelled{background:#fef2f2;color:#991b1b}
    .mono{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New",monospace}

    /* Dark Mode Support */
    [data-theme="dark"] .card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .card-body {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] h1,
    [data-theme="dark"] h2,
    [data-theme="dark"] h3,
    [data-theme="dark"] h4,
    [data-theme="dark"] h5,
    [data-theme="dark"] h6 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-secondary,
    [data-theme="dark"] .small.text-secondary {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] .fw-semibold,
    [data-theme="dark"] .fw-medium,
    [data-theme="dark"] .fw-bold {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .border-top,
    [data-theme="dark"] .border-bottom {
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .bg-light {
      background: var(--surface-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .bg-primary.bg-opacity-10,
    [data-theme="dark"] .bg-info.bg-opacity-10,
    [data-theme="dark"] .bg-warning.bg-opacity-10,
    [data-theme="dark"] .bg-success.bg-opacity-10,
    [data-theme="dark"] .bg-danger.bg-opacity-10 {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .form-select,
    [data-theme="dark"] .form-control {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-select:focus,
    [data-theme="dark"] .form-control:focus {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .table {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table-light {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table > :not(caption) > * > * {
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .table-hover tbody tr:hover {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .alert-warning {
      background: rgba(251, 146, 60, 0.15) !important;
      color: var(--text-color) !important;
      border-color: rgba(251, 146, 60, 0.3) !important;
    }

    [data-theme="dark"] .link-primary {
      color: #818cf8 !important;
    }

    [data-theme="dark"] .text-muted {
      color: var(--text-color) !important;
      opacity: 0.6;
    }

    [data-theme="dark"] .text-primary {
      color: #818cf8 !important;
    }

    [data-theme="dark"] .text-success {
      color: #34d399 !important;
    }

    [data-theme="dark"] .text-danger {
      color: #f87171 !important;
    }

    [data-theme="dark"] .text-warning {
      color: #fbbf24 !important;
    }

    [data-theme="dark"] .text-info {
      color: #60a5fa !important;
    }

    /* Dark mode badges with better visibility */
    [data-theme="dark"] .badge-pending { 
      background: rgba(251, 146, 60, 0.2) !important; 
      color: #fb923c !important; 
    }
    [data-theme="dark"] .badge-confirmed { 
      background: rgba(6, 182, 212, 0.2) !important; 
      color: #22d3ee !important; 
    }
    [data-theme="dark"] .badge-inprog { 
      background: rgba(99, 102, 241, 0.2) !important; 
      color: #818cf8 !important; 
    }
    [data-theme="dark"] .badge-completed { 
      background: rgba(52, 211, 153, 0.2) !important; 
      color: #34d399 !important; 
    }
    [data-theme="dark"] .badge-cancelled { 
      background: rgba(248, 113, 113, 0.2) !important; 
      color: #f87171 !important; 
    }

    /* Modal dark mode */
    [data-theme="dark"] .modal-content {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .modal-header {
      background: var(--card-bg) !important;
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .modal-body {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .modal-footer {
      background: var(--surface-bg) !important;
      border-top-color: var(--border-color) !important;
    }

    [data-theme="dark"] .modal-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .form-label {
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .btn-outline-info:hover {
      color: white !important;
    }
    [data-theme="dark"] .table>:not(caption)>*>* {
      background-color: #1f2937 !important;
    }
    
  </style>
  @endpush

  <div class="container-xxl py-3">

    {{-- Header --}}
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <h3 class="h3 mb-0">Booking Details</h3>
        <p class="text-secondary mb-0">{{ $booking->booking_reference }}</p>
      </div>
      <div class="d-flex gap-2">
        @if($booking->is_company_booking && $booking->company)
          <a href="{{ route('admin.invoices.create', $booking) }}" class="btn btn-sm btn-outline-info" title="Create/View Invoice" style="padding:10px 20px !important;border-radius:0.25rem !important">
            <i class="bi bi-file-earmark-text me-1"></i>Company Invoice
          </a>
        @endif
        @if(! $booking->is_company_booking && !$booking->company)
                    <a href="{{ route('public.booking.show', $booking->booking_reference) }}" class="btn btn-sm btn-outline-info" style="padding:10px 20px !important;border-radius:0.25rem !important" title="Create/View Invoice">
                      <i class="bi bi-person-workspace me-1"></i>Customer Invoice
                    </a>
        @endif
        <!-- <a href="{{ route('admin.bookings.print', $booking) }}" target="_blank" class="btn btn-primary" style="padding:10px 20px !important;border-radius:0.25rem !important">
          <i class="bi bi-printer me-2"></i>Print
        </a> -->
        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-outline-secondary" style="padding:10px 20px !important;border-radius:0.25rem !important">
          <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.bookings.expenses', $booking) }}" class="btn btn-outline-success" style="padding:10px 20px !important;border-radius:0.25rem !important">
          <i class="bi bi-receipt me-2"></i>Expenses
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary" style="padding:10px 20px !important;border-radius:0.25rem !important">
          <i class="bi bi-arrow-left me-2"></i>Back to Bookings
        </a>
      </div>
    </div>

    <div class="row g-4">
      {{-- Main column --}}
      <div class="col-12 col-lg-8 vstack gap-4">

        {{-- Booking Status --}}
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3">
              <div>
                <h5 class="card-title mb-1">Booking Status</h5>
                <div class="text-secondary small">{{ $booking->getStatusDescription() }}</div>
              </div>
              
              <span class="badge {{ $badgeClass }} px-3 py-2 text-capitalize">
                {{ str_replace('_',' ', $booking->status) }}
              </span>
            </div>

            {{-- Status Management --}}
            @if($booking->getAvailableStatusTransitions())
              <div class="border-top pt-3">
                <h6 class="mb-2">Update Status</h6>
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="d-flex gap-2 flex-wrap">
                  @csrf
                  <select name="status" class="form-select" style="max-width: 200px;">
                    <option value="">Select new status</option>
                    @foreach($booking->getAvailableStatusTransitions() as $status)
                      <option value="{{ $status }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                      </option>
                    @endforeach
                  </select>
                  <!-- <input type="text" name="notes" placeholder="Notes (optional)" class="form-control" style="max-width: 200px;"> -->
                  <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                </form>
              </div>
            @endif

            {{-- Quick Actions --}}
            <div class="border-top pt-3 mt-3">
              <h6 class="mb-2">Quick Actions</h6>
              <div class="d-flex gap-2 flex-wrap">
                @if($booking->canStart())
                  <form method="POST" action="{{ route('admin.bookings.start', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="bi bi-play-fill me-1"></i>Start Booking
                    </button>
                  </form>
                @endif

                @if($booking->canComplete())
                  <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="bi bi-check-circle-fill me-1"></i>Complete Booking
                    </button>
                  </form>
                @endif

                @if($booking->status === 'completed' && $booking->extra_hours == 0)
                  <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addExtraHoursModal">
                    <i class="bi bi-clock-fill me-1"></i>Add Extra Hours
                  </button>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Booking Information --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-4">Booking Information</h5>
            
            {{-- Basic Info Row --}}
            <div class="row g-4 mb-4 pb-4 border-bottom">
              <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-primary bg-opacity-10 rounded p-3">
                    <i class="bi bi-receipt text-primary fs-4"></i>
                  </div>
                  <div>
                    <div class="text-secondary small mb-1">Booking Reference</div>
                    <div class="fw-semibold">{{ $booking->booking_reference }}</div>
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-info bg-opacity-10 rounded p-3">
                    <i class="bi bi-calendar-event text-info fs-4"></i>
                  </div>
                  <div>
                    <div class="text-secondary small mb-1">Booking Date</div>
                    <div class="fw-semibold">{{ $booking->start_date ? $booking->booking_date->format('M d, Y') : $booking->booking_date->format('M d, Y') }}</div>
                  </div>
                </div>
              </div>

              @if($booking->start_time)
              <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-warning bg-opacity-10 rounded p-3">
                    <i class="bi bi-clock text-warning fs-4"></i>
                  </div>
                  <div>
                    <div class="text-secondary small mb-1">Start Time</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</div>
                  </div>
                </div>
              </div>
              @endif
              
              <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-success bg-opacity-10 rounded p-3">
                    <i class="bi bi-tag text-success fs-4"></i>
                  </div>
                  <div>
                    <div class="text-secondary small mb-1">Booking Type</div>
                    <div class="fw-semibold">{{ $booking->getBookingTypeLabel() }}</div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Dates & Source --}}
            <div class="row g-3 mb-4 pb-4 border-bottom">
              @if($booking->start_date)
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="text-secondary small mb-1">Start Date</div>
                <div class="fw-medium">{{ $booking->start_date->format('D, M d, Y') }}</div>
              </div>
              @endif
              
              @if($booking->end_date)
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="text-secondary small mb-1">End Date</div>
                <div class="fw-medium">{{ $booking->end_date->format('D, M d, Y') }}</div>
              </div>
              @endif
              
              @if($booking->source)
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="text-secondary small mb-1">Source</div>
                <div class="fw-medium">{{ $booking->source }}</div>
              </div>
              @endif
              
              @if($booking->helpers_count)
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="text-secondary small mb-1">Helpers</div>
                <div class="fw-medium">{{ $booking->helpers_count }} helper(s)</div>
              </div>
              @endif
            </div>

            {{-- Hourly Details (if hourly booking) --}}
            @if($booking->booking_type === 'hourly')
            <div class="row g-3 mb-4 pb-4 border-bottom">
              <div class="col-12">
                <h6 class="text-muted mb-3">Hourly Rate Details</h6>
              </div>
              <div class="col-12 col-sm-4">
                <div class="text-secondary small mb-1">Hourly Rate</div>
                <div class="fw-semibold text-primary fs-5">£{{ number_format($booking->hourly_rate, 2) }}/hour</div>
              </div>
              <div class="col-12 col-sm-4">
                <div class="text-secondary small mb-1">Booked Hours</div>
                <div class="fw-semibold fs-5">{{ $booking->booked_hours }} hours</div>
              </div>
              <div class="col-12 col-sm-4">
                <div class="text-secondary small mb-1">Calculated Total</div>
                <div class="fw-semibold text-success fs-5">£{{ number_format($booking->hourly_rate * $booking->booked_hours, 2) }}</div>
              </div>
            </div>
            @endif

            {{-- Financial Summary --}}
            <div class="row g-3 mb-3">
              <div class="col-12">
                <h6 class="text-muted mb-3">Financial Summary</h6>
              </div>
              
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-light rounded p-3">
                  <div class="text-secondary small mb-1">Original Total</div>
                  <div class="fw-bold fs-5">£{{ number_format($booking->total_fare, 2) }}</div>
                </div>
              </div>
              
              @if($booking->discount > 0)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-warning bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Discount</div>
                  <div class="fw-bold fs-5 text-warning">- £{{ number_format($booking->discount, 2) }}</div>
                  <!-- @if($booking->discount_reason)
                    <div class="small text-muted mt-1">{{ $booking->discount_reason }}</div>
                  @endif -->
                </div>
                </div>
              @endif

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-primary bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Final Total</div>
                  <div class="fw-bold fs-5 text-primary">£{{ number_format($booking->getFinalTotalFare(), 2) }}</div>
                </div>
              </div>
              
              @if($booking->deposit > 0)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-success bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Deposit Paid</div>
                  <div class="fw-bold fs-5 text-success">£{{ number_format($booking->deposit, 2) }}</div>
                </div>
                </div>
              @endif

              @if($booking->remaining_amount > 0)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-danger bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Remaining</div>
                  <div class="fw-bold fs-5 text-danger">£{{ number_format($booking->remaining_amount, 2) }}</div>
                </div>
                </div>
              @endif

              @if($booking->payment_method)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-info bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Payment Method</div>
                  <div class="fw-bold">{{ $booking->getPaymentMethodLabel() }}</div>
                </div>
              </div>
              @endif
            </div>

            {{-- Extra Hours (if applicable) --}}
            @if($booking->extra_hours && $booking->extra_hours > 0)
            <div class="alert alert-warning mb-3">
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history fs-5"></i>
                <div>
                  <strong>Extra Hours:</strong> {{ $booking->extra_hours }} hours @ £{{ number_format($booking->extra_hours_rate, 2) }}/hour
                  = <strong>£{{ number_format($booking->extra_hours_amount, 2) }}</strong>
                </div>
              </div>
            </div>
            @endif

            {{-- Expenses Summary --}}
            @php
              $totalExpenses = $booking->getTotalExpenses();
              $netProfit = $booking->getNetProfit();
              $totalRevenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
            @endphp
            
            @if($totalExpenses > 0 || $booking->is_company_booking || ($booking->extra_hours && $booking->extra_hours > 0))
            <div class="row g-3 mt-3 pt-3 border-top">
              <div class="col-12">
                <h6 class="text-muted mb-3">Business Summary</h6>
              </div>
              
              {{-- Revenue Breakdown --}}
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-primary bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Base Revenue</div>
                  <div class="fw-bold fs-5 text-primary">£{{ number_format($booking->getFinalTotalFare(), 2) }}</div>
                  <div class="small text-muted">After discount</div>
                </div>
              </div>

              @if($booking->extra_hours && $booking->extra_hours > 0)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-warning bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Extra Hours</div>
                  <div class="fw-bold fs-5 text-warning">+£{{ number_format($booking->extra_hours_amount, 2) }}</div>
                  <div class="small text-muted">{{ $booking->extra_hours }} hrs @ £{{ number_format($booking->extra_hours_rate, 2) }}</div>
                </div>
              </div>
              @endif
              
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-success bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Total Revenue</div>
                  <div class="fw-bold fs-4 text-success">£{{ number_format($totalRevenue, 2) }}</div>
                </div>
              </div>
              
              {{-- Deductions --}}
              @if($totalExpenses > 0)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-danger bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Total Expenses</div>
                  <div class="fw-bold fs-5 text-danger">£{{ number_format($totalExpenses, 2) }}</div>
                  <a href="{{ route('admin.bookings.expenses', $booking) }}" class="small text-decoration-none">
                    View Details →
                  </a>
                </div>
              </div>
              @endif
              
              @if($booking->is_company_booking && $booking->company_commission_amount > 0)
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="bg-info bg-opacity-10 rounded p-3">
                  <div class="text-secondary small mb-1">Company Commission</div>
                  <div class="fw-bold fs-5 text-info">£{{ number_format($booking->company_commission_amount, 2) }}</div>
                </div>
                </div>
              @endif

              {{-- Net Profit - Full Width --}}
              <div class="col-12">
                <div class="bg-success bg-opacity-10 rounded p-4 text-center">
                  <div class="text-secondary small mb-2">Net Profit</div>
                  <div class="fw-bold text-success" style="font-size: 2.5rem;">£{{ number_format($netProfit, 2) }}</div>
                  <div class="small text-muted mt-2">
                    Revenue: £{{ number_format($totalRevenue, 2) }} - Expenses: £{{ number_format($totalExpenses, 2) }}
                    @if($booking->is_company_booking && $booking->company_commission_amount > 0)
                      - Commission: £{{ number_format($booking->company_commission_amount, 2) }}
                    @endif
                  </div>
                </div>
              </div>
              
            </div>
            @else
            <div class="text-center py-3 border-top mt-3">
              <a href="{{ route('admin.bookings.expenses', $booking) }}" class="btn btn-primary">
                <i class="bi bi-wallet2 me-2"></i>Add Expenses
              </a>
            </div>
            @endif

            {{-- Final Amount Display --}}
            @if($booking->extra_hours && $booking->extra_hours > 0)
            <div class="row mt-4 pt-4 border-top">
              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 rounded p-4">
                  <div>
                    <div class="text-secondary small mb-1">Final Amount (Including Extra Hours)</div>
                    <div class="text-muted small">Base: £{{ number_format($booking->getFinalTotalFare(), 2) }} + Extra: £{{ number_format($booking->extra_hours_amount, 2) }}</div>
                  </div>
                  <div class="fw-bold text-primary" style="font-size: 2rem;">
                    £{{ number_format($booking->final_amount, 2) }}
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>

        {{-- Job Details --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Job Details</h5>
            <div class="vstack gap-3">
              <div>
                <div class="text-secondary small">Job Description</div>
                <div>{{ $booking->job_description }}</div>
              </div>

              @if($booking->special_instructions)
                <div>
                  <div class="text-secondary small">Special Instructions</div>
                  <div>{{ $booking->special_instructions }}</div>
                </div>
              @endif

              @if($booking->driver_notes)
                <div>
                  <div class="text-secondary small">Driver Notes</div>
                  <div>{{ $booking->driver_notes }}</div>
                </div>
              @endif

              @if($booking->porter_notes)
                <div>
                  <div class="text-secondary small">Porter Notes</div>
                  <div>{{ $booking->porter_notes }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Location Details --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Location Details</h5>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="fw-medium mb-1">Pickup Location</div>
                <div>{{ $booking->pickup_address }}</div>
                @if($booking->pickup_postcode)
                  <div class="text-secondary small mt-1">{{ $booking->pickup_postcode }}</div>
                @endif
              </div>
              <div class="col-12 col-md-6">
                <div class="fw-medium mb-1">Delivery Location</div>
                <div>{{ $booking->delivery_address }}</div>
                @if($booking->delivery_postcode)
                  <div class="text-secondary small mt-1">{{ $booking->delivery_postcode }}</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Services --}}
        @if($booking->services->count() > 0)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">Services</h5>
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Service</th>
                      <th>Quantity</th>
                      <th>Rate</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($booking->services as $service)
                      <tr>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->pivot->quantity }}</td>
                        <td>£{{ number_format($service->pivot->unit_rate, 2) }}</td>
                        <td class="fw-medium">£{{ number_format($service->pivot->total_amount, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot class="table-light">
                    <tr>
                      <td colspan="3" class="fw-medium">Total</td>
                      <td class="fw-medium">£{{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        @endif

        {{-- Video Survey --}}
        @if($booking->survey)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">
                <i class="bi bi-camera-video me-2"></i>Video Survey
              </h5>
              
              <div class="row g-3">
                {{-- Survey Type --}}
                <div class="col-12 col-md-6">
                  <div class="text-secondary small mb-1">Survey Type</div>
                  <div class="d-flex align-items-center gap-2">
                    @if($booking->survey->survey_type === 'video_call')
                      <i class="bi bi-telephone-video text-primary"></i>
                      <span class="fw-medium">Video Call</span>
                    @elseif($booking->survey->survey_type === 'video_recording')
                      <i class="bi bi-record-circle text-danger"></i>
                      <span class="fw-medium">Video Recording</span>
                    @elseif($booking->survey->survey_type === 'list')
                      <i class="bi bi-list-ul text-info"></i>
                      <span class="fw-medium">List</span>
                    @endif
                  </div>
                </div>

                {{-- Schedule (for video call) --}}
                @if($booking->survey->survey_type === 'video_call' && ($booking->survey->schedule_date || $booking->survey->schedule_time))
                  <div class="col-12 col-md-6">
                    <div class="text-secondary small mb-1">Schedule</div>
                    <div class="d-flex align-items-center gap-2">
                      <i class="bi bi-calendar-event text-primary"></i>
                      <span class="fw-medium">
                        @if($booking->survey->schedule_date)
                          {{ $booking->survey->schedule_date->format('M d, Y') }}
                        @endif
                        @if($booking->survey->schedule_time)
                          at {{ \Carbon\Carbon::parse($booking->survey->schedule_time)->format('h:i A') }}
                        @endif
                      </span>
                    </div>
                  </div>
                @endif

                {{-- Status (for video call and video recording) --}}
                @if($booking->survey->status)
                  <div class="col-12 col-md-6">
                    <div class="text-secondary small mb-1">Status</div>
                    <div>
                      <span class="badge bg-{{ $booking->survey->status_badge }} px-3 py-2">
                        @if($booking->survey->status === 'done')
                          <i class="bi bi-check-circle me-1"></i>
                        @elseif($booking->survey->status === 'pending')
                          <i class="bi bi-clock me-1"></i>
                        @elseif($booking->survey->status === 'not_agreed')
                          <i class="bi bi-x-circle me-1"></i>
                        @endif
                        {{ $booking->survey->status_label }}
                      </span>
                    </div>
                  </div>
                @endif

                {{-- List Content --}}
                @if($booking->survey->survey_type === 'list' && $booking->survey->list_content)
                  <div class="col-12">
                    <div class="text-secondary small mb-1">List Content</div>
                    <div class="border rounded p-3 bg-light">
                      <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $booking->survey->list_content }}</pre>
                    </div>
                  </div>
                @endif

                {{-- Video File --}}
                @if($booking->survey->survey_type === 'video_recording' && $booking->survey->video_path)
                  <div class="col-12">
                    <div class="text-secondary small mb-1">Uploaded Video</div>
                    <div>
                      <a href="{{ Storage::url($booking->survey->video_path) }}" 
                         target="_blank" 
                         class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-play me-1"></i>
                        View Video
                      </a>
                    </div>
                  </div>
                @endif

                {{-- Notes --}}
                @if($booking->survey->notes)
                  <div class="col-12">
                    <div class="text-secondary small mb-1">Notes</div>
                    <div class="border rounded p-3 bg-light">
                      {{ $booking->survey->notes }}
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endif

      </div>

      {{-- Sidebar --}}
      <aside class="col-12 col-lg-4 vstack gap-4">

        {{-- Customer --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Customer</h5>
            <div class="vstack gap-2">
              <div>
                <div class="text-secondary small">Name</div>
                <div>{{ $booking->customer->name }}</div>
              </div>
              <div>
                <div class="text-secondary small">Email</div>
                <div>{{ $booking->customer->email }}</div>
              </div>
              <div>
                <div class="text-secondary small">Phone</div>
                <div>{{ $booking->customer->phone }}</div>
              </div>
              @if($booking->customer->company_name)
                <div>
                  <div class="text-secondary small">Company</div>
                  <div>{{ $booking->customer->company_name }}</div>
                </div>
              @endif
            </div>
            <div class="mt-3">
              <a href="{{ route('admin.customers.show', $booking->customer) }}" class="link-primary small fw-medium">
                View Customer Details →
              </a>
            </div>
          </div>
        </div>

        {{-- Assignment --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Assignment</h5>
            <div class="vstack gap-3">
              <div>
                <div class="text-secondary small">Driver</div>
                @if($booking->driver)
                  <div>{{ $booking->driver->name }}</div>
                  <div class="small text-secondary">{{ $booking->driver->phone }}</div>
                @else
                  <div class="text-secondary">Not assigned</div>
                @endif
              </div>

              <div>
                <div class="text-secondary small">Porter(s)</div>
                @if($booking->porter_names)
                  <div>{{ $booking->porter_names }}</div>
                  @if($booking->hasMultiplePorters())
                    <div class="small text-secondary">Multiple porters assigned</div>
                  @elseif($booking->porter)
                    <div class="small text-secondary">{{ $booking->porter->phone }}</div>
                  @endif
                @else
                  <div class="text-secondary">Not assigned</div>
                @endif
              </div>

              <div>
                <div class="text-secondary small">Vehicle</div>
                @if($booking->vehicle)
                  <div>{{ $booking->vehicle->registration_number }}</div>
                  <div class="small text-secondary">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                @else
                  <div class="text-secondary">Not assigned</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Company Information --}}
        @if($booking->is_company_booking)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">Company Information</h5>
              <div class="vstack gap-2">
                @if($booking->company_name)
                  <div>
                    <div class="text-secondary small">Company Name</div>
                    <div class="fw-medium">{{ $booking->company_name }}</div>
                  </div>
                @endif
                @if($booking->company_phone)
                <div>
                    <div class="text-secondary small">Company Phone</div>
                    <div>{{ $booking->company_phone }}</div>
                </div>
                @endif
                <div>
                  <div class="text-secondary small">Commission Amount</div>
                  <div class="fw-medium text-success">£{{ number_format($booking->company_commission_amount, 2) }}</div>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Extra Hours --}}
        @if($booking->extra_hours && $booking->extra_hours > 0)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">Extra Hours</h5>
              <div class="vstack gap-2">
                <div>
                  <div class="text-secondary small">Extra Hours</div>
                  <div>{{ $booking->extra_hours }} hours</div>
                </div>
                <div>
                  <div class="text-secondary small">Rate per Hour</div>
                  <div>£{{ number_format($booking->extra_hours_rate, 2) }}</div>
                </div>
                <div>
                  <div class="text-secondary small">Extra Hours Total</div>
                  <div class="fw-medium">£{{ number_format($booking->extra_hours_amount, 2) }}</div>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Timeline --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Timeline</h5>
            <div class="vstack gap-2">
              <div>
                <div class="text-secondary small">Created</div>
                <div>{{ $booking->created_at->format('M d, Y g:i A') }}</div>
                <div class="small text-secondary">by {{ $booking->createdBy->name }}</div>
              </div>
              @if($booking->started_at)
                <div>
                  <div class="text-secondary small">Started</div>
                  <div>{{ $booking->started_at->format('M d, Y g:i A') }}</div>
                </div>
              @endif
              @if($booking->completed_at)
                <div>
                  <div class="text-secondary small">Completed</div>
                  <div>{{ $booking->completed_at->format('M d, Y g:i A') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>

      </aside>
    </div>
  </div>

  {{-- Extra Hours Modal --}}
  <div class="modal fade" id="addExtraHoursModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Extra Hours</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.bookings.extra-hours', $booking) }}">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="extra_hours" class="form-label">Extra Hours</label>
              <input type="number" min="1" class="form-control" id="extra_hours" name="extra_hours" required>
            </div>
            <div class="mb-3">
              <label for="extra_hours_rate" class="form-label">Rate per Hour</label>
              <input type="number" step="0.01" min="0" class="form-control" id="extra_hours_rate" name="extra_hours_rate" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Extra Hours</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Print Styles --}}
  @push('styles')
  <style>
    @media print {
      /* Hide unnecessary elements */
      .btn, button, .modal, nav, aside, .sidebar, header, footer,
      .no-print, .d-print-none, form, .border-top.pt-3.mt-3,
      .card-title, h1, h3, h5, h6, .text-secondary.mb-0 {
        display: none !important;
      }

      /* Reset page */
      body {
        margin: 0;
        padding: 20px;
        background: white;
        color: black;
      }

      .container-xxl, .container, .row, .col-12, .col-lg-8, .col-lg-4 {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      .card {
        border: none !important;
        box-shadow: none !important;
        page-break-inside: avoid;
        margin-bottom: 20px !important;
      }

      .card-body {
        padding: 0 !important;
      }

      /* Print Header */
      .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #000;
      }

      .print-header h1 {
        display: block !important;
        font-size: 28px;
        font-weight: bold;
        margin: 0 0 10px 0;
        color: #000;
      }

      .print-header p {
        display: block !important;
        font-size: 14px;
        margin: 5px 0;
        color: #333;
      }

      /* Section Headers */
      .print-section-title {
        display: block !important;
        font-size: 18px;
        font-weight: bold;
        margin: 20px 0 10px 0;
        padding-bottom: 5px;
        border-bottom: 2px solid #333;
        color: #000;
      }

      /* Tables */
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
      }

      table td, table th {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
      }

      table th {
        background-color: #f5f5f5;
        font-weight: bold;
      }

      /* Grid layout for print */
      .print-grid {
        display: table !important;
        width: 100%;
        margin-bottom: 15px;
      }

      .print-row {
        display: table-row !important;
      }

      .print-cell {
        display: table-cell !important;
        padding: 8px;
        border: 1px solid #ddd;
        vertical-align: top;
      }

      .print-cell-label {
        font-weight: bold;
        width: 40%;
      }

      /* Financial Summary */
      .print-financial {
        background-color: #f9f9f9;
        padding: 15px;
        margin: 20px 0;
        border: 2px solid #000;
      }

      .print-financial-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #ddd;
      }

      .print-financial-row:last-child {
        border-bottom: none;
        font-weight: bold;
        font-size: 16px;
        padding-top: 10px;
        border-top: 2px solid #000;
      }

      /* Status badge */
      .badge {
        display: inline-block !important;
        padding: 5px 10px;
        border: 2px solid #000;
        border-radius: 5px;
        font-weight: bold;
      }

      /* Page breaks */
      .page-break {
        page-break-after: always;
      }

      /* Footer */
      .print-footer {
        display: block !important;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #000;
        text-align: center;
        font-size: 12px;
      }
    }

    /* Print-only elements (hidden on screen) */
    .print-only {
      display: none;
    }

    @media print {
      .print-only {
        display: block !important;
      }
    }
  </style>
  @endpush

  {{-- Print Content (Hidden on screen, visible on print) --}}
  <div class="print-only">
    {{-- Print Header --}}
    <div class="print-header">
      <h1>BOOKING DETAILS</h1>
      <p><strong>Reference:</strong> {{ $booking->booking_reference }}</p>
      <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
      <p><strong>Status:</strong> <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', strtoupper($booking->status)) }}</span></p>
    </div>

    {{-- Customer Information --}}
    <div class="print-section-title">CUSTOMER INFORMATION</div>
    <div class="print-grid">
      <div class="print-row">
        <div class="print-cell print-cell-label">Customer Name:</div>
        <div class="print-cell">{{ $booking->customer->name }}</div>
      </div>
      <div class="print-row">
        <div class="print-cell print-cell-label">Email:</div>
        <div class="print-cell">{{ $booking->customer->email }}</div>
      </div>
      <div class="print-row">
        <div class="print-cell print-cell-label">Phone:</div>
        <div class="print-cell">{{ $booking->customer->phone }}</div>
      </div>
      @if($booking->customer->address)
      <div class="print-row">
        <div class="print-cell print-cell-label">Address:</div>
        <div class="print-cell">{{ $booking->customer->address }}</div>
      </div>
      @endif
    </div>

    {{-- Company Information (if applicable) --}}
    @if($booking->is_company_booking)
    <div class="print-section-title">COMPANY INFORMATION</div>
    <div class="print-grid">
      @if($booking->company_name)
      <div class="print-row">
        <div class="print-cell print-cell-label">Company Name:</div>
        <div class="print-cell">{{ $booking->company_name }}</div>
      </div>
      @endif
      @if($booking->company_phone)
      <div class="print-row">
        <div class="print-cell print-cell-label">Company Phone:</div>
        <div class="print-cell">{{ $booking->company_phone }}</div>
      </div>
      @endif
      @if($booking->company_commission_amount)
      <div class="print-row">
        <div class="print-cell print-cell-label">Commission Amount:</div>
        <div class="print-cell">£{{ number_format($booking->company_commission_amount, 2) }}</div>
      </div>
      @endif
    </div>
    @endif

    {{-- Booking Details --}}
    <div class="print-section-title">BOOKING DETAILS</div>
    <div class="print-grid">
      <div class="print-row">
        <div class="print-cell print-cell-label">Booking Date:</div>
        <div class="print-cell">{{ $booking->booking_date->format('F d, Y') }}</div>
      </div>
      @if($booking->start_date)
      <div class="print-row">
        <div class="print-cell print-cell-label">Start Date:</div>
        <div class="print-cell">{{ $booking->start_date->format('F d, Y') }}</div>
      </div>
      @endif
      @if($booking->end_date)
      <div class="print-row">
        <div class="print-cell print-cell-label">End Date:</div>
        <div class="print-cell">{{ $booking->end_date->format('F d, Y') }}</div>
      </div>
      @endif
      @if($booking->start_time)
      <div class="print-row">
        <div class="print-cell print-cell-label">Start Time:</div>
        <div class="print-cell">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</div>
      </div>
      @endif
      <div class="print-row">
        <div class="print-cell print-cell-label">Booking Type:</div>
        <div class="print-cell">{{ $booking->getBookingTypeLabel() }}</div>
      </div>
      @if($booking->source)
      <div class="print-row">
        <div class="print-cell print-cell-label">Source:</div>
        <div class="print-cell">{{ $booking->source }}</div>
      </div>
      @endif
      @if($booking->helpers_count)
      <div class="print-row">
        <div class="print-cell print-cell-label">Helpers:</div>
        <div class="print-cell">{{ $booking->helpers_count }} helper(s)</div>
      </div>
      @endif
    </div>

    {{-- Location Details --}}
    <div class="print-section-title">LOCATION DETAILS</div>
    <div class="print-grid">
      <div class="print-row">
        <div class="print-cell print-cell-label">Pickup Address:</div>
        <div class="print-cell">{{ $booking->pickup_address }}@if($booking->pickup_postcode), {{ $booking->pickup_postcode }}@endif</div>
      </div>
      @if($booking->via_address)
      <div class="print-row">
        <div class="print-cell print-cell-label">Via Address:</div>
        <div class="print-cell">{{ $booking->via_address }}</div>
      </div>
      @endif
      <div class="print-row">
        <div class="print-cell print-cell-label">Delivery Address:</div>
        <div class="print-cell">{{ $booking->delivery_address }}@if($booking->delivery_postcode), {{ $booking->delivery_postcode }}@endif</div>
      </div>
    </div>

    {{-- Job Description --}}
    @if($booking->job_description)
    <div class="print-section-title">JOB DESCRIPTION</div>
    <p style="padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">{{ $booking->job_description }}</p>
    @endif

    {{-- Special Instructions --}}
    @if($booking->special_instructions)
    <div class="print-section-title">SPECIAL INSTRUCTIONS</div>
    <p style="padding: 10px; border: 1px solid #ddd; background: #fff3cd;">{{ $booking->special_instructions }}</p>
    @endif

    {{-- Staff Assigned --}}
    <div class="print-section-title">STAFF ASSIGNED</div>
    <div class="print-grid">
      @if($booking->driver)
      <div class="print-row">
        <div class="print-cell print-cell-label">Driver:</div>
        <div class="print-cell">{{ $booking->driver->name }}</div>
      </div>
      @endif
      @if($booking->porter_names)
      <div class="print-row">
        <div class="print-cell print-cell-label">Porter(s):</div>
        <div class="print-cell">{{ $booking->porter_names }}</div>
      </div>
      @endif
      @if($booking->vehicle)
      <div class="print-row">
        <div class="print-cell print-cell-label">Vehicle:</div>
        <div class="print-cell">{{ $booking->vehicle->registration_number }} ({{ $booking->vehicle->make }} {{ $booking->vehicle->model }})</div>
      </div>
      @endif
    </div>

    {{-- Financial Summary --}}
    <div class="print-section-title">FINANCIAL SUMMARY</div>
    <div class="print-financial">
      <div class="print-financial-row">
        <span>Original Total:</span>
        <span>£{{ number_format($booking->total_fare, 2) }}</span>
      </div>
      @if($booking->discount > 0)
      <div class="print-financial-row">
        <span>Discount:</span>
        <span>-£{{ number_format($booking->discount, 2) }}</span>
      </div>
      @endif
      <div class="print-financial-row">
        <span>Final Total:</span>
        <span>£{{ number_format($booking->getFinalTotalFare(), 2) }}</span>
      </div>
      @if($booking->extra_hours && $booking->extra_hours > 0)
      <div class="print-financial-row">
        <span>Extra Hours ({{ $booking->extra_hours }} hrs @ £{{ number_format($booking->extra_hours_rate, 2) }}):</span>
        <span>+£{{ number_format($booking->extra_hours_amount, 2) }}</span>
      </div>
      @endif
      @php
        $totalRevenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
      @endphp
      <div class="print-financial-row">
        <span>Total Revenue:</span>
        <span>£{{ number_format($totalRevenue, 2) }}</span>
      </div>
      @if($booking->deposit > 0)
      <div class="print-financial-row">
        <span>Deposit Paid:</span>
        <span>£{{ number_format($booking->deposit, 2) }}</span>
      </div>
      @endif
      @if($booking->remaining_amount > 0)
      <div class="print-financial-row">
        <span><strong>Remaining Amount:</strong></span>
        <span><strong>£{{ number_format($booking->remaining_amount, 2) }}</strong></span>
      </div>
      @endif
      @if($booking->payment_method)
      <div class="print-financial-row" style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
        <span>Payment Method:</span>
        <span>{{ $booking->getPaymentMethodLabel() }}</span>
      </div>
      @endif
    </div>

    {{-- Expenses (if any) --}}
    @php
      $totalExpenses = $booking->getTotalExpenses();
    @endphp
    @if($totalExpenses > 0)
    <div class="print-section-title">EXPENSES</div>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Description</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($booking->expenses as $expense)
        <tr>
          <td>{{ $expense->expense_type_label }}</td>
          <td>{{ $expense->description ?? '-' }}</td>
          <td>£{{ number_format($expense->amount, 2) }}</td>
        </tr>
        @endforeach
        <tr style="font-weight: bold; background-color: #f5f5f5;">
          <td colspan="2">Total Expenses</td>
          <td>£{{ number_format($totalExpenses, 2) }}</td>
        </tr>
      </tbody>
    </table>
    @endif

    {{-- Net Profit --}}
    @php
      $netProfit = $booking->getNetProfit();
    @endphp
    @if($totalExpenses > 0 || $booking->is_company_booking)
    <div class="print-section-title">NET PROFIT</div>
    <div class="print-financial">
      <div class="print-financial-row">
        <span>Total Revenue:</span>
        <span>£{{ number_format($totalRevenue, 2) }}</span>
      </div>
      @if($totalExpenses > 0)
      <div class="print-financial-row">
        <span>Total Expenses:</span>
        <span>-£{{ number_format($totalExpenses, 2) }}</span>
      </div>
      @endif
      @if($booking->is_company_booking && $booking->company_commission_amount > 0)
      <div class="print-financial-row">
        <span>Company Commission:</span>
        <span>-£{{ number_format($booking->company_commission_amount, 2) }}</span>
      </div>
      @endif
      <div class="print-financial-row">
        <span><strong>NET PROFIT:</strong></span>
        <span><strong>£{{ number_format($netProfit, 2) }}</strong></span>
      </div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="print-footer">
      <p>This is a computer-generated document. No signature is required.</p>
      <p>Printed on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
  </div>
</x-admin.layouts.app>

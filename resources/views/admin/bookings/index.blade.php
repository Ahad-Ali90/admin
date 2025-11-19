{{-- resources/views/admin/bookings/index.blade.php --}}
<x-admin.layouts.app>
  {{-- ===== Color scheme tweaks (feel free to move to your app.css) ===== --}}
  <style>
    :root{
      --bs-primary:#4f46e5;           /* Indigo */
      --bs-primary-rgb:79,70,229;
      --bs-secondary:#6c757d;
      --bs-success:#16a34a;
      --bs-danger:#e11d48;
      --bs-warning:#f59e0b;
      --bs-info:#0ea5e9;
      --surface:#ffffff;
      --surface-2:#f8f9fb;
    }
    .card { border: 0; box-shadow: 0 2px 10px rgba(16,24,40,.06); }
    .btn-primary { box-shadow: 0 2px 6px rgba(var(--bs-primary-rgb),.25); }
    .badge-soft { background: rgba(0,0,0,.06); }
    .badge-pending   { background:#fff7ed; color:#9a3412; }      /* amber-50 / brown */
    .badge-confirmed { background:#ecfeff; color:#155e75; }      /* cyan-50 / teal */
    .badge-inprog    { background:#eef2ff; color:#3730a3; }      /* indigo-50 */
    .badge-completed { background:#ecfdf5; color:#065f46; }      /* emerald-50 */
    .badge-cancelled { background:#fef2f2; color:#991b1b; }      /* red-50 */
    .badge-not-converted { background:#f3f4f6; color:#000000; }  /* gray-100 / black */
    .text-truncate-1 { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .text-truncate-2 {
      display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
  </style>

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="d-flex flex-column gap-3 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <h1 class="h3 mb-1">Bookings</h1>
        <p class="text-secondary mb-0">Manage all your transport bookings</p>
      </div>
      <div class="w-100 w-md-auto">
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary w-100 w-md-auto">
          <i class="bi bi-plus-lg me-2"></i> New Booking
        </a>
      </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET">
          <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-3">
              <label for="search" class="form-label">Search</label>
              <input type="text" name="search" id="search" value="{{ request('search') }}"
                     class="form-control" placeholder="Reference, customer name, email, phone">
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
              <label for="status" class="form-label">Status</label>
              <select id="status" name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending"     @selected(request('status')=='pending')>Pending</option>
                <option value="confirmed"   @selected(request('status')=='confirmed')>Confirmed</option>
                <option value="in_progress" @selected(request('status')=='in_progress')>In Progress</option>
                <option value="completed"   @selected(request('status')=='completed')>Completed</option>
                <option value="cancelled"   @selected(request('status')=='cancelled')>Cancelled</option>
              </select>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
              <label for="date_from" class="form-label">From Date</label>
              <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
              <label for="date_to" class="form-label">To Date</label>
              <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-funnel me-2"></i> Filter
              </button>
              <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-x-circle me-2"></i> Clear
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Bookings list --}}
    @if($bookings->count())
      <div class="vstack gap-3">
        @foreach($bookings as $booking)
          @php
            $badgeClass = match($booking->status){
              'pending'       => 'badge-pending',
              'confirmed'     => 'badge-confirmed',
              'in_progress'   => 'badge-inprog',
              'completed'     => 'badge-completed',
              'cancelled'     => 'badge-cancelled',
              'not_converted' => 'badge-not-converted',
              default         => 'badge-soft'
            };
          @endphp

          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between">
                {{-- Left/meta --}}
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge {{ $badgeClass }} px-2 py-1 text-capitalize">
                      {{ str_replace('_',' ', $booking->status) }}
                    </span>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="link-primary fw-semibold text-truncate-1">
                      {{ $booking->booking_reference }}
                    </a>
                  </div>

                  <div class="text-truncate-1 fw-medium">{{ $booking->customer->name }}</div>

                  <div class="row g-2 mt-2 text-secondary small">
                    <div class="col-auto">
                      <i class="bi bi-calendar2-event me-1"></i>
                      @if($booking->start_date)
                        {{ $booking->start_date->format('M d, Y') }}
                      @else
                        {{ $booking->booking_date->format('M d, Y') }}
                      @endif
                    </div>
                    <div class="col-12 col-md-auto text-truncate-1">
                      <i class="bi bi-geo-alt me-1"></i>
                      {{ $booking->pickup_postcode }} → {{ $booking->delivery_postcode }}
                    </div>
                    <div class="col-auto">
                      <i class="bi bi-cash-coin me-1"></i>
                      £{{ number_format($booking->total_fare, 2) }}
                    </div>
                    @if($booking->is_company_booking && $booking->company_commission_amount > 0)
                      <div class="col-auto">
                        <i class="bi bi-building me-1"></i>
                        Commission: £{{ number_format($booking->company_commission_amount, 2) }}
                      </div>
                    @endif
                    @if($booking->discount > 0)
                      <div class="col-auto">
                        <i class="bi bi-percent me-1"></i>
                        Discount: £{{ number_format($booking->discount, 2) }}
                      </div>
                    @endif
                    @if($booking->deposit > 0)
                      <div class="col-auto">
                        <i class="bi bi-credit-card me-1"></i>
                        Deposit: £{{ number_format($booking->deposit, 2) }}
                      </div>
                    @endif
                    @if($booking->remaining_amount > 0)
                      <div class="col-auto">
                        <i class="bi bi-wallet me-1"></i>
                        Remaining: £{{ number_format($booking->remaining_amount, 2) }}
                      </div>
                    @endif
                  </div>

                  @if($booking->driver || $booking->porter_names)
                    <div class="d-flex flex-wrap gap-3 mt-2 small text-secondary">
                      @if($booking->driver)
                        <span><i class="bi bi-person-badge me-1"></i>Driver: {{ $booking->driver->name }}</span>
                      @endif
                      @if($booking->porter_names)
                        <span><i class="bi bi-person-workspace me-1"></i>Porter(s): {{ $booking->porter_names }}</span>
                      @endif
                    </div>
                  @endif
                </div>

                {{-- Right/actions --}}
                <div class="d-flex align-items-start gap-2 ms-lg-3">
                  <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" style="padding:4px 8px !important;border-radius:0.25rem !important">
                    <i class="bi bi-eye me-1"></i>
                  </a>
                  
                  @if($booking->is_company_booking && $booking->company)
                    <a href="{{ route('admin.invoices.create', $booking) }}" class="btn btn-sm btn-outline-info" style="padding:4px 8px !important;border-radius:0.25rem !important" title="Create/View Invoice">
                      <i class="bi bi-file-earmark-text me-1"></i> 
                    </a>
                  @endif
                  
                  @if($booking->canStart())
                    <form method="POST" action="{{ route('admin.bookings.start', $booking) }}" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-success" style="padding:4px 8px !important;border-radius:0.25rem !important">
                        <i class="bi bi-play-fill me-1"></i> Start
                      </button>
                    </form>
                  @endif

                  @if($booking->canComplete())
                    <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-success" style="padding:4px 8px !important;border-radius:0.25rem !important">
                        <i class="bi bi-check-circle-fill me-1"></i> Complete
                      </button>
                    </form>
                  @endif

                  <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i> Edit
                  </a>
                  
                  <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}"
                        onsubmit="return confirm('Are you sure you want to delete this booking?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash me-1"></i> Delete
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="card mt-4">
        <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
          <div class="small text-secondary order-2 order-md-1">
            {{-- Optional summary text --}}
          </div>
          <div class="order-1 order-md-2 w-100 w-md-auto overflow-auto">
            {{ $bookings->links() }}
          </div>
        </div>
      </div>
    @else
      <div class="card">
        <div class="card-body text-center py-5">
          <div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div>
          <h5 class="mb-1">No bookings found</h5>
          <p class="text-secondary mb-3">Get started by creating a new booking.</p>
          <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> New Booking
          </a>
        </div>
      </div>
    @endif
  </div>

  {{-- Bootstrap Icons (if not already loaded in your layout) --}}
  @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  @endpush
</x-admin.layouts.app>

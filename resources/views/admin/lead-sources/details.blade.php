{{-- resources/views/admin/lead-sources/details.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .stat-card {
      border-radius: 12px;
      padding: 1.5rem;
      height: 100%;
      transition: transform 0.2s;
      border: 0;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
      color: white;
    }
    .stat-card.purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card.green { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .stat-card.blue { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .stat-card.orange { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .stat-card.red { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stat-card.teal { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(16,24,40,.12);
    }
    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      background: rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
    
    .status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .status-pending { background: #fff7ed; color: #c2410c; }
    .status-confirmed { background: #dbeafe; color: #1e40af; }
    .status-in_progress { background: #ede9fe; color: #6b21a8; }
    .status-completed { background: #dcfce7; color: #15803d; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .source-header {
      background: linear-gradient(135deg, {{ $source->color }}20 0%, {{ $source->color }}10 100%);
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .filter-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
    }

    .booking-card {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1.25rem;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }

    .booking-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transform: translateY(-2px);
      border-color: #4f46e5;
    }

    [data-theme="dark"] .stat-card.purple,
    [data-theme="dark"] .stat-card.green,
    [data-theme="dark"] .stat-card.blue,
    [data-theme="dark"] .stat-card.orange,
    [data-theme="dark"] .stat-card.red,
    [data-theme="dark"] .stat-card.teal {
      /* Keep gradient backgrounds in dark mode */
      color: white !important;
    }

    [data-theme="dark"] .filter-card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .booking-card {
      background: var(--card-bg) !important;
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-header {
      background-color: var(--card-bg) !important;
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-header h5,
    [data-theme="dark"] .card-header .mb-0 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-body {
      background-color: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .source-header {
      background: linear-gradient(135deg, {{ $source->color }}30 0%, {{ $source->color }}15 100%) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .source-header h1,
    [data-theme="dark"] .source-header h2 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-secondary {
      color: #9ca3af !important;
    }

    [data-theme="dark"] .text-muted {
      color: #6b7280 !important;
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="source-header mb-4">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon" style="background: {{ $source->color }}20; color: {{ $source->color }};">
            <i class="bi {{ $source->icon ?? 'bi-megaphone' }}"></i>
          </div>
          <div>
            <h1 class="h2 mb-1">{{ $source->name }}</h1>
            <p class="text-muted mb-0">Lead Source Analytics & Bookings</p>
          </div>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.lead-sources.manage') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Sources
          </a>
        </div>
      </div>
    </div>

    {{-- Key Statistics (Dashboard Style) --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card purple">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-calendar-check"></i>
            </div>
            <div>
              <div class="opacity-90 small">Total Bookings</div>
              <div class="fs-3 fw-bold">{{ number_format($stats['total_bookings']) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card green">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-currency-pound"></i>
            </div>
            <div>
              <div class="opacity-90 small">Total Revenue</div>
              <div class="fs-3 fw-bold">£{{ number_format($stats['total_revenue'], 0) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card red">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-x-circle"></i>
            </div>
            <div>
              <div class="opacity-90 small">Total Expenses</div>
              <div class="fs-3 fw-bold">£{{ number_format($stats['total_expenses'], 0) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card orange">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
              <div class="opacity-90 small">Total Profit</div>
              <div class="fs-3 fw-bold">£{{ number_format($stats['total_profit'], 0) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="text-secondary small">Completed</div>
                <div class="fs-4 fw-bold text-success">{{ number_format($stats['completed_bookings']) }}</div>
              </div>
              <div class="bg-success bg-opacity-10 p-2 rounded">
                <i class="bi bi-check-circle text-success fs-5"></i>
              </div>
            </div>
            <div class="small text-secondary">Successfully Finished</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="text-secondary small">Pending</div>
                <div class="fs-4 fw-bold text-warning">{{ number_format($stats['pending_bookings']) }}</div>
              </div>
              <div class="bg-warning bg-opacity-10 p-2 rounded">
                <i class="bi bi-clock-history text-warning fs-5"></i>
              </div>
            </div>
            <div class="small text-secondary">Awaiting Confirmation</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="text-secondary small">Commission Paid</div>
                <div class="fs-4 fw-bold text-info">£{{ number_format($stats['total_commission_paid'], 2) }}</div>
              </div>
              <div class="bg-info bg-opacity-10 p-2 rounded">
                <i class="bi bi-building text-info fs-5"></i>
              </div>
            </div>
            <div class="small text-secondary">Total Company Commission</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Filters --}}
    <div class="filter-card">
      <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>Filters</h5>
      <form method="GET" action="{{ route('admin.lead-sources.details', $source->id) }}" class="row g-3">
        <div class="col-12 col-md-3">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label for="date_from" class="form-label">Date From</label>
          <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-12 col-md-3">
          <label for="date_to" class="form-label">Date To</label>
          <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-12 col-md-3 d-flex align-items-end gap-2">
          <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-search me-2"></i>Apply Filters
          </button>
          <a href="{{ route('admin.lead-sources.details', $source->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg"></i>
          </a>
        </div>
      </form>
    </div>

    {{-- Bookings List --}}
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Bookings ({{ $bookings->total() }})</h5>
      </div>
      <div class="card-body">
        @if($bookings->count() > 0)
          @foreach($bookings as $booking)
            <div class="booking-card">
              <div class="row g-3 align-items-center">
                <div class="col-12 col-md-3">
                  <div class="d-flex align-items-center gap-2">
                    <strong>#{{ $booking->booking_reference }}</strong>
                    <span class="status-badge status-{{ $booking->status }}">
                      {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                  </div>
                  <small class="text-muted d-block mt-1">
                    <i class="bi bi-calendar me-1"></i>{{ $booking->booking_date->format('M d, Y') }}
                  </small>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Customer</div>
                  <div class="fw-medium">{{ $booking->customer->name ?? 'N/A' }}</div>
                </div>
                <div class="col-12 col-md-2">
                  <div class="small text-muted">Amount</div>
                  <div class="fw-bold text-success">£{{ number_format($booking->manual_amount ?? 0, 2) }}</div>
                </div>
                <div class="col-12 col-md-2">
                  <div class="small text-muted">Expenses</div>
                  <div class="fw-bold text-danger">£{{ number_format($booking->expenses->sum('amount'), 2) }}</div>
                </div>
                <div class="col-12 col-md-2 text-end">
                  <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>View
                  </a>
                </div>
              </div>
              <!-- @if($booking->job_description)
                <div class="mt-2 pt-2 border-top">
                  <small class="text-muted">{{ Str::limit($booking->job_description, 100) }}</small>
                </div>
              @endif -->
            </div>
          @endforeach

          {{-- Pagination --}}
          <div class="mt-4">
            {{ $bookings->links() }}
          </div>
        @else
          <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-secondary d-block mb-3"></i>
            <h5 class="text-secondary">No Bookings Found</h5>
            <p class="text-muted">No bookings match your current filters.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-admin.layouts.app>


{{-- resources/views/admin/companies/details.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .stat-card {
      border-radius: 12px;
      padding: 20px;
      height: 100%;
      transition: transform 0.2s;
    }
    .stat-card:hover {
      transform: translateY(-2px);
    }
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
    .icon-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .icon-green { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .icon-orange { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .icon-purple { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .icon-red { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .icon-teal { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
    
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
    [data-theme="dark"] h3,
    [data-theme="dark"] h5 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-secondary,
    [data-theme="dark"] .small.text-secondary {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] .fw-medium,
    [data-theme="dark"] .fw-bold {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .stat-card {
      background: var(--card-bg) !important;
      border: 1px solid var(--border-color) !important;
    }

    [data-theme="dark"] .stat-card:hover {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .bg-light {
      background: var(--surface-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .bg-success.bg-opacity-10 {
      background: rgba(52, 211, 153, 0.15) !important;
    }

    [data-theme="dark"] .border-success {
      border-color: rgba(52, 211, 153, 0.4) !important;
    }

    [data-theme="dark"] .table {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table-light {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table-hover tbody tr:hover {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .table > :not(caption) > * > * {
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
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

    [data-theme="dark"] .display-6 {
      color: var(--text-color) !important;
      opacity: 0.6;
    }

    [data-theme="dark"] a {
      color: #818cf8 !important;
    }

    [data-theme="dark"] a:hover {
      color: #a5b4fc !important;
    }

    [data-theme="dark"] .progress {
      background: var(--surface-bg) !important;
    }

    [data-theme="dark"] .badge {
      color: white !important;
    }

    /* Dark mode status badges with better visibility */
    [data-theme="dark"] .status-pending { 
      background: rgba(251, 146, 60, 0.2) !important; 
      color: #fb923c !important; 
    }
    [data-theme="dark"] .status-confirmed { 
      background: rgba(96, 165, 250, 0.2) !important; 
      color: #60a5fa !important; 
    }
    [data-theme="dark"] .status-in_progress { 
      background: rgba(168, 85, 247, 0.2) !important; 
      color: #a855f7 !important; 
    }
    [data-theme="dark"] .status-completed { 
      background: rgba(52, 211, 153, 0.2) !important; 
      color: #34d399 !important; 
    }
    [data-theme="dark"] .status-cancelled { 
      background: rgba(248, 113, 113, 0.2) !important; 
      color: #f87171 !important; 
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="d-flex flex-column gap-3 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <div class="d-flex align-items-center gap-2 mb-2">
          <a href="{{ route('admin.companies.manage') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
          </a>
          <h1 class="h3 mb-0">{{ $company->name }}</h1>
        </div>
        <p class="text-secondary mb-0">Complete analytics and booking history</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.bookings.create') }}?company_id={{ $company->id }}" class="btn btn-primary">
          <i class="bi bi-plus-lg me-2"></i>New Booking
        </a>
      </div>
    </div>

    {{-- Company Info Card --}}
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title mb-3">Company Information</h5>
        <div class="row g-3">
          <div class="col-12 col-md-3">
            <div class="text-secondary small">Company Name</div>
            <div class="fw-medium">{{ $company->name }}</div>
          </div>
          @if($company->email)
          <div class="col-12 col-md-3">
            <div class="text-secondary small">Email</div>
            <div><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></div>
          </div>
          @endif
          @if($company->phone)
          <div class="col-12 col-md-3">
            <div class="text-secondary small">Phone</div>
            <div><a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></div>
          </div>
          @endif
          @if($company->address)
          <div class="col-12 col-md-3">
            <div class="text-secondary small">Address</div>
            <div>{{ $company->address }}</div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 g-md-4 mb-4">
      {{-- Total Bookings --}}
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-blue">
              <i class="bi bi-calendar-check text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Total Bookings</div>
              <div class="fs-4 fw-bold">{{ $stats['total_bookings'] }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Completed Bookings --}}
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-green">
              <i class="bi bi-check-circle text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Completed</div>
              <div class="fs-4 fw-bold">{{ $stats['completed_bookings'] }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Pending Bookings --}}
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-orange">
              <i class="bi bi-clock-history text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Pending</div>
              <div class="fs-4 fw-bold">{{ $stats['pending_bookings'] }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Total Revenue --}}
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-purple">
              <i class="bi bi-cash-stack text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Total Revenue</div>
              <div class="fs-4 fw-bold text-success">£{{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Commission Paid --}}
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-red">
              <i class="bi bi-arrow-down-circle text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Commission Paid</div>
              <div class="fs-4 fw-bold text-danger">£{{ number_format($stats['total_commission_paid'], 2) }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Commission Pending --}}
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-teal">
              <i class="bi bi-hourglass-split text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Commission Pending</div>
              <div class="fs-4 fw-bold text-warning">£{{ number_format($stats['total_commission_pending'], 2) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Financial Summary --}}
    <div class="row g-4 mb-4">
      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-4">Financial Summary</h5>
            <div class="vstack gap-3">
              <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <span class="text-secondary">Total Revenue</span>
                <span class="fs-5 fw-bold text-success">£{{ number_format($stats['total_revenue'], 2) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <span class="text-secondary">Commission Paid</span>
                <span class="fs-5 fw-bold text-danger">-£{{ number_format($stats['total_commission_paid'], 2) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center p-3 bg-success bg-opacity-10 rounded border border-success">
                <span class="fw-medium">Net Profit</span>
                <span class="fs-4 fw-bold text-success">£{{ number_format($stats['net_profit'], 2) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <span class="text-secondary">Average Commission/Booking</span>
                <span class="fs-6 fw-medium">£{{ number_format($stats['average_commission'], 2) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-4">Booking Status Distribution</h5>
            <div class="vstack gap-3">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="bg-success rounded-circle" style="width:12px;height:12px;"></div>
                  <span>Completed</span>
                </div>
                <span class="badge bg-success">{{ $stats['completed_bookings'] }}</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: {{ $stats['total_bookings'] > 0 ? ($stats['completed_bookings'] / $stats['total_bookings'] * 100) : 0 }}%"></div>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="bg-warning rounded-circle" style="width:12px;height:12px;"></div>
                  <span>Pending</span>
                </div>
                <span class="badge bg-warning">{{ $stats['pending_bookings'] }}</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" role="progressbar" 
                     style="width: {{ $stats['total_bookings'] > 0 ? ($stats['pending_bookings'] / $stats['total_bookings'] * 100) : 0 }}%"></div>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="bg-danger rounded-circle" style="width:12px;height:12px;"></div>
                  <span>Cancelled</span>
                </div>
                <span class="badge bg-danger">{{ $stats['cancelled_bookings'] }}</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-danger" role="progressbar" 
                     style="width: {{ $stats['total_bookings'] > 0 ? ($stats['cancelled_bookings'] / $stats['total_bookings'] * 100) : 0 }}%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="card-title mb-0">All Bookings</h5>
          <a href="{{ route('admin.bookings.index') }}?company_id={{ $company->id }}" class="btn btn-sm btn-outline-secondary">
            View All
          </a>
        </div>

        @if($company->bookings->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Reference</th>
                  <th>Customer</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th class="text-end">Revenue</th>
                  <th class="text-end">Commission</th>
                  <th class="text-end">Net</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($company->bookings as $booking)
                  @php
                    $revenue = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                    $commission = $booking->company_commission_amount ?? 0;
                    $net = $revenue - $commission;
                  @endphp
                  <tr>
                    <td class="fw-medium">{{ $booking->booking_reference }}</td>
                    <td>{{ $booking->customer->name ?? '-' }}</td>
                    <td>{{ $booking->start_date ? $booking->start_date->format('M d, Y') : $booking->booking_date->format('M d, Y') }}</td>
                    <td>
                      <span class="status-badge status-{{ $booking->status }}">
                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                      </span>
                    </td>
                    <td class="text-end">£{{ number_format($revenue, 2) }}</td>
                    <td class="text-end text-danger">£{{ number_format($commission, 2) }}</td>
                    <td class="text-end text-success fw-medium">£{{ number_format($net, 2) }}</td>
                    <td class="text-end">
                      <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="4" class="fw-bold">Total</td>
                  <td class="text-end fw-bold">£{{ number_format($stats['total_revenue'], 2) }}</td>
                  <td class="text-end fw-bold text-danger">£{{ number_format($stats['total_commission_paid'], 2) }}</td>
                  <td class="text-end fw-bold text-success">£{{ number_format($stats['net_profit'], 2) }}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div>
            <h5 class="mb-1">No bookings yet</h5>
            <p class="text-secondary mb-3">Create the first booking for this company.</p>
            <a href="{{ route('admin.bookings.create') }}?company_id={{ $company->id }}" class="btn btn-primary">
              <i class="bi bi-plus-lg me-2"></i>Create Booking
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-admin.layouts.app>


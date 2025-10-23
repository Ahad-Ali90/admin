{{-- resources/views/admin/dashboard.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .stat-icon{
      width:36px;height:36px;border-radius:.5rem;display:inline-flex;align-items:center;justify-content:center
    }
    .icon-indigo{background:#4f46e5}
    .icon-amber{background:#f59e0b}
    .icon-blue{background:#3b82f6}
    .icon-green{background:#16a34a}
    .dot{width:.5rem;height:.5rem;border-radius:9999px;background:#3b82f6}
    .badge-pending{background:#fff7ed;color:#9a3412}
    .badge-confirmed{background:#ecfeff;color:#155e75}
    .badge-inprog{background:#eef2ff;color:#3730a3}
    .badge-completed{background:#ecfdf5;color:#065f46}
    .badge-cancelled{background:#fef2f2;color:#991b1b}
  </style>
  @endpush

  @php
    $statusBadge = fn($s)=> match($s){
      'pending'     => 'badge-pending',
      'confirmed'   => 'badge-confirmed',
      'in_progress' => 'badge-inprog',
      'completed'   => 'badge-completed',
      'cancelled'   => 'badge-cancelled',
      default       => 'badge-secondary'
    };
  @endphp

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="mb-4">
      <h1 class="h3 mb-1">Dashboard</h1>
      <p class="text-secondary mb-0">Welcome back, {{ Auth::user()->name }}! Here's what's happening with TBR Transport.</p>
    </div>

    <!-- Stats Grid -->
    <div class="row g-3 g-sm-4 mb-4">
      <!-- Total Bookings -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-indigo">
                <i class="bi bi-journal-text text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">Total Bookings</div>
                <div class="fs-5 fw-semibold">{{ $stats['total_bookings'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Bookings -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-amber">
                <i class="bi bi-clock text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">Pending Bookings</div>
                <div class="fs-5 fw-semibold">{{ $stats['pending_bookings'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- In Progress -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-blue">
                <i class="bi bi-lightning-charge text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">In Progress</div>
                <div class="fs-5 fw-semibold">{{ $stats['in_progress_bookings'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Completed -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-green">
                <i class="bi bi-check2-circle text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">Completed</div>
                <div class="fs-5 fw-semibold">{{ $stats['completed_bookings'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Revenue Stats -->
    <div class="row g-3 g-sm-4 mb-4">
      <!-- Total Revenue -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-green">
                <i class="bi bi-currency-pound text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">Total Revenue</div>
                <div class="fs-5 fw-semibold">£{{ number_format($stats['total_revenue'], 2) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Monthly Revenue -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-blue">
                <i class="bi bi-bar-chart-line text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">This Month</div>
                <div class="fs-5 fw-semibold">£{{ number_format($stats['monthly_revenue'], 2) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Revenue -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon icon-amber">
                <i class="bi bi-wallet2 text-white"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-secondary small">Pending Revenue</div>
                <div class="fs-5 fw-semibold">£{{ number_format($stats['pending_revenue'], 2) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
      <!-- Recent Bookings -->
      <div class="col-12 col-xxl-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Recent Bookings</h5>

            @if($recent_bookings->count())
              <div class="list-group list-group-flush">
                @foreach($recent_bookings as $booking)
                  <div class="list-group-item px-0">
                    <div class="d-flex align-items-start gap-3">
                      <span class="badge {{ $statusBadge($booking->status) }} text-capitalize">
                        {{ str_replace('_',' ', $booking->status) }}
                      </span>
                      <div class="flex-grow-1">
                        <div class="fw-medium">{{ $booking->customer->name }}</div>
                        <div class="text-secondary small">
                          {{ $booking->booking_reference }} • {{ $booking->booking_date->format('M d, Y') }}
                        </div>
                      </div>
                      <div class="text-secondary small">
                        £{{ number_format($booking->total_amount, 2) }}
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center text-secondary py-4">No recent bookings</div>
            @endif

            <div class="mt-3">
              <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary w-100">
                View all bookings
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Upcoming Bookings -->
      <div class="col-12 col-xxl-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Upcoming Bookings</h5>

            @if($upcoming_bookings->count())
              <div class="list-group list-group-flush">
                @foreach($upcoming_bookings as $booking)
                  <div class="list-group-item px-0">
                    <div class="d-flex align-items-start gap-3">
                      <div class="dot flex-shrink-0 mt-2"></div>
                      <div class="flex-grow-1">
                        <div class="fw-medium">{{ $booking->customer->name }}</div>
                        <div class="text-secondary small">
                          {{ $booking->booking_date->format('M d, Y g:i A') }}
                        </div>
                      </div>
                      <div class="text-secondary small text-nowrap">
                        {{ $booking->pickup_postcode }} → {{ $booking->delivery_postcode }}
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center text-secondary py-4">No upcoming bookings</div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Maintenance Alerts -->
    @if($maintenance_alerts->count() > 0)
      <div class="alert alert-warning mt-4" role="alert">
        <div class="d-flex gap-3">
          <i class="bi bi-exclamation-triangle fs-5"></i>
          <div>
            <div class="fw-semibold">Vehicle Maintenance Alerts</div>
            <ul class="mb-0 mt-1 ps-3">
              @foreach($maintenance_alerts as $vehicle)
                <li class="small">
                  {{ $vehicle->registration_number }} —
                  @if($vehicle->isMotExpiring())
                    MOT expires {{ $vehicle->mot_expiry_date->format('M d, Y') }}
                  @elseif($vehicle->isInsuranceExpiring())
                    Insurance expires {{ $vehicle->insurance_expiry_date->format('M d, Y') }}
                  @elseif($vehicle->needsService())
                    Service due {{ $vehicle->next_service_due->format('M d, Y') }}
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif
  </div>
</x-admin.layouts.app>

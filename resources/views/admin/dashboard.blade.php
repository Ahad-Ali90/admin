{{-- resources/views/admin/dashboard.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06);border-radius:12px;transition:transform 0.2s}
    .card:hover{transform:translateY(-2px);box-shadow:0 4px 20px rgba(16,24,40,.12)}
    
    /* Stat Cards */
    .stat-card {
      border-radius:12px;
      padding:1.5rem;
      height:100%;
      background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color:white;
    }
    .stat-card.green{background:linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)}
    .stat-card.blue{background:linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)}
    .stat-card.orange{background:linear-gradient(135deg, #fa709a 0%, #fee140 100%)}
    .stat-card.purple{background:linear-gradient(135deg, #667eea 0%, #764ba2 100%)}
    .stat-card.red{background:linear-gradient(135deg, #f093fb 0%, #f5576c 100%)}
    .stat-card.teal{background:linear-gradient(135deg, #30cfd0 0%, #330867 100%)}
    
    .stat-icon{
      width:50px;
      height:50px;
      border-radius:12px;
      background:rgba(255,255,255,0.2);
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:1.5rem;
    }
    
    /* Status Badges */
    .status-badge{padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:600}
    .status-pending{background:#fff7ed;color:#c2410c}
    .status-confirmed{background:#dbeafe;color:#1e40af}
    .status-in_progress{background:#ede9fe;color:#6b21a8}
    .status-completed{background:#dcfce7;color:#15803d}
    .status-cancelled{background:#fee2e2;color:#991b1b}
    
    /* Chart Container */
    .chart-container{position:relative;height:300px}
    
    /* Alert Cards */
    .alert-card{
      border-left:4px solid;
      border-radius:8px;
      padding:1rem;
      margin-bottom:0.75rem;
    }
    .alert-warning{border-color:#f59e0b;background:#fffbeb}
    .alert-danger{border-color:#ef4444;background:#fef2f2}
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="mb-4">
      <h1 class="h2 mb-2">Dashboard</h1>
      <p class="text-secondary">Welcome back, {{ Auth::user()->name }}! Here's your business overview.</p>
    </div>

    {{-- Key Statistics --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card purple">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-calendar-check"></i>
            </div>
            <div>
              <div class="opacity-90 small">Total Bookings</div>
              <div class="fs-3 fw-bold">{{ $stats['total_bookings'] }}</div>
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
        <div class="stat-card blue">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-people"></i>
            </div>
            <div>
              <div class="opacity-90 small">Total Customers</div>
              <div class="fs-3 fw-bold">{{ $stats['total_customers'] }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card orange">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon">
              <i class="bi bi-truck"></i>
            </div>
            <div>
              <div class="opacity-90 small">Available Vehicles</div>
              <div class="fs-3 fw-bold">{{ $stats['available_vehicles'] }}/{{ $stats['total_vehicles'] }}</div>
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
                <div class="text-secondary small">This Month</div>
                <div class="fs-4 fw-bold text-success">£{{ number_format($stats['monthly_revenue'], 2) }}</div>
              </div>
              <div class="bg-success bg-opacity-10 p-2 rounded">
                <i class="bi bi-graph-up text-success fs-5"></i>
              </div>
            </div>
            <div class="small text-secondary">Monthly Revenue</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="text-secondary small">Pending</div>
                <div class="fs-4 fw-bold text-warning">{{ $stats['pending_bookings'] }}</div>
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
                <div class="text-secondary small">Completed</div>
                <div class="fs-4 fw-bold text-primary">{{ $stats['completed_bookings'] }}</div>
              </div>
              <div class="bg-primary bg-opacity-10 p-2 rounded">
                <i class="bi bi-check-circle text-primary fs-5"></i>
              </div>
            </div>
            <div class="small text-secondary">Successfully Finished</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Revenue & Status Charts in One Row --}}
    <div class="row g-4 mb-4">
      {{-- Revenue Chart --}}
      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-4">Revenue Overview (Last 6 Months)</h5>
            <div class="chart-container">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      {{-- Booking Status Chart --}}
      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-4">Booking Status Distribution</h5>
            <div class="row">
              <div class="col-md-6">
                <div class="chart-container" style="height:250px">
                  <canvas id="statusChart"></canvas>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex flex-column gap-3 justify-content-center h-100">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                      <div style="width:12px;height:12px;border-radius:50%;background:#fbbf24"></div>
                      <span>Pending</span>
                    </div>
                    <span class="fw-bold">{{ $booking_status['pending'] }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                      <div style="width:12px;height:12px;border-radius:50%;background:#60a5fa"></div>
                      <span>Confirmed</span>
                    </div>
                    <span class="fw-bold">{{ $booking_status['confirmed'] }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                      <div style="width:12px;height:12px;border-radius:50%;background:#a78bfa"></div>
                      <span>In Progress</span>
                    </div>
                    <span class="fw-bold">{{ $booking_status['in_progress'] }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                      <div style="width:12px;height:12px;border-radius:50%;background:#34d399"></div>
                      <span>Completed</span>
                    </div>
                    <span class="fw-bold">{{ $booking_status['completed'] }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                      <div style="width:12px;height:12px;border-radius:50%;background:#f87171"></div>
                      <span>Cancelled</span>
                    </div>
                    <span class="fw-bold">{{ $booking_status['cancelled'] }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      {{-- Left Column --}}
      <div class="col-12 col-lg-8">

        {{-- Recent Bookings --}}
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title mb-0">Recent Bookings</h5>
              <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Reference</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recent_bookings as $booking)
                    @php
                      $amount = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                    @endphp
                    <tr>
                      <td class="fw-medium">{{ $booking->booking_reference }}</td>
                      <td>{{ $booking->customer->name ?? '-' }}</td>
                      <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                      <td>
                        <span class="status-badge status-{{ $booking->status }}">
                          {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                      </td>
                      <td class="text-end fw-bold">£{{ number_format($amount, 2) }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-secondary py-4">No bookings yet</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Right Column --}}
      <div class="col-12 col-lg-4">
        {{-- Today's Bookings --}}
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Today's Schedule</h5>
            @forelse($today_bookings as $booking)
              <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                <div class="text-center">
                  <div class="fw-bold">{{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '--:--' }}</div>
                  <div class="small text-secondary">{{ $booking->booking_date->format('M d') }}</div>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-medium">{{ $booking->customer->name ?? 'N/A' }}</div>
                  <div class="small text-secondary">{{ Str::limit($booking->pickup_address, 30) }}</div>
                  <span class="status-badge status-{{ $booking->status }} mt-1">{{ ucfirst($booking->status) }}</span>
                </div>
              </div>
            @empty
              <div class="text-center text-secondary py-4">
                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                <div>No bookings today</div>
              </div>
            @endforelse
          </div>
        </div>

        {{-- Upcoming Bookings --}}
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Upcoming (Next 7 Days)</h5>
            @forelse($upcoming_bookings as $booking)
              <div class="d-flex gap-3 mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded">
                  <i class="bi bi-calendar-event text-primary"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-medium">{{ $booking->customer->name ?? 'N/A' }}</div>
                  <div class="small text-secondary">{{ $booking->booking_date->format('M d, Y') }}</div>
                </div>
              </div>
            @empty
              <div class="text-center text-secondary py-3">
                <div class="small">No upcoming bookings</div>
              </div>
            @endforelse
          </div>
        </div>

        {{-- Maintenance Alerts --}}
        @if($maintenance_alerts->count() > 0)
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3 text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>Maintenance Alerts
            </h5>
            @foreach($maintenance_alerts as $vehicle)
              <div class="alert-card alert-warning">
                <div class="fw-medium">{{ $vehicle->registration_number }}</div>
                <div class="small">
                  @if($vehicle->mot_expiry_date && $vehicle->mot_expiry_date->isPast())
                    <div class="text-danger">MOT Expired!</div>
                  @elseif($vehicle->mot_expiry_date && $vehicle->mot_expiry_date->lte(now()->addDays(30)))
                    <div>MOT expires {{ $vehicle->mot_expiry_date->format('M d') }}</div>
                  @endif
                  
                  @if($vehicle->insurance_expiry_date && $vehicle->insurance_expiry_date->lte(now()->addDays(30)))
                    <div>Insurance expires {{ $vehicle->insurance_expiry_date->format('M d') }}</div>
                  @endif
                  
                  @if($vehicle->next_service_due && $vehicle->next_service_due->lte(now()))
                    <div>Service overdue</div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Quick Stats --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Quick Stats</h5>
            <div class="d-flex justify-content-between mb-3">
              <span class="text-secondary">Active Drivers</span>
              <span class="fw-bold">{{ $stats['total_drivers'] }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span class="text-secondary">Active Porters</span>
              <span class="fw-bold">{{ $stats['total_porters'] }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span class="text-secondary">Companies</span>
              <span class="fw-bold">{{ $stats['total_companies'] }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary">In Progress</span>
              <span class="fw-bold text-primary">{{ $stats['in_progress_bookings'] }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: @json(array_column($monthlyRevenueData, 'month')),
        datasets: [{
          label: 'Revenue (£)',
          data: @json(array_column($monthlyRevenueData, 'revenue')),
          borderColor: 'rgb(102, 126, 234)',
          backgroundColor: 'rgba(102, 126, 234, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '£' + value.toLocaleString();
              }
            }
          }
        }
      }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: ['Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'],
        datasets: [{
          data: [
            {{ $booking_status['pending'] }},
            {{ $booking_status['confirmed'] }},
            {{ $booking_status['in_progress'] }},
            {{ $booking_status['completed'] }},
            {{ $booking_status['cancelled'] }}
          ],
          backgroundColor: [
            '#fbbf24',
            '#60a5fa',
            '#a78bfa',
            '#34d399',
            '#f87171'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>
  @endpush
</x-admin.layouts.app>

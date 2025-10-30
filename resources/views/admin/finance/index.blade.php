{{-- resources/views/admin/finance/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .finance-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
      padding: 1.5rem;
      height: 100%;
      transition: transform 0.2s;
    }
    
    .finance-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(16,24,40,.12);
    }
    
    .stat-card-finance {
      border-radius: 12px;
      padding: 1.5rem;
      height: 100%;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card-finance::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 150px;
      height: 150px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      transform: translate(50%, -50%);
    }
    
    .stat-card-finance.revenue {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card-finance.expenses {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card-finance.profit {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-card-finance.margin {
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stat-icon-finance {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      background: rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    
    .view-toggle {
      background: white;
      border-radius: 12px;
      padding: 0.5rem;
      display: inline-flex;
      gap: 0.5rem;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
    }
    
    .view-toggle button {
      padding: 0.5rem 1.5rem;
      border: none;
      background: transparent;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 500;
    }
    
    .view-toggle button.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    .chart-container-finance {
      position: relative;
      height: 400px;
      margin-top: 1rem;
    }
    
    .data-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .data-table thead th {
      background: #f9fafb;
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      color: #374151;
      border-bottom: 2px solid #e5e7eb;
    }
    
    .data-table tbody td {
      padding: 1rem;
      border-bottom: 1px solid #f3f4f6;
    }
    
    .data-table tbody tr:hover {
      background: #f9fafb;
    }
    
    .profit-positive {
      color: #16a34a;
      font-weight: 600;
    }
    
    .profit-negative {
      color: #dc2626;
      font-weight: 600;
    }
    
    .badge-profit {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .badge-high {
      background: #dcfce7;
      color: #15803d;
    }
    
    .badge-medium {
      background: #fef3c7;
      color: #92400e;
    }
    
    .badge-low {
      background: #fee2e2;
      color: #991b1b;
    }
    
    .month-selector {
      background: white;
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .month-selector select {
      padding: 0.5rem 1rem;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      cursor: pointer;
    }
    
    .insight-card {
      background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
      border-left: 4px solid #667eea;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
    
    .insight-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #667eea;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }
    
    @media (max-width: 768px) {
      .stat-card-finance {
        padding: 1rem;
      }
      
      .stat-icon-finance {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
      }
      
      .view-toggle button {
        padding: 0.4rem 1rem;
        font-size: 0.875rem;
      }
      
      .chart-container-finance {
        height: 300px;
      }
      
      .data-table {
        font-size: 0.875rem;
      }
      
      .data-table thead th,
      .data-table tbody td {
        padding: 0.75rem 0.5rem;
      }
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h1 class="h2 mb-2">Profit & Loss Analysis</h1>
        <p class="text-secondary mb-0">Track your business financial performance</p>
      </div>
      
      <div class="view-toggle">
        <button class="{{ $view === 'monthly' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('admin.finance.index', ['view' => 'monthly']) }}'">
          <i class="bi bi-calendar-month me-2"></i>Monthly
        </button>
        <button class="{{ $view === 'weekly' ? 'active' : '' }}" 
                onclick="window.location.href='{{ route('admin.finance.index', ['view' => 'weekly', 'year' => $year, 'month' => $month]) }}'">
          <i class="bi bi-calendar-week me-2"></i>Weekly
        </button>
      </div>
    </div>

    {{-- Overall Statistics --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-finance revenue">
          <div class="stat-icon-finance">
            <i class="bi bi-cash-stack"></i>
          </div>
          <div class="opacity-90 mb-1">Total Revenue</div>
          <div class="fs-3 fw-bold">£{{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-finance expenses">
          <div class="stat-icon-finance">
            <i class="bi bi-wallet2"></i>
          </div>
          <div class="opacity-90 mb-1">Total Expenses</div>
          <div class="fs-3 fw-bold">£{{ number_format($stats['total_expenses'], 2) }}</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-finance profit">
          <div class="stat-icon-finance">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
          <div class="opacity-90 mb-1">Net Profit</div>
          <div class="fs-3 fw-bold">£{{ number_format($stats['total_profit'], 2) }}</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-finance margin">
          <div class="stat-icon-finance">
            <i class="bi bi-percent"></i>
          </div>
          <div class="opacity-90 mb-1">Profit Margin</div>
          <div class="fs-3 fw-bold">{{ number_format($stats['profit_margin'], 1) }}%</div>
        </div>
      </div>
    </div>

    @if($view === 'monthly')
      {{-- Monthly View --}}
      <div class="row g-4">
        <div class="col-12 col-lg-8">
          {{-- Monthly Chart --}}
          <div class="finance-card">
            <h5 class="mb-3">Monthly Profit & Loss Trend (Last 12 Months)</h5>
            <div class="chart-container-finance">
              <canvas id="monthlyChart"></canvas>
            </div>
          </div>

          {{-- Monthly Data Table --}}
          <div class="finance-card mt-4">
            <h5 class="mb-3">Monthly Breakdown</h5>
            <div class="table-responsive">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Month</th>
                    <th>Bookings</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Expenses</th>
                    <th class="text-end">Profit/Loss</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($monthlyData as $data)
                    <tr>
                      <td class="fw-medium">{{ $data['month'] }}</td>
                      <td>{{ $data['bookings_count'] }}</td>
                      <td class="text-end">£{{ number_format($data['revenue'], 2) }}</td>
                      <td class="text-end">£{{ number_format($data['expenses'], 2) }}</td>
                      <td class="text-end {{ $data['profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                        £{{ number_format($data['profit'], 2) }}
                      </td>
                      <td class="text-center">
                        @if($data['profit'] > 5000)
                          <span class="badge-profit badge-high">High</span>
                        @elseif($data['profit'] > 0)
                          <span class="badge-profit badge-medium">Medium</span>
                        @else
                          <span class="badge-profit badge-low">Loss</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          {{-- Best & Worst Months --}}
          <div class="finance-card mb-4">
            <h5 class="mb-3">Performance Insights</h5>
            
            @php
              $bestMonth = collect($monthlyData)->sortByDesc('profit')->first();
              $worstMonth = collect($monthlyData)->sortBy('profit')->first();
              $avgProfit = collect($monthlyData)->avg('profit');
            @endphp

            <div class="insight-card">
              <div class="d-flex gap-3">
                <div class="insight-icon">
                  <i class="bi bi-trophy"></i>
                </div>
                <div>
                  <div class="small text-secondary">Best Month</div>
                  <div class="fw-bold">{{ $bestMonth['month'] }}</div>
                  <div class="text-success fw-bold">£{{ number_format($bestMonth['profit'], 2) }}</div>
                </div>
              </div>
            </div>

            <div class="insight-card" style="background: linear-gradient(135deg, #f5576c15 0%, #f093fb15 100%); border-color: #f5576c;">
              <div class="d-flex gap-3">
                <div class="insight-icon" style="background: #f5576c;">
                  <i class="bi bi-arrow-down-circle"></i>
                </div>
                <div>
                  <div class="small text-secondary">Worst Month</div>
                  <div class="fw-bold">{{ $worstMonth['month'] }}</div>
                  <div class="{{ $worstMonth['profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                    £{{ number_format($worstMonth['profit'], 2) }}
                  </div>
                </div>
              </div>
            </div>

            <div class="insight-card" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%); border-color: #4facfe;">
              <div class="d-flex gap-3">
                <div class="insight-icon" style="background: #4facfe;">
                  <i class="bi bi-graph-up"></i>
                </div>
                <div>
                  <div class="small text-secondary">Average Monthly Profit</div>
                  <div class="fw-bold fs-5">£{{ number_format($avgProfit, 2) }}</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Quick Stats --}}
          <div class="finance-card">
            <h5 class="mb-3">Quick Stats</h5>
            @php
              $profitableMonths = collect($monthlyData)->where('profit', '>', 0)->count();
              $totalBookings = collect($monthlyData)->sum('bookings_count');
            @endphp
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span class="text-secondary">Profitable Months</span>
              <span class="fw-bold">{{ $profitableMonths }}/12</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span class="text-secondary">Total Bookings</span>
              <span class="fw-bold">{{ $totalBookings }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary">Avg Bookings/Month</span>
              <span class="fw-bold">{{ round($totalBookings / 12) }}</span>
            </div>
          </div>
        </div>
      </div>

    @else
      {{-- Weekly View --}}
      <div class="month-selector mb-4">
        <label class="fw-medium">Select Month:</label>
        <select id="monthSelect" class="form-select" style="width: auto;">
          @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
              {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
            </option>
          @endfor
        </select>
        <select id="yearSelect" class="form-select" style="width: auto;">
          @for($y = now()->year; $y >= now()->year - 2; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
        </select>
        <button class="btn btn-primary" onclick="applyFilter()">
          <i class="bi bi-funnel me-2"></i>Apply
        </button>
      </div>

      <div class="row g-4">
        <div class="col-12 col-lg-8">
          {{-- Weekly Chart --}}
          <div class="finance-card">
            <h5 class="mb-3">Weekly Profit & Loss - {{ $selectedDate->format('F Y') }}</h5>
            <div class="chart-container-finance">
              <canvas id="weeklyChart"></canvas>
            </div>
          </div>

          {{-- Weekly Data Table --}}
          <div class="finance-card mt-4">
            <h5 class="mb-3">Weekly Breakdown</h5>
            <div class="table-responsive">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Week</th>
                    <th>Date Range</th>
                    <th>Bookings</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Expenses</th>
                    <th class="text-end">Profit/Loss</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($weeklyData as $data)
                    <tr>
                      <td class="fw-medium">{{ $data['week'] }}</td>
                      <td>{{ $data['date_range'] }}</td>
                      <td>{{ $data['bookings_count'] }}</td>
                      <td class="text-end">£{{ number_format($data['revenue'], 2) }}</td>
                      <td class="text-end">£{{ number_format($data['expenses'], 2) }}</td>
                      <td class="text-end {{ $data['profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                        £{{ number_format($data['profit'], 2) }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-secondary py-4">
                        No data available for this month
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          {{-- Best & Worst Weeks --}}
          <div class="finance-card mb-4">
            <h5 class="mb-3">Weekly Insights</h5>
            
            @if(count($weeklyData) > 0)
              @php
                $bestWeek = collect($weeklyData)->sortByDesc('profit')->first();
                $worstWeek = collect($weeklyData)->sortBy('profit')->first();
                $avgWeeklyProfit = collect($weeklyData)->avg('profit');
              @endphp

              <div class="insight-card">
                <div class="d-flex gap-3">
                  <div class="insight-icon">
                    <i class="bi bi-trophy"></i>
                  </div>
                  <div>
                    <div class="small text-secondary">Best Week</div>
                    <div class="fw-bold">{{ $bestWeek['week'] }}</div>
                    <div class="small text-muted">{{ $bestWeek['date_range'] }}</div>
                    <div class="text-success fw-bold">£{{ number_format($bestWeek['profit'], 2) }}</div>
                  </div>
                </div>
              </div>

              <div class="insight-card" style="background: linear-gradient(135deg, #f5576c15 0%, #f093fb15 100%); border-color: #f5576c;">
                <div class="d-flex gap-3">
                  <div class="insight-icon" style="background: #f5576c;">
                    <i class="bi bi-arrow-down-circle"></i>
                  </div>
                  <div>
                    <div class="small text-secondary">Worst Week</div>
                    <div class="fw-bold">{{ $worstWeek['week'] }}</div>
                    <div class="small text-muted">{{ $worstWeek['date_range'] }}</div>
                    <div class="{{ $worstWeek['profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                      £{{ number_format($worstWeek['profit'], 2) }}
                    </div>
                  </div>
                </div>
              </div>

              <div class="insight-card" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%); border-color: #4facfe;">
                <div class="d-flex gap-3">
                  <div class="insight-icon" style="background: #4facfe;">
                    <i class="bi bi-graph-up"></i>
                  </div>
                  <div>
                    <div class="small text-secondary">Average Weekly Profit</div>
                    <div class="fw-bold fs-5">£{{ number_format($avgWeeklyProfit, 2) }}</div>
                  </div>
                </div>
              </div>
            @else
              <div class="text-center text-secondary py-4">
                <i class="bi bi-info-circle fs-1 d-block mb-2"></i>
                <div>No data for this month</div>
              </div>
            @endif
          </div>

          {{-- Month Summary --}}
          <div class="finance-card">
            <h5 class="mb-3">Month Summary</h5>
            @php
              $monthRevenue = collect($weeklyData)->sum('revenue');
              $monthExpenses = collect($weeklyData)->sum('expenses');
              $monthProfit = collect($weeklyData)->sum('profit');
              $totalWeekBookings = collect($weeklyData)->sum('bookings_count');
            @endphp
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span class="text-secondary">Total Revenue</span>
              <span class="fw-bold text-success">£{{ number_format($monthRevenue, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span class="text-secondary">Total Expenses</span>
              <span class="fw-bold text-danger">£{{ number_format($monthExpenses, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span class="text-secondary">Net Profit</span>
              <span class="fw-bold {{ $monthProfit >= 0 ? 'text-success' : 'text-danger' }}">
                £{{ number_format($monthProfit, 2) }}
              </span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary">Total Bookings</span>
              <span class="fw-bold">{{ $totalWeekBookings }}</span>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    @if($view === 'monthly')
      // Monthly Chart
      const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
      new Chart(monthlyCtx, {
        type: 'bar',
        data: {
          labels: @json(array_column($monthlyData, 'month_short')),
          datasets: [
            {
              label: 'Revenue',
              data: @json(array_column($monthlyData, 'revenue')),
              backgroundColor: 'rgba(102, 126, 234, 0.8)',
              borderRadius: 8
            },
            {
              label: 'Expenses',
              data: @json(array_column($monthlyData, 'expenses')),
              backgroundColor: 'rgba(245, 85, 108, 0.8)',
              borderRadius: 8
            },
            {
              label: 'Profit',
              data: @json(array_column($monthlyData, 'profit')),
              backgroundColor: 'rgba(79, 172, 254, 0.8)',
              borderRadius: 8
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
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
    @else
      // Weekly Chart
      const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
      new Chart(weeklyCtx, {
        type: 'line',
        data: {
          labels: @json(array_column($weeklyData, 'week')),
          datasets: [
            {
              label: 'Revenue',
              data: @json(array_column($weeklyData, 'revenue')),
              borderColor: 'rgb(102, 126, 234)',
              backgroundColor: 'rgba(102, 126, 234, 0.1)',
              tension: 0.4,
              fill: true
            },
            {
              label: 'Expenses',
              data: @json(array_column($weeklyData, 'expenses')),
              borderColor: 'rgb(245, 85, 108)',
              backgroundColor: 'rgba(245, 85, 108, 0.1)',
              tension: 0.4,
              fill: true
            },
            {
              label: 'Profit',
              data: @json(array_column($weeklyData, 'profit')),
              borderColor: 'rgb(79, 172, 254)',
              backgroundColor: 'rgba(79, 172, 254, 0.1)',
              tension: 0.4,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
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

      function applyFilter() {
        const month = document.getElementById('monthSelect').value;
        const year = document.getElementById('yearSelect').value;
        window.location.href = `{{ route('admin.finance.index') }}?view=weekly&month=${month}&year=${year}`;
      }
    @endif
  </script>
  @endpush
</x-admin.layouts.app>


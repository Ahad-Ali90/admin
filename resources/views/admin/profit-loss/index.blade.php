{{-- resources/views/admin/profit-loss/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    :root {
      --primary: #4f46e5;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-800: #1f2937;
    }

    /* Summary Cards */
    .summary-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border-left: 4px solid;
    }
    
    .summary-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .summary-card.revenue { border-color: var(--primary); }
    .summary-card.expense { border-color: var(--danger); }
    .summary-card.profit { border-color: var(--success); }
    .summary-card.margin { border-color: var(--warning); }
    
    .summary-card .icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .summary-card.revenue .icon { background: rgba(79, 70, 229, 0.1); color: var(--primary); }
    .summary-card.expense .icon { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .summary-card.profit .icon { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .summary-card.margin .icon { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    
    .summary-card .label {
      font-size: 0.875rem;
      color: #6b7280;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .summary-card .value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--gray-800);
      line-height: 1;
    }

    /* Filter Section */
    .filter-section {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }
    
    .filter-section h6 {
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--gray-800);
    }

    /* Report Tables */
    .report-table {
      width: 100%;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }
    
    .report-table thead {
      background: var(--gray-100);
    }
    
    .report-table th {
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      font-size: 0.875rem;
      color: var(--gray-800);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .report-table td {
      padding: 1rem;
      border-top: 1px solid var(--gray-200);
      color: #374151;
    }
    
    .report-table tbody tr:hover {
      background: var(--gray-100);
    }

    /* Expense Type Badge */
    .expense-badge {
      padding: 0.35rem 0.75rem;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: capitalize;
    }
    
    .expense-badge.driver_payment { background: #dbeafe; color: #1e40af; }
    .expense-badge.porter_payment { background: #e0e7ff; color: #4338ca; }
    .expense-badge.fuel { background: #fed7aa; color: #9a3412; }
    .expense-badge.congestion_charge { background: #fecaca; color: #991b1b; }
    .expense-badge.ulez_charge { background: #fef3c7; color: #92400e; }
    .expense-badge.toll_charge { background: #f3e8ff; color: #6b21a8; }
    .expense-badge.parking { background: #d1fae5; color: #065f46; }
    .expense-badge.maintenance { background: #e0f2fe; color: #075985; }
    .expense-badge.other { background: #f3f4f6; color: #374151; }

    /* Section Headers */
    .section-header {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 1.5rem;
      padding-bottom: 0.75rem;
      border-bottom: 2px solid var(--gray-200);
    }

    /* Profit/Loss Color */
    .text-profit { color: var(--success); }
    .text-loss { color: var(--danger); }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .summary-card .value { font-size: 1.5rem; }
      .report-table { font-size: 0.875rem; }
      .report-table th, .report-table td { padding: 0.75rem 0.5rem; }
      
      /* Horizontal scroll for tables */
      .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
    }

    /* Dark Mode Support */
    [data-theme="dark"] .summary-card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .summary-card .label {
      color: var(--text-color) !important;
      opacity: 0.8;
    }

    [data-theme="dark"] .summary-card .value {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .filter-section {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .filter-section h6 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .report-table {
      background: var(--card-bg) !important;
    }

    [data-theme="dark"] .report-table thead {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .report-table th {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .report-table td {
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .report-table tbody tr:hover {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-body {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .expense-badge {
      border: 1px solid var(--border-color) !important;
    }

    [data-theme="dark"] .section-header {
      color: var(--text-color) !important;
      border-bottom: 2px solid var(--border-color) !important;
    }

    [data-theme="dark"] .text-profit,
    [data-theme="dark"] .text-loss {
      filter: brightness(1.2);
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="mb-4">
      <h1 class="h2 mb-2">Profit & Loss Report</h1>
      <p class="text-secondary">Comprehensive financial overview of revenue, expenses, and profitability.</p>
    </div>

    {{-- Filters --}}
    <div class="filter-section">
      <h6><i class="bi bi-funnel me-2"></i>Filter Report</h6>
      <form method="GET" action="{{ route('admin.profit-loss.index') }}">
        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" id="year" class="form-select">
              @for($y = now()->year; $y >= now()->year - 3; $y--)
                <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
              @endfor
            </select>
          </div>
          
          <div class="col-12 col-md-3">
            <label for="month" class="form-label">Month</label>
            <select name="month" id="month" class="form-select">
              @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($month == $m)>
                  {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                </option>
              @endfor
            </select>
          </div>
          
          <div class="col-12 col-md-3">
            <label for="week" class="form-label">Week (Optional)</label>
            <select name="week" id="week" class="form-select">
              <option value="">All Weeks</option>
              @foreach($weeks as $w)
                <option value="{{ $w['number'] }}" @selected($week == $w['number'])>
                  Week {{ $w['number'] }} ({{ $w['start'] }} - {{ $w['end'] }})
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="col-12 col-md-3">
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-search me-2"></i>Apply Filter
            </button>
          </div>
        </div>
      </form>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="summary-card revenue">
          <div class="icon"><i class="bi bi-cash-stack"></i></div>
          <div class="label">Total Revenue</div>
          <div class="value">£{{ number_format($totalRevenue, 2) }}</div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="summary-card expense">
          <div class="icon"><i class="bi bi-receipt"></i></div>
          <div class="label">Total Expenses</div>
          <div class="value">£{{ number_format($totalExpenses, 2) }}</div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="summary-card profit">
          <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
          <div class="label">Net Profit</div>
          <div class="value {{ $netProfit >= 0 ? 'text-profit' : 'text-loss' }}">
            £{{ number_format($netProfit, 2) }}
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="summary-card margin">
          <div class="icon"><i class="bi bi-percent"></i></div>
          <div class="label">Profit Margin</div>
          <div class="value {{ $profitMargin >= 0 ? 'text-profit' : 'text-loss' }}">
            {{ number_format($profitMargin, 1) }}%
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Left Column: Reports --}}
      <div class="col-12 col-lg-8">
        
        {{-- Weekly Breakdown --}}
        @if(!$week)
        <div class="mb-4">
          <h5 class="section-header">
            <i class="bi bi-calendar-week me-2"></i>Weekly Breakdown - {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
          </h5>
          <div class="table-responsive">
            <table class="report-table">
              <thead>
                <tr>
                  <th>Week</th>
                  <th>Period</th>
                  <th>Bookings</th>
                  <th class="text-end">Revenue</th>
                  <th class="text-end">Expenses</th>
                  <th class="text-end">Profit</th>
                </tr>
              </thead>
              <tbody>
                @forelse($weeks as $w)
                  <tr>
                    <td><strong>Week {{ $w['number'] }}</strong></td>
                    <td>{{ $w['start'] }} - {{ $w['end'] }}</td>
                    <td>{{ $w['bookings_count'] }}</td>
                    <td class="text-end">£{{ number_format($w['revenue'], 2) }}</td>
                    <td class="text-end">£{{ number_format($w['expenses'], 2) }}</td>
                    <td class="text-end {{ $w['profit'] >= 0 ? 'text-profit' : 'text-loss' }}">
                      <strong>£{{ number_format($w['profit'], 2) }}</strong>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-secondary">No data available</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        @endif

        {{-- Monthly Breakdown --}}
        <div class="mb-4">
          <h5 class="section-header">
            <i class="bi bi-calendar3 me-2"></i>Monthly Breakdown - {{ $year }}
          </h5>
          <div class="table-responsive">
            <table class="report-table">
              <thead>
                <tr>
                  <th>Month</th>
                  <th>Bookings</th>
                  <th class="text-end">Revenue</th>
                  <th class="text-end">Expenses</th>
                  <th class="text-end">Profit</th>
                </tr>
              </thead>
              <tbody>
                @foreach($months as $m)
                  <tr>
                    <td><strong>{{ $m['name'] }}</strong></td>
                    <td>{{ $m['bookings_count'] }}</td>
                    <td class="text-end">£{{ number_format($m['revenue'], 2) }}</td>
                    <td class="text-end">£{{ number_format($m['expenses'], 2) }}</td>
                    <td class="text-end {{ $m['profit'] >= 0 ? 'text-profit' : 'text-loss' }}">
                      <strong>£{{ number_format($m['profit'], 2) }}</strong>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- Detailed Expenses List --}}
        <div class="mb-4">
          <h5 class="section-header">
            <i class="bi bi-list-ul me-2"></i>Detailed Expenses
          </h5>
          <div class="table-responsive">
            <table class="report-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Booking</th>
                  <th>Customer</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody>
                @forelse($allExpenses as $expense)
                  <tr>
                    <td>{{ $expense->created_at->format('M d, Y') }}</td>
                    <td>
                      <a href="{{ route('admin.bookings.show', $expense->booking) }}" class="text-decoration-none">
                        {{ $expense->booking->booking_reference }}
                      </a>
                    </td>
                    <td>{{ $expense->booking->customer->name }}</td>
                    <td>
                      <span class="expense-badge {{ $expense->expense_type }}">
                        {{ str_replace('_', ' ', $expense->expense_type) }}
                      </span>
                    </td>
                    <td>{{ $expense->description ?: '-' }}</td>
                    <td class="text-end"><strong>£{{ number_format($expense->amount, 2) }}</strong></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-secondary">No expenses recorded</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>

      {{-- Right Column: Summary --}}
      <div class="col-12 col-lg-4">
        
        {{-- Expenses Breakdown by Type --}}
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-pie-chart me-2"></i>Expenses by Type
            </h6>
            
            @if($expensesByType->count() > 0)
              <div class="vstack gap-3">
                @foreach($expensesByType as $type => $amount)
                  <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="expense-badge {{ $type }}">
                        {{ str_replace('_', ' ', $type) }}
                      </span>
                      <strong>£{{ number_format($amount, 2) }}</strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar" 
                           style="width: {{ $totalExpenses > 0 ? ($amount / $totalExpenses) * 100 : 0 }}%"></div>
                    </div>
                    <div class="small text-secondary">
                      {{ $totalExpenses > 0 ? number_format(($amount / $totalExpenses) * 100, 1) : 0 }}% of total
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-secondary text-center mb-0">No expenses recorded</p>
            @endif
          </div>
        </div>

        {{-- Company Commission --}}
        @if($totalCommission > 0)
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-building me-2"></i>Company Commission
            </h6>
            <div class="text-center">
              <div class="text-secondary small mb-1">Total Commission Paid</div>
              <div class="fs-3 fw-bold text-danger">£{{ number_format($totalCommission, 2) }}</div>
            </div>
          </div>
        </div>
        @endif

        {{-- Quick Stats --}}
        <div class="card">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-info-circle me-2"></i>Quick Stats
            </h6>
            <div class="vstack gap-3">
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Total Bookings</span>
                <strong>{{ $bookings->count() }}</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Avg Revenue/Booking</span>
                <strong>£{{ $bookings->count() > 0 ? number_format($totalRevenue / $bookings->count(), 2) : '0.00' }}</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Avg Expense/Booking</span>
                <strong>£{{ $bookings->count() > 0 ? number_format($totalExpenses / $bookings->count(), 2) : '0.00' }}</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Avg Profit/Booking</span>
                <strong class="{{ $netProfit >= 0 ? 'text-profit' : 'text-loss' }}">
                  £{{ $bookings->count() > 0 ? number_format($netProfit / $bookings->count(), 2) : '0.00' }}
                </strong>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</x-admin.layouts.app>


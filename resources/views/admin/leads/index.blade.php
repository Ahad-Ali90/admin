{{-- resources/views/admin/leads/index.blade.php --}}
<x-admin.layouts.app>
  {{-- Chart.js CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
  @push('styles')
  <style>
    .lead-page {
      background: #ffffff;
      padding: 2rem;
    }

    .report-section {
      margin-bottom: 3rem;
    }

    .report-title {
      text-align: center;
      font-size: 1.25rem;
      font-weight: 700;
      padding: 0.75rem;
      background: #ffffff;
      color: #000000;
      border: 2px solid #000000;
    }

    .report-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2rem;
    }

    .report-table th,
    .report-table td {
      border: 1px solid #000000;
      padding: 0.75rem;
      text-align: center;
    }

    .report-table th {
      background: #ffffff;
      font-weight: 700;
      font-size: 0.95rem;
    }

    .report-table td {
      background: #ffffff;
    }

    .filter-section {
      margin-bottom: 2rem;
      padding: 1.5rem;
      background: #f8f9fa;
      border-radius: 8px;
    }

    .page-header {
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 3px solid #000000;
    }

    .page-header h1 {
      font-size: 2rem;
      font-weight: 700;
      margin: 0;
    }

    /* View Toggle Button */
    .view-toggle-btn {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      font-weight: 600;
      background: #4f46e5;
      border: none;
      color: white;
      border-radius: 50px;
      box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
      z-index: 1000;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .view-toggle-btn:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(79, 70, 229, 0.5);
      background: #4338ca;
    }

    /* Charts Container */
    .charts-container {
      display: none;
    }

    .charts-container.active {
      display: block;
    }

    .reports-container.hidden {
      display: none;
    }

    .chart-card {
      background: white;
      border-radius: 12px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .chart-title {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: #1f2937;
      text-align: center;
    }

    .chart-card canvas {
      max-height: 300px;
    }

    /* Filter Toggle Button - Mobile Only */
    .filter-toggle-btn {
      display: none;
      width: 100%;
      margin-bottom: 1rem;
      padding: 0.75rem;
      font-size: 1rem;
      font-weight: 600;
      background: transparent;
      border: 2px solid #4f46e5;
      color: #4f46e5;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .filter-toggle-btn:hover {
      background: #4f46e5;
      color: white;
      transform: translateY(-2px);
    }

    .filter-toggle-btn i {
      transition: transform 0.3s ease;
    }

    .filter-toggle-btn.active i {
      transform: rotate(180deg);
    }

    /* Mobile Responsive Styles */
    .report-section {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .report-table {
      min-width: 600px;
    }

    @media (max-width: 768px) {
      .lead-page {
        padding: 1rem;
      }

      .page-header h1 {
        font-size: 1.5rem;
      }

      .page-header p {
        font-size: 0.9rem;
      }

      /* Show filter toggle button on mobile */
      .filter-toggle-btn {
        display: block;
      }

      /* Hide filter section by default on mobile */
      .filter-section {
        display: none;
        padding: 1rem;
      }

      .filter-section.show {
        display: block;
        animation: slideDown 0.3s ease;
      }

      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .report-title {
        font-size: 1.1rem;
      }

      .report-table th,
      .report-table td {
        padding: 0.5rem;
        font-size: 0.85rem;
      }

      .report-section {
        margin-bottom: 2rem;
      }

      .report-section::after {
        content: '← Swipe to view more →';
        display: block;
        text-align: center;
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        padding: 0.25rem;
      }
    }

    @media (max-width: 576px) {
      .lead-page {
        padding: 0.75rem;
      }

      .page-header {
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
      }

      .page-header h1 {
        font-size: 1.25rem;
      }

      .page-header p {
        font-size: 0.85rem;
      }

      .filter-section {
        padding: 0.75rem;
      }

      .report-title {
        font-size: 1rem;
        padding: 0.5rem;
      }

      .report-table {
        min-width: 500px;
      }

      .report-table th,
      .report-table td {
        padding: 0.4rem;
        font-size: 0.8rem;
        white-space: nowrap;
      }
    }

    /* Dark Mode Support */
    [data-theme="dark"] .lead-page {
      background: var(--bg-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .page-header {
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .page-header h1,
    [data-theme="dark"] .page-header p {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .filter-section {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .filter-section label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .report-title {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .report-table {
      background: var(--card-bg) !important;
    }

    [data-theme="dark"] .report-table th {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .report-table td {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .report-table tbody tr:hover td {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .card-body {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .card-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .chart-card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .chart-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .view-toggle-btn {
      background: #4f46e5 !important;
      color: white !important;
    }

    [data-theme="dark"] .view-toggle-btn:hover {
      background: #4338ca !important;
    }

    [data-theme="dark"] .filter-toggle-btn {
      border-color: #4f46e5 !important;
      color: #4f46e5 !important;
      background: transparent !important;
    }

    [data-theme="dark"] .filter-toggle-btn:hover {
      background: #4f46e5 !important;
      color: white !important;
    }

    [data-theme="dark"] .text-muted {
      color: var(--text-color) !important;
      opacity: 0.7;
    }
  </style>
  @endpush

  <div class="lead-page">
    {{-- Page Header --}}
    <div class="page-header">
      <h1>Lead Tracker Reports</h1>
      <p class="text-muted mb-0">Monitor your booking inquiries and conversion rates</p>
    </div>

    {{-- Filter Toggle Button (Mobile Only) --}}
    <button type="button" class="filter-toggle-btn" id="filterToggleBtn" onclick="toggleFilters()">
      <i class="bi bi-funnel-fill me-2"></i>Show Filters
      <i class="bi bi-chevron-down ms-2"></i>
    </button>

    {{-- Filters --}}
    <div class="filter-section" id="filterSection">
      <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 g-md-3">
        <div class="col-12 col-sm-6 col-md-4">
          <label class="form-label fw-bold">Month</label>
          <select name="month" class="form-select">
            @for($i = 1; $i <= 12; $i++)
              <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create(null, $i, 1)->format('F') }}
              </option>
            @endfor
          </select>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <label class="form-label fw-bold">Year</label>
          <select name="year" class="form-select">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
              <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                {{ $y }}
              </option>
            @endfor
          </select>
        </div>

        <div class="col-12 col-sm-6 col-md-2">
          <label class="form-label fw-bold d-none d-md-block">&nbsp;</label>
          <button type="submit" class="btn btn-outline-primary w-100">
            <i class="bi bi-funnel me-1"></i>Apply
          </button>
        </div>

        <div class="col-12 col-sm-6 col-md-2">
          <label class="form-label fw-bold d-none d-md-block">&nbsp;</label>
          <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary w-100">
            <i class="bi bi-x-circle me-1"></i>Clear
          </a>
        </div>
      </form>
    </div>
    {{-- View Toggle Button --}}
    <button type="button" class="view-toggle-btn" id="viewToggleBtn" onclick="toggleView()">
      <i class="bi bi-bar-chart-fill me-2"></i>View as Graph
    </button>

    {{-- Summary Statistics --}}
    <div class="row g-2 g-md-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card border-primary">
          <div class="card-body text-center">
            <h5 class="card-title text-primary">Total Leads</h5>
            <h2 class="mb-0">{{ \App\Models\Booking::count() }}</h2>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-success">
          <div class="card-body text-center">
            <h5 class="card-title text-success">Converted</h5>
            <h2 class="mb-0">{{ \App\Models\Booking::whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])->count() }}</h2>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-danger">
          <div class="card-body text-center">
            <h5 class="card-title text-danger">Not Converted</h5>
            <h2 class="mb-0">{{ \App\Models\Booking::where('status', 'not_converted')->count() }}</h2>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-info">
          <div class="card-body text-center">
            <h5 class="card-title text-info">Conversion Rate</h5>
            <h2 class="mb-0">
              @php
                $total = \App\Models\Booking::count();
                $converted = \App\Models\Booking::whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])->count();
                $rate = $total > 0 ? round(($converted / $total) * 100, 1) : 0;
              @endphp
              {{ $rate }}%
            </h2>
          </div>
        </div>
      </div>
    </div>
    {{-- Reports Container (Tables) --}}
    <div class="reports-container" id="reportsContainer">
    
    {{-- Weekly Report --}}
    <div class="report-section">
      <table class="report-table">
        <thead>
          <tr>
            <th colspan="5" class="report-title">Marketing Weekly Report - {{ \Carbon\Carbon::create(request('year', now()->year), request('month', now()->month), 1)->format('F Y') }}</th>
          </tr>
          <tr>
            <th>Week</th>
            <th>Total Inquiries</th>
            <th>Leads Converted</th>
            <th>Leads Not Converted</th>
            <th>Top Lead Source</th>
          </tr>
        </thead>
        <tbody>
          @php
            $selectedMonth = request('month', now()->month);
            $selectedYear = request('year', now()->year);
            $startOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            
            // Generate weeks for the selected month
            $weeks = [];
            $currentWeekStart = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $weekNumber = 1;
            
            while ($currentWeekStart <= $endOfMonth) {
              $weekEnd = $currentWeekStart->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
              
              if ($weekEnd >= $startOfMonth) {
                // Get bookings for this week
                $weekBookings = \App\Models\Booking::whereBetween('booking_date', [$currentWeekStart, $weekEnd])->get();
                
                $totalInquiries = $weekBookings->count();
                $converted = $weekBookings->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])->count();
                $notConverted = $weekBookings->where('status', 'not_converted')->count();
                
                // Get top source
                $topSource = $weekBookings->groupBy('lead_source')
                  ->map(fn($items) => $items->count())
                  ->sortDesc()
                  ->keys()
                  ->first();
                
                $weeks[] = [
                  'number' => $weekNumber,
                  'total' => $totalInquiries,
                  'converted' => $converted,
                  'not_converted' => $notConverted,
                  'top_source' => $topSource ? ucfirst(str_replace('_', ' ', $topSource)) : 'N/A',
                ];
                
                $weekNumber++;
              }
              
              $currentWeekStart->addWeek();
              
              if ($currentWeekStart > $endOfMonth->copy()->addWeek()) {
                break;
              }
            }
          @endphp

          @forelse($weeks as $week)
            <tr>
              <td><strong>Week {{ $week['number'] }}</strong></td>
              <td>{{ $week['total'] }}</td>
              <td>{{ $week['converted'] }}</td>
              <td>{{ $week['not_converted'] }}</td>
              <td>{{ $week['top_source'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-muted">No data available for this month</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Monthly Report --}}
    <div class="report-section">
      <table class="report-table">
        <thead>
          <tr>
            <th colspan="5" class="report-title">Marketing Monthly Report - {{ request('year', now()->year) }}</th>
          </tr>
          <tr>
            <th>Month</th>
            <th>Total Inquiries</th>
            <th>Leads Converted</th>
            <th>Leads Not Converted</th>
            <th>Top Lead Source</th>
          </tr>
        </thead>
        <tbody>
          @php
            // Monthly data for selected year (all 12 months)
            $selectedYear = request('year', now()->year);
            $months = [];
            
            // Loop through all 12 months of the selected year
            for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
              // Get bookings for this month
              $monthBookings = \App\Models\Booking::whereYear('booking_date', $selectedYear)
                ->whereMonth('booking_date', $monthNum)
                ->get();
              
              $totalInquiries = $monthBookings->count();
              $converted = $monthBookings->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])->count();
              $notConverted = $monthBookings->where('status', 'not_converted')->count();
              
              // Get top source
              $topSource = $monthBookings->groupBy('lead_source')
                ->map(fn($items) => $items->count())
                ->sortDesc()
                ->keys()
                ->first();
              
              $months[] = [
                'name' => \Carbon\Carbon::create($selectedYear, $monthNum, 1)->format('F'),
                'total' => $totalInquiries,
                'converted' => $converted,
                'not_converted' => $notConverted,
                'top_source' => $topSource ? ucfirst(str_replace('_', ' ', $topSource)) : 'N/A',
              ];
            }
          @endphp

          @foreach($months as $month)
            <tr>
              <td><strong>{{ $month['name'] }}</strong></td>
              <td>{{ $month['total'] }}</td>
              <td>{{ $month['converted'] }}</td>
              <td>{{ $month['not_converted'] }}</td>
              <td>{{ $month['top_source'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </div>
    {{-- End Reports Container --}}

    {{-- Charts Container (Graphs) --}}
    <div class="charts-container" id="chartsContainer">
      <div class="row g-3">
        {{-- Weekly Chart --}}
        <div class="col-12 col-md-4">
          <div class="chart-card">
            <h3 class="chart-title">
              <i class="bi bi-calendar-week me-2"></i>Weekly Report
            </h3>
            <canvas id="weeklyChart"></canvas>
          </div>
        </div>

        {{-- Monthly Chart --}}
        <div class="col-12 col-md-4">
          <div class="chart-card">
            <h3 class="chart-title">
              <i class="bi bi-calendar3 me-2"></i>Monthly Report
            </h3>
            <canvas id="monthlyChart"></canvas>
          </div>
        </div>

        {{-- Lead Sources Chart --}}
        <div class="col-12 col-md-4">
          <div class="chart-card">
            <h3 class="chart-title">
              <i class="bi bi-bar-chart-fill me-2"></i>Lead Sources
            </h3>
            <canvas id="sourcesChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    {{-- End Charts Container --}}

  </div>

  @push('scripts')
  <script>
    function toggleFilters() {
      const filterSection = document.getElementById('filterSection');
      const toggleBtn = document.getElementById('filterToggleBtn');
      
      // Toggle the 'show' class
      filterSection.classList.toggle('show');
      toggleBtn.classList.toggle('active');
      
      // Update button text
      if (filterSection.classList.contains('show')) {
        toggleBtn.innerHTML = '<i class="bi bi-funnel-fill me-2"></i>Hide Filters<i class="bi bi-chevron-up ms-2"></i>';
      } else {
        toggleBtn.innerHTML = '<i class="bi bi-funnel-fill me-2"></i>Show Filters<i class="bi bi-chevron-down ms-2"></i>';
      }
    }

    // Toggle between Table and Graph view
    let currentView = 'table';
    let weeklyChart = null;
    let monthlyChart = null;
    let sourcesChart = null;

    function toggleView() {
      const reportsContainer = document.getElementById('reportsContainer');
      const chartsContainer = document.getElementById('chartsContainer');
      const toggleBtn = document.getElementById('viewToggleBtn');
      
      if (currentView === 'table') {
        // Switch to graph view
        reportsContainer.classList.add('hidden');
        chartsContainer.classList.add('active');
        toggleBtn.innerHTML = '<i class="bi bi-table me-2"></i>View as Table';
        currentView = 'graph';
        
        // Initialize charts if not already done
        if (!weeklyChart) {
          initCharts();
        }
      } else {
        // Switch to table view
        reportsContainer.classList.remove('hidden');
        chartsContainer.classList.remove('active');
        toggleBtn.innerHTML = '<i class="bi bi-bar-chart-fill me-2"></i>View as Graph';
        currentView = 'table';
      }
    }

    function initCharts() {
      // Weekly Data
      const weeklyData = @json($weeks ?? []);
      
      // Monthly Data
      const monthlyData = @json($months ?? []);
      
      // Create Weekly Chart
      const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
      weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
          labels: weeklyData.map(w => 'Week ' + w.number),
          datasets: [
            {
              label: 'Total Inquiries',
              data: weeklyData.map(w => w.total),
              backgroundColor: 'rgba(79, 70, 229, 0.8)',
              borderColor: 'rgba(79, 70, 229, 1)',
              borderWidth: 2
            },
            {
              label: 'Converted',
              data: weeklyData.map(w => w.converted),
              backgroundColor: 'rgba(16, 185, 129, 0.8)',
              borderColor: 'rgba(16, 185, 129, 1)',
              borderWidth: 2
            },
            {
              label: 'Not Converted',
              data: weeklyData.map(w => w.not_converted),
              backgroundColor: 'rgba(239, 68, 68, 0.8)',
              borderColor: 'rgba(239, 68, 68, 1)',
              borderWidth: 2
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });

      // Create Monthly Chart
      const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
      monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
          labels: monthlyData.map(m => m.name),
          datasets: [
            {
              label: 'Total Inquiries',
              data: monthlyData.map(m => m.total),
              borderColor: 'rgba(79, 70, 229, 1)',
              backgroundColor: 'rgba(79, 70, 229, 0.1)',
              tension: 0.4,
              fill: true,
              borderWidth: 3
            },
            {
              label: 'Converted',
              data: monthlyData.map(m => m.converted),
              borderColor: 'rgba(16, 185, 129, 1)',
              backgroundColor: 'rgba(16, 185, 129, 0.1)',
              tension: 0.4,
              fill: true,
              borderWidth: 3
            },
            {
              label: 'Not Converted',
              data: monthlyData.map(m => m.not_converted),
              borderColor: 'rgba(239, 68, 68, 1)',
              backgroundColor: 'rgba(239, 68, 68, 0.1)',
              tension: 0.4,
              fill: true,
              borderWidth: 3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              position: 'top',
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });

      // Lead Sources Bar Chart
      const sourcesData = monthlyData.reduce((acc, month) => {
        const source = month.top_source;
        if (source && source !== 'N/A') {
          acc[source] = (acc[source] || 0) + month.total;
        }
        return acc;
      }, {});

      const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
      sourcesChart = new Chart(sourcesCtx, {
        type: 'bar',
        data: {
          labels: Object.keys(sourcesData),
          datasets: [{
            label: 'Total Leads',
            data: Object.values(sourcesData),
            backgroundColor: [
              'rgba(79, 70, 229, 0.8)',
              'rgba(16, 185, 129, 0.8)',
              'rgba(239, 68, 68, 0.8)',
              'rgba(245, 158, 11, 0.8)',
              'rgba(59, 130, 246, 0.8)',
              'rgba(168, 85, 247, 0.8)',
              'rgba(236, 72, 153, 0.8)',
              'rgba(20, 184, 166, 0.8)',
            ],
            borderColor: [
              'rgba(79, 70, 229, 1)',
              'rgba(16, 185, 129, 1)',
              'rgba(239, 68, 68, 1)',
              'rgba(245, 158, 11, 1)',
              'rgba(59, 130, 246, 1)',
              'rgba(168, 85, 247, 1)',
              'rgba(236, 72, 153, 1)',
              'rgba(20, 184, 166, 1)',
            ],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    }
  </script>
  @endpush
</x-admin.layouts.app>

{{-- resources/views/admin/calendar/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .calendar-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
      overflow: hidden;
    }
    
    .calendar-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
    }
    
    .calendar-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      gap: 0.5rem;
    }
    
    .calendar-nav button {
      background: rgba(255,255,255,0.2);
      border: none;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s;
      white-space: nowrap;
    }
    
    .calendar-nav button:hover {
      background: rgba(255,255,255,0.3);
    }
    
    .calendar-nav h3 {
      font-size: 1.5rem;
      text-align: center;
      flex: 1;
    }
    
    .legend-container {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
    }
    
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 1px;
      background: #e5e7eb;
    }
    
    .calendar-day-header {
      background: #f9fafb;
      padding: 1rem;
      text-align: center;
      font-weight: 600;
      color: #6b7280;
      font-size: 0.875rem;
    }
    
    .calendar-day {
      background: white;
      min-height: 120px;
      padding: 0.5rem;
      position: relative;
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .calendar-day:hover {
      background: #f9fafb;
    }
    
    .calendar-day.other-month {
      background: #f9fafb;
      opacity: 0.5;
    }
    
    .calendar-day.today {
      background: #eff6ff;
      border: 2px solid #3b82f6;
    }
    
    .day-number {
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 0.5rem;
    }
    
    .calendar-day.other-month .day-number {
      color: #9ca3af;
    }
    
    .booking-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      display: inline-block;
      margin: 2px;
    }
    
    .booking-dot.pending { background: #fbbf24; }
    .booking-dot.confirmed { background: #60a5fa; }
    .booking-dot.in_progress { background: #a78bfa; }
    .booking-dot.completed { background: #34d399; }
    .booking-dot.cancelled { background: #f87171; }
    
    .booking-count {
      font-size: 0.75rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }
    
    .booking-preview {
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 4px;
      margin-top: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .booking-preview.pending { background: #fef3c7; color: #92400e; }
    .booking-preview.confirmed { background: #dbeafe; color: #1e40af; }
    .booking-preview.in_progress { background: #ede9fe; color: #6b21a8; }
    .booking-preview.completed { background: #d1fae5; color: #065f46; }
    .booking-preview.cancelled { background: #fee2e2; color: #991b1b; }
    
    .stats-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 2px 10px rgba(16,24,40,.06);
      margin-bottom: 1rem;
    }
    
    .stat-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid #f3f4f6;
    }
    
    .stat-item:last-child {
      border-bottom: none;
    }
    
    .stat-label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .stat-badge {
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }
    
    /* Modal Styles */
    .modal-content {
      border-radius: 12px;
      border: none;
    }
    
    .booking-card {
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 0.75rem;
      transition: all 0.2s;
    }
    
    .booking-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    
    .status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-confirmed { background: #dbeafe; color: #1e40af; }
    .status-in_progress { background: #ede9fe; color: #6b21a8; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
    
    /* Tablet Responsive */
    @media (max-width: 992px) {
      .calendar-header {
        padding: 1.5rem;
      }
      
      .calendar-nav h3 {
        font-size: 1.25rem;
      }
      
      .calendar-nav button {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
      }
      
      .legend-container {
        gap: 0.75rem;
      }
      
      .legend-item {
        font-size: 0.875rem;
      }
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .calendar-header {
        padding: 1rem;
      }
      
      .calendar-nav {
        margin-bottom: 0.75rem;
      }
      
      .calendar-nav h3 {
        font-size: 1.1rem;
      }
      
      .calendar-nav button {
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
      }
      
      .calendar-nav button span {
        display: none;
      }
      
      .legend-container {
        gap: 0.5rem;
      }
      
      .legend-item {
        font-size: 0.75rem;
      }
      
      .legend-item span.count {
        display: none;
      }
      
      .calendar-day {
        min-height: 80px;
        padding: 0.25rem;
      }
      
      .booking-preview {
        display: none;
      }
      
      .day-number {
        font-size: 0.875rem;
      }
      
      .booking-count {
        font-size: 0.65rem;
      }
    }
    
    /* Extra Small Mobile */
    @media (max-width: 576px) {
      .calendar-header {
        padding: 0.75rem;
      }
      
      .calendar-nav h3 {
        font-size: 1rem;
      }
      
      .calendar-nav button {
        padding: 0.3rem 0.5rem;
        font-size: 0.75rem;
      }
      
      .legend-container {
        gap: 0.4rem;
        font-size: 0.7rem;
      }
      
      .legend-item span {
        display: none;
      }
      
      .calendar-day {
        min-height: 60px;
        padding: 0.2rem;
      }
      
      .day-number {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
      }
      
      .booking-dot {
        width: 5px;
        height: 5px;
        margin: 1px;
      }
    }

    /* Dark Mode Support */
    [data-theme="dark"] .calendar-container {
      background: var(--card-bg) !important;
    }

    [data-theme="dark"] .calendar-grid {
      background: var(--border-color) !important;
    }

    [data-theme="dark"] .calendar-day-header {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .calendar-day {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .calendar-day:hover {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .calendar-day.other-month {
      background: var(--surface-bg) !important;
    }

    [data-theme="dark"] .calendar-day.today {
      background: var(--card-bg) !important;
      border: 2px solid #4f46e5;
    }

    [data-theme="dark"] .day-number {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .booking-count {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .sidebar {
      background: var(--card-bg) !important;
    }

    [data-theme="dark"] .legend-container {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .booking-item {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    /* Sidebar Cards Dark Mode */
    [data-theme="dark"] .stats-card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .stat-item {
      border-bottom: 1px solid var(--border-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .stat-label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .booking-card {
      background: var(--card-bg) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .booking-preview {
      color: var(--text-color) !important;
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="mb-4">
      <h1 class="h2 mb-2">Booking Calendar</h1>
      <p class="text-secondary">View and manage all bookings in calendar view</p>
    </div>

    <div class="row g-4">
      {{-- Calendar --}}
      <div class="col-12 col-lg-9">
        <div class="calendar-container">
          {{-- Calendar Header --}}
          <div class="calendar-header">
            <div class="calendar-nav">
              <button onclick="changeMonth(-1)">
                <i class="bi bi-chevron-left"></i> <span>Previous</span>
              </button>
              <h3 class="mb-0" id="currentMonth">{{ $currentDate->format('F Y') }}</h3>
              <button onclick="changeMonth(1)">
                <span>Next</span> <i class="bi bi-chevron-right"></i>
              </button>
            </div>
            <div class="legend-container">
              <div class="legend-item">
                <div class="booking-dot pending"></div>
                <span>Pending <span class="count">({{ $stats['pending'] }})</span></span>
              </div>
              <div class="legend-item">
                <div class="booking-dot confirmed"></div>
                <span>Confirmed <span class="count">({{ $stats['confirmed'] }})</span></span>
              </div>
              <div class="legend-item">
                <div class="booking-dot in_progress"></div>
                <span>In Progress <span class="count">({{ $stats['in_progress'] }})</span></span>
              </div>
              <div class="legend-item">
                <div class="booking-dot completed"></div>
                <span>Completed <span class="count">({{ $stats['completed'] }})</span></span>
              </div>
              <div class="legend-item">
                <div class="booking-dot cancelled"></div>
                <span>Cancelled <span class="count">({{ $stats['cancelled'] }})</span></span>
              </div>
            </div>
          </div>

          {{-- Calendar Grid --}}
          <div class="calendar-grid">
            {{-- Day Headers --}}
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>

            {{-- Calendar Days --}}
            @php
              $startOfMonth = $currentDate->copy()->startOfMonth();
              $endOfMonth = $currentDate->copy()->endOfMonth();
              $startDate = $startOfMonth->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
              $endDate = $endOfMonth->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
              $currentDay = $startDate->copy();
            @endphp

            @while($currentDay <= $endDate)
              @php
                $dateKey = $currentDay->format('Y-m-d');
                $dayBookings = $bookingsByDate->get($dateKey, collect());
                $isToday = $currentDay->isToday();
                $isCurrentMonth = $currentDay->month == $currentDate->month;
              @endphp

              <div class="calendar-day {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}"
                   onclick="showDayBookings('{{ $dateKey }}', '{{ $currentDay->format('F d, Y') }}')">
                <div class="day-number">{{ $currentDay->day }}</div>
                
                @if($dayBookings->count() > 0)
                  <div class="booking-count">
                    {{ $dayBookings->count() }} booking{{ $dayBookings->count() > 1 ? 's' : '' }}
                  </div>
                  
                  {{-- Show dots for each booking status --}}
                  <div class="mb-1">
                    @foreach($dayBookings->take(5) as $booking)
                      <span class="booking-dot {{ $booking->status }}"></span>
                    @endforeach
                    @if($dayBookings->count() > 5)
                      <span class="small text-muted">+{{ $dayBookings->count() - 5 }}</span>
                    @endif
                  </div>
                  
                  {{-- Show first 2 booking previews --}}
                  @foreach($dayBookings->take(2) as $booking)
                    <div class="booking-preview {{ $booking->status }}">
                      {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '--:--' }} 
                      {{ Str::limit($booking->customer->name ?? 'N/A', 15) }}
                    </div>
                  @endforeach
                @endif
              </div>

              @php
                $currentDay->addDay();
              @endphp
            @endwhile
          </div>
        </div>
      </div>

      {{-- Sidebar --}}
      <div class="col-12 col-lg-3">
        {{-- Monthly Stats --}}
        <div class="stats-card">
          <h5 class="mb-3">{{ $currentDate->format('F') }} Summary</h5>
          <div class="stat-item">
            <div class="stat-label">
              <span class="stat-badge" style="background:#667eea"></span>
              <span>Total Bookings</span>
            </div>
            <span class="fw-bold">{{ $stats['total_month'] }}</span>
          </div>
          <div class="stat-item">
            <div class="stat-label">
              <span class="stat-badge" style="background:#fbbf24"></span>
              <span>Pending</span>
            </div>
            <span class="fw-bold">{{ $stats['pending'] }}</span>
          </div>
          <div class="stat-item">
            <div class="stat-label">
              <span class="stat-badge" style="background:#60a5fa"></span>
              <span>Confirmed</span>
            </div>
            <span class="fw-bold">{{ $stats['confirmed'] }}</span>
          </div>
          <div class="stat-item">
            <div class="stat-label">
              <span class="stat-badge" style="background:#a78bfa"></span>
              <span>In Progress</span>
            </div>
            <span class="fw-bold">{{ $stats['in_progress'] }}</span>
          </div>
          <div class="stat-item">
            <div class="stat-label">
              <span class="stat-badge" style="background:#34d399"></span>
              <span>Completed</span>
            </div>
            <span class="fw-bold">{{ $stats['completed'] }}</span>
          </div>
          <div class="stat-item">
            <div class="stat-label">
              <span class="stat-badge" style="background:#f87171"></span>
              <span>Cancelled</span>
            </div>
            <span class="fw-bold">{{ $stats['cancelled'] }}</span>
          </div>
        </div>

        {{-- Quick Actions --}}
        <div class="stats-card">
          <h5 class="mb-3">Quick Actions</h5>
          <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary w-100 mb-2">
            <i class="bi bi-plus-circle me-2"></i>New Booking
          </a>
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary w-100">
            <i class="bi bi-list-ul me-2"></i>All Bookings
          </a>
        </div>

        {{-- Today's Highlight --}}
        <div class="stats-card">
          <h5 class="mb-3">Today's Bookings</h5>
          @php
            $todayBookings = $bookingsByDate->get(now()->format('Y-m-d'), collect());
          @endphp
          @if($todayBookings->count() > 0)
            <div class="text-center mb-3">
              <div class="display-4 fw-bold text-primary">{{ $todayBookings->count() }}</div>
              <div class="text-secondary">booking{{ $todayBookings->count() > 1 ? 's' : '' }} today</div>
            </div>
            <button class="btn btn-outline-primary btn-sm w-100" 
                    onclick="showDayBookings('{{ now()->format('Y-m-d') }}', '{{ now()->format('F d, Y') }}')">
              View Details
            </button>
          @else
            <div class="text-center text-secondary py-3">
              <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
              <div>No bookings today</div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Day Bookings Modal --}}
  <div class="modal fade" id="dayBookingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDate"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalBody">
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    let currentMonth = {{ $currentDate->month }};
    let currentYear = {{ $currentDate->year }};

    function changeMonth(direction) {
      currentMonth += direction;
      if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
      } else if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
      }
      window.location.href = `{{ route('admin.calendar.index') }}?month=${currentMonth}&year=${currentYear}`;
    }

    function showDayBookings(date, dateLabel) {
      const modal = new bootstrap.Modal(document.getElementById('dayBookingsModal'));
      document.getElementById('modalDate').textContent = 'Bookings for ' + dateLabel;
      document.getElementById('modalBody').innerHTML = `
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      `;
      modal.show();

      // Fetch bookings for this date
      fetch(`{{ route('admin.calendar.bookings') }}?date=${date}`)
        .then(response => response.json())
        .then(data => {
          if (data.bookings.length === 0) {
            document.getElementById('modalBody').innerHTML = `
              <div class="text-center text-secondary py-5">
                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                <h5>No bookings on this day</h5>
                <p>There are no scheduled bookings for this date.</p>
              </div>
            `;
          } else {
            let html = '';
            data.bookings.forEach(booking => {
              html += `
                <div class="booking-card">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h6 class="mb-1">${booking.reference}</h6>
                      <span class="status-badge status-${booking.status}">${booking.status_label}</span>
                    </div>
                    <div class="text-end">
                      <div class="fw-bold text-primary">£${booking.amount}</div>
                      <div class="small text-secondary">${booking.time}</div>
                    </div>
                  </div>
                  <div class="row g-2 mb-2">
                    <div class="col-6">
                      <div class="small text-secondary">Customer</div>
                      <div class="fw-medium">${booking.customer}</div>
                    </div>
                    <div class="col-6">
                      <div class="small text-secondary">Driver</div>
                      <div class="fw-medium">${booking.driver}</div>
                    </div>
                  </div>
                  <div class="mb-2">
                    <div class="small text-secondary">Pickup</div>
                    <div>${booking.pickup || 'N/A'}</div>
                  </div>
                  <div class="mb-3">
                    <div class="small text-secondary">Drop-off</div>
                    <div>${booking.dropoff || 'N/A'}</div>
                  </div>
                  <a href="${booking.view_url}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye me-2"></i>View Details
                  </a>
                </div>
              `;
            });
            document.getElementById('modalBody').innerHTML = html;
          }
        })
        .catch(error => {
          document.getElementById('modalBody').innerHTML = `
            <div class="text-center text-danger py-5">
              <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
              <h5>Error loading bookings</h5>
              <p>Please try again later.</p>
            </div>
          `;
        });
    }
  </script>
  @endpush
</x-admin.layouts.app>


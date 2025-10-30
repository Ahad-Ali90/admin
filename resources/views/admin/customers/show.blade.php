{{-- resources/views/admin/customers/show.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06);border-radius:12px}
    
    /* Customer Header Card */
    .customer-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 12px;
      padding: 2rem;
      margin-bottom: 2rem;
    }
    .customer-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      font-weight: 700;
      border: 3px solid rgba(255,255,255,0.3);
    }
    
    /* Stats Cards */
    .stat-card {
      border-radius: 12px;
      padding: 1.5rem;
      height: 100%;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(16,24,40,.12);
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
    .icon-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .icon-green { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .icon-blue { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .icon-orange { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    
    /* Contact Info Cards */
    .contact-card {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 12px;
      padding: 1.5rem;
      border: 2px solid transparent;
      transition: all 0.3s;
    }
    .contact-card:hover {
      border-color: #667eea;
      transform: translateY(-2px);
    }
    .contact-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }
    .icon-email { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .icon-phone { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .icon-location { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    
    /* Action Buttons */
    .action-btn {
      border-radius: 10px;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s;
      border: 2px solid transparent;
    }
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-call {
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      color: white;
      border: none;
    }
    .btn-email {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
    }
    .btn-whatsapp {
      background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
      color: white;
      border: none;
    }
    
    /* Timeline */
    .timeline-item {
      position: relative;
      padding-left: 2rem;
      padding-bottom: 1.5rem;
      border-left: 2px solid #e9ecef;
    }
    .timeline-item:last-child {
      border-left: 2px solid transparent;
    }
    .timeline-dot {
      position: absolute;
      left: -6px;
      top: 0;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #667eea;
    }
    
    /* Status Badges */
    .status-badge {
      padding: 6px 14px;
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
    [data-theme="dark"] h2,
    [data-theme="dark"] h3,
    [data-theme="dark"] h4,
    [data-theme="dark"] h5 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-secondary,
    [data-theme="dark"] .small.text-secondary {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] .stat-card {
      background: var(--card-bg) !important;
      border: 1px solid var(--border-color) !important;
    }

    [data-theme="dark"] .stat-card:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .contact-card {
      background: var(--surface-bg) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .contact-card:hover {
      border-color: #667eea !important;
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .fw-medium {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .timeline-item {
      border-left-color: var(--border-color) !important;
    }

    [data-theme="dark"] .timeline-item:last-child {
      border-left-color: transparent !important;
    }

    [data-theme="dark"] .bg-light {
      background: var(--surface-bg) !important;
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
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table > :not(caption) > * > * {
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .display-6 {
      color: var(--text-color) !important;
      opacity: 0.6;
    }

    [data-theme="dark"] .card.border-danger {
      border-color: #991b1b !important;
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

  @php
    $bookingsCount = $customer->bookings_count ?? ($customer->bookings->count() ?? 0);
    
    // Calculate total spent from all bookings (total_fare + extra_hours_amount - discount)
    $totalSpent = 0;
    foreach($customer->bookings as $booking) {
      $finalFare = $booking->getFinalTotalFare();
      $extraHours = $booking->extra_hours_amount ?? 0;
      $totalSpent += $finalFare + $extraHours;
    }
    
    $completedBookings = $customer->bookings()->where('status', 'completed')->count();
    $pendingBookings = $customer->bookings()->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count();
  @endphp

  <div class="container-xxl py-4">
    {{-- Customer Header Card --}}
    <div class="customer-header">
      <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
        <div class="customer-avatar">
          {{ strtoupper(substr($customer->name, 0, 1)) }}
        </div>
        <div class="flex-grow-1">
          <h1 class="h2 mb-1">{{ $customer->name }}</h1>
          <div class="d-flex flex-wrap gap-3 opacity-90">
            <span><i class="bi bi-calendar-check me-1"></i>Joined {{ $customer->created_at->format('M d, Y') }}</span>
            @if($customer->customer_source)
              <span><i class="bi bi-tag me-1"></i>Source: {{ ucfirst($customer->customer_source) }}</span>
            @endif
          </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-light">
            <i class="bi bi-pencil me-1"></i>Edit
          </a>
          <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Back
          </a>
        </div>
      </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-purple">
              <i class="bi bi-calendar-check text-white"></i>
            </div>
            <div>
              <div class="text-secondary small">Total Bookings</div>
              <div class="fs-4 fw-bold">{{ $bookingsCount }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-green">
              <i class="bi bi-currency-pound text-white"></i>
            </div>
            <div>
              <div class="text-secondary small">Total Spent</div>
              <div class="fs-4 fw-bold text-success">£{{ number_format($totalSpent, 2) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-blue">
              <i class="bi bi-check-circle text-white"></i>
            </div>
            <div>
              <div class="text-secondary small">Completed</div>
              <div class="fs-4 fw-bold">{{ $completedBookings }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-orange">
              <i class="bi bi-clock-history text-white"></i>
            </div>
            <div>
              <div class="text-secondary small">Pending</div>
              <div class="fs-4 fw-bold">{{ $pendingBookings }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      {{-- Left Column --}}
      <div class="col-12 col-lg-8">
        {{-- Contact Information --}}
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-4">
              <i class="bi bi-person-lines-fill me-2 text-primary"></i>Contact Information
            </h5>
            
            <div class="row g-3">
              {{-- Email Card --}}
              @if($customer->email)
              <div class="col-12 col-md-6">
                <div class="contact-card">
                  <div class="d-flex align-items-start gap-3">
                    <div class="contact-icon icon-email">
                      <i class="bi bi-envelope-fill text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                      <div class="small text-secondary mb-1">Email Address</div>
                      <div class="fw-medium mb-2">{{ $customer->email }}</div>
                      <a href="mailto:{{ $customer->email }}" class="btn btn-sm action-btn btn-email">
                        <i class="bi bi-envelope me-1"></i>Send Email
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endif

              {{-- Phone Card --}}
              @if($customer->phone)
              <div class="col-12 col-md-6">
                <div class="contact-card">
                  <div class="d-flex align-items-start gap-3">
                    <div class="contact-icon icon-phone">
                      <i class="bi bi-telephone-fill text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                      <div class="small text-secondary mb-1">Phone Number</div>
                      <div class="fw-medium mb-2">{{ $customer->phone }}</div>
                      <div class="d-flex gap-2">
                        <a href="tel:{{ $customer->phone }}" class="btn btn-sm action-btn btn-call">
                          <i class="bi bi-telephone me-1"></i>Call Now
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" 
                           target="_blank" class="btn btn-sm action-btn btn-whatsapp">
                          <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endif

              {{-- Address Card --}}
              @if($customer->address)
              <div class="col-12">
                <div class="contact-card">
                  <div class="d-flex align-items-start gap-3">
                    <div class="contact-icon icon-location">
                      <i class="bi bi-geo-alt-fill text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                      <div class="small text-secondary mb-1">Address</div>
                      <div class="fw-medium mb-1">{{ $customer->address }}</div>
                      @if($customer->postcode)
                        <div class="text-secondary small mb-2">{{ $customer->postcode }}</div>
                      @endif
                      <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($customer->address . ' ' . $customer->postcode) }}" 
                         target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-map me-1"></i>View on Map
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Notes --}}
        @if($customer->notes)
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bi bi-sticky-fill me-2 text-warning"></i>Notes
            </h5>
            <div class="bg-light p-3 rounded">
              <p class="mb-0">{{ $customer->notes }}</p>
            </div>
          </div>
        </div>
        @endif

        {{-- Recent Bookings --}}
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="card-title mb-0">
                <i class="bi bi-calendar-range me-2 text-info"></i>Recent Bookings
              </h5>
              @if($bookingsCount > 0)
                <a href="{{ route('admin.bookings.index', ['search' => $customer->name]) }}" 
                   class="btn btn-sm btn-outline-primary">
                  View All
                </a>
              @endif
            </div>

            @php
              $recentBookings = $customer->bookings()->latest('booking_date')->take(5)->get();
            @endphp

            @if($recentBookings->count())
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Reference</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th class="text-end">Amount</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($recentBookings as $booking)
                      @php
                        $bookingTotal = $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
                      @endphp
                      <tr>
                        <td class="fw-medium">{{ $booking->booking_reference }}</td>
                        <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                        <td>
                          <span class="status-badge status-{{ $booking->status }}">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                          </span>
                        </td>
                        <td class="text-end fw-bold">£{{ number_format($bookingTotal, 2) }}</td>
                        <td class="text-end">
                          <a href="{{ route('admin.bookings.show', $booking) }}" 
                             class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-5">
                <div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div>
                <h5 class="mb-1">No bookings yet</h5>
                <p class="text-secondary mb-3">Create the first booking for this customer.</p>
                <a href="{{ route('admin.bookings.create', ['customer_id' => $customer->id]) }}" 
                   class="btn btn-primary">
                  <i class="bi bi-plus-lg me-2"></i>Create Booking
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Right Column --}}
      <div class="col-12 col-lg-4">
        {{-- Quick Actions --}}
        <!-- <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bi bi-lightning-fill me-2 text-warning"></i>Quick Actions
            </h5>
            <div class="d-grid gap-2">
              <a href="{{ route('admin.bookings.create', ['customer_id' => $customer->id]) }}" 
                 class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Create New Booking
              </a>
              @if($customer->email)
              <a href="mailto:{{ $customer->email }}" class="btn btn-outline-secondary">
                <i class="bi bi-envelope me-2"></i>Send Email
              </a>
              @endif
              @if($customer->phone)
              <a href="tel:{{ $customer->phone }}" class="btn btn-outline-success">
                <i class="bi bi-telephone me-2"></i>Call Customer
              </a>
              @endif
              <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-2"></i>Edit Details
              </a>
            </div>
          </div>
        </div> -->

        {{-- Customer Timeline --}}
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-4">
              <i class="bi bi-clock-history me-2 text-info"></i>Timeline
            </h5>
            <div class="timeline">
              <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="small text-secondary mb-1">Customer Created</div>
                <div class="fw-medium">{{ $customer->created_at->format('M d, Y g:i A') }}</div>
              </div>
              @if($customer->updated_at != $customer->created_at)
              <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="small text-secondary mb-1">Last Updated</div>
                <div class="fw-medium">{{ $customer->updated_at->format('M d, Y g:i A') }}</div>
              </div>
              @endif
              @php
                $lastBooking = $customer->bookings()->latest('booking_date')->first();
              @endphp
              @if($lastBooking)
              <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="small text-secondary mb-1">Last Booking</div>
                <div class="fw-medium">{{ $lastBooking->booking_date->format('M d, Y') }}</div>
              </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Danger Zone --}}
        @if($bookingsCount == 0)
        <div class="card border-danger">
          <div class="card-body">
            <h5 class="card-title text-danger mb-3">
              <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
            </h5>
            <p class="small text-secondary mb-3">
              This action cannot be undone. This will permanently delete the customer.
            </p>
            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                  onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-trash me-2"></i>Delete Customer
              </button>
            </form>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</x-admin.layouts.app>

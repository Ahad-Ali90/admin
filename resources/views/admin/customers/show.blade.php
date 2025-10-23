{{-- resources/views/admin/customers/show.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-business{background:#eef2ff;color:#3730a3}
    .stat-icon{width:36px;height:36px;border-radius:.5rem;display:inline-flex;align-items:center;justify-content:center}
    .icon-indigo{background:#4f46e5}
    .icon-green{background:#16a34a}
    .icon-blue{background:#3b82f6}
  </style>
  @endpush

  @php
    // Helpers/derived values (safe fallbacks if counts weren't eager-loaded)
    $bookingsCount = $customer->bookings_count ?? ($customer->bookings->count() ?? 0);
    // If you eager-load sum as total_spent, use that. Otherwise quick sum() fallback:
    $totalSpent = $customer->total_spent ?? ($customer->bookings->sum('total_amount') ?? 0);
  @endphp

  <div class="container-xxl py-3">
    {{-- Header --}}
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-3">
      <div>
        <h1 class="h3 mb-1">{{ $customer->name }}</h1>
        <div class="d-flex flex-wrap align-items-center gap-2">
          @if($customer->customer_type === 'business')
            <span class="badge badge-business">Business</span>
            @if($customer->company_name)
              <span class="text-secondary small"><i class="bi bi-building me-1"></i>{{ $customer->company_name }}</span>
            @endif
          @else
            <span class="badge text-bg-light border">Individual</span>
          @endif
          <span class="text-secondary small">Joined {{ $customer->created_at->format('M d, Y') }}</span>
        </div>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-outline-secondary">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.bookings.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
          <i class="bi bi-plus-lg me-1"></i>New Booking
        </a>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary">
          <i class="bi bi-arrow-left me-1"></i>Back to list
        </a>
      </div>
    </div>

    {{-- Top stats --}}
    <div class="row g-3 g-sm-4 mb-4">
      <div class="col-12 col-md-4">
        <div class="card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon icon-indigo"><i class="bi bi-journal-text text-white"></i></div>
            <div>
              <div class="text-secondary small">Total Bookings</div>
              <div class="fs-5 fw-semibold">{{ $bookingsCount }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon icon-green"><i class="bi bi-currency-pound text-white"></i></div>
            <div>
              <div class="text-secondary small">Total Spent</div>
              <div class="fs-5 fw-semibold">£{{ number_format($totalSpent, 2) }}</div>
            </div>
          </div>
        </div>
      </div>
      {{-- Optional: last booking date --}}
      <div class="col-12 col-md-4">
        @php
          $lastBooking = ($customer->relationLoaded('bookings') ? $customer->bookings : $customer->bookings()->latest('booking_date'))->first();
        @endphp
        <div class="card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon icon-blue"><i class="bi bi-calendar-event text-white"></i></div>
            <div>
              <div class="text-secondary small">Last Booking</div>
              <div class="fs-6 fw-medium">
                @if($lastBooking)
                  {{ $lastBooking->booking_date->format('M d, Y') }}
                @else
                  <span class="text-secondary">—</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Main grid --}}
    <div class="row g-4">
      {{-- Left column --}}
      <div class="col-12 col-lg-8 vstack gap-4">
        {{-- Contact & Address --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Contact</h5>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="text-secondary small">Email</div>
                <div>
                  @if($customer->email)
                    <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                  @else
                    <span class="text-secondary">—</span>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="text-secondary small">Phone</div>
                <div>
                  @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                  @else
                    <span class="text-secondary">—</span>
                  @endif
                </div>
              </div>

              @if($customer->customer_type === 'business' && $customer->company_name)
                <div class="col-12">
                  <div class="text-secondary small">Company</div>
                  <div>{{ $customer->company_name }}</div>
                </div>
              @endif

              <div class="col-12">
                <div class="text-secondary small">Address</div>
                <div class="mb-1">{{ $customer->address ?: '—' }}</div>
                @if($customer->postcode)
                  <div class="text-secondary small">{{ $customer->postcode }}</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Notes --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Notes</h5>
            <div class="text-break">
              {!! $customer->notes ? e($customer->notes) : '<span class="text-secondary">No notes added.</span>' !!}
            </div>
          </div>
        </div>

        {{-- Recent Bookings --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Recent Bookings</h5>
            @php
              $recentBookings = $customer->bookings()
                                         ->latest('booking_date')
                                         ->take(10)
                                         ->get();
            @endphp

            @if($recentBookings->count())
              <div class="table-responsive">
                <table class="table align-middle">
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
                    @foreach($recentBookings as $b)
                      <tr>
                        <td class="fw-medium">{{ $b->booking_reference }}</td>
                        <td>{{ $b->booking_date->format('M d, Y') }}</td>
                        <td class="text-capitalize">
                          <span class="badge text-bg-light border">{{ str_replace('_',' ', $b->status) }}</span>
                        </td>
                        <td class="text-end">£{{ number_format($b->total_amount, 2) }}</td>
                        <td class="text-end">
                          <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">
                            View
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center text-secondary py-3">No bookings yet.</div>
            @endif

            <div class="mt-3 d-flex justify-content-end">
              <a href="{{ route('admin.bookings.index', ['search' => $customer->name]) }}" class="btn btn-outline-secondary">
                View all bookings
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- Right column --}}
      <aside class="col-12 col-lg-4 vstack gap-4">
        {{-- Quick Actions --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Quick Actions</h5>
            <div class="vstack gap-2">
              <a href="{{ route('admin.bookings.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Create Booking
              </a>
              <a href="mailto:{{ $customer->email }}" class="btn btn-outline-secondary">
                <i class="bi bi-envelope me-1"></i>Email Customer
              </a>
              <a href="tel:{{ $customer->phone }}" class="btn btn-outline-secondary">
                <i class="bi bi-telephone me-1"></i>Call Customer
              </a>
            </div>
          </div>
        </div>

        {{-- Meta --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Customer Meta</h5>
            <div class="vstack gap-2">
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Customer Type</span>
                <span class="fw-medium text-capitalize">{{ $customer->customer_type }}</span>
              </div>
              @if($customer->customer_source)
                <div class="d-flex justify-content-between">
                  <span class="text-secondary">Source</span>
                  <span class="fw-medium text-capitalize">{{ $customer->customer_source }}</span>
                </div>
              @endif
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Created</span>
                <span class="fw-medium">{{ $customer->created_at->format('M d, Y g:i A') }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-secondary">Last Updated</span>
                <span class="fw-medium">{{ $customer->updated_at->format('M d, Y g:i A') }}</span>
              </div>
            </div>
          </div>
        </div>

        {{-- Danger Zone (optional delete) --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3 text-danger">Danger Zone</h5>
            @if(($bookingsCount ?? 0) == 0)
              <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                    onsubmit="return confirm('Delete this customer? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">
                  <i class="bi bi-trash me-1"></i>Delete Customer
                </button>
              </form>
            @else
              <div class="text-secondary small">
                This customer has bookings and cannot be deleted.
              </div>
            @endif
          </div>
        </div>
      </aside>
    </div>
  </div>
</x-admin.layouts.app>

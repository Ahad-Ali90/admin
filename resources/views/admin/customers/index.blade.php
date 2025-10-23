{{-- resources/views/admin/customers/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .avatar{
      width:40px;height:40px;border-radius:50%;
      display:inline-flex;align-items:center;justify-content:center;
      background:#e9ecef;color:#495057;font-weight:600
    }
    .badge-business{background:#eef2ff;color:#3730a3}  /* soft indigo */
  </style>
  @endpush

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <h1 class="h3 mb-1">Customers</h1>
        <p class="text-secondary mb-0">Manage your customer database</p>
      </div>
      <div class="w-100 w-md-auto">
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary w-100 w-md-auto">
          <i class="bi bi-plus-lg me-2"></i> New Customer
        </a>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET">
          <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-5">
              <label for="search" class="form-label">Search</label>
              <input type="text" name="search" id="search" value="{{ request('search') }}"
                     class="form-control" placeholder="Name, email, phone, company">
            </div>
            <div class="col-12 col-md-4 col-lg-3">
              <label for="customer_type" class="form-label">Customer Type</label>
              <select name="customer_type" id="customer_type" class="form-select">
                <option value="">All Types</option>
                <option value="individual" @selected(request('customer_type')=='individual')>Individual</option>
                <option value="business"   @selected(request('customer_type')=='business')>Business</option>
              </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end">
              <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-outline-secondary w-100">
                  <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-danger d-none d-md-inline-block">
                  <i class="bi bi-x-circle"></i>
                </a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Customers list -->
    <div class="card">
      @if($customers->count() > 0)
        <div class="list-group list-group-flush">
          @foreach($customers as $customer)
            <div class="list-group-item">
              <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <!-- Left/meta -->
                <div class="d-flex align-items-start gap-3 flex-grow-1">
                  <div class="avatar">
                    {{ strtoupper(Str::substr($customer->name,0,1)) }}
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                      <h6 class="mb-0">{{ $customer->name }}</h6>
                      @if($customer->customer_type === 'business')
                        <span class="badge badge-business">Business</span>
                      @endif
                    </div>
                    <div class="text-secondary small">{{ $customer->email }}</div>

                    <div class="d-flex flex-wrap gap-3 mt-2 small text-secondary">
                      @if($customer->phone)
                        <div><i class="bi bi-telephone me-1"></i>{{ $customer->phone }}</div>
                      @endif
                      <div><i class="bi bi-files me-1"></i>{{ $customer->bookings_count }} bookings</div>
                      @if($customer->company_name)
                        <div><i class="bi bi-building me-1"></i>{{ $customer->company_name }}</div>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- Right/actions -->
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>View
                  </a>
                  <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i>Edit
                  </a>
                  @if($customer->bookings_count == 0)
                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                          onsubmit="return confirm('Are you sure you want to delete this customer?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                      </button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="card-body border-top">
          <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <div class="small text-secondary order-2 order-md-1">
              {{-- Optional: summary text --}}
            </div>
            <div class="order-1 order-md-2 w-100 w-md-auto overflow-auto">
              {{ $customers->links() }}
            </div>
          </div>
        </div>
      @else
        <div class="card-body text-center py-5">
          <div class="display-6 text-secondary mb-2"><i class="bi bi-people"></i></div>
          <h5 class="mb-1">No customers found</h5>
          <p class="text-secondary mb-3">Get started by creating a new customer.</p>
          <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> New Customer
          </a>
        </div>
      @endif
    </div>
  </div>
</x-admin.layouts.app>

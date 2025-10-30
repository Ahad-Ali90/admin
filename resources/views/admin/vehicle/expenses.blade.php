{{-- resources/views/admin/vehicle/expenses.blade.php --}}
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
    .stat-card:hover { transform: translateY(-2px); }
    
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
    
    .expense-type-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

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

    [data-theme="dark"] .border-top {
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .form-select,
    [data-theme="dark"] .form-control,
    [data-theme="dark"] textarea {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-select:focus,
    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] textarea:focus {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .form-control::placeholder,
    [data-theme="dark"] textarea::placeholder {
      color: var(--text-color) !important;
      opacity: 0.5;
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

    [data-theme="dark"] .text-primary {
      color: #818cf8 !important;
    }

    [data-theme="dark"] .text-info {
      color: #60a5fa !important;
    }

    [data-theme="dark"] .display-6 {
      color: var(--text-color) !important;
      opacity: 0.6;
    }

    [data-theme="dark"] .badge {
      color: white !important;
    }

    [data-theme="dark"] .alert-success {
      background: rgba(52, 211, 153, 0.15) !important;
      color: var(--text-color) !important;
      border-color: rgba(52, 211, 153, 0.3) !important;
    }

    [data-theme="dark"] .expense-type-badge {
      border: 1px solid var(--border-color) !important;
    }

    /* Modal dark mode */
    [data-theme="dark"] .modal-content {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .modal-header {
      background: var(--card-bg) !important;
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .modal-body {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .modal-footer {
      background: var(--surface-bg) !important;
      border-top-color: var(--border-color) !important;
    }

    [data-theme="dark"] .modal-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .invalid-feedback {
      color: #f87171 !important;
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="d-flex flex-column gap-3 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <div class="d-flex align-items-center gap-2 mb-2">
          <a href="{{ route('admin.vehicles.manage') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
          </a>
          <h1 class="h3 mb-0">{{ $vehicle->registration_number }} - Expenses</h1>
        </div>
        <p class="text-secondary mb-0">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
      </div>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
          <i class="bi bi-plus-lg me-2"></i>Add Expense
        </button>
      </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Filters --}}
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.vehicles.expenses', $vehicle) }}" class="row g-3">
          <div class="col-12 col-md-4">
            <label for="filter_month" class="form-label">Month</label>
            <select name="month" id="filter_month" class="form-select">
              <option value="">All Months</option>
              @php
                $months = [
                  1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                  5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                  9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ];
              @endphp
              @foreach($months as $num => $name)
                <option value="{{ $num }}" @selected(request('month') == $num)>{{ $name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12 col-md-3">
            <label for="filter_year" class="form-label">Year</label>
            <select name="year" id="filter_year" class="form-select">
              <option value="">All Years</option>
              @php
                $currentYear = date('Y');
                $startYear = $availableMonths->min('year') ?? $currentYear;
              @endphp
              @for($y = $currentYear; $y >= $startYear; $y--)
                <option value="{{ $y }}" @selected(request('year') == $y)>{{ $y }}</option>
              @endfor
            </select>
          </div>

          <div class="col-12 col-md-3">
            <label for="filter_expense_type" class="form-label">Expense Type</label>
            <select name="expense_type" id="filter_expense_type" class="form-select">
              <option value="">All Types</option>
              <option value="fuel" @selected(request('expense_type') == 'fuel')>Fuel</option>
              <option value="maintenance" @selected(request('expense_type') == 'maintenance')>Maintenance</option>
              <option value="repair" @selected(request('expense_type') == 'repair')>Repair</option>
              <option value="insurance" @selected(request('expense_type') == 'insurance')>Insurance</option>
              <option value="mot" @selected(request('expense_type') == 'mot')>MOT</option>
              <option value="tax" @selected(request('expense_type') == 'tax')>Tax</option>
              <option value="cleaning" @selected(request('expense_type') == 'cleaning')>Cleaning</option>
              <option value="parking" @selected(request('expense_type') == 'parking')>Parking</option>
              <option value="toll" @selected(request('expense_type') == 'toll')>Toll</option>
              <option value="fine" @selected(request('expense_type') == 'fine')>Fine</option>
              <option value="service" @selected(request('expense_type') == 'service')>Service</option>
              <option value="parts" @selected(request('expense_type') == 'parts')>Parts</option>
              <option value="tyres" @selected(request('expense_type') == 'tyres')>Tyres</option>
              <option value="other" @selected(request('expense_type') == 'other')>Other</option>
            </select>
          </div>

          <div class="col-12 col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-funnel me-1"></i>Filter
            </button>
            @if(request()->hasAny(['month', 'year', 'expense_type']))
              <a href="{{ route('admin.vehicles.expenses', $vehicle) }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i>
              </a>
            @endif
          </div>
        </form>

        {{-- Quick Month Links --}}
        @if($availableMonths->count() > 0)
          <div class="mt-3 pt-3 border-top">
            <div class="small text-secondary mb-2">Quick Filter:</div>
            <div class="d-flex flex-wrap gap-2">
              @foreach($availableMonths->take(6) as $am)
                @php
                  $monthName = date('M Y', mktime(0, 0, 0, $am->month, 1, $am->year));
                @endphp
                <a href="{{ route('admin.vehicles.expenses', $vehicle) }}?month={{ $am->month }}&year={{ $am->year }}" 
                   class="btn btn-sm {{ request('month') == $am->month && request('year') == $am->year ? 'btn-primary' : 'btn-outline-secondary' }}">
                  {{ $monthName }}
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 g-md-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-blue">
              <i class="bi bi-cash-stack text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">
                {{ request()->hasAny(['month', 'year', 'expense_type']) ? 'Filtered' : 'Total' }} Expenses
              </div>
              <div class="fs-4 fw-bold text-danger">£{{ number_format($stats['filtered_total'], 2) }}</div>
              @if(request()->hasAny(['month', 'year', 'expense_type']) && $stats['total_expenses'] != $stats['filtered_total'])
                <div class="small text-secondary">of £{{ number_format($stats['total_expenses'], 2) }} total</div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-green">
              <i class="bi bi-calendar-month text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Monthly Average</div>
              <div class="fs-4 fw-bold">£{{ number_format($stats['monthly_average'], 2) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-orange">
              <i class="bi bi-graph-up text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Total Revenue</div>
              <div class="fs-4 fw-bold text-success">£{{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon icon-purple">
              <i class="bi bi-trophy text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-secondary small">Net Profit</div>
              <div class="fs-4 fw-bold {{ $stats['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                £{{ number_format($stats['net_profit'], 2) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Expense Breakdown by Type --}}
    @if(count($stats['expenses_by_type']) > 0)
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title mb-4">Expense Breakdown by Type</h5>
        <div class="row g-3">
          @foreach($stats['expenses_by_type'] as $type => $amount)
            @php
              $expenseModel = new \App\Models\VehicleExpense(['expense_type' => $type]);
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
              <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi {{ $expenseModel->expense_type_icon }} text-{{ $expenseModel->expense_type_color }}"></i>
                  <span>{{ $expenseModel->expense_type_label }}</span>
                </div>
                <span class="fw-bold">£{{ number_format($amount, 2) }}</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    {{-- Expenses List --}}
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">
          {{ request()->hasAny(['month', 'year', 'expense_type']) ? 'Filtered' : 'All' }} Expenses
          <span class="badge bg-secondary">{{ $filteredExpenses->count() }}</span>
        </h5>

        @if($filteredExpenses->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Date</th>
                  <th>Type</th>
                  <th>Title</th>
                  <th>Vendor</th>
                  <th>Receipt #</th>
                  <th>Mileage</th>
                  <th class="text-end">Amount</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($filteredExpenses as $expense)
                  <tr>
                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td>
                      <span class="expense-type-badge bg-{{ $expense->expense_type_color }} bg-opacity-10 text-{{ $expense->expense_type_color }}">
                        <i class="bi {{ $expense->expense_type_icon }}"></i>
                        {{ $expense->expense_type_label }}
                      </span>
                    </td>
                    <td>
                      <div class="fw-medium">{{ $expense->title }}</div>
                      @if($expense->description)
                        <div class="small text-secondary">{{ Str::limit($expense->description, 50) }}</div>
                      @endif
                    </td>
                    <td>{{ $expense->vendor ?? '-' }}</td>
                    <td>{{ $expense->receipt_number ?? '-' }}</td>
                    <td>{{ $expense->mileage_at_expense ? number_format($expense->mileage_at_expense) . ' mi' : '-' }}</td>
                    <td class="text-end fw-bold">£{{ number_format($expense->amount, 2) }}</td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-outline-primary" 
                              onclick="viewExpense({{ $expense->id }})"
                              data-bs-toggle="modal" 
                              data-bs-target="#viewExpenseModal">
                        <i class="bi bi-eye"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" 
                              onclick="editExpense({{ $expense->id }})"
                              data-bs-toggle="modal" 
                              data-bs-target="#editExpenseModal">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <form method="POST" action="{{ route('admin.vehicles.expenses.destroy', [$vehicle, $expense]) }}" 
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this expense?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="6" class="fw-bold">
                    {{ request()->hasAny(['month', 'year', 'expense_type']) ? 'Filtered' : '' }} Total
                  </td>
                  <td class="text-end fw-bold">£{{ number_format($stats['filtered_total'], 2) }}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <div class="display-6 text-secondary mb-2"><i class="bi bi-receipt"></i></div>
            <h5 class="mb-1">No expenses recorded</h5>
            <p class="text-secondary mb-3">Start tracking expenses for this vehicle.</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
              <i class="bi bi-plus-lg me-2"></i>Add First Expense
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Add Expense Modal --}}
  <div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST" action="{{ route('admin.vehicles.expenses.store', $vehicle) }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New Expense</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label for="expense_type" class="form-label">Expense Type <span class="text-danger">*</span></label>
                <select name="expense_type" id="expense_type" class="form-select @error('expense_type') is-invalid @enderror" required>
                  <option value="">Select type</option>
                  <option value="fuel">Fuel</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="repair">Repair</option>
                  <option value="insurance">Insurance</option>
                  <option value="mot">MOT</option>
                  <option value="tax">Tax</option>
                  <option value="cleaning">Cleaning</option>
                  <option value="parking">Parking</option>
                  <option value="toll">Toll</option>
                  <option value="fine">Fine</option>
                  <option value="service">Service</option>
                  <option value="parts">Parts</option>
                  <option value="tyres">Tyres</option>
                  <option value="other">Other</option>
                </select>
                @error('expense_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label for="expense_date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}"
                       class="form-control @error('expense_date') is-invalid @enderror" required>
                @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="form-control @error('title') is-invalid @enderror" 
                       placeholder="e.g., Full tank refuel" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="2"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Additional details...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-4">
                <label for="amount" class="form-label">Amount (£) <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0"
                       class="form-control @error('amount') is-invalid @enderror" required>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-4">
                <label for="receipt_number" class="form-label">Receipt Number</label>
                <input type="text" name="receipt_number" id="receipt_number" value="{{ old('receipt_number') }}"
                       class="form-control @error('receipt_number') is-invalid @enderror">
                @error('receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-4">
                <label for="vendor" class="form-label">Vendor</label>
                <input type="text" name="vendor" id="vendor" value="{{ old('vendor') }}"
                       class="form-control @error('vendor') is-invalid @enderror"
                       placeholder="e.g., Shell, Kwik Fit">
                @error('vendor')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label for="mileage_at_expense" class="form-label">Mileage at Expense</label>
                <input type="number" name="mileage_at_expense" id="mileage_at_expense" value="{{ old('mileage_at_expense', $vehicle->mileage) }}"
                       class="form-control @error('mileage_at_expense') is-invalid @enderror" min="0">
                @error('mileage_at_expense')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" rows="2"
                          class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Expense</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    const expenses = @json($filteredExpenses);
    
    function viewExpense(id) {
      const expense = expenses.find(e => e.id === id);
      if (!expense) return;
      
      // You can implement a view modal here
      console.log('View expense:', expense);
    }
    
    function editExpense(id) {
      const expense = expenses.find(e => e.id === id);
      if (!expense) return;
      
      // You can implement an edit modal here
      console.log('Edit expense:', expense);
    }
  </script>
  @endpush
</x-admin.layouts.app>


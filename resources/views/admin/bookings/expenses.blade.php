<x-admin.layouts.app>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Booking Expenses</h1>
                    <p class="text-muted mb-0">Manage expenses for booking #{{ $booking->id }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Booking
                    </a>
                </div>
            </div>

            {{-- Booking Summary --}}
            <!-- <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-secondary small">Customer</div>
                            <div class="fw-medium">{{ $booking->customer->name }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-secondary small">Final Total Fare</div>
                            <div class="fw-medium">£{{ number_format($booking->getFinalTotalFare(), 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-secondary small">Total Expenses</div>
                            <div class="fw-medium text-danger">£{{ number_format($booking->getTotalExpenses(), 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-secondary small">Net Profit</div>
                            <div class="fw-medium text-success">£{{ number_format($booking->getNetProfit(), 2) }}</div>
                        </div>
                    </div>
                    
                    {{-- Calculation Breakdown --}}
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <div class="text-secondary small">Original Total</div>
                            <div class="fw-medium">£{{ number_format($booking->total_fare, 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-secondary small">Discount</div>
                            <div class="fw-medium text-warning">-£{{ number_format($booking->discount ?? 0, 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-secondary small">Final Total</div>
                            <div class="fw-medium text-primary">£{{ number_format($booking->getFinalTotalFare(), 2) }}</div>
                        </div>
                        @if($booking->extra_hours && $booking->extra_hours > 0)
                        <div class="col-md-3">
                            <div class="text-secondary small">Extra Hours</div>
                            <div class="fw-medium text-success">+£{{ number_format($booking->extra_hours_amount ?? 0, 2) }}</div>
                            <div class="small text-muted">{{ $booking->extra_hours }} hrs @ £{{ number_format($booking->extra_hours_rate, 2) }}/hr</div>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Revenue & Deductions --}}
                    <div class="row g-3 mt-3 pt-3 border-top">
                        <div class="col-md-3">
                            <div class="text-secondary small">Total Revenue</div>
                            <div class="fw-bold text-success">£{{ number_format($booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0), 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-secondary small">Total Expenses</div>
                            <div class="fw-bold text-danger">-£{{ number_format($booking->getTotalExpenses(), 2) }}</div>
                        </div>
                        @if($booking->is_company_booking && $booking->company_commission_amount > 0)
                        <div class="col-md-3">
                            <div class="text-secondary small">Company Commission</div>
                            <div class="fw-bold text-info">-£{{ number_format($booking->company_commission_amount ?? 0, 2) }}</div>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <div class="text-secondary small">Net Profit</div>
                            <div class="fw-bold fs-5 text-success">£{{ number_format($booking->getNetProfit(), 2) }}</div>
                        </div>
                    </div>
                </div>
            </div> -->

            {{-- Add New Expense Form --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Expense</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.expenses.store', $booking) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="expense_type" class="form-label">Expense Type</label>
                                <select name="expense_type" id="expense_type" class="form-select @error('expense_type') is-invalid @enderror" required>
                                    <option value="">Select expense type</option>
                                    <option value="driver_payment" @selected(old('expense_type')=='driver_payment')>Driver Payment</option>
                                    <option value="porter_payment" @selected(old('expense_type')=='porter_payment')>Porter Payment</option>
                                    <option value="congestion_charge" @selected(old('expense_type')=='congestion_charge')>Congestion Charge</option>
                                    <option value="ulez_charge" @selected(old('expense_type')=='ulez_charge')>ULEZ Charge</option>
                                    <option value="toll_charge" @selected(old('expense_type')=='toll_charge')>Toll Charge</option>
                                    <option value="extra_waiting" @selected(old('expense_type')=='extra_waiting')>Extra Waiting Time</option>
                                    <option value="fuel" @selected(old('expense_type')=='fuel')>Fuel</option>
                                    <option value="other" @selected(old('expense_type')=='other')>Other</option>
                                </select>
                                @error('expense_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label for="amount" class="form-label">Amount (£)</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label for="expense_date" class="form-label">Expense Date</label>
                                <input type="date" name="expense_date" id="expense_date" 
                                       class="form-control @error('expense_date') is-invalid @enderror" 
                                       value="{{ old('expense_date', date('Y-m-d')) }}" required>
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label for="paid_to_user_id" class="form-label">Paid To</label>
                                <select name="paid_to_user_id" id="paid_to_user_id" class="form-select @error('paid_to_user_id') is-invalid @enderror">
                                    <option value="">Select user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected(old('paid_to_user_id')==$user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('paid_to_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" name="description" id="description" 
                                       class="form-control @error('description') is-invalid @enderror" 
                                       value="{{ old('description') }}" placeholder="Brief description of the expense">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="notes" class="form-label">Notes</label>
                                <input type="text" name="notes" id="notes" 
                                       class="form-control @error('notes') is-invalid @enderror" 
                                       value="{{ old('notes') }}" placeholder="Additional notes">
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Expense
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Expenses List --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Expenses List</h5>
                </div>
                <div class="card-body">
                    @if($booking->expenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Paid To</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $expense->expense_type_label }}</span>
                                            </td>
                                            <td>{{ $expense->description ?? '-' }}</td>
                                            <td class="fw-medium">£{{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->paidToUser->name ?? '-' }}</td>
                                            <td>{{ $expense->notes ?? '-' }}</td>
                                            <td>
                                                <form action="{{ route('admin.bookings.expenses.destroy', [$booking, $expense]) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3">Total Expenses</th>
                                        <th class="text-danger">£{{ number_format($booking->getTotalExpenses(), 2) }}</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No expenses recorded</h5>
                            <p class="text-muted">Add your first expense using the form above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default amount for congestion charge
    const expenseTypeSelect = document.getElementById('expense_type');
    const amountInput = document.getElementById('amount');
    
    expenseTypeSelect.addEventListener('change', function() {
        if (this.value === 'congestion_charge') {
            amountInput.value = '16.00';
        } else if (this.value === 'ulez_charge') {
            amountInput.value = '';
        }
    });
});
</script>
</x-admin.layouts.app>

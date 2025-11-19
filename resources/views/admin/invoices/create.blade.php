{{-- resources/views/admin/invoices/create.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    
    /* Dark Mode Support */
    [data-theme="dark"] .card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .card-footer {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .card-body {
      background: var(--card-bg) !important;
    }
  </style>
  @endpush

  <div class="container-fluid py-4">
    <div class="row mb-4">
      <div class="col">
        <h1 class="h3 mb-0">Create Invoice</h1>
        <p class="text-muted mb-0">Booking Reference: {{ $booking->booking_reference }}</p>
      </div>
      <div class="col-auto">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Back
        </a>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h5>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <form action="{{ route('admin.invoices.store', $booking) }}" method="POST">
      @csrf

      <div class="card">
        <div class="card-body">
          <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
              <h5>Invoice Items</h5>
              <p class="text-muted small mb-0">Add items to the invoice. You can add multiple rows.</p>
            </div>
            <div style="min-width: 300px;">
              <!-- <label class="form-label">Attendant Name</label> -->
              <input type="text" name="company_attendant" class="form-control" 
                     value="{{ old('company_attendant', $booking->company->attendant ?? '') }}" 
                     placeholder="Enter attendant name">
              <!-- <small class="text-muted">Will appear on invoice as "Attn:"</small> -->
            </div>
          </div>

          <div id="items-container">
            @if($invoice->items->count() > 0)
              @foreach($invoice->items as $index => $item)
                <div class="item-row mb-3 p-3 border rounded">
                  <div class="row g-3">
                    <div class="col-md-5">
                      <label class="form-label">Description <span class="text-danger">*</span></label>
                      <textarea name="items[{{ $index }}][description]" class="form-control" rows="1" required>{{ $item->description }}</textarea>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Rate <span class="text-danger">*</span></label>
                      <input type="text" name="items[{{ $index }}][rate]" class="form-control" value="{{ $item->rate }}" placeholder="e.g., 105.00 / hr" required>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Amount (£) <span class="text-danger">*</span></label>
                      <input type="number" name="items[{{ $index }}][amount]" class="form-control" step="0.01" min="0" value="{{ $item->amount }}" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                      <button type="button" class="btn btn-danger w-100 remove-item-btn">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
            @else
              <div class="item-row mb-3 p-3 border rounded">
                <div class="row g-3">
                  <div class="col-md-5">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="items[0][description]" class="form-control" rows="2" required></textarea>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Rate <span class="text-danger">*</span></label>
                    <input type="text" name="items[0][rate]" class="form-control" placeholder="e.g., 105.00 / hr" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Amount (£) <span class="text-danger">*</span></label>
                    <input type="number" name="items[0][amount]" class="form-control" step="0.01" min="0" required>
                  </div>
                  <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 remove-item-btn">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            @endif
          </div>

          <div class="mt-3">
            <button type="button" id="add-item-btn" class="btn btn-outline-primary">
              <i class="bi bi-plus-lg me-1"></i>Add Another Item
            </button>
          </div>
        </div>

        <div class="card-footer bg-transparent border-top">
          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary" style="padding:10px 20px !important;border-radius:0.25rem !important">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary" style="padding:10px 20px !important;border-radius:0.25rem !important">
              <i class="bi bi-check-lg me-1"></i>Save & View Invoice
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item-btn');
    let itemIndex = {{ $invoice->items->count() > 0 ? $invoice->items->count() : 1 }};

    addBtn.addEventListener('click', function() {
      const newItem = document.createElement('div');
      newItem.className = 'item-row mb-3 p-3 border rounded';
      newItem.innerHTML = `
        <div class="row g-3">
          <div class="col-md-5">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="items[${itemIndex}][description]" class="form-control" rows="2" required></textarea>
          </div>
          <div class="col-md-3">
            <label class="form-label">Rate <span class="text-danger">*</span></label>
            <input type="text" name="items[${itemIndex}][rate]" class="form-control" placeholder="e.g., 105.00 / hr" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Amount (£) <span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][amount]" class="form-control" step="0.01" min="0" required>
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger w-100 remove-item-btn">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      `;
      container.appendChild(newItem);
      itemIndex++;
      attachRemoveHandlers();
    });

    function attachRemoveHandlers() {
      document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.onclick = function() {
          if (container.querySelectorAll('.item-row').length > 1) {
            btn.closest('.item-row').remove();
          } else {
            alert('At least one item is required.');
          }
        };
      });
    }

    attachRemoveHandlers();
  });
  </script>
  @endpush
</x-admin.layouts.app>


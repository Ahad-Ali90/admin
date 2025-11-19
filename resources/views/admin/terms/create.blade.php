{{-- resources/views/admin/terms/create.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    
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

    [data-theme="dark"] .card-footer {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--primary-color) !important;
    }

    [data-theme="dark"] h1,
    [data-theme="dark"] h3,
    [data-theme="dark"] h5,
    [data-theme="dark"] h6 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-muted,
    [data-theme="dark"] .small.text-muted {
      color: var(--text-color) !important;
      opacity: 0.7;
    }
  </style>
  @endpush

  <div class="container-fluid py-4">
    <div class="row mb-4">
      <div class="col">
        <h1 class="h3 mb-0">Create Terms and Conditions</h1>
        <p class="text-muted mb-0">Add new terms and conditions</p>
      </div>
      <div class="col-auto">
        <a href="{{ route('admin.terms.index') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Back to List
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

    <form action="{{ route('admin.terms.store') }}" method="POST">
      @csrf

      <div class="card">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-8">
              <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
              <input type="text" 
                     class="form-control @error('title') is-invalid @enderror" 
                     id="title" 
                     name="title" 
                     value="{{ old('title') }}" 
                     placeholder="Enter title"
                     required>
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
              <select class="form-select @error('type') is-invalid @enderror" 
                      id="type" 
                      name="type" 
                      required>
                <option value="">Select Type</option>
                <option value="customer" {{ old('type') === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="company" {{ old('type') === 'company' ? 'selected' : '' }}>Company</option>
              </select>
              @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12">
              <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
              <textarea class="form-control @error('content') is-invalid @enderror" 
                        id="content" 
                        name="content" 
                        rows="10" 
                        placeholder="Enter terms and conditions content"
                        required>{{ old('content') }}</textarea>
              @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="text-muted">You can use line breaks. HTML is allowed.</small>
            </div>

            <div class="col-md-6">
              <label for="display_order" class="form-label">Display Order</label>
              <input type="number" 
                     class="form-control @error('display_order') is-invalid @enderror" 
                     id="display_order" 
                     name="display_order" 
                     value="{{ old('display_order', 0) }}" 
                     min="0"
                     placeholder="0">
              @error('display_order')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="text-muted">Lower numbers appear first</small>
            </div>

            <div class="col-md-6">
              <label class="form-label d-block mb-3">Status</label>
              <div class="form-check form-switch">
                <input class="form-check-input" 
                       type="checkbox" 
                       role="switch" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                  Active (visible to users)
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer bg-transparent border-top">
          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.terms.index') }}" class="btn btn-outline-secondary">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Create Term
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</x-admin.layouts.app>


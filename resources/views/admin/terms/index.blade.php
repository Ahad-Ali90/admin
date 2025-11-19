{{-- resources/views/admin/terms/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    
    .badge{padding:0.5rem 0.75rem;border-radius:0.5rem;font-weight:500}
    
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

    [data-theme="dark"] .table {
      color: var(--text-color) !important;
      --bs-table-bg: var(--card-bg) !important;
      --bs-table-hover-bg: var(--hover-bg) !important;
      --bs-table-hover-color: var(--text-color) !important;
    }

    [data-theme="dark"] .table thead th {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .table tbody td {
      border-color: var(--border-color) !important;
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table tbody tr {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    /* Override Bootstrap table-hover specifically */
    [data-theme="dark"] .table-hover tbody tr:hover {
      background-color: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table-hover tbody tr:hover td,
    [data-theme="dark"] .table-hover tbody tr:hover th {
      background-color: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table tbody tr:hover {
      background: var(--hover-bg) !important;
      --bs-table-accent-bg: var(--hover-bg) !important;
    }

    [data-theme="dark"] .table tbody tr:hover td {
      background: transparent !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table tbody tr:hover td strong {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table tbody tr:hover .text-muted {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] h1,
    [data-theme="dark"] h3,
    [data-theme="dark"] h5,
    [data-theme="dark"] h6 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-muted {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] .small.text-muted {
      color: var(--text-color) !important;
      opacity: 0.6;
    }
  </style>
  @endpush

  <div class="container-fluid py-4">
    <div class="row mb-4">
      <div class="col">
        <h1 class="h3 mb-0">Terms and Conditions</h1>
        <p class="text-muted mb-0">Manage terms and conditions for customers and companies</p>
      </div>
      <div class="col-auto">
        <a href="{{ route('admin.terms.create') }}" class="btn btn-primary">
          <i class="bi bi-plus-lg me-1"></i>Add New Term
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        @if($terms->isEmpty())
          <div class="text-center py-5">
            <i class="bi bi-file-text" style="font-size: 3rem; color: #6c757d;"></i>
            <p class="mt-3 mb-0 text-muted">No terms and conditions found. Create one to get started.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>Title</th>
                  <th style="width: 120px;">Type</th>
                  <th style="width: 100px;">Order</th>
                  <th style="width: 100px;">Status</th>
                  <th style="width: 150px;" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($terms as $term)
                  <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                      <strong>{{ $term->title }}</strong>
                      <br>
                      <small class="text-muted">
                        {{ Str::limit(strip_tags($term->content), 80) }}
                      </small>
                    </td>
                    <td>
                      @if($term->type === 'customer')
                        <span class="badge bg-primary">Customer</span>
                      @else
                        <span class="badge bg-info">Company</span>
                      @endif
                    </td>
                    <td>
                      <span class="badge bg-secondary">{{ $term->display_order }}</span>
                    </td>
                    <td>
                      @if($term->is_active)
                        <span class="badge bg-success">Active</span>
                      @else
                        <span class="badge bg-danger">Inactive</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <div class="btn-group" role="group">
                        <a href="{{ route('admin.terms.edit', $term) }}" 
                           class="btn btn-sm btn-outline-primary"
                           title="Edit">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.terms.destroy', $term) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this term?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-admin.layouts.app>


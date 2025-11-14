{{-- resources/views/admin/lead-sources/manage.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .source-card {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1.5rem;
      transition: all 0.3s ease;
      cursor: pointer;
      background: white;
    }
    
    .source-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-color: #4f46e5;
    }
    
    .source-card.inactive {
      opacity: 0.6;
      background: #f9fafb;
    }
    
    .source-icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .color-picker-preview {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      border: 2px solid #e5e7eb;
      cursor: pointer;
    }
    
    .modal-content {
      border-radius: 16px;
      border: none;
    }
    
    .modal-header {
      border-bottom: 1px solid #f3f4f6;
      padding: 1.5rem;
    }
    
    .modal-footer {
      border-top: 1px solid #f3f4f6;
      padding: 1.5rem;
    }

    /* Dark Mode Support */
    [data-theme="dark"] h1,
    [data-theme="dark"] h2,
    [data-theme="dark"] h5,
    [data-theme="dark"] h6 {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-secondary,
    [data-theme="dark"] .text-muted {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] p {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .source-card {
      background: var(--card-bg) !important;
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .source-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
      border-color: #4f46e5 !important;
    }

    [data-theme="dark"] .source-card.inactive {
      background: var(--surface-bg) !important;
    }

    [data-theme="dark"] .color-picker-preview {
      border-color: var(--border-color) !important;
    }

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

    [data-theme="dark"] .form-label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-control-color {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-control:focus {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .form-control::placeholder {
      color: var(--text-color) !important;
      opacity: 0.5;
    }

    [data-theme="dark"] .form-check-input {
      background-color: var(--input-bg) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-check-input:checked {
      background-color: var(--primary) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .form-check-label {
      color: var(--text-color) !important;
    }

    

    [data-theme="dark"] .badge.bg-light {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-dark {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] a {
      color: white !important;
    }

    

    [data-theme="dark"] small {
      color: var(--text-color) !important;
      opacity: 0.8;
    }

    [data-theme="dark"] .alert-success {
      background: rgba(52, 211, 153, 0.15) !important;
      color: var(--text-color) !important;
      border-color: rgba(52, 211, 153, 0.3) !important;
    }

    [data-theme="dark"] .alert-danger {
      background: rgba(248, 113, 113, 0.15) !important;
      color: var(--text-color) !important;
      border-color: rgba(248, 113, 113, 0.3) !important;
    }
  </style>
  @endpush

  <div class="container-xxl py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h2 mb-2">Lead Sources</h1>
        <p class="text-secondary mb-0">Manage lead sources for tracking where bookings come from.</p>
      </div>
      <button type="button" class="btn btn-primary" onclick="openCreateModal()">
        <i class="bi bi-plus-circle me-2"></i>Add Lead Source
      </button>
    </div>

    {{-- Sources Grid --}}
    <div class="row g-4" id="sourcesGrid">
      {{-- Will be populated by JavaScript --}}
    </div>
  </div>

  {{-- Create/Edit Modal --}}
  <div class="modal fade" id="sourceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Lead Source</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="sourceForm">
            <input type="hidden" id="sourceId">
            
            <div class="mb-3">
              <label for="name" class="form-label">Source Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" required 
                     placeholder="e.g., Facebook, Google Ads, Instagram">
            </div>

            <div class="mb-3">
              <label for="icon" class="form-label">Icon (Bootstrap Icon class)</label>
              <input type="text" class="form-control" id="icon" 
                     placeholder="e.g., bi-facebook, bi-google, bi-instagram">
              <small class="text-muted">
                Browse icons at: 
                <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>
              </small>
            </div>

            <div class="mb-3">
              <label for="color" class="form-label">Color</label>
              <div class="d-flex align-items-center gap-2">
                <input type="color" class="form-control form-control-color" id="color" value="#4f46e5">
                <input type="text" class="form-control" id="colorHex" value="#4f46e5" 
                       pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
              </div>
            </div>

            <div class="mb-3 d-none">
              <label for="sort_order" class="form-label">Sort Order</label>
              <input type="number" class="form-control" id="sort_order" min="0" value="0">
              <small class="text-muted">Lower numbers appear first</small>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" checked>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveSource()">
            <i class="bi bi-check-circle me-2"></i>Save
          </button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    let sources = [];
    let modal = null;
    let editingId = null;

    document.addEventListener('DOMContentLoaded', function() {
      modal = new bootstrap.Modal(document.getElementById('sourceModal'));
      
      // Sync color inputs
      document.getElementById('color').addEventListener('input', (e) => {
        document.getElementById('colorHex').value = e.target.value;
      });
      
      document.getElementById('colorHex').addEventListener('input', (e) => {
        if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
          document.getElementById('color').value = e.target.value;
        }
      });
      
      loadSources();
    });

    function loadSources() {
      fetch('{{ route('admin.lead-sources.index') }}')
        .then(res => res.json())
        .then(data => {
          sources = data;
          renderSources();
        })
        .catch(err => {
          console.error('Error loading sources:', err);
          showToast('Error loading lead sources', 'error');
        });
    }

    function renderSources() {
      const grid = document.getElementById('sourcesGrid');
      
      if (sources.length === 0) {
        grid.innerHTML = `
          <div class="col-12">
            <div class="text-center py-5">
              <i class="bi bi-inbox fs-1 text-secondary d-block mb-3"></i>
              <h5 class="text-secondary">No Lead Sources Yet</h5>
              <p class="text-muted">Click "Add Lead Source" to create your first source.</p>
            </div>
          </div>
        `;
        return;
      }

      grid.innerHTML = sources.map(source => `
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="source-card ${source.is_active ? '' : 'inactive'}" onclick="viewSourceDetails(${source.id})">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div class="source-icon" style="background: ${source.color}20; color: ${source.color};">
                <i class="bi ${source.icon || 'bi-megaphone'}"></i>
              </div>
              <div class="d-flex gap-1" onclick="event.stopPropagation()">
                <button class="btn btn-outline-danger p-1" style="border:none !important" onclick="openEditModal(${source.id}, event)" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
                
              </div>
            </div>
            <h6 class="mb-2 fw-bold">${source.name}</h6>
            <div class="d-flex gap-2 align-items-center flex-wrap">
              <span class="badge ${source.is_active ? 'bg-success' : 'bg-secondary'}">
                ${source.is_active ? 'Active' : 'Inactive'}
              </span>
              <span class="badge bg-info text-white">Jobs: ${source.bookings_count || 0}</span>
            </div>
          </div>
        </div>
      `).join('');
    }

    function openCreateModal() {
      editingId = null;
      document.getElementById('modalTitle').textContent = 'Add Lead Source';
      document.getElementById('sourceForm').reset();
      document.getElementById('sourceId').value = '';
      document.getElementById('color').value = '#4f46e5';
      document.getElementById('colorHex').value = '#4f46e5';
      document.getElementById('is_active').checked = true;
      modal.show();
    }

    function viewSourceDetails(id) {
      window.location.href = `{{ url('admin/lead-sources') }}/${id}/details`;
    }

    function openEditModal(id, event) {
      if (event) {
        event.stopPropagation();
      }
      const source = sources.find(s => s.id === id);
      if (!source) return;

      editingId = id;
      document.getElementById('modalTitle').textContent = 'Edit Lead Source';
      document.getElementById('sourceId').value = source.id;
      document.getElementById('name').value = source.name;
      document.getElementById('icon').value = source.icon || '';
      document.getElementById('color').value = source.color;
      document.getElementById('colorHex').value = source.color;
      document.getElementById('sort_order').value = source.sort_order;
      document.getElementById('is_active').checked = source.is_active;
      modal.show();
    }

    function saveSource() {
      const form = document.getElementById('sourceForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const data = {
        name: document.getElementById('name').value,
        icon: document.getElementById('icon').value,
        color: document.getElementById('color').value,
        sort_order: document.getElementById('sort_order').value,
        is_active: document.getElementById('is_active').checked ? 1 : 0,
      };

      const url = editingId 
        ? `{{ url('admin/lead-sources') }}/${editingId}`
        : '{{ route('admin.lead-sources.store') }}';
      
      const method = editingId ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast(data.message, 'success');
          modal.hide();
          loadSources();
        } else {
          showToast(data.message || 'Error saving lead source', 'error');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showToast('Error saving lead source', 'error');
      });
    }

    function deleteSource(id, event) {
      event.stopPropagation();
      
      if (!confirm('Are you sure you want to delete this lead source? This cannot be undone.')) {
        return;
      }

      fetch(`{{ url('admin/lead-sources') }}/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast(data.message, 'success');
          loadSources();
        } else {
          showToast(data.message || 'Error deleting lead source', 'error');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showToast('Error deleting lead source', 'error');
      });
    }

    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
      toast.style.zIndex = '9999';
      toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
      `;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }
  </script>
  @endpush
</x-admin.layouts.app>


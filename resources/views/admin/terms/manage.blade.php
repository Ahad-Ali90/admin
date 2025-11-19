{{-- resources/views/admin/terms/manage.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-type{padding:0.5rem 0.75rem;border-radius:0.5rem;font-weight:500}
    
    /* Dark Mode Support */
    [data-theme="dark"] .card {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .card-body {
      background: var(--card-bg) !important;
    }
    [data-theme="dark"] .table {
      color: var(--text-color) !important;
      --bs-table-bg: var(--card-bg) !important;
      --bs-table-hover-bg: var(--hover-bg) !important;
    }
    [data-theme="dark"] .table thead th {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }
    [data-theme="dark"] .table tbody td {
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .table tbody tr:hover {
      background: var(--hover-bg) !important;
    }
    [data-theme="dark"] .modal-content {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-radius: 0 !important;
    }
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }
    [data-theme="dark"] .modal-body {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .modal-title {
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .btn-close {
      filter: invert(1);
    }
    .modal-content {
      border-radius: 0 !important;
    }
    .modal-dialog {
      max-height: 90vh;
    }
    .modal-body {
      max-height: calc(90vh - 200px);
      overflow-y: auto;
    }
  </style>
  @endpush

  <div class="container-xxl py-3">
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-3">
      <div>
        <h1 class="h3 mb-1">Terms and Conditions</h1>
        <p class="text-secondary mb-0">Manage terms for customers and companies.</p>
      </div>
      <div>
        <button id="btnCreate" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Term</button>
      </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
      <div class="card-body">
        <form id="filterForm" class="row g-2">
          <div class="col-12 col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by title">
          </div>
          <div class="col-12 col-md-3">
            <select name="type" class="form-select">
              <option value="">All Types</option>
              <option value="customer">Customer</option>
              <option value="company">Company</option>
            </select>
          </div>
          <div class="col-12 col-md-3">
            <select name="status" class="form-select">
              <option value="">All Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="col-12 col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Table --}}
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Title</th>
                <th style="width: 120px;">Type</th>
                <!-- <th style="width: 100px;">Order</th> -->
                <!-- <th style="width: 100px;">Status</th> -->
                <th style="width: 150px;" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="tbodyTerms"></tbody>
          </table>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div id="pageMeta" class="small text-secondary"></div>
          <nav><ul id="pager" class="pagination mb-0"></ul></nav>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal --}}
  <div class="modal fade" id="termModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="termForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Terms and Conditions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="formAlert" class="alert alert-danger d-none"></div>

            <div class="vstack gap-3">
              <div class="row">
                <div class="col-md-8">
                  <label class="form-label">Title <span class="text-danger">*</span></label>
                  <input name="title" class="form-control" placeholder="Enter title">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Type <span class="text-danger">*</span></label>
                  <select name="type" class="form-select">
                    <option value="">Select Type</option>
                    <option value="customer">Customer</option>
                    <option value="company">Company</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="form-label">Content <span class="text-danger">*</span></label>
                <textarea name="content" id="content" class="form-control" rows="10"></textarea>
              </div>

              <div class="row">
                <!-- <div class="col-md-6">
                  <label class="form-label">Display Order</label>
                  <input name="display_order" type="number" min="0" value="0" class="form-control" placeholder="0">
                  <small class="text-muted">Lower numbers appear first</small>
                </div> -->
                <div class="col-md-12">
                  <label class="form-label d-block mb-3">Status</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" checked>
                    <label class="form-check-label" for="is_active">Active (visible to users)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="btnSubmit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  {{-- TinyMCE CDN --}}
  
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap === 'undefined') { console.error('Bootstrap JS not loaded'); return; }

    let modal, modalEl, editId = null;
    const tbody = document.getElementById('tbodyTerms');
    const filterForm = document.getElementById('filterForm');
    const termForm = document.getElementById('termForm');
    const pageMeta = document.getElementById('pageMeta');
    const pager = document.getElementById('pager');
    let currentPage = 1;

    modalEl = document.getElementById('termModal');
    modal = new bootstrap.Modal(modalEl);

    // Clear form when modal is hidden
    modalEl.addEventListener('hidden.bs.modal', function() {
      termForm.reset();
    });

    function fetchTerms(page = 1) {
      currentPage = page;
      const fd = new FormData(filterForm);
      const params = new URLSearchParams(fd);
      params.append('page', page);

      fetch(`/admin/terms?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(res => {
        renderTable(res.data);
        renderPagination(res);
      })
      .catch(err => console.error(err));
    }

    function renderTable(items) {
      tbody.innerHTML = '';
      if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">No terms found</td></tr>';
        return;
      }
      items.forEach(item => {
        const tr = document.createElement('tr');
        
        const typeClass = item.type === 'customer' ? 'bg-primary' : 'bg-info';
        const statusClass = item.is_active ? 'bg-success' : 'bg-danger';
        
        tr.innerHTML = `
          <td>
            <strong>${item.title}</strong>
            <br><small class="text-muted">${truncate(stripHtml(item.content), 60)}</small>
          </td>
          <td><span class="badge ${typeClass}">${capitalize(item.type)}</span></td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${item.id}">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${item.id}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      // Attach handlers
      document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => editTerm(btn.dataset.id));
      });
      document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => deleteTerm(btn.dataset.id));
      });
    }

    function renderPagination(res) {
      pageMeta.textContent = `Showing ${res.from ?? 0} to ${res.to ?? 0} of ${res.total ?? 0} entries`;
      pager.innerHTML = '';
      
      if (!res.links) return;
      
      res.links.forEach(link => {
        const li = document.createElement('li');
        li.className = 'page-item' + (link.active ? ' active' : '') + (!link.url ? ' disabled' : '');
        li.innerHTML = `<a class="page-link" href="#">${link.label}</a>`;
        
        if (link.url) {
          li.addEventListener('click', (e) => {
            e.preventDefault();
            const url = new URL(link.url);
            const page = url.searchParams.get('page') || 1;
            fetchTerms(page);
          });
        }
        pager.appendChild(li);
      });
    }

    function stripHtml(html) {
      const tmp = document.createElement('div');
      tmp.innerHTML = html;
      return tmp.textContent || tmp.innerText || '';
    }

    function truncate(str, len) {
      return str.length > len ? str.substring(0, len) + '...' : str;
    }

    function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }

    document.getElementById('btnCreate').addEventListener('click', () => {
      editId = null;
      document.getElementById('modalTitle').textContent = 'Add Terms and Conditions';
      termForm.reset();
      
      // Clear TinyMCE editor
      if (typeof tinymce !== 'undefined') {
        const editor = tinymce.get('content');
        if (editor) {
          editor.setContent('');
        }
      }
      
      document.getElementById('formAlert').classList.add('d-none');
      modal.show();
    });

    function editTerm(id) {
      editId = id;
      document.getElementById('modalTitle').textContent = 'Edit Terms and Conditions';
      document.getElementById('formAlert').classList.add('d-none');
      
      fetch(`/admin/terms/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(data => {
        termForm.querySelector('[name="title"]').value = data.title || '';
        termForm.querySelector('[name="type"]').value = data.type || '';
        termForm.querySelector('[name="is_active"]').checked = data.is_active;
        
        // Set content in both textarea and TinyMCE
        const contentTextarea = termForm.querySelector('[name="content"]');
        contentTextarea.value = data.content || '';
        
        // Update TinyMCE editor if it's initialized
        if (typeof tinymce !== 'undefined') {
          const editor = tinymce.get('content');
          if (editor) {
            editor.setContent(data.content || '');
          } else {
            // If editor not yet initialized, wait for it
            setTimeout(() => {
              const editor = tinymce.get('content');
              if (editor) {
                editor.setContent(data.content || '');
              }
            }, 200);
          }
        }
        
        modal.show();
      })
      .catch(err => {
        console.error(err);
        alert('Failed to load term data');
      });
    }

    function deleteTerm(id) {
      if (!confirm('Are you sure you want to delete this term?')) return;
      
      fetch(`/admin/terms/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(r => r.json())
      .then(() => {
        fetchTerms(currentPage);
      })
      .catch(err => {
        console.error(err);
        alert('Failed to delete term');
      });
    }

    termForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const fd = new FormData(termForm);
      
      // Convert checkbox to 1/0
      fd.set('is_active', fd.get('is_active') ? '1' : '0');
      
      const url = editId ? `/admin/terms/${editId}` : '/admin/terms';
      const method = editId ? 'PUT' : 'POST';
      
      if (method === 'PUT') {
        fd.append('_method', 'PUT');
      }
      
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
      })
      .then(r => r.json())
      .then(res => {
        if (res.errors) {
          let msg = '<ul class="mb-0">';
          Object.values(res.errors).forEach(errArr => {
            errArr.forEach(err => msg += `<li>${err}</li>`);
          });
          msg += '</ul>';
          document.getElementById('formAlert').innerHTML = msg;
          document.getElementById('formAlert').classList.remove('d-none');
        } else {
          modal.hide();
          fetchTerms(currentPage);
        }
      })
      .catch(err => {
        console.error(err);
        document.getElementById('formAlert').textContent = 'An error occurred.';
        document.getElementById('formAlert').classList.remove('d-none');
      });
    });

    filterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      fetchTerms(1);
    });

    // Initial load
    fetchTerms();
  });
  </script>
  @endpush
</x-admin.layouts.app>


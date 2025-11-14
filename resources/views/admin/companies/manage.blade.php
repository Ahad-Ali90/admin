<x-admin.layouts.app>
  @push('styles')
  <style>
    /* Fix table row hover and active states - override Bootstrap table-hover */
    #companiesTable.table-hover > tbody > tr:hover {
      background-color: #f8f9fa !important;
      --bs-table-hover-bg: #f8f9fa !important;
    }
    #companiesTable.table-hover > tbody > tr:hover > td {
      background-color: transparent !important;
      color: #212529 !important;
    }
    #companiesTable tbody tr {
      cursor: pointer;
      background-color: transparent !important;
    }
    #companiesTable tbody tr:active,
    #companiesTable tbody tr.active {
      background-color: #e9ecef !important;
    }
    #companiesTable tbody tr:active td,
    #companiesTable tbody tr.active td {
      background-color: transparent !important;
      color: #212529 !important;
    }
    /* Dark mode support */
    [data-theme="dark"] #companiesTable.table-hover > tbody > tr:hover {
      background-color: #374151 !important;
      --bs-table-hover-bg: #374151 !important;
    }
    [data-theme="dark"] #companiesTable.table-hover > tbody > tr:hover > td {
      background-color: transparent !important;
      color: #f9fafb !important;
    }
    [data-theme="dark"] #companiesTable tbody tr:active,
    [data-theme="dark"] #companiesTable tbody tr.active {
      background-color: #4b5563 !important;
    }
    [data-theme="dark"] #companiesTable tbody tr:active td,
    [data-theme="dark"] #companiesTable tbody tr.active td {
      background-color: transparent !important;
      color: #f9fafb !important;
    }
    /* Fix button container */
    .action-buttons {
      display: flex;
      gap: 0.25rem;
      align-items: center;
    }
    .action-buttons .btn {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
  </style>
  @endpush
  <div class="container-xxl py-3">
    {{-- Header --}}
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <h1 class="h3 mb-1">Companies</h1>
        <p class="text-secondary mb-0">Manage company information</p>
      </div>
      <div>
        <button type="button" class="btn btn-primary" onclick="openCompanyModal()">
          <i class="bi bi-plus-lg me-2"></i>Add Company
        </button>
      </div>
    </div>

    {{-- Companies Table --}}
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="companiesTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th style="width: 150px;">Actions</th>
              </tr>
            </thead>
            <tbody id="companiesTableBody">
              <tr>
                <td colspan="6" class="text-center">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Company Modal --}}
  <div class="modal fade" id="companyModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="companyModalTitle">Add Company</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="companyForm">
          <div class="modal-body">
            <input type="hidden" id="companyId">
            
            <div class="mb-3">
              <label for="companyName" class="form-label">Company Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="companyName" required>
              <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
              <label for="companyType" class="form-label">Type <span class="text-danger">*</span></label>
              <select class="form-select" id="companyType" required>
                <option value="">Select Type</option>
                <option value="subcontractor">Subcontractor</option>
                <option value="commercial_client">Commercial Client</option>
              </select>
              <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
              <label for="companyEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="companyEmail">
              <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
              <label for="companyPhone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="companyPhone">
              <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
              <label for="companyAddress" class="form-label">Address</label>
              <textarea class="form-control" id="companyAddress" rows="2"></textarea>
              <div class="invalid-feedback"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="saveCompanyBtn">
              <span class="spinner-border spinner-border-sm d-none me-2" id="saveSpinner"></span>
              Save Company
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    const companyModal = new bootstrap.Modal(document.getElementById('companyModal'));
    let companies = [];

    // Load companies on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadCompanies();
    });

    // Load all companies
    function loadCompanies() {
      fetch('{{ route('admin.companies.index') }}')
        .then(response => response.json())
        .then(data => {
          companies = data;
          renderCompaniesTable();
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error loading companies', 'error');
        });
    }

    // Render companies table
    function renderCompaniesTable() {
      const tbody = document.getElementById('companiesTableBody');
      
      if (companies.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              <i class="bi bi-building fs-1 d-block mb-2"></i>
              No companies found. Click "Add Company" to create one.
            </td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = companies.map(company => {
        const typeLabel = company.type === 'subcontractor' ? 'Subcontractor' : 'Commercial Client';
        const typeBadge = company.type === 'subcontractor' 
          ? '<span class="badge bg-info">Subcontractor</span>' 
          : '<span class="badge bg-primary">Commercial Client</span>';
        return `
        <tr>
          <td class="fw-medium">${company.name}</td>
          <td>${typeBadge}</td>
          <td>${company.email || '<span class="text-muted">-</span>'}</td>
          <td>${company.phone || '<span class="text-muted">-</span>'}</td>
          <td>${company.address || '<span class="text-muted">-</span>'}</td>
          <td>
            <div class="action-buttons">
              <a href="{{ url('admin/companies') }}/${company.id}/details" class="btn btn-sm btn-outline-info" title="View Details">
                <i class="bi bi-eye"></i>
              </a>
              <button class="btn btn-sm btn-outline-primary" onclick="editCompany(${company.id})" title="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteCompany(${company.id}, '${company.name}')" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
      }).join('');
    }

    // Open modal for new company
    function openCompanyModal() {
      document.getElementById('companyModalTitle').textContent = 'Add Company';
      document.getElementById('companyForm').reset();
      document.getElementById('companyId').value = '';
      clearValidation();
      companyModal.show();
    }

    // Edit company
    function editCompany(id) {
      fetch(`{{ url('admin/companies') }}/${id}`)
        .then(response => response.json())
        .then(company => {
          document.getElementById('companyModalTitle').textContent = 'Edit Company';
          document.getElementById('companyId').value = company.id;
          document.getElementById('companyName').value = company.name;
          document.getElementById('companyType').value = company.type || 'commercial_client';
          document.getElementById('companyEmail').value = company.email || '';
          document.getElementById('companyPhone').value = company.phone || '';
          document.getElementById('companyAddress').value = company.address || '';
          clearValidation();
          companyModal.show();
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error loading company', 'error');
        });
    }

    // Save company (create or update)
    document.getElementById('companyForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const id = document.getElementById('companyId').value;
      const data = {
        name: document.getElementById('companyName').value,
        type: document.getElementById('companyType').value,
        email: document.getElementById('companyEmail').value,
        phone: document.getElementById('companyPhone').value,
        address: document.getElementById('companyAddress').value,
      };

      const saveBtn = document.getElementById('saveCompanyBtn');
      const spinner = document.getElementById('saveSpinner');
      saveBtn.disabled = true;
      spinner.classList.remove('d-none');

      const url = id ? `{{ url('admin/companies') }}/${id}` : '{{ route('admin.companies.store') }}';
      const method = id ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          showToast(result.message, 'success');
          companyModal.hide();
          loadCompanies();
        } else {
          showValidationErrors(result.errors);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error saving company', 'error');
      })
      .finally(() => {
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
      });
    });

    // Delete company
    function deleteCompany(id, name) {
      if (!confirm(`Are you sure you want to delete "${name}"?`)) {
        return;
      }

      fetch(`{{ url('admin/companies') }}/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          showToast(result.message, 'success');
          loadCompanies();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error deleting company', 'error');
      });
    }

    // Show validation errors
    function showValidationErrors(errors) {
      clearValidation();
      Object.keys(errors).forEach(field => {
        const input = document.getElementById('company' + field.charAt(0).toUpperCase() + field.slice(1));
        if (input) {
          input.classList.add('is-invalid');
          const feedback = input.nextElementSibling;
          if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = errors[field][0];
          }
        }
      });
    }

    // Clear validation
    function clearValidation() {
      document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    // Show toast notification
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
      toast.style.zIndex = '9999';
      toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      document.body.appendChild(toast);
      
      setTimeout(() => {
        toast.remove();
      }, 3000);
    }
  </script>
  @endpush
</x-admin.layouts.app>


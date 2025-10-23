<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-role{background:#f1f5f9;color:#334155}
    .badge-admin        {background:#eef2ff;color:#3730a3}
    .badge-booking_grabber{background:#ecfeff;color:#155e75}
    .badge-driver       {background:#ecfdf5;color:#065f46}
    .badge-porter       {background:#fff7ed;color:#9a3412}
    .badge-active       {background:#ecfdf5;color:#065f46}
    .badge-inactive     {background:#fef2f2;color:#991b1b}
    .table thead th{white-space:nowrap}
  </style>
  @endpush

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-3">
      <div>
        <h1 class="h3 mb-1">Staff</h1>
        <p class="text-secondary mb-0">Manage team members, roles, and access.</p>
      </div>
      <div>
        <button id="btnCreate" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Staff</button>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
      <div class="card-body">
        <form id="filterForm" class="row g-2">
          <div class="col-12 col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search name, email, phone">
          </div>
          <div class="col-6 col-md-3">
            <select name="role" class="form-select">
              <option value="">All roles</option>
              <option value="admin">Admin</option>
              <option value="booking_grabber">Booking Grabber</option>
              <option value="driver">Driver</option>
              <option value="porter">Porter</option>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <select name="status" class="form-select">
              <option value="">All status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="col-12 col-md-1 d-grid">
            <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i></button>
          </div>
        </form>
      </div>
    </div>

    <!-- Table -->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="tbodyStaff">
              <!-- rows -->
            </tbody>
          </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
          <div id="pageMeta" class="small text-secondary"></div>
          <nav><ul id="pager" class="pagination mb-0"></ul></nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <div class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form id="staffForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Staff</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="formAlert" class="alert alert-danger d-none"></div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input name="name" class="form-control">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input name="email" type="email" class="form-control">
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select">
                  <option value="admin">Admin</option>
                  <option value="booking_grabber">Booking Grabber</option>
                  <option value="driver">Driver</option>
                  <option value="porter">Porter</option>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control">
              </div>

              <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" rows="2" class="form-control"></textarea>
              </div>

              <!-- Password block -->
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="togglePassword">
                  <label class="form-check-label" for="togglePassword">Set/Change Password</label>
                </div>
              </div>
              <div class="col-12 col-md-6 pass-block d-none">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" autocomplete="new-password">
              </div>
              <div class="col-12 col-md-6 pass-block d-none">
                <label class="form-label">Confirm Password</label>
                <input name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnSubmit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
document.addEventListener('DOMContentLoaded', function () {
  // Ensure Bootstrap bundle is loaded (defines global `bootstrap`)
  if (typeof bootstrap === 'undefined') {
    console.error('Bootstrap JS not loaded. Include the bundle before @stack("scripts").');
    return; // prevent runtime errors below
  }

  const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const routes = {
    index:   "{{ route('admin.staff.index') }}",
    store:   "{{ route('admin.staff.store') }}",
    show:    (id)=> "{{ route('admin.staff.show', ':id') }}".replace(':id', id),
    update:  (id)=> "{{ route('admin.staff.update', ':id') }}".replace(':id', id),
    destroy: (id)=> "{{ route('admin.staff.destroy', ':id') }}".replace(':id', id),
  };

  // Elements & state (with guards)
  const tableBody  = document.getElementById('tbodyStaff');
  const pager      = document.getElementById('pager');
  const pageMeta   = document.getElementById('pageMeta');
  const filterForm = document.getElementById('filterForm');

  const modalEl    = document.getElementById('staffModal');
  const form       = document.getElementById('staffForm');
  const formAlert  = document.getElementById('formAlert');
  const btnSubmit  = document.getElementById('btnSubmit');
  const btnCreate  = document.getElementById('btnCreate');
  const modalTitle = document.getElementById('modalTitle');
  const passToggle = document.getElementById('togglePassword');

  if (!tableBody || !pager || !pageMeta || !modalEl || !form || !btnSubmit || !btnCreate || !modalTitle) {
    console.error('One or more required elements are missing from the DOM.');
    return;
  }

  const staffModal = new bootstrap.Modal(modalEl);
  let currentPageUrl = routes.index;
  let editingId = null;

  function esc(s) {
    return (s ?? '').toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }
  function clsRole(role){ return 'badge-' + (role || 'role'); }
  function clsStatus(st){ return 'badge-' + (st || 'active'); }

  function rowHtml(u) {
    const created   = u.created_at ? new Date(u.created_at).toLocaleDateString() : '';
    const roleLabel = (u.role || '').replace(/_/g, ' ');
    return `
      <tr data-id="${u.id}">
        <td class="fw-semibold">${esc(u.name)}</td>
        <td>${esc(u.email)}</td>
        <td><span class="badge ${clsRole(u.role)} text-capitalize">${esc(roleLabel)}</span></td>
        <td>${esc(u.phone || '')}</td>
        <td><span class="badge ${clsStatus(u.status)} text-capitalize">${esc(u.status || '')}</span></td>
        <td>${esc(created)}</td>
        <td class="text-end">
          <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-edit"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-sm btn-outline-danger btn-del"><i class="bi bi-trash"></i></button>
          </div>
        </td>
      </tr>
    `;
  }

  function buildPager(links, meta) {
    pager.innerHTML = '';
    if (!Array.isArray(links)) return;

    links.forEach(link => {
      const li = document.createElement('li');
      li.className = 'page-item' + (link.active ? ' active' : '') + (link.url === null ? ' disabled' : '');
      const a = document.createElement('a');
      a.className = 'page-link';
      a.href = '#';
      a.innerHTML = String(link.label).replace('&laquo;', '«').replace('&raquo;', '»');
      a.addEventListener('click', (e) => {
        e.preventDefault();
        if (link.url) { currentPageUrl = link.url; loadStaff(); }
      });
      li.appendChild(a);
      pager.appendChild(li);
    });

    const from  = meta?.from ?? 0;
    const to    = meta?.to ?? 0;
    const total = meta?.total ?? 0;
    pageMeta.textContent = `Showing ${from}-${to} of ${total}`;
  }

  function clearErrors() {
    formAlert.classList.add('d-none');
    formAlert.innerHTML = '';
    [...form.elements].forEach(el => el.classList.remove('is-invalid'));
  }

  function showErrors(errors) {
    formAlert.classList.remove('d-none');
    formAlert.innerHTML = `<ul class="mb-0">${
      Object.entries(errors).map(([k, v]) => `<li>${esc(v[0])}</li>`).join('')
    }</ul>`;
    Object.keys(errors).forEach(name => {
      if (form.elements[name]) form.elements[name].classList.add('is-invalid');
    });
  }

  function setFormValues(data) {
    const fields = ['name','email','role','status','phone','address'];
    fields.forEach(n => {
      if (form.elements[n]) form.elements[n].value = data?.[n] ?? '';
    });
  }

  function getFormValues() {
    const fd = new FormData(form);
    return Object.fromEntries(fd.entries());
  }

  async function loadStaff() {
    try {
      const query = filterForm ? new URLSearchParams(new FormData(filterForm)).toString() : '';
      const url   = currentPageUrl + (currentPageUrl.includes('?') ? '&' : '?') + query;
      const res   = await fetch(url, { headers: { 'Accept': 'application/json' } });

      if (!res.ok) {
        console.error('Failed to load staff list:', res.status);
        return;
      }

      const data = await res.json();
      tableBody.innerHTML = (data.data || []).map(rowHtml).join('');
      buildPager(data.links || [], data);
    } catch (e) {
      console.error('Network error loading staff:', e);
    }
  }

  // Filters
  filterForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    currentPageUrl = routes.index;
    loadStaff();
  });

  // Add
  btnCreate.addEventListener('click', () => {
    editingId = null;
    modalTitle.textContent = 'Add Staff';
    btnSubmit.textContent  = 'Create';
    clearErrors();
    form.reset();
    if (passToggle) { passToggle.checked = true; togglePasswordBlock(); }
    staffModal.show();
  });

  // Toggle password block
  function togglePasswordBlock() {
    document.querySelectorAll('.pass-block').forEach(el => {
      const show = !!passToggle?.checked;
      el.classList.toggle('d-none', !show);
      if (!show) {
        if (form.elements['password']) form.elements['password'].value = '';
        if (form.elements['password_confirmation']) form.elements['password_confirmation'].value = '';
      }
    });
  }
  passToggle?.addEventListener('change', togglePasswordBlock);

  // Edit/Delete handlers (event delegation)
  tableBody.addEventListener('click', async (e) => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const id = tr.getAttribute('data-id');

    if (e.target.closest('.btn-edit')) {
      clearErrors();
      const res = await fetch(routes.show(id), { headers: { 'Accept': 'application/json' } });
      if (!res.ok) { alert('Failed to load user'); return; }
      const u = await res.json();
      editingId = id;
      modalTitle.textContent = 'Edit Staff';
      btnSubmit.textContent  = 'Save Changes';
      form.reset();
      setFormValues(u);
      if (passToggle) { passToggle.checked = false; togglePasswordBlock(); }
      staffModal.show();
    }

    if (e.target.closest('.btn-del')) {
      if (!confirm('Delete this staff member?')) return;
      const res = await fetch(routes.destroy(id), {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
      });
      if (!res.ok) {
        const txt = await res.text();
        alert(txt || 'Delete failed');
      }
      loadStaff();
    }
  });

  // Submit (create/update)
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors(); btnSubmit.disabled = true;

    const method  = editingId ? 'PUT' : 'POST';
    const url     = editingId ? routes.update(editingId) : routes.store;
    const payload = getFormValues();

    // remove empty password fields if hidden
    if (!passToggle || !passToggle.checked) {
      delete payload.password;
      delete payload.password_confirmation;
    }

    try {
      const res = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      if (res.status === 422) {
        const { errors } = await res.json();
        showErrors(errors);
        btnSubmit.disabled = false; return;
      }
      if (!res.ok) {
        const txt = await res.text();
        formAlert.classList.remove('d-none');
        formAlert.textContent = txt || 'An error occurred';
        btnSubmit.disabled = false; return;
      }

      staffModal.hide();
      form.reset();
      loadStaff();
    } catch (err) {
      formAlert.classList.remove('d-none');
      formAlert.textContent = 'Network error. Please try again.';
    } finally {
      btnSubmit.disabled = false;
    }
  });

  // Init
  togglePasswordBlock();
  loadStaff();
});
</script>

  @endpush
</x-admin.layouts.app>

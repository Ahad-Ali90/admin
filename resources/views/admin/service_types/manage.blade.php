{{-- resources/views/admin/service_types/manage.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-type{background:#f1f5f9;color:#334155}
    .badge-fixed    {background:#eef2ff;color:#3730a3}
    .badge-hourly   {background:#ecfeff;color:#155e75}
    .badge-half_day {background:#fefce8;color:#854d0e}
    .badge-full_day {background:#ecfdf5;color:#065f46}
  </style>
  @endpush

  <div class="container-xxl py-3">
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-3">
      <div>
        <h1 class="h3 mb-1">Service Types</h1>
        <p class="text-secondary mb-0">Create and manage your pricing options.</p>
      </div>
      <div>
        <button id="btnCreate" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Service Type</button>
      </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
      <div class="card-body">
        <form id="filterForm" class="row g-2">
          <div class="col-12 col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by name">
          </div>
          
          <div class="col-12 col-md-1 d-grid">
            <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i></button>
          </div>
        </form>
      </div>
    </div>

    {{-- Table --}}
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Name</th>
                <th class="text-end">Price (£)</th>
                <th class="text-end">Updated</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="tbodyTypes"></tbody>
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
  <div class="modal fade" id="typeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <form id="typeForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Service Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="formAlert" class="alert alert-danger d-none"></div>

            <div class="vstack gap-3">
              <div>
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input name="name" class="form-control">
              </div>
              <div>
                <label class="form-label">Price (£) <span class="text-danger">*</span></label>
                <input name="price" type="number" step="0.01" min="0" class="form-control">
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
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap === 'undefined') { console.error('Bootstrap JS not loaded'); return; }

    const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const routes = {
      index:   "{{ route('admin.service_types.index') }}",
      store:   "{{ route('admin.service_types.store') }}",
      show:    (id)=> "{{ route('admin.service_types.show', ':id') }}".replace(':id', id),
      update:  (id)=> "{{ route('admin.service_types.update', ':id') }}".replace(':id', id),
      destroy: (id)=> "{{ route('admin.service_types.destroy', ':id') }}".replace(':id', id),
    };

    // Elements
    const tbody     = document.getElementById('tbodyTypes');
    const pager     = document.getElementById('pager');
    const pageMeta  = document.getElementById('pageMeta');
    const filterForm= document.getElementById('filterForm');
    const modalEl   = document.getElementById('typeModal');
    const typeModal = new bootstrap.Modal(modalEl);
    const form      = document.getElementById('typeForm');
    const formAlert = document.getElementById('formAlert');
    const btnCreate = document.getElementById('btnCreate');
    const btnSubmit = document.getElementById('btnSubmit');
    const modalTitle= document.getElementById('modalTitle');

    let currentPageUrl = routes.index;
    let editingId = null;

    function esc(s){ return (s ?? '').toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
    function clsType(t){ return 'badge-' + (t || 'type'); }
    function labelType(t){ return (t || '').replace('_',' ').replace('_',' '); } // half_day -> half day

    function rowHtml(r){
      const updated = r.updated_at ? new Date(r.updated_at).toLocaleDateString() : '';
      return `
        <tr data-id="${r.id}">
          <td class="fw-semibold">${esc(r.name)}</td>
          <td class="text-end">£${esc(Number(r.price).toFixed(2))}</td>
          <td class="text-end">${esc(updated)}</td>
          <td class="text-end">
            <div class="btn-group">
              <button type="button" class="btn btn-sm btn-outline-secondary btn-edit"><i class="bi bi-pencil"></i></button>
              <button type="button" class="btn btn-sm btn-outline-danger btn-del"><i class="bi bi-trash"></i></button>
            </div>
          </td>
        </tr>
      `;
    }

    function buildPager(links, meta){
      pager.innerHTML = '';
      (links || []).forEach(link=>{
        const li = document.createElement('li');
        li.className = 'page-item' + (link.active?' active':'') + (link.url===null?' disabled':'');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.innerHTML = String(link.label).replace('&laquo;','«').replace('&raquo;','»');
        a.addEventListener('click', e=>{
          e.preventDefault();
          if(link.url){ currentPageUrl = link.url; loadData(); }
        });
        li.appendChild(a); pager.appendChild(li);
      });
      pageMeta.textContent = `Showing ${meta?.from ?? 0}-${meta?.to ?? 0} of ${meta?.total ?? 0}`;
    }

    function clearErrors(){
      formAlert.classList.add('d-none'); formAlert.innerHTML='';
      [...form.elements].forEach(el=> el.classList.remove('is-invalid'));
    }
    function showErrors(errors){
      formAlert.classList.remove('d-none');
      formAlert.innerHTML = `<ul class="mb-0">${
        Object.entries(errors).map(([k,v])=>`<li>${esc(v[0])}</li>`).join('')
      }</ul>`;
      Object.keys(errors).forEach(name=>{
        if(form.elements[name]) form.elements[name].classList.add('is-invalid');
      });
    }

    function setFormValues(d){
      (form.elements['name'] || {}).value         = d?.name ?? '';
      (form.elements['price'] || {}).value        = d?.price ?? '';
    }

    function getFormValues(){
      const fd = new FormData(form);
      return Object.fromEntries(fd.entries());
    }

    async function loadData(){
      const q = new URLSearchParams(new FormData(filterForm)).toString();
      const url = currentPageUrl + (currentPageUrl.includes('?') ? '&' : '?') + q;
      const res = await fetch(url, { headers:{ 'Accept':'application/json' } });
      const data = await res.json();
      tbody.innerHTML = (data.data || []).map(rowHtml).join('');
      buildPager(data.links || [], data);
    }

    // Filters
    filterForm.addEventListener('submit', (e)=>{
      e.preventDefault();
      currentPageUrl = routes.index;
      loadData();
    });

    // Add
    btnCreate.addEventListener('click', ()=>{
      editingId = null;
      modalTitle.textContent = 'Add Service Type';
      btnSubmit.textContent  = 'Create';
      clearErrors();
      form.reset();
      setFormValues({pricing_type:'fixed'});
      typeModal.show();
    });

    // Row actions
    tbody.addEventListener('click', async (e)=>{
      const tr = e.target.closest('tr'); if(!tr) return;
      const id = tr.getAttribute('data-id');

      if (e.target.closest('.btn-edit')) {
        clearErrors();
        const res = await fetch(routes.show(id), { headers:{ 'Accept':'application/json' }});
        if (!res.ok) { alert('Failed to load item'); return; }
        const d = await res.json();
        editingId = id;
        modalTitle.textContent = 'Edit Service Type';
        btnSubmit.textContent  = 'Save Changes';
        form.reset(); setFormValues(d);
        typeModal.show();
      }

      if (e.target.closest('.btn-del')) {
        if(!confirm('Delete this service type?')) return;
        const res = await fetch(routes.destroy(id), {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        if (!res.ok) {
          const txt = await res.text();
          alert(txt || 'Delete failed');
        }
        loadData();
      }
    });

    // Submit (create/update)
    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      clearErrors(); btnSubmit.disabled = true;

      const method  = editingId ? 'PUT' : 'POST';
      const url     = editingId ? routes.update(editingId) : routes.store;
      const payload = getFormValues();

      try {
        const res = await fetch(url, {
          method,
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':'application/json'
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

        typeModal.hide();
        form.reset();
        loadData();
      } catch (err) {
        formAlert.classList.remove('d-none');
        formAlert.textContent = 'Network error. Please try again.';
      } finally {
        btnSubmit.disabled = false;
      }
    });

    // init
    loadData();
  });
  </script>
  @endpush
</x-admin.layouts.app>

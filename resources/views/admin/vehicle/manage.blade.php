<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-soft{background:#f1f3f5;color:#495057}
    .badge-available{background:#ecfdf5;color:#065f46}
    .badge-in_use{background:#eef2ff;color:#3730a3}
    .badge-maintenance{background:#fff7ed;color:#9a3412}
    .badge-retired{background:#fef2f2;color:#991b1b}
    .table thead th{white-space:nowrap}
  </style>
  @endpush

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-3">
      <div>
        <h1 class="h3 mb-1">Vehicles</h1>
        <p class="text-secondary mb-0">Manage your fleet on a single page.</p>
      </div>
      <div class="d-flex gap-2">
        <button id="btnCreate" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Vehicle</button>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
      <div class="card-body">
        <form id="filterForm" class="row g-2">
          <div class="col-12 col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search reg, make, model, color">
          </div>
          <div class="col-6 col-md-3">
            <select name="vehicle_type" class="form-select">
              <option value="">All types</option>
              <option value="van">Van</option>
              <option value="truck">Truck</option>
              <option value="lorry">Lorry</option>
              <option value="car">Car</option>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <select name="status" class="form-select">
              <option value="">All status</option>
              <option value="available">Available</option>
              <option value="in_use">In use</option>
              <option value="maintenance">Maintenance</option>
              <option value="retired">Retired</option>
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
          <table class="table align-middle" id="vehiclesTable">
            <thead class="table-light">
              <tr>
                <th>Reg #</th>
                <th>Make/Model</th>
                <th>Year</th>
                <th>Type</th>
                <th>Status</th>
                <th>MOT</th>
                <th>Insurance</th>
                <th>Mileage</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="tbodyVehicles">
              <!-- rows injected -->
            </tbody>
          </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
          <div class="small text-secondary" id="pageMeta"></div>
          <nav>
            <ul class="pagination mb-0" id="pager"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <div class="modal fade" id="vehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form id="vehicleForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Vehicle</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="formAlert" class="alert alert-danger d-none"></div>

            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">Registration # *</label>
                <input name="registration_number" class="form-control">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Make *</label>
                <input name="make" class="form-control">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Model *</label>
                <input name="model" class="form-control">
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label">Year *</label>
                <input type="number" name="year" class="form-control">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Type *</label>
                <select name="vehicle_type" class="form-select">
                  <option value="van">Van</option>
                  <option value="truck">Truck</option>
                  <option value="lorry">Lorry</option>
                  <option value="car">Car</option>
                </select>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Color</label>
                <input name="color" class="form-control">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select">
                  <option value="available">Available</option>
                  <option value="in_use">In use</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="retired">Retired</option>
                </select>
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label">Capacity (m³)</label>
                <input type="number" name="capacity_cubic_meters" class="form-control" min="0">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Max Weight (kg)</label>
                <input type="number" name="max_weight_kg" class="form-control" min="0">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Mileage *</label>
                <input type="number" name="mileage" class="form-control" min="0" value="0">
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label">MOT Expiry *</label>
                <input type="date" name="mot_expiry_date" class="form-control">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Insurance Expiry *</label>
                <input type="date" name="insurance_expiry_date" class="form-control">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Last Service</label>
                <input type="date" name="last_service_date" class="form-control">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Next Service Due</label>
                <input type="date" name="next_service_due" class="form-control">
              </div>

              <div class="col-6 col-md-4">
                <label class="form-label">Purchase Price (£)</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" min="0">
              </div>
              <div class="col-6 col-md-4">
                <label class="form-label">Monthly Insurance (£)</label>
                <input type="number" step="0.01" name="monthly_insurance" class="form-control" min="0">
              </div>
              <div class="col-6 col-md-4">
                <label class="form-label">Monthly Finance (£)</label>
                <input type="number" step="0.01" name="monthly_finance" class="form-control" min="0">
              </div>

              <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="2" class="form-control"></textarea>
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

  <!-- CSRF + Scripts -->
  @push('scripts')
  <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const routes = {
      index:   "{{ route('admin.vehicles.index') }}",
      store:   "{{ route('admin.vehicles.store') }}",
      show:    (id)=> "{{ route('admin.vehicles.show', ':id') }}".replace(':id', id),
      update:  (id)=> "{{ route('admin.vehicles.update', ':id') }}".replace(':id', id),
      destroy: (id)=> "{{ route('admin.vehicles.destroy', ':id') }}".replace(':id', id),
    };

    // State
    let currentPageUrl = routes.index;
    let editingId = null;
    const tableBody = document.getElementById('tbodyVehicles');
    const pager = document.getElementById('pager');
    const pageMeta = document.getElementById('pageMeta');

    // Modal
    const vehicleModal = new bootstrap.Modal(document.getElementById('vehicleModal'));
    const form = document.getElementById('vehicleForm');
    const modalTitle = document.getElementById('modalTitle');
    const formAlert = document.getElementById('formAlert');
    const btnSubmit = document.getElementById('btnSubmit');

    // Helpers
    function badgeStatus(s){
      const map = { available:'badge-available', in_use:'badge-in_use', maintenance:'badge-maintenance', retired:'badge-retired' };
      return map[s] || 'badge-soft';
    }
    function esc(s){ return (s??'').toString().replace(/[&<>"']/g,m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m])); }
    function rowHtml(v){
      return `
      <tr data-id="${v.id}">
        <td class="fw-semibold">${esc(v.registration_number)}</td>
        <td>${esc(v.make)} ${esc(v.model)}</td>
        <td>${esc(v.year)}</td>
        <td class="text-capitalize">${esc(v.vehicle_type)}</td>
        <td><span class="badge ${badgeStatus(v.status)} text-capitalize">${esc(v.status.replace('_',' '))}</span></td>
        <td>${esc(v.mot_expiry_date ?? '')}</td>
        <td>${esc(v.insurance_expiry_date ?? '')}</td>
        <td>${esc(v.mileage)}</td>
        <td class="text-end">
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-secondary btn-edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger btn-del"><i class="bi bi-trash"></i></button>
          </div>
        </td>
      </tr>`;
    }
    function toDateInput(val){
  if (!val) return '';
  // already correct format?
  if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return val;
  // try to parse and strip timezone offset (avoid off-by-one)
  const d = new Date(val);
  if (isNaN(d.getTime())) return ''; 
  const local = new Date(d.getTime() - d.getTimezoneOffset()*60000);
  return local.toISOString().slice(0,10);
}

function setFormValues(data){
  const fields = [
    'registration_number','make','model','year','vehicle_type','color',
    'capacity_cubic_meters','max_weight_kg','status','mileage',
    'mot_expiry_date','insurance_expiry_date','last_service_date','next_service_due',
    'purchase_price','monthly_insurance','monthly_finance','notes'
  ];
  const dateFields = ['mot_expiry_date','insurance_expiry_date','last_service_date','next_service_due'];

  fields.forEach(name=>{
    const el = form.elements[name];
    if(!el) return;
    const v = data?.[name] ?? '';
    el.value = dateFields.includes(name) ? toDateInput(v) : v;
  });
}

    function getFormValues(){
      const fd = new FormData(form);
      return Object.fromEntries(fd.entries());
    }
    function clearErrors(){
      formAlert.classList.add('d-none'); formAlert.innerHTML='';
      [...form.elements].forEach(el=> el.classList.remove('is-invalid'));
    }
    function showErrors(errors){
      formAlert.classList.remove('d-none');
      formAlert.innerHTML = `<ul class="mb-0">${Object.entries(errors).map(([k,v])=>`<li>${esc(v[0])}</li>`).join('')}</ul>`;
      Object.keys(errors).forEach(name=>{
        if(form.elements[name]) form.elements[name].classList.add('is-invalid');
      });
    }
    function buildPager(links, meta){
      pager.innerHTML = '';
      links.forEach(link=>{
        const li = document.createElement('li');
        li.className = 'page-item' + (link.active ? ' active' : '') + (link.url===null ? ' disabled' : '');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.innerHTML = link.label.replace('&laquo;','«').replace('&raquo;','»');
        a.addEventListener('click',(e)=>{
          e.preventDefault();
          if(link.url){ currentPageUrl = link.url; loadVehicles(); }
        });
        li.appendChild(a);
        pager.appendChild(li);
      });
      pageMeta.textContent = `Showing ${meta.from ?? 0}-${meta.to ?? 0} of ${meta.total ?? 0}`;
    }

    // Load list
    async function loadVehicles(){
      const res = await fetch(currentPageUrl + buildQueryFromFilters(), { headers: { 'Accept':'application/json' } });
      const data = await res.json();
      tableBody.innerHTML = data.data.map(rowHtml).join('');
      buildPager(data.links, data);
    }
    function buildQueryFromFilters(){
      const params = new URLSearchParams(new FormData(document.getElementById('filterForm'))).toString();
      return (currentPageUrl.includes('?') ? '&' : '?') + params;
    }

    // Events
    document.getElementById('filterForm').addEventListener('submit', (e)=>{
      e.preventDefault(); currentPageUrl = routes.index; loadVehicles();
    });

    document.getElementById('btnCreate').addEventListener('click', ()=>{
      editingId = null; modalTitle.textContent = 'Add Vehicle'; btnSubmit.textContent='Create';
      clearErrors(); form.reset(); setFormValues({ status:'available', vehicle_type:'van', mileage:0 });
      vehicleModal.show();
    });

    // Row delegation: edit/delete
    tableBody.addEventListener('click', async (e)=>{
      const tr = e.target.closest('tr');
      if(!tr) return;
      const id = tr.getAttribute('data-id');

      if(e.target.closest('.btn-edit')){
        clearErrors();
        const res = await fetch(routes.show(id), { headers: { 'Accept':'application/json' } });
        const v = await res.json();
        editingId = id; modalTitle.textContent = 'Edit Vehicle'; btnSubmit.textContent='Save Changes';
        setFormValues(v);
        vehicleModal.show();
      }

      if(e.target.closest('.btn-del')){
        if(!confirm('Delete this vehicle?')) return;
        await fetch(routes.destroy(id), {
          method:'DELETE',
          headers:{ 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' }
        });
        loadVehicles();
      }
    });

    // Create/Update submit
    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      clearErrors();
      btnSubmit.disabled = true;

      const method = editingId ? 'PUT' : 'POST';
      const url    = editingId ? routes.update(editingId) : routes.store;

      const payload = getFormValues();

      try{
        const res = await fetch(url, {
          method,
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':'application/json'
          },
          body: JSON.stringify(payload)
        });

        if(res.status === 422){
          const { errors } = await res.json();
          showErrors(errors); btnSubmit.disabled = false; return;
        }

        if(!res.ok){
          const txt = await res.text();
          formAlert.classList.remove('d-none');
          formAlert.textContent = txt || 'An error occurred';
          btnSubmit.disabled = false; return;
        }

        vehicleModal.hide();
        form.reset();
        loadVehicles();
      } catch(err){
        formAlert.classList.remove('d-none');
        formAlert.textContent = 'Network error. Please try again.';
      } finally{
        btnSubmit.disabled = false;
      }
    });

    // Init
    loadVehicles();
  </script>
  @endpush
</x-admin.layouts.app>

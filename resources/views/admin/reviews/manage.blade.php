{{-- resources/views/admin/reviews/manage.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    
    /* Stars */
    .stars {
      color: #fbbf24;
      font-size: 16px;
    }
    .stars-empty {
      color: #d1d5db;
    }
    
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
    
    /* Rating stars in form */
    .rating-stars {
      font-size: 28px;
      cursor: pointer;
    }
    .rating-stars .star {
      color: #d1d5db;
      transition: color 0.2s;
    }
    .rating-stars .star:hover,
    .rating-stars .star.selected {
      color: #fbbf24;
    }
  </style>
  @endpush

  <div class="container-xxl py-3">
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-3">
      <div>
        <h1 class="h3 mb-1">Reviews</h1>
        <p class="text-secondary mb-0">Manage customer reviews and ratings.</p>
      </div>
      <div>
        <button id="btnCreate" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Review</button>
      </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
      <div class="card-body">
        <form id="filterForm" class="row g-2">
          <div class="col-12 col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by customer name or review">
          </div>
          <div class="col-12 col-md-3">
            <select name="rating" class="form-select">
              <option value="">All Ratings</option>
              <option value="5">5 Stars</option>
              <option value="4">4 Stars</option>
              <option value="3">3 Stars</option>
              <option value="2">2 Stars</option>
              <option value="1">1 Star</option>
            </select>
          </div>
          <div class="col-12 col-md-3">
            <select name="source" class="form-select">
              <option value="">All Sources</option>
              <option value="Google">Google</option>
              <option value="Trustpilot">Trustpilot</option>
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
                <th>Customer Name</th>
                <th>Review</th>
                <th style="width: 120px;">Rating</th>
                <th style="width: 120px;">Source</th>
                <th style="width: 150px;">Booking Ref</th>
                <th style="width: 150px;" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="tbodyReviews"></tbody>
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
  <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="reviewForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Review</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="formAlert" class="alert alert-danger d-none"></div>

            <div class="vstack gap-3">
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                  <input name="customer_name" class="form-control" placeholder="Enter customer name" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Source <span class="text-danger">*</span></label>
                  <select name="source" class="form-select" required>
                    <option value="">Select Source</option>
                    <option value="Google">Google</option>
                    <option value="Trustpilot">Trustpilot</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Booking Ref</label>
                  <select name="booking_id" class="form-select" id="bookingSelect">
                    <option value="">None</option>
                  </select>
                  <small class="text-muted">Optional</small>
                </div>
              </div>

              <div>
                <label class="form-label">Rating <span class="text-danger">*</span></label>
                <input type="hidden" name="rating" id="ratingInput" required>
                <div class="rating-stars" id="ratingStars">
                  <span class="star" data-value="1">★</span>
                  <span class="star" data-value="2">★</span>
                  <span class="star" data-value="3">★</span>
                  <span class="star" data-value="4">★</span>
                  <span class="star" data-value="5">★</span>
                </div>
                <small class="text-muted">Click to rate</small>
              </div>

              <div>
                <label class="form-label">Review <span class="text-danger">*</span></label>
                <textarea name="review" class="form-control" rows="5" placeholder="Enter review text" required></textarea>
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

    let modal, modalEl, editId = null;
    const tbody = document.getElementById('tbodyReviews');
    const filterForm = document.getElementById('filterForm');
    const reviewForm = document.getElementById('reviewForm');
    const pageMeta = document.getElementById('pageMeta');
    const pager = document.getElementById('pager');
    const ratingStars = document.getElementById('ratingStars');
    const ratingInput = document.getElementById('ratingInput');
    const bookingSelect = document.getElementById('bookingSelect');
    let currentPage = 1;

    modalEl = document.getElementById('reviewModal');
    modal = new bootstrap.Modal(modalEl);

    // Clear form when modal is hidden
    modalEl.addEventListener('hidden.bs.modal', function() {
      reviewForm.reset();
      clearRating();
    });

    // Rating stars functionality
    ratingStars.addEventListener('click', function(e) {
      if (e.target.classList.contains('star')) {
        const value = e.target.dataset.value;
        setRating(value);
      }
    });

    function setRating(value) {
      ratingInput.value = value;
      const stars = ratingStars.querySelectorAll('.star');
      stars.forEach((star, index) => {
        if (index < value) {
          star.classList.add('selected');
        } else {
          star.classList.remove('selected');
        }
      });
    }

    function clearRating() {
      ratingInput.value = '';
      ratingStars.querySelectorAll('.star').forEach(star => {
        star.classList.remove('selected');
      });
    }

    // Load bookings for dropdown
    function loadBookings() {
      fetch('/admin/reviews/bookings', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(bookings => {
        bookingSelect.innerHTML = '<option value="">None</option>';
        bookings.forEach(booking => {
          const option = document.createElement('option');
          option.value = booking.id;
          option.textContent = `${booking.booking_reference} - ${booking.customer?.name || 'N/A'}`;
          bookingSelect.appendChild(option);
        });
      })
      .catch(err => console.error(err));
    }

    function fetchReviews(page = 1) {
      currentPage = page;
      const fd = new FormData(filterForm);
      const params = new URLSearchParams(fd);
      params.append('page', page);

      fetch(`/admin/reviews?${params}`, {
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
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No reviews found</td></tr>';
        return;
      }
      items.forEach(item => {
        const tr = document.createElement('tr');
        
        const sourceClass = item.source === 'Google' ? 'bg-danger' : 'bg-info';
        const stars = generateStars(item.rating);
        const bookingRef = item.booking?.booking_reference || 'N/A';
        
        tr.innerHTML = `
          <td><strong>${item.customer_name}</strong></td>
          <td>
            <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
              ${item.review}
            </div>
          </td>
          <td>
            <div class="stars">${stars}</div>
          </td>
          <td><span class="">${item.source}</span></td>
          <td><span class="">${bookingRef}</span></td>
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
        btn.addEventListener('click', () => editReview(btn.dataset.id));
      });
      document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => deleteReview(btn.dataset.id));
      });
    }

    function generateStars(rating) {
      let html = '';
      for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
          html += '★';
        } else {
          html += '<span class="stars-empty">★</span>';
        }
      }
      return html;
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
            fetchReviews(page);
          });
        }
        pager.appendChild(li);
      });
    }

    document.getElementById('btnCreate').addEventListener('click', () => {
      editId = null;
      document.getElementById('modalTitle').textContent = 'Add Review';
      reviewForm.reset();
      clearRating();
      loadBookings();
      document.getElementById('formAlert').classList.add('d-none');
      modal.show();
    });

    function editReview(id) {
      editId = id;
      document.getElementById('modalTitle').textContent = 'Edit Review';
      document.getElementById('formAlert').classList.add('d-none');
      
      fetch(`/admin/reviews/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(data => {
        reviewForm.querySelector('[name="customer_name"]').value = data.customer_name || '';
        reviewForm.querySelector('[name="review"]').value = data.review || '';
        reviewForm.querySelector('[name="source"]').value = data.source || 'Google';
        
        setRating(data.rating);
        
        loadBookings();
        setTimeout(() => {
          bookingSelect.value = data.booking_id || '';
        }, 100);
        
        modal.show();
      })
      .catch(err => {
        console.error(err);
        alert('Failed to load review data');
      });
    }

    function deleteReview(id) {
      if (!confirm('Are you sure you want to delete this review?')) return;
      
      fetch(`/admin/reviews/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(r => r.json())
      .then(() => {
        fetchReviews(currentPage);
      })
      .catch(err => {
        console.error(err);
        alert('Failed to delete review');
      });
    }

    reviewForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const fd = new FormData(reviewForm);
      
      // If booking is empty, remove it
      if (!fd.get('booking_id')) {
        fd.delete('booking_id');
      }
      
      const url = editId ? `/admin/reviews/${editId}` : '/admin/reviews';
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
          fetchReviews(currentPage);
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
      fetchReviews(1);
    });

    // Initial load
    fetchReviews();
  });
  </script>
  @endpush
</x-admin.layouts.app>


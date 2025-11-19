{{-- resources/views/admin/color-schemes/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .color-card {
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    .color-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
      border-color: var(--btn-primary-bg) !important;
    }
    .color-preview-box {
      width: 100%;
      height: 80px;
      border-radius: 8px;
      border: 2px solid #e5e7eb;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .color-preview-box::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image: 
        linear-gradient(45deg, #ccc 25%, transparent 25%),
        linear-gradient(-45deg, #ccc 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #ccc 75%),
        linear-gradient(-45deg, transparent 75%, #ccc 75%);
      background-size: 10px 10px;
      background-position: 0 0, 0 5px, 5px -5px, -5px 0px;
      opacity: 0.3;
      z-index: 0;
    }
    .color-preview-box .color-fill {
      position: relative;
      z-index: 1;
      width: 100%;
      height: 100%;
      border-radius: 6px;
    }
    .category-header {
      background: linear-gradient(135deg, var(--btn-primary-bg), var(--btn-primary-hover));
      color: white;
      padding: 1rem 1.5rem;
      border-radius: 12px 12px 0 0;
      margin-bottom: 0;
    }
    .live-preview-section {
      position: fixed;
      top: 80px;
      right: 20px;
      width: 320px;
      z-index: 1000;
      background: var(--card-bg);
      border: 2px solid var(--card-border);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      max-height: calc(100vh - 100px);
      overflow-y: auto;
      transition: all 0.3s ease;
    }
    .live-preview-section::-webkit-scrollbar {
      width: 6px;
    }
    .live-preview-section::-webkit-scrollbar-track {
      background: var(--surface-bg);
      border-radius: 3px;
    }
    .live-preview-section::-webkit-scrollbar-thumb {
      background: var(--border-color);
      border-radius: 3px;
    }
    @media (max-width: 1200px) {
      .live-preview-section {
        position: relative;
        top: 0;
        right: 0;
        width: 100%;
        max-height: none;
        margin-bottom: 2rem;
      }
      .main-content {
        margin-right: 0 !important;
      }
    }
    .main-content {
      margin-right: 360px;
      transition: margin 0.3s ease;
    }
    @media (max-width: 1200px) {
      .main-content {
        margin-right: 0;
      }
    }
    .color-input-group {
      position: relative;
    }
    .color-input-group .form-control-color {
      position: absolute;
      right: 0;
      top: 0;
      width: 50px;
      height: 100%;
      border: none;
      border-left: 2px solid var(--border-color);
      border-radius: 0 8px 8px 0;
      cursor: pointer;
    }
    .color-input-group input[type="text"] {
      padding-right: 60px;
    }
    .save-indicator {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--btn-success-bg);
      color: white;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 1000;
      display: none;
      animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
      from {
        transform: translateY(100px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
    .category-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      background: var(--card-header-bg);
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 600;
    }
    
    /* Attractive Toggle Switch */
    .toggle-switch-wrapper {
      flex-grow: 1;
    }
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 70px;
      height: 32px;
      cursor: pointer;
    }
    .toggle-switch-input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .toggle-switch-slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 34px;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    .toggle-switch-slider:before {
      position: absolute;
      content: "";
      height: 24px;
      width: 24px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 50%;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-switch-input:checked + .toggle-switch-slider {
      background: linear-gradient(135deg, var(--btn-primary-bg), var(--btn-primary-hover));
    }
    .toggle-switch-input:checked + .toggle-switch-slider:before {
      transform: translateX(38px);
    }
    .toggle-switch-label-on,
    .toggle-switch-label-off {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 0.65rem;
      font-weight: 700;
      color: white;
      text-shadow: 0 1px 2px rgba(0,0,0,0.2);
      transition: opacity 0.3s;
    }
    .toggle-switch-label-on {
      left: 8px;
      opacity: 0;
    }
    .toggle-switch-label-off {
      right: 8px;
      opacity: 1;
    }
    .toggle-switch-input:checked + .toggle-switch-slider .toggle-switch-label-on {
      opacity: 1;
    }
    .toggle-switch-input:checked + .toggle-switch-slider .toggle-switch-label-off {
      opacity: 0;
    }
    .toggle-switch-input:not(:checked) + .toggle-switch-slider .toggle-switch-label-on {
      opacity: 0;
    }
    .toggle-switch-input:not(:checked) + .toggle-switch-slider .toggle-switch-label-off {
      opacity: 1;
    }
    
    /* Enhanced Live Preview */
    .preview-item {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 8px;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }
    .preview-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .preview-label {
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-muted);
      margin-top: 0.5rem;
    }
  </style>
  @endpush

  <div class="container-xxl py-3">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <h1 class="h3 mb-1">
          <i class="bi bi-palette-fill me-2 text-primary"></i>Color Scheme Management
        </h1>
        <p class="text-secondary mb-0">Customize your application's color scheme. Changes apply instantly across all pages.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.color-schemes.create') }}" class="btn btn-primary">
          <i class="bi bi-plus-lg me-1"></i><span class="d-none d-md-inline">Add New Color</span>
          <span class="d-md-none">Add</span>
        </a>
        <button type="button" onclick="resetToDefaults()" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-clockwise me-1"></i><span class="d-none d-md-inline">Reset</span>
        </button>
      </div>
    </div>

    {{-- Real-time Preview Card - Fixed Position --}}
    <div class="live-preview-section">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">
          <i class="bi bi-eye-fill me-2 text-primary"></i>Live Preview
        </h5>
        <span class="badge bg-success" id="previewStatus">Live</span>
      </div>
      <div class="vstack gap-3">
        {{-- Buttons Preview --}}
        <div>
          <h6 class="small fw-bold text-uppercase mb-2 text-muted">Buttons</h6>
          <div class="d-grid gap-2">
            <button class="btn btn-primary w-100" id="previewBtnPrimary">
              <i class="bi bi-check-circle me-1"></i>Primary Button
            </button>
            <button class="btn btn-secondary w-100">
              <i class="bi bi-gear me-1"></i>Secondary
            </button>
            <button class="btn btn-success w-100">
              <i class="bi bi-check-lg me-1"></i>Success
            </button>
            <button class="btn btn-danger w-100">
              <i class="bi bi-x-circle me-1"></i>Danger
            </button>
            <button class="btn btn-outline-primary w-100">Outline Primary</button>
          </div>
        </div>
        
        {{-- Card Preview --}}
        <div>
          <h6 class="small fw-bold text-uppercase mb-2 text-muted">Card</h6>
          <div class="card mb-0" id="previewCard" style="border-width: 2px;">
            <div class="card-header fw-bold">
              <i class="bi bi-card-heading me-1"></i>Card Header
            </div>
            <div class="card-body">
              <h6 class="card-title mb-2">Card Title</h6>
              <p class="card-text small mb-0">This is a preview card showing how colors look.</p>
            </div>
          </div>
        </div>
        
        {{-- Inputs Preview --}}
        <div>
          <h6 class="small fw-bold text-uppercase mb-2 text-muted">Inputs</h6>
          <div class="vstack gap-2">
            <input type="text" 
                   class="form-control form-control-sm" 
                   placeholder="Text input" 
                   id="previewInput"
                   value="Sample text">
            <select class="form-select form-select-sm" id="previewSelect">
              <option>Select an option</option>
              <option selected>Selected Option</option>
            </select>
            <textarea class="form-control form-control-sm" rows="2" placeholder="Textarea">Sample content</textarea>
          </div>
        </div>
        
        {{-- Table Preview --}}
        <div>
          <h6 class="small fw-bold text-uppercase mb-2 text-muted">Table</h6>
          <div class="table-responsive">
            <table class="table table-sm table-hover mb-0" id="previewTable">
              <thead>
                <tr>
                  <th style="font-size: 0.7rem;">Column 1</th>
                  <th style="font-size: 0.7rem;">Column 2</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="font-size: 0.75rem;">Row 1</td>
                  <td style="font-size: 0.75rem;">Data</td>
                </tr>
                <tr>
                  <td style="font-size: 0.75rem;">Row 2</td>
                  <td style="font-size: 0.75rem;">Data</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
        {{-- Badge Preview --}}
        <div>
          <h6 class="small fw-bold text-uppercase mb-2 text-muted">Badges</h6>
          <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-primary">Primary</span>
            <span class="badge bg-secondary">Secondary</span>
            <span class="badge bg-success">Success</span>
            <span class="badge bg-danger">Danger</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Color Schemes by Category --}}
    <div class="main-content">
      <form id="bulkColorForm" method="POST" action="{{ route('admin.color-schemes.bulk-update') }}">
        @csrf
        @method('POST')
        
        @foreach($colorSchemes as $category => $schemes)
        <div class="card mb-4 color-category-card">
          <div class="category-header">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0 text-white">
                  <i class="bi bi-{{ $category === 'buttons' ? 'cursor-fill' : ($category === 'cards' ? 'square-fill' : ($category === 'tables' ? 'table' : ($category === 'inputs' ? 'input-cursor-text' : 'palette-fill'))) }} me-2"></i>
                  {{ ucfirst($category) }}
                </h5>
              </div>
              <span class="badge bg-light text-dark">{{ count($schemes) }} colors</span>
            </div>
          </div>
          <div class="card-body p-3 p-md-4">
            <div class="row g-3">
              @foreach($schemes as $scheme)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                  <div class="color-card border rounded p-3 h-100 bg-white">
                    {{-- Color Preview Box --}}
                    <div class="color-preview-box mb-3" data-key="{{ $scheme->key }}">
                      <div class="color-fill" style="background-color: {{ $scheme->value }};"></div>
                    </div>
                    
                    {{-- Color Info --}}
                    <div class="mb-3">
                      <label class="form-label fw-bold mb-1 small text-uppercase text-muted">
                        {{ $scheme->key }}
                      </label>
                      @if($scheme->description)
                        <p class="small text-muted mb-2">{{ $scheme->description }}</p>
                      @endif
                    </div>
                    
                    {{-- Color Input Group --}}
                    <div class="color-input-group mb-3">
                      <input type="text" 
                             name="colors[{{ $scheme->key }}]" 
                             class="form-control color-value-input" 
                             value="{{ $scheme->value }}"
                             data-key="{{ $scheme->key }}"
                             placeholder="#000000"
                             autocomplete="off">
                      <input type="color" 
                             class="form-control form-control-color color-picker-input" 
                             value="{{ $scheme->value }}"
                             data-key="{{ $scheme->key }}"
                             title="Pick color">
                    </div>
                    
                    {{-- Actions --}}
                    <div class="d-flex gap-2 align-items-center justify-content-between">
                      {{-- Attractive Toggle Switch --}}
                      <div class="toggle-switch-wrapper">
                        <label class="toggle-switch" for="switch-{{ $scheme->id }}">
                          <input type="checkbox" 
                                 class="toggle-switch-input" 
                                 {{ $scheme->is_active ? 'checked' : '' }}
                                 onchange="toggleColorActive({{ $scheme->id }}, this)"
                                 id="switch-{{ $scheme->id }}">
                          <span class="toggle-switch-slider">
                            <span class="toggle-switch-label-on">ON</span>
                            <span class="toggle-switch-label-off">OFF</span>
                          </span>
                        </label>
                      </div>
                      
                      {{-- Action Buttons - Icons Only --}}
                      <div class="d-flex gap-1">
                        <a href="{{ route('admin.color-schemes.edit', $scheme) }}" 
                           class="btn btn-sm btn-outline-primary p-2" 
                           title="Edit"
                           style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0 !important;">
                          <i class="bi bi-pencil-fill"></i>
                        </a>
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger p-2" 
                                onclick="deleteColor({{ $scheme->id }})"
                                title="Delete"
                                style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0 !important;">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endforeach

      {{-- Save Button --}}
      <div class="card border-primary">
        <div class="card-body text-center">
          <button type="submit" class="btn btn-primary btn-lg px-5" id="saveBtn">
            <i class="bi bi-save me-2"></i>Save All Changes
          </button>
          <p class="text-muted small mt-2 mb-0">
            <i class="bi bi-info-circle me-1"></i>Changes will be applied immediately across the entire application
          </p>
        </div>
      </div>
      </form>
    </div>
  </div>

  {{-- Save Indicator --}}
  <div class="save-indicator" id="saveIndicator">
    <i class="bi bi-check-circle me-2"></i>Colors saved successfully!
  </div>

  {{-- Delete Form (Hidden) --}}
  <form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

  <script>
    // Real-time color update
    function updateColorPreview(key, value) {
      // Update preview box
      const previewBox = document.querySelector(`.color-preview-box[data-key="${key}"] .color-fill`);
      if (previewBox) {
        previewBox.style.backgroundColor = value;
      }
      
      // Update CSS variable in real-time
      const cssKey = '--' + key.replace(/-/g, '-');
      document.documentElement.style.setProperty(cssKey, value);
      
      // Update specific preview elements
      applyColorToPreviewElements(key, value);
      
      // Mark as changed
      document.getElementById('saveBtn').classList.add('btn-warning');
      document.getElementById('saveBtn').innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Unsaved Changes';
    }

    // Apply color to preview elements
    function applyColorToPreviewElements(key, value) {
      // Button colors
      if (key === 'btn-primary-bg') {
        document.getElementById('previewBtnPrimary').style.backgroundColor = value;
        document.querySelectorAll('.btn-primary').forEach(btn => {
          btn.style.backgroundColor = value;
        });
      }
      if (key === 'btn-primary-text') {
        document.getElementById('previewBtnPrimary').style.color = value;
      }
      if (key === 'btn-secondary-bg') {
        document.querySelectorAll('.btn-secondary').forEach(btn => {
          btn.style.backgroundColor = value;
        });
      }
      if (key === 'btn-success-bg') {
        document.querySelectorAll('.btn-success').forEach(btn => {
          btn.style.backgroundColor = value;
        });
      }
      if (key === 'btn-danger-bg') {
        document.querySelectorAll('.btn-danger').forEach(btn => {
          btn.style.backgroundColor = value;
        });
      }
      
      // Card colors
      if (key === 'card-bg') {
        document.getElementById('previewCard').style.backgroundColor = value;
      }
      if (key === 'card-border') {
        document.getElementById('previewCard').style.borderColor = value;
      }
      if (key === 'card-header-bg') {
        document.querySelector('#previewCard .card-header').style.backgroundColor = value;
      }
      
      // Input colors
      if (key === 'input-border') {
        document.getElementById('previewInput').style.borderColor = value;
        document.getElementById('previewSelect').style.borderColor = value;
        document.querySelectorAll('#previewCard textarea').forEach(el => {
          el.style.borderColor = value;
        });
      }
      if (key === 'input-focus-border') {
        const style = document.createElement('style');
        style.textContent = `
          #previewInput:focus, #previewSelect:focus {
            border-color: ${value} !important;
          }
        `;
        document.head.appendChild(style);
      }
      if (key === 'input-bg') {
        document.getElementById('previewInput').style.backgroundColor = value;
        document.getElementById('previewSelect').style.backgroundColor = value;
      }
      
      // Table colors
      if (key === 'table-header-bg') {
        document.querySelector('#previewTable thead').style.backgroundColor = value;
      }
      if (key === 'table-header-text') {
        document.querySelectorAll('#previewTable thead th').forEach(th => {
          th.style.color = value;
        });
      }
      if (key === 'table-row-hover') {
        const style = document.createElement('style');
        style.textContent = `
          #previewTable tbody tr:hover {
            background-color: ${value} !important;
          }
        `;
        document.head.appendChild(style);
      }
      
      // Badge colors
      if (key === 'btn-primary-bg') {
        document.querySelectorAll('.badge.bg-primary').forEach(badge => {
          badge.style.backgroundColor = value;
        });
      }
    }

    // Color picker change
    document.querySelectorAll('.color-picker-input').forEach(picker => {
      picker.addEventListener('input', function() {
        const key = this.dataset.key;
        const value = this.value;
        const textInput = document.querySelector(`input[data-key="${key}"]`);
        if (textInput) {
          textInput.value = value;
          updateColorPreview(key, value);
        }
      });
    });

    // Text input change
    document.querySelectorAll('.color-value-input').forEach(input => {
      input.addEventListener('input', function() {
        const key = this.dataset.key;
        const value = this.value;
        const colorPicker = document.querySelector(`.color-picker-input[data-key="${key}"]`);
        
        // Update color picker if valid hex
        if (value.match(/^#[0-9A-F]{6}$/i)) {
          if (colorPicker) colorPicker.value = value;
        }
        
        updateColorPreview(key, value);
      });
    });

    // Toggle color active status
    function toggleColorActive(id, checkbox) {
      fetch(`/admin/color-schemes/${id}/toggle-active`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          checkbox.nextElementSibling.textContent = data.is_active ? 'Active' : 'Inactive';
          // Reload page to reflect changes
          setTimeout(() => location.reload(), 500);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        checkbox.checked = !checkbox.checked;
      });
    }

    // Delete color
    function deleteColor(id) {
      if (!confirm('Are you sure you want to delete this color? This action cannot be undone.')) {
        return;
      }
      
      const form = document.getElementById('deleteForm');
      form.action = `/admin/color-schemes/${id}`;
      form.submit();
    }

    // Form submission with AJAX
    document.getElementById('bulkColorForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const saveBtn = document.getElementById('saveBtn');
      const originalHtml = saveBtn.innerHTML;
      saveBtn.disabled = true;
      saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
      
      const formData = new FormData(this);
      const colors = {};
      
      formData.forEach((value, key) => {
        if (key.startsWith('colors[')) {
          const colorKey = key.match(/colors\[(.*?)\]/)[1];
          colors[colorKey] = value;
        }
      });

      fetch(this.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ colors })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success indicator
          const indicator = document.getElementById('saveIndicator');
          indicator.style.display = 'block';
          setTimeout(() => {
            indicator.style.display = 'none';
          }, 3000);
          
          // Reset button
          saveBtn.disabled = false;
          saveBtn.classList.remove('btn-warning');
          saveBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Saved!';
          saveBtn.classList.add('btn-success');
          
          setTimeout(() => {
            saveBtn.classList.remove('btn-success');
            saveBtn.innerHTML = originalHtml;
          }, 2000);
          
          // Reload to get fresh data
          setTimeout(() => location.reload(), 1000);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Error - Try Again';
        saveBtn.classList.add('btn-danger');
        setTimeout(() => {
          saveBtn.classList.remove('btn-danger');
          saveBtn.innerHTML = originalHtml;
        }, 3000);
      });
    });

    // Reset to defaults
    function resetToDefaults() {
      if (!confirm('Are you sure you want to reset all colors to default values? This will overwrite all current colors.')) {
        return;
      }
      
      // You can implement this by calling a route that runs the seeder
      alert('Reset functionality can be added by running: php artisan db:seed --class=ColorSchemeSeeder');
    }

    // Initialize all colors on page load
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.color-value-input').forEach(input => {
        const key = input.dataset.key;
        const value = input.value;
        if (value) {
          updateColorPreview(key, value);
        }
      });
    });
  </script>
</x-admin.layouts.app>

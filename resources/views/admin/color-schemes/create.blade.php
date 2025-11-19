{{-- resources/views/admin/color-schemes/create.blade.php --}}
<x-admin.layouts.app>
  <div class="container-xxl py-3">
    <div class="mb-4">
      <a href="{{ route('admin.color-schemes.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Colors
      </a>
      <h1 class="h3 mb-1">Add New Color</h1>
      <p class="text-secondary mb-0">Create a new color scheme entry.</p>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.color-schemes.store') }}">
          @csrf
          
          <div class="row g-3">
            <div class="col-md-6">
              <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
              <input type="text" 
                     class="form-control @error('key') is-invalid @enderror" 
                     id="key" 
                     name="key" 
                     value="{{ old('key') }}" 
                     placeholder="e.g., btn-primary-bg"
                     required>
              <small class="text-muted">Unique identifier (use hyphens, e.g., btn-primary-bg)</small>
              @error('key')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
              <select class="form-select @error('category') is-invalid @enderror" 
                      id="category" 
                      name="category" 
                      required>
                <option value="">Select category</option>
                @foreach($categories as $key => $label)
                  <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
              @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="value" class="form-label">Color Value <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="color" 
                       class="form-control form-control-color" 
                       id="colorPicker"
                       value="{{ old('value', '#4f46e5') }}"
                       style="width: 60px; height: 42px;">
                <input type="text" 
                       class="form-control @error('value') is-invalid @enderror" 
                       id="value" 
                       name="value" 
                       value="{{ old('value', '#4f46e5') }}" 
                       placeholder="#4f46e5"
                       required>
              </div>
              <small class="text-muted">Hex color code (e.g., #4f46e5) or CSS value</small>
              @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="is_active" class="form-label">Status</label>
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                  Active
                </label>
              </div>
            </div>

            <div class="col-12">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control @error('description') is-invalid @enderror" 
                        id="description" 
                        name="description" 
                        rows="3"
                        placeholder="Brief description of what this color is used for">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12">
              <div class="card bg-light">
                <div class="card-body">
                  <h6 class="card-title">Preview</h6>
                  <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary" id="previewBtn">Preview Button</button>
                    <div class="card" style="width: 200px;">
                      <div class="card-body" id="previewCard">Preview Card</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-1"></i>Create Color
            </button>
            <a href="{{ route('admin.color-schemes.index') }}" class="btn btn-outline-secondary">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    const colorPicker = document.getElementById('colorPicker');
    const valueInput = document.getElementById('value');
    const previewBtn = document.getElementById('previewBtn');
    const previewCard = document.getElementById('previewCard');

    colorPicker.addEventListener('input', function() {
      valueInput.value = this.value;
      updatePreview();
    });

    valueInput.addEventListener('input', function() {
      if (this.value.match(/^#[0-9A-F]{6}$/i)) {
        colorPicker.value = this.value;
      }
      updatePreview();
    });

    function updatePreview() {
      const color = valueInput.value;
      previewBtn.style.backgroundColor = color;
      previewCard.style.backgroundColor = color;
      previewCard.style.color = '#ffffff';
    }

    updatePreview();
  </script>
</x-admin.layouts.app>


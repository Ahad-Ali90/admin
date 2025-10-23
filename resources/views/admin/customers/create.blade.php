{{-- resources/views/admin/customers/create.blade.php --}}
<x-admin.layouts.app>
  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="mb-4">
      <h1 class="h3 mb-1">Create New Customer</h1>
      <p class="text-secondary mb-0">Add a new customer to your database.</p>
    </div>

    <form method="POST" action="{{ route('admin.customers.store') }}" class="vstack gap-4">
      @csrf

      <!-- Basic Information -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Basic Information</h5>

          <div class="row g-3">
            <!-- Name -->
            <div class="col-12 col-md-6">
              <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" value="{{ old('name') }}"
                     class="form-control @error('name') is-invalid @enderror" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Email -->
            <div class="col-12 col-md-6">
              <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email" value="{{ old('email') }}"
                     class="form-control @error('email') is-invalid @enderror" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Phone -->
            <div class="col-12 col-md-6">
              <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
              <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                     class="form-control @error('phone') is-invalid @enderror" required>
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Customer Type -->
            <div class="col-12 col-md-6">
              <label for="customer_type" class="form-label">Customer Type <span class="text-danger">*</span></label>
              <select name="customer_type" id="customer_type"
                      class="form-select @error('customer_type') is-invalid @enderror" required>
                <option value="">Select type</option>
                <option value="individual" @selected(old('customer_type')=='individual')>Individual</option>
                <option value="business"   @selected(old('customer_type')=='business')>Business</option>
              </select>
              @error('customer_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <!-- Company Name (conditional) -->
          <div id="company_name_field" class="row g-3 mt-1" style="display:none;">
            <div class="col-12">
              <label for="company_name" class="form-label">Company Name</label>
              <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                     class="form-control @error('company_name') is-invalid @enderror">
              @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      <!-- Address Information -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Address Information</h5>

          <div class="row g-3">
            <!-- Address -->
            <div class="col-12">
              <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
              <textarea name="address" id="address" rows="3"
                        class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
              @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Postcode -->
            <div class="col-12 col-md-6">
              <label for="postcode" class="form-label">Postcode</label>
              <input type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"
                     class="form-control @error('postcode') is-invalid @enderror">
              @error('postcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Information -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Additional Information</h5>

          <div class="row g-3">
            <!-- Customer Source -->
            <div class="col-12 col-md-6">
              <label for="customer_source" class="form-label">How did they find you?</label>
              <select name="customer_source" id="customer_source"
                      class="form-select @error('customer_source') is-invalid @enderror">
                <option value="">Select source</option>
                <option value="website"       @selected(old('customer_source')=='website')>Website</option>
                <option value="google"        @selected(old('customer_source')=='google')>Google Search</option>
                <option value="facebook"      @selected(old('customer_source')=='facebook')>Facebook</option>
                <option value="instagram"     @selected(old('customer_source')=='instagram')>Instagram</option>
                <option value="referral"      @selected(old('customer_source')=='referral')>Referral</option>
                <option value="advertisement" @selected(old('customer_source')=='advertisement')>Advertisement</option>
                <option value="other"         @selected(old('customer_source')=='other')>Other</option>
              </select>
              @error('customer_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Notes -->
            <div class="col-12">
              <label for="notes" class="form-label">Notes</label>
              <textarea name="notes" id="notes" rows="3"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Any additional notes about this customer...">{{ old('notes') }}</textarea>
              @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Customer</button>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
    (function(){
      const typeSel = document.getElementById('customer_type');
      const field   = document.getElementById('company_name_field');
      const input   = document.getElementById('company_name');

      function toggleCompany(){
        const isBiz = typeSel.value === 'business';
        field.style.display = isBiz ? 'flex' : 'none';
        input.required = isBiz;
        if(!isBiz){ input.value = ''; }
      }

      typeSel.addEventListener('change', toggleCompany);
      document.addEventListener('DOMContentLoaded', toggleCompany);
    })();
  </script>
  @endpush
</x-admin.layouts.app>

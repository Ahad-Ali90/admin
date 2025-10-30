{{-- resources/views/admin/customers/edit.blade.php --}}
<x-admin.layouts.app>
  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="mb-4 d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between">
      <div>
        <h1 class="h3 mb-1">Edit Customer</h1>
        <p class="text-secondary mb-0">Update details for <span class="fw-semibold">{{ $customer->name }}</span>.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">View</a>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary">Back to list</a>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="vstack gap-4">
      @csrf
      @method('PUT')

      <!-- Basic Information -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Basic Information</h5>

          <div class="row g-3">
            <!-- Name -->
            <div class="col-12 col-md-6">
              <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name"
                     value="{{ old('name', $customer->name) }}"
                     class="form-control @error('name') is-invalid @enderror" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Email -->
            <div class="col-12 col-md-6">
              <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email"
                     value="{{ old('email', $customer->email) }}"
                     class="form-control @error('email') is-invalid @enderror" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Phone -->
            <div class="col-12 col-md-6">
              <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
              <input type="tel" name="phone" id="phone"
                     value="{{ old('phone', $customer->phone) }}"
                     class="form-control @error('phone') is-invalid @enderror" required>
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $customer->address) }}</textarea>
              @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Postcode -->
            <div class="col-12 col-md-6">
              <label for="postcode" class="form-label">Postcode</label>
              <input type="text" name="postcode" id="postcode"
                     value="{{ old('postcode', $customer->postcode) }}"
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
              @php $source = old('customer_source', $customer->customer_source); @endphp
              <select name="customer_source" id="customer_source"
                      class="form-select @error('customer_source') is-invalid @enderror">
                <option value="">Select source</option>
                <option value="website"       @selected($source=='website')>Website</option>
                <option value="google"        @selected($source=='google')>Google Search</option>
                <option value="facebook"      @selected($source=='facebook')>Facebook</option>
                <option value="instagram"     @selected($source=='instagram')>Instagram</option>
                <option value="referral"      @selected($source=='referral')>Referral</option>
                <option value="advertisement" @selected($source=='advertisement')>Advertisement</option>
                <option value="other"         @selected($source=='other')>Other</option>
              </select>
              @error('customer_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Notes -->
            <div class="col-12">
              <label for="notes" class="form-label">Notes</label>
              <textarea name="notes" id="notes" rows="3"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Any additional notes about this customer...">{{ old('notes', $customer->notes) }}</textarea>
              @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>

</x-admin.layouts.app>

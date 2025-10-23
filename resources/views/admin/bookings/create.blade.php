{{-- resources/views/admin/bookings/create.blade.php --}}
<x-admin.layouts.app>
  <div class="container-xxl py-3">
    {{-- Header --}}
    <div class="mb-4">
      <h1 class="h3 mb-1">Create New Booking</h1>
      <p class="text-secondary mb-0">Fill in the details to create a new transport booking.</p>
    </div>

    <form method="POST" action="{{ route('admin.bookings.store') }}" class="vstack gap-4">
      @csrf

      {{-- Booking Details --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Booking Details</h5>

          <div class="row g-3">
            {{-- Customer --}}
            <div class="col-12">
              <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
              <select name="customer_id" id="customer_id"
                      class="form-select @error('customer_id') is-invalid @enderror" required>
                <option value="">Select a customer</option>
                @foreach($customers as $customer)
                  <option value="{{ $customer->id }}" @selected(old('customer_id')==$customer->id)>
                    {{ $customer->name }} ({{ $customer->email }})
                  </option>
                @endforeach
              </select>
              @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Booking Date --}}
            <div class="col-12 col-md-3">
              <label for="booking_date" class="form-label">Booking Date <span class="text-danger">*</span></label>
              <input type="date" name="booking_date" id="booking_date"
                     value="{{ old('booking_date', date('Y-m-d')) }}"
                     class="form-control @error('booking_date') is-invalid @enderror" required>
              @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Start Date --}}
            <div class="col-12 col-md-3">
              <label for="start_date" class="form-label">Start Date</label>
              <input type="date" name="start_date" id="start_date"
                     value="{{ old('start_date') }}"
                     class="form-control @error('start_date') is-invalid @enderror">
              @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- End Date --}}
            <div class="col-12 col-md-3">
              <label for="end_date" class="form-label">End Date</label>
              <input type="date" name="end_date" id="end_date"
                     value="{{ old('end_date') }}"
                     class="form-control @error('end_date') is-invalid @enderror">
              @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Start Time --}}
            <div class="col-12 col-md-3">
              <label for="start_time" class="form-label">Start Time</label>
              <input type="time" name="start_time" id="start_time"
                     value="{{ old('start_time') }}"
                     class="form-control @error('start_time') is-invalid @enderror">
              @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Source --}}
            <div class="col-12 col-md-4">
              <label for="source" class="form-label">Source</label>
              <input type="text" name="source" id="source"
                     value="{{ old('source') }}"
                     class="form-control @error('source') is-invalid @enderror"
                     placeholder="Where did this booking come from?">
              @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Contact No --}}
            <div class="col-12 col-md-4">
              <label for="contact_no" class="form-label">Contact No</label>
              <input type="text" name="contact_no" id="contact_no"
                     value="{{ old('contact_no') }}"
                     class="form-control @error('contact_no') is-invalid @enderror"
                     placeholder="Customer contact number">
              @error('contact_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Email --}}
            <div class="col-12 col-md-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email"
                     value="{{ old('email') }}"
                     class="form-control @error('email') is-invalid @enderror"
                     placeholder="Customer email">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Estimated Hours --}}
            <div class="col-12 col-md-6">
              <label for="estimated_hours" class="form-label">Estimated Hours</label>
              <input type="number" name="estimated_hours" id="estimated_hours" min="1"
                     value="{{ old('estimated_hours') }}"
                     class="form-control @error('estimated_hours') is-invalid @enderror">
              @error('estimated_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Week Start --}}
            <div class="col-12 col-md-6">
              <label for="week_start" class="form-label">Week Start</label>
              <input type="date" name="week_start" id="week_start"
                     value="{{ old('week_start') }}"
                     class="form-control @error('week_start') is-invalid @enderror">
              @error('week_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Location Details (unchanged) --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Location Details</h5>
          <div class="row g-3">
            {{-- Pickup Address --}}
            <div class="col-12">
              <label for="pickup_address" class="form-label">Pickup Address <span class="text-danger">*</span></label>
              <textarea name="pickup_address" id="pickup_address" rows="3" class="form-control @error('pickup_address') is-invalid @enderror" required>{{ old('pickup_address') }}</textarea>
              @error('pickup_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Delivery Address --}}
            <div class="col-12">
              <label for="delivery_address" class="form-label">Delivery Address <span class="text-danger">*</span></label>
              <textarea name="delivery_address" id="delivery_address" rows="3" class="form-control @error('delivery_address') is-invalid @enderror" required>{{ old('delivery_address') }}</textarea>
              @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Via Address --}}
            <div class="col-12">
              <label for="via_address" class="form-label">Via Address</label>
              <textarea name="via_address" id="via_address" rows="2" class="form-control @error('via_address') is-invalid @enderror" placeholder="Optional: Address to visit on the way">{{ old('via_address') }}</textarea>
              @error('via_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Postcodes --}}
            <div class="col-12 col-md-6">
              <label for="pickup_postcode" class="form-label">Pickup Postcode</label>
              <input type="text" name="pickup_postcode" id="pickup_postcode"
                     value="{{ old('pickup_postcode') }}"
                     class="form-control @error('pickup_postcode') is-invalid @enderror">
              @error('pickup_postcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="delivery_postcode" class="form-label">Delivery Postcode</label>
              <input type="text" name="delivery_postcode" id="delivery_postcode"
                     value="{{ old('delivery_postcode') }}"
                     class="form-control @error('delivery_postcode') is-invalid @enderror">
              @error('delivery_postcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Job Details --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Job Details</h5>
          <div class="row g-3">
            <div class="col-12">
              <label for="job_description" class="form-label">Job Description <span class="text-danger">*</span></label>
              <textarea name="job_description" id="job_description" rows="4"
                        class="form-control @error('job_description') is-invalid @enderror"
                        placeholder="Describe what needs to be moved or delivered..." required>{{ old('job_description') }}</textarea>
              @error('job_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label for="special_instructions" class="form-label">Special Instructions</label>
              <textarea name="special_instructions" id="special_instructions" rows="3"
                        class="form-control @error('special_instructions') is-invalid @enderror"
                        placeholder="Any special requirements or instructions...">{{ old('special_instructions') }}</textarea>
              @error('special_instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Assignment (Optional) --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Assignment (Optional)</h5>

          <div class="row g-3">
            {{-- Driver --}}
            <div class="col-12 col-md-4">
              <label for="driver_id" class="form-label">Driver</label>
              <select name="driver_id" id="driver_id" class="form-select @error('driver_id') is-invalid @enderror">
                <option value="">Select a driver</option>
                @foreach($drivers as $driver)
                  <option value="{{ $driver->id }}" @selected(old('driver_id')==$driver->id)>{{ $driver->name }}</option>
                @endforeach
              </select>
              @error('driver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Porter -> allow multiple --}}
            <div class="col-12 col-md-4">
              <label for="porter_ids" class="form-label">Porter(s)</label>
              <select name="porter_ids[]" id="porter_ids" class="form-select @error('porter_ids') is-invalid @enderror" multiple>
                @foreach($porters as $porter)
                  <option value="{{ $porter->id }}" @selected( is_array(old('porter_ids')) && in_array($porter->id, old('porter_ids')) )>
                    {{ $porter->name }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">Hold Ctrl / Cmd to select multiple porters.</small>
              @error('porter_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            {{-- Vehicle --}}
            <div class="col-12 col-md-4">
              <label for="vehicle_id" class="form-label">Vehicle</label>
              <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror">
                <option value="">Select a vehicle</option>
                @foreach($vehicles as $vehicle)
                  <option value="{{ $vehicle->id }}" @selected(old('vehicle_id')==$vehicle->id)>
                    {{ $vehicle->registration_number }} ({{ $vehicle->make }} {{ $vehicle->model }})
                  </option>
                @endforeach
              </select>
              @error('vehicle_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>
      {{-- Services (Optional) --}}
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="card-title mb-0">Services (Optional)</h5>
            <button type="button" id="add-service" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-plus-lg me-1"></i>Add Service
            </button>
          </div>

          <div id="services-container" class="vstack gap-2">
            {{-- dynamic rows injected here --}}
          </div>

          <template id="service-row-template">
            <div class="service-row border rounded p-2">
              <div class="row g-2 align-items-end">
                <div class="col-12 col-lg">
                  <label class="form-label mb-1">Service Type</label>
                  <select class="form-select service-select">
                    <option value="">Select a service type</option>
                    @foreach($services as $service)
                      <option value="{{ $service->id }}">
                        {{ $service->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-6 col-md-2">
                  <label class="form-label mb-1">Qty</label>
                  <input type="number" min="1" step="1" class="form-control service-qty" value="1">
                </div>

                <div class="col-12 col-md-2 col-lg-auto">
                  <button type="button" class="btn btn-outline-danger w-100 remove-service">
                    <i class="bi bi-trash me-1"></i>Remove
                  </button>
                </div>
              </div>
              {{-- hidden real inputs --}}
              <input type="hidden" class="real-service-id" name="">
              <input type="hidden" class="real-service-qty" name="">
            </div>
          </template>
        </div>
      </div>

      {{-- Booking Type and Pricing --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Booking Type & Pricing</h5>

          <div class="row g-3">
            {{-- Booking Type --}}
            <div class="col-12 col-md-6">
              <label for="booking_type" class="form-label">Booking Type <span class="text-danger">*</span></label>
              <select name="booking_type" id="booking_type" class="form-select @error('booking_type') is-invalid @enderror" required>
                <option value="">Select booking type</option>
                <option value="fixed" @selected(old('booking_type')=='fixed')>Fixed Price</option>
                <option value="hourly" @selected(old('booking_type')=='hourly')>Hourly Rate</option>
              </select>
              @error('booking_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Helpers Count --}}
            <div class="col-12 col-md-6">
              <label for="helpers_count" class="form-label">Number of Helpers</label>
              <input type="number" name="helpers_count" id="helpers_count" min="1" value="{{ old('helpers_count', 1) }}"
                     class="form-control @error('helpers_count') is-invalid @enderror">
              @error('helpers_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Fixed Price Fields --}}
            <div id="fixed-price-fields" class="row g-3" style="display: none;">
              <div class="col-12 col-md-6">
                <label for="total_fare" class="form-label">Total Fare</label>
                <input type="number" step="0.01" min="0" name="total_fare" id="total_fare"
                       value="{{ old('total_fare') }}"
                       class="form-control @error('total_fare') is-invalid @enderror">
                @error('total_fare')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12 col-md-6">
                <label for="booked_hours" class="form-label">Estimated Hours</label>
                <input type="number" min="1" name="booked_hours" id="booked_hours"
                       value="{{ old('booked_hours') }}"
                       class="form-control @error('booked_hours') is-invalid @enderror">
                @error('booked_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Hourly Rate Fields --}}
            <div id="hourly-rate-fields" class="row g-3" style="display: none;">
              <div class="col-12 col-md-6">
                <label for="hourly_rate" class="form-label">Hourly Rate</label>
                <input type="number" step="0.01" min="0" name="hourly_rate" id="hourly_rate"
                       value="{{ old('hourly_rate') }}"
                       class="form-control @error('hourly_rate') is-invalid @enderror">
                @error('hourly_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12 col-md-6">
                <label for="booked_hours" class="form-label">Total Hours</label>
                <input type="number" min="1" name="booked_hours" id="booked_hours_hourly"
                       value="{{ old('booked_hours') }}"
                       class="form-control @error('booked_hours') is-invalid @enderror">
                @error('booked_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Deposit --}}
            <div class="col-12 col-md-6">
              <label for="deposit" class="form-label">Deposit</label>
              <input type="number" step="0.01" min="0" name="deposit" id="deposit"
                     value="{{ old('deposit', 0) }}"
                     class="form-control @error('deposit') is-invalid @enderror">
              @error('deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Payment Method --}}
            <div class="col-12 col-md-6">
              <label for="payment_method" class="form-label">Payment Method</label>
              <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                <option value="">Select payment method</option>
                <option value="cash" @selected(old('payment_method')=='cash')>Cash</option>
                <option value="card" @selected(old('payment_method')=='card')>Card</option>
                <option value="bank_transfer" @selected(old('payment_method')=='bank_transfer')>Bank Transfer</option>
              </select>
              @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Discount --}}
            <div class="col-12 col-md-6">
              <label for="discount" class="form-label">Discount Amount</label>
              <input type="number" step="0.01" min="0" name="discount" id="discount"
                     value="{{ old('discount', 0) }}"
                     class="form-control @error('discount') is-invalid @enderror">
              @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Discount Reason --}}
            <div class="col-12 col-md-6">
              <label for="discount_reason" class="form-label">Discount Reason</label>
              <input type="text" name="discount_reason" id="discount_reason"
                     value="{{ old('discount_reason') }}"
                     class="form-control @error('discount_reason') is-invalid @enderror"
                     placeholder="Reason for discount">
              @error('discount_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Details Shared with Customer --}}
            <div class="col-12">
              <label for="details_shared_with_customer" class="form-label">Details Shared with Customer</label>
              <textarea name="details_shared_with_customer" id="details_shared_with_customer" rows="3"
                        class="form-control @error('details_shared_with_customer') is-invalid @enderror"
                        placeholder="Details that will be shared with the customer">{{ old('details_shared_with_customer') }}</textarea>
              @error('details_shared_with_customer')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Notes --}}
            <div class="col-12">
              <label for="notes" class="form-label">Notes</label>
              <textarea name="notes" id="notes" rows="3"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Internal notes about this booking">{{ old('notes') }}</textarea>
              @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Review Link --}}
            <div class="col-12">
              <label for="review_link" class="form-label">Review Link</label>
              <input type="url" name="review_link" id="review_link"
                     value="{{ old('review_link') }}"
                     class="form-control @error('review_link') is-invalid @enderror"
                     placeholder="Link to customer review">
              @error('review_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Manual Amount Entry --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Booking Amount</h5>

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="manual_amount" class="form-label">Total Amount <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" id="manual_amount" name="manual_amount" 
                     class="form-control @error('manual_amount') is-invalid @enderror" 
                     value="{{ old('manual_amount') }}" required>
              @error('manual_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">Enter the total amount for this booking manually.</small>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_company_booking" name="is_company_booking" value="1" 
                       @checked(old('is_company_booking'))>
                <label class="form-check-label" for="is_company_booking">
                  Company Booking
                </label>
              </div>
            </div>
          </div>

          {{-- Company Information --}}
          <div id="company-commission-section" class="row g-3 mt-3" style="display: none;">
            <div class="col-12">
              <h6 class="text-primary mb-3">Company Information</h6>
            </div>
            
            <div class="col-12 col-md-6">
              <label for="company_name" class="form-label">Company Name</label>
              <input type="text" name="company_name" id="company_name" 
                     value="{{ old('company_name') }}"
                     class="form-control @error('company_name') is-invalid @enderror"
                     placeholder="Enter company name">
              @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="company_phone" class="form-label">Company Phone</label>
              <input type="text" name="company_phone" id="company_phone" 
                     value="{{ old('company_phone') }}"
                     class="form-control @error('company_phone') is-invalid @enderror"
                     placeholder="Enter company phone number">
              @error('company_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="company_commission_rate" class="form-label">Commission Rate (%)</label>
              <input type="number" step="0.01" min="0" max="100" id="company_commission_rate" 
                     name="company_commission_rate" class="form-control @error('company_commission_rate') is-invalid @enderror" 
                     value="{{ old('company_commission_rate') }}">
              @error('company_commission_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="company_commission_amount" class="form-label">Commission Amount (£)</label>
              <input type="number" step="0.01" min="0" id="company_commission_amount" 
                     name="company_commission_amount" class="form-control @error('company_commission_amount') is-invalid @enderror" 
                     value="{{ old('company_commission_amount') }}"
                     placeholder="Enter commission amount">
              @error('company_commission_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          {{-- Extra Hours --}}
          <div class="row g-3 mt-3">
            <div class="col-12 col-md-4">
              <label for="extra_hours" class="form-label">Extra Hours</label>
              <input type="number" min="0" id="extra_hours" name="extra_hours" 
                     class="form-control" value="{{ old('extra_hours') }}">
            </div>
            <div class="col-12 col-md-4">
              <label for="extra_hours_rate" class="form-label">Extra Hours Rate</label>
              <input type="number" step="0.01" min="0" id="extra_hours_rate" name="extra_hours_rate" 
                     class="form-control" value="{{ old('extra_hours_rate') }}">
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label">Extra Hours Total</label>
              <input type="text" id="extra_hours_total" class="form-control" readonly>
            </div>
          </div>
        </div>
      </div>

      

      {{-- Actions --}}
      <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Booking</button>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
  (function(){
    // Service management
    let serviceIndex = 0;
    const btnAdd = document.getElementById('add-service');
    const container = document.getElementById('services-container');
    const tpl = document.getElementById('service-row-template');

    // Company booking toggle
    const isCompanyBooking = document.getElementById('is_company_booking');
    const companyCommissionSection = document.getElementById('company-commission-section');

    // Booking type toggle
    const bookingType = document.getElementById('booking_type');
    const fixedPriceFields = document.getElementById('fixed-price-fields');
    const hourlyRateFields = document.getElementById('hourly-rate-fields');

    // Extra hours calculation
    const extraHours = document.getElementById('extra_hours');
    const extraHoursRate = document.getElementById('extra_hours_rate');
    const extraHoursTotal = document.getElementById('extra_hours_total');

    function toFixed2(v){ return Number(v || 0).toFixed(2); }

    // Booking type toggle
    bookingType?.addEventListener('change', function(){
      if (this.value === 'fixed') {
        fixedPriceFields.style.display = 'block';
        hourlyRateFields.style.display = 'none';
      } else if (this.value === 'hourly') {
        fixedPriceFields.style.display = 'none';
        hourlyRateFields.style.display = 'block';
      } else {
        fixedPriceFields.style.display = 'none';
        hourlyRateFields.style.display = 'none';
      }
    });

    // Company booking toggle
    isCompanyBooking?.addEventListener('change', function(){
      companyCommissionSection.style.display = this.checked ? 'block' : 'none';
    });

    // Extra hours calculation
    function calculateExtraHoursTotal() {
      const hours = parseFloat(extraHours?.value || 0);
      const rate = parseFloat(extraHoursRate?.value || 0);
      const total = hours * rate;
      extraHoursTotal.value = toFixed2(total);
    }

    extraHours?.addEventListener('input', calculateExtraHoursTotal);
    extraHoursRate?.addEventListener('input', calculateExtraHoursTotal);

    // Service management
    btnAdd?.addEventListener('click', () => {
      const node = tpl.content.cloneNode(true);
      const row = node.querySelector('.service-row');

      const sel = row.querySelector('.service-select');
      const qty = row.querySelector('.service-qty');
      const btnR = row.querySelector('.remove-service');

      const hidId = row.querySelector('.real-service-id');
      const hidQty = row.querySelector('.real-service-qty');

      // Set unique names for hidden inputs
      hidId.name = `services[${serviceIndex}][service_id]`;
      hidQty.name = `services[${serviceIndex}][qty]`;

      function updateRow(){
        hidId.value = sel.value || '';
        hidQty.value = parseInt(qty.value || 1, 10);
      }

      sel.addEventListener('change', updateRow);
      qty.addEventListener('input', updateRow);

      btnR.addEventListener('click', () => {
        row.remove();
      });

      container.appendChild(node);
      serviceIndex++;
    });

    // Initialize
    calculateExtraHoursTotal();
    if (isCompanyBooking?.checked) {
      companyCommissionSection.style.display = 'block';
    }
    
    // Initialize booking type fields
    if (bookingType?.value === 'fixed') {
      fixedPriceFields.style.display = 'block';
      hourlyRateFields.style.display = 'none';
    } else if (bookingType?.value === 'hourly') {
      fixedPriceFields.style.display = 'none';
      hourlyRateFields.style.display = 'block';
    }

  })();
  </script>
  @endpush
</x-admin.layouts.app>

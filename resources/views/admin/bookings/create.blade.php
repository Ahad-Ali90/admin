{{-- resources/views/admin/bookings/create.blade.php --}}
<x-admin.layouts.app>
  <div class="container-xxl py-3">
    {{-- Header --}}
    <div class="mb-4">
      <h1 class="h3 mb-1">{{ isset($booking) ? 'Edit Booking' : 'Create New Booking' }}</h1>
      <p class="text-secondary mb-0">
        @if(isset($booking))
          Update details for {{ $booking->booking_reference }}.
        @else
          Fill in the details to create a new transport booking.
        @endif
      </p>
    </div>

    <form id="bookingForm" method="POST" action="{{ isset($booking) ? route('admin.bookings.update', $booking) : route('admin.bookings.store') }}" class="vstack gap-4">
      @csrf
      @if(isset($booking))
        @method('PUT')
      @endif

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
                  <option value="{{ $customer->id }}" @selected(old('customer_id', isset($booking) ? $booking->customer_id : '')==$customer->id)>
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
                     value="{{ old('booking_date', isset($booking) ? $booking->booking_date->format('Y-m-d') : date('Y-m-d')) }}"
                     class="form-control @error('booking_date') is-invalid @enderror" required>
              @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Start Date --}}
            <div class="col-12 col-md-3">
              <label for="start_date" class="form-label">Start Date</label>
              <input type="date" name="start_date" id="start_date"
                     value="{{ old('start_date', isset($booking) && $booking->start_date ? $booking->start_date->format('Y-m-d') : '') }}"
                     class="form-control @error('start_date') is-invalid @enderror">
              @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- End Date --}}
            <div class="col-12 col-md-3">
              <label for="end_date" class="form-label">End Date</label>
              <input type="date" name="end_date" id="end_date"
                     value="{{ old('end_date', isset($booking) && $booking->end_date ? $booking->end_date->format('Y-m-d') : '') }}"
                     class="form-control @error('end_date') is-invalid @enderror">
              @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Start Time --}}
            <div class="col-12 col-md-3">
              <label for="start_time" class="form-label">Start Time</label>
              <input type="time" name="start_time" id="start_time"
                     value="{{ old('start_time', isset($booking) ? $booking->start_time?->format('H:i') : '') }}"
                     class="form-control @error('start_time') is-invalid @enderror">
              @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
              <textarea name="pickup_address" id="pickup_address" rows="3" class="form-control @error('pickup_address') is-invalid @enderror" required>{{ old('pickup_address', isset($booking) ? $booking->pickup_address : '') }}</textarea>
              @error('pickup_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Delivery Address --}}
            <div class="col-12">
              <label for="delivery_address" class="form-label">Delivery Address <span class="text-danger">*</span></label>
              <textarea name="delivery_address" id="delivery_address" rows="3" class="form-control @error('delivery_address') is-invalid @enderror" required>{{ old('delivery_address', isset($booking) ? $booking->delivery_address : '') }}</textarea>
              @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Via Address --}}
            <div class="col-12">
              <label for="via_address" class="form-label">Via Address</label>
              <textarea name="via_address" id="via_address" rows="2" class="form-control @error('via_address') is-invalid @enderror" placeholder="Optional: Address to visit on the way">{{ old('via_address', isset($booking) ? $booking->via_address : '') }}</textarea>
              @error('via_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Postcodes --}}
            <div class="col-12 col-md-6">
              <label for="pickup_postcode" class="form-label">Pickup Postcode</label>
              <input type="text" name="pickup_postcode" id="pickup_postcode"
                     value="{{ old('pickup_postcode', isset($booking) ? $booking->pickup_postcode : '') }}"
                     class="form-control @error('pickup_postcode') is-invalid @enderror">
              @error('pickup_postcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="delivery_postcode" class="form-label">Delivery Postcode</label>
              <input type="text" name="delivery_postcode" id="delivery_postcode"
                     value="{{ old('delivery_postcode', isset($booking) ? $booking->delivery_postcode : '') }}"
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
                        placeholder="Describe what needs to be moved or delivered..." required>{{ old('job_description', isset($booking) ? $booking->job_description : '') }}</textarea>
              @error('job_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label for="special_instructions" class="form-label">Special Instructions</label>
              <textarea name="special_instructions" id="special_instructions" rows="3"
                        class="form-control @error('special_instructions') is-invalid @enderror"
                        placeholder="Any special requirements or instructions...">{{ old('special_instructions', isset($booking) ? $booking->special_instructions : '') }}</textarea>
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
                  <option value="{{ $driver->id }}" @selected(old('driver_id', isset($booking) ? $booking->driver_id : '')==$driver->id)>{{ $driver->name }}</option>
                @endforeach
              </select>
              @error('driver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Porter -> allow multiple with modern design --}}
            <div class="col-12">
              <label class="form-label">Porter(s)</label>
              <div class="porter-selection-container">
                <div class="row g-2">
                  @php
                    $selectedPorters = old('porter_ids', isset($booking) ? $booking->porter_ids : []);
                    if (!is_array($selectedPorters)) {
                      $selectedPorters = [];
                    }
                  @endphp
                  @foreach($porters as $porter)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                      <div class="porter-card {{ in_array($porter->id, $selectedPorters) ? 'selected' : '' }}" 
                           data-porter-id="{{ $porter->id }}"
                           onclick="togglePorter(this)">
                        <input type="checkbox" 
                               name="porter_ids[]" 
                               value="{{ $porter->id }}" 
                               class="porter-checkbox"
                               {{ in_array($porter->id, $selectedPorters) ? 'checked' : '' }}>
                        <div class="porter-icon">
                          <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="porter-name">{{ $porter->name }}</div>
                        <div class="porter-checkmark">
                          <i class="bi bi-check-circle-fill"></i>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              <small class="text-muted">Click to select/unselect porters. Multiple selections allowed.</small>
              @error('porter_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            {{-- Vehicle --}}
            <div class="col-12 col-md-4">
              <label for="vehicle_id" class="form-label">Vehicle</label>
              <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror">
                <option value="">Select a vehicle</option>
                @foreach($vehicles as $vehicle)
                  <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', isset($booking) ? $booking->vehicle_id : '')==$vehicle->id)>
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
            <h5 class="card-title mb-0">Service Types (Optional)</h5>
            <button type="button" id="add-service" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-plus-lg me-1"></i>Add Service Type
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

                <div class="col-12 col-md-2 col-lg-auto">
                  <button type="button" class="btn btn-outline-danger w-100 remove-service">
                    <i class="bi bi-trash me-1"></i>Remove
                  </button>
                </div>
              </div>
              {{-- hidden real inputs --}}
              <input type="hidden" class="real-service-id" name="">
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
                <option value="fixed" @selected(old('booking_type', isset($booking) ? $booking->booking_type : '')=='fixed')>Fixed Price</option>
                <option value="hourly" @selected(old('booking_type', isset($booking) ? $booking->booking_type : '')=='hourly')>Hourly Rate</option>
              </select>
              @error('booking_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Helpers Count --}}
            <div class="col-12 col-md-6">
              <label for="helpers_count" class="form-label">Number of Helpers</label>
              <input type="number" name="helpers_count" id="helpers_count" min="1" value="{{ old('helpers_count', isset($booking) ? $booking->helpers_count : 1) }}"
                     class="form-control @error('helpers_count') is-invalid @enderror">
              @error('helpers_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Fixed Price Fields --}}
            <div id="fixed-price-fields" class="row g-3" style="display: none;">
              <div class="col-12">
                <label for="total_fare" class="form-label">Total Fare</label>
                <input type="number" step="0.01" min="0" name="total_fare" id="total_fare"
                       value="{{ old('total_fare', isset($booking) ? $booking->total_fare : '') }}"
                       class="form-control @error('total_fare') is-invalid @enderror">
                @error('total_fare')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Hourly Rate Fields --}}
            <div id="hourly-rate-fields" class="row g-3" style="display: none;">
              <div class="col-12 col-md-6">
                <label for="hourly_rate" class="form-label">Hourly Rate</label>
                <input type="number" step="0.01" min="0" name="hourly_rate" id="hourly_rate"
                       value="{{ old('hourly_rate', isset($booking) ? $booking->hourly_rate : '') }}"
                       class="form-control @error('hourly_rate') is-invalid @enderror">
                @error('hourly_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12 col-md-6">
                <label for="booked_hours" class="form-label">Total Hours</label>
                <input type="number" min="1" name="booked_hours" id="booked_hours_hourly"
                       value="{{ old('booked_hours', isset($booking) ? $booking->booked_hours : '') }}"
                       class="form-control @error('booked_hours') is-invalid @enderror">
                @error('booked_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Deposit --}}
            <div class="col-12 col-md-6">
              <label for="deposit" class="form-label">Deposit</label>
              <input type="number" step="0.01" min="0" name="deposit" id="deposit"
                     value="{{ old('deposit', isset($booking) ? $booking->deposit : 0) }}"
                     class="form-control @error('deposit') is-invalid @enderror">
              @error('deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Payment Method --}}
            <div class="col-12 col-md-6">
              <label for="payment_method" class="form-label">Payment Method</label>
              <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                <option value="">Select payment method</option>
                <option value="cash" @selected(old('payment_method', isset($booking) ? $booking->payment_method : '')=='cash')>Cash</option>
                <option value="card" @selected(old('payment_method', isset($booking) ? $booking->payment_method : '')=='card')>Card</option>
                <option value="bank_transfer" @selected(old('payment_method', isset($booking) ? $booking->payment_method : '')=='bank_transfer')>Bank Transfer</option>
              </select>
              @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Discount --}}
            <div class="col-12 col-md-6">
              <label for="discount" class="form-label">Discount Amount</label>
              <input type="number" step="0.01" min="0" name="discount" id="discount"
                     value="{{ old('discount', isset($booking) ? $booking->discount : 0) }}"
                     class="form-control @error('discount') is-invalid @enderror">
              @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Discount Reason --}}
            <div class="col-12 col-md-6">
              <label for="discount_reason" class="form-label">Discount Reason</label>
              <input type="text" name="discount_reason" id="discount_reason"
                     value="{{ old('discount_reason', isset($booking) ? $booking->discount_reason : '') }}"
                     class="form-control @error('discount_reason') is-invalid @enderror"
                     placeholder="Reason for discount">
              @error('discount_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Details Shared with Customer --}}
            <div class="col-12">
              <label for="details_shared_with_customer" class="form-label">Details Shared with Customer</label>
              <textarea name="details_shared_with_customer" id="details_shared_with_customer" rows="3"
                        class="form-control @error('details_shared_with_customer') is-invalid @enderror"
                        placeholder="Details that will be shared with the customer">{{ old('details_shared_with_customer', isset($booking) ? $booking->details_shared_with_customer : '') }}</textarea>
              @error('details_shared_with_customer')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Notes --}}
            <div class="col-12">
              <label for="notes" class="form-label">Notes</label>
              <textarea name="notes" id="notes" rows="3"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Internal notes about this booking">{{ old('notes', isset($booking) ? $booking->notes : '') }}</textarea>
              @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                     value="{{ old('manual_amount', isset($booking) ? $booking->manual_amount : '') }}" required>
              @error('manual_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">Enter the total amount for this booking manually.</small>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_company_booking" name="is_company_booking" value="1" 
                       @checked(old('is_company_booking', isset($booking) ? $booking->is_company_booking : false))>
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
              <label for="company_id" class="form-label">Select Company</label>
              <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                <option value="">Select a company</option>
                @foreach($companies as $company)
                  <option value="{{ $company->id }}" 
                          data-phone="{{ $company->phone }}"
                          @selected(old('company_id', isset($booking) ? $booking->company_id : '') == $company->id)>
                    {{ $company->name }}
                  </option>
                @endforeach
              </select>
              @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">Don't see your company? <a href="{{ route('admin.companies.manage') }}" target="_blank">Add new company</a></small>
            </div>

            <div class="col-12 col-md-6">
              <label for="company_commission_amount" class="form-label">Commission Amount (£)</label>
              <input type="number" step="0.01" min="0" id="company_commission_amount" 
                     name="company_commission_amount" class="form-control @error('company_commission_amount') is-invalid @enderror" 
                     value="{{ old('company_commission_amount', isset($booking) ? $booking->company_commission_amount : '') }}"
                     placeholder="Enter commission amount">
              @error('company_commission_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          {{-- Extra Hours --}}
          <div class="row g-3 mt-3">
            <div class="col-12 col-md-4">
              <label for="extra_hours" class="form-label">Extra Hours</label>
              <input type="number" min="0" id="extra_hours" name="extra_hours" 
                     class="form-control" value="{{ old('extra_hours', isset($booking) ? $booking->extra_hours : '') }}">
            </div>
            <div class="col-12 col-md-4">
              <label for="extra_hours_rate" class="form-label">Extra Hours Rate</label>
              <input type="number" step="0.01" min="0" id="extra_hours_rate" name="extra_hours_rate" 
                     class="form-control" value="{{ old('extra_hours_rate', isset($booking) ? $booking->extra_hours_rate : '') }}">
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label">Extra Hours Total</label>
              <input type="text" id="extra_hours_total" class="form-control" readonly>
            </div>
          </div>
        </div>
      </div>

      {{-- Lead Source --}}
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">
            <i class="bi bi-graph-up-arrow me-2"></i>Lead Source
          </h5>

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="lead_source" class="form-label">Where did this inquiry come from?</label>
              <select name="lead_source" id="lead_source" class="form-select @error('lead_source') is-invalid @enderror">
                @foreach($leadSources as $source)
                  <option value="{{ $source->slug }}" 
                          @selected(old('lead_source', isset($booking) ? $booking->lead_source : 'phone') == $source->slug)>
                    <i class="bi {{ $source->icon }}"></i> {{ $source->name }}
                  </option>
                @endforeach
              </select>
              @error('lead_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">
                <a href="{{ route('admin.lead-sources.manage') }}" target="_blank" class="text-decoration-none">
                  <i class="bi bi-gear me-1"></i>Manage Lead Sources
                </a>
              </small>
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
        <div class="d-flex gap-2">
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancel
          </a>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-outline-danger" onclick="saveAsNotConverted(event)">
            <i class="bi bi-x-octagon me-2"></i>Mark as Not Converted
          </button>
          <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-check-circle me-2"></i>{{ isset($booking) ? 'Update Booking' : 'Save Booking' }}
          </button>
        </div>
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

    // Hourly rate calculation
    const hourlyRate = document.getElementById('hourly_rate');
    const bookedHours = document.getElementById('booked_hours_hourly');
    const totalFare = document.getElementById('total_fare');
    const discount = document.getElementById('discount');
    const manualAmount = document.getElementById('manual_amount');

    function calculateHourlyTotal() {
      if (bookingType?.value === 'hourly' && hourlyRate && bookedHours) {
        const rate = parseFloat(hourlyRate.value || 0);
        const hours = parseFloat(bookedHours.value || 0);
        const total = rate * hours;
        totalFare.value = toFixed2(total);
        updateManualAmount();
      }
    }

    function updateManualAmount() {
      if (totalFare && discount && manualAmount) {
        const fare = parseFloat(totalFare.value || 0);
        const discountAmount = parseFloat(discount.value || 0);
        const finalAmount = fare - discountAmount;
        manualAmount.value = toFixed2(Math.max(0, finalAmount));
      }
    }

    hourlyRate?.addEventListener('input', calculateHourlyTotal);
    bookedHours?.addEventListener('input', calculateHourlyTotal);
    discount?.addEventListener('input', updateManualAmount);

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
      const btnR = row.querySelector('.remove-service');

      const hidId = row.querySelector('.real-service-id');

      // Set unique names for hidden inputs
      hidId.name = `services[${serviceIndex}][service_id]`;

      function updateRow(){
        hidId.value = sel.value || '';
      }

      sel.addEventListener('change', updateRow);

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
    
    // Initialize company booking section visibility
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

  // Porter selection toggle function
  function togglePorter(card) {
    const checkbox = card.querySelector('.porter-checkbox');
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
      card.classList.add('selected');
    } else {
      card.classList.remove('selected');
    }
  }

  // Save as Not Converted Function
  function saveAsNotConverted(event) {
    event.preventDefault();
    
    // Get the BOOKING form specifically (not logout form!)
    const form = document.getElementById('bookingForm');
    
    // Get form using ID selectors (more reliable)
    const customerSelect = document.getElementById('customer_id');
    const bookingDate = document.getElementById('booking_date');
    const pickupAddress = document.getElementById('pickup_address');
    const deliveryAddress = document.getElementById('delivery_address');
    const jobDescription = document.getElementById('job_description');
    
    // Debug log
    console.log('Form elements found:', {
      form: !!form,
      customer: !!customerSelect,
      date: !!bookingDate,
      pickup: !!pickupAddress,
      delivery: !!deliveryAddress,
      job: !!jobDescription
    });
    
    if (!form) {
      alert('Error: Form not found. Please refresh the page.');
      return;
    }
    
    // Check if required fields are filled
    if (!customerSelect || !customerSelect.value) {
      alert('Please select a customer first');
      if (customerSelect) customerSelect.focus();
      return;
    }
    
    if (!bookingDate || !bookingDate.value) {
      alert('Please enter booking date');
      if (bookingDate) bookingDate.focus();
      return;
    }
    
    if (!pickupAddress || !pickupAddress.value.trim()) {
      alert('Please enter pickup address');
      if (pickupAddress) pickupAddress.focus();
      return;
    }
    
    if (!deliveryAddress || !deliveryAddress.value.trim()) {
      alert('Please enter delivery address');
      if (deliveryAddress) deliveryAddress.focus();
      return;
    }
    
    if (!jobDescription || !jobDescription.value.trim()) {
      alert('Please enter job description');
      if (jobDescription) jobDescription.focus();
      return;
    }
    
    if (!confirm('Mark this as Not Converted? Customer inquired but did not book.')) {
      return;
    }

    // Set the status to not_converted
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = 'not_converted';
    form.appendChild(statusInput);
    
    // Set manual_amount to 0 for not_converted (customer didn't get a quote)
    const manualAmountField = document.getElementById('manual_amount');
    if (manualAmountField) {
      if (!manualAmountField.value || manualAmountField.value === '') {
        manualAmountField.value = '0';
      }
    } else {
      // Create hidden field if not exists
      const manualAmountInput = document.createElement('input');
      manualAmountInput.type = 'hidden';
      manualAmountInput.name = 'manual_amount';
      manualAmountInput.value = '0';
      form.appendChild(manualAmountInput);
    }
    
    // Set booking_type to fixed (required field)
    const bookingTypeField = document.getElementById('booking_type') || form.querySelector('[name="booking_type"]');
    if (!bookingTypeField || !bookingTypeField.value) {
      const bookingTypeInput = document.createElement('input');
      bookingTypeInput.type = 'hidden';
      bookingTypeInput.name = 'booking_type';
      bookingTypeInput.value = 'fixed';
      form.appendChild(bookingTypeInput);
    }
    
    // Remove required attribute from booking_date to bypass "after_or_equal:today" validation
    // This is only for not_converted status
    if (bookingDate) {
      bookingDate.removeAttribute('min');
    }
    
    // Submit the form
    console.log('Submitting booking as not_converted...');
    form.submit();
  }
  </script>

  <style>
    /* Porter Card Styles */
    .porter-selection-container {
      margin-top: 0.5rem;
    }

    .porter-card {
      position: relative;
      background: #fff;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      padding: 1.25rem;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
      min-height: 140px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .porter-card:hover {
      border-color: #3b82f6;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
      transform: translateY(-2px);
    }

    .porter-card.selected {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border-color: #2563eb;
      color: white;
      box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
    }

    .porter-checkbox {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    .porter-icon {
      font-size: 2rem;
      color: #6b7280;
      transition: all 0.3s ease;
    }

    .porter-card.selected .porter-icon {
      color: white;
      transform: scale(1.1);
    }

    .porter-name {
      font-weight: 600;
      font-size: 0.95rem;
      color: #1f2937;
      transition: all 0.3s ease;
    }

    .porter-card.selected .porter-name {
      color: white;
    }

    .porter-checkmark {
      position: absolute;
      top: 8px;
      right: 8px;
      font-size: 1.25rem;
      color: white;
      opacity: 0;
      transform: scale(0);
      transition: all 0.3s ease;
    }

    .porter-card.selected .porter-checkmark {
      opacity: 1;
      transform: scale(1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .porter-card {
        min-height: 120px;
        padding: 1rem;
      }

      .porter-icon {
        font-size: 1.75rem;
      }

      .porter-name {
        font-size: 0.875rem;
      }
    }

    /* Animation for selection */
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }

    .porter-card.selected {
      animation: pulse 0.3s ease;
    }
  </style>
  @endpush
</x-admin.layouts.app>

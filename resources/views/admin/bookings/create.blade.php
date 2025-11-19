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

    {{-- Display All Validation Errors --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h5>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="bookingForm" method="POST" action="{{ isset($booking) ? route('admin.bookings.update', $booking) : route('admin.bookings.store') }}" class="vstack gap-4" onsubmit="debugFormSubmit(event)" enctype="multipart/form-data">
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
              <div class="date-time-wrapper">
                <i class="bi bi-calendar-event date-time-icon"></i>
                <input type="date" name="booking_date" id="booking_date"
                       value="{{ old('booking_date', isset($booking) ? $booking->booking_date->format('Y-m-d') : date('Y-m-d')) }}"
                       class="form-control date-time-input @error('booking_date') is-invalid @enderror" required>
              </div>
              @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Start Date --}}
            <div class="col-12 col-md-3">
              <label for="start_date" class="form-label">Start Date</label>
              <div class="date-time-wrapper">
                <i class="bi bi-calendar-check date-time-icon"></i>
                <input type="date" name="start_date" id="start_date"
                       value="{{ old('start_date', isset($booking) && $booking->start_date ? $booking->start_date->format('Y-m-d') : '') }}"
                       class="form-control date-time-input @error('start_date') is-invalid @enderror">
              </div>
              @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- End Date --}}
            <div class="col-12 col-md-3">
              <label for="end_date" class="form-label">End Date</label>
              <div class="date-time-wrapper">
                <i class="bi bi-calendar-x date-time-icon"></i>
                <input type="date" name="end_date" id="end_date"
                       value="{{ old('end_date', isset($booking) && $booking->end_date ? $booking->end_date->format('Y-m-d') : '') }}"
                       class="form-control date-time-input @error('end_date') is-invalid @enderror">
              </div>
              @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Start Time --}}
            <div class="col-12 col-md-3">
              <label for="start_time" class="form-label">Start Time</label>
              <div class="date-time-wrapper">
                <i class="bi bi-clock date-time-icon"></i>
                <input type="time" name="start_time" id="start_time"
                       step="60"
                       value="{{ old('start_time', isset($booking) ? $booking->start_time?->format('H:i') : '') }}"
                       class="form-control date-time-input @error('start_time') is-invalid @enderror">
              </div>
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
                  <select class="form-select service-select" name="" data-service-select>
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
            </div>
          </template>
        </div>
      </div>

      {{-- Video Survey --}}
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="card-title mb-0">
              <i class="bi bi-camera-video me-2"></i>Video Survey
            </h5>
            <button type="button" class="toggle-survey-btn" id="toggleSurveyBtn">
              <span class="toggle-text">Yes</span>
              <i class="bi bi-chevron-down toggle-icon"></i>
            </button>
          </div>

          <div id="survey-fields" style="display: {{ old('survey_type', isset($booking) && $booking->survey ? 'block' : 'none') }};">
            {{-- Survey Type Selection --}}
            <div class="mb-3">
              <label class="form-label">Survey Type <span class="text-danger">*</span></label>
              <div class="d-flex gap-3 flex-wrap">
                <div class="modern-radio-card">
                  <input class="modern-radio-input survey-type-radio" type="radio" name="survey_type" id="survey_video_call" value="video_call"
                         {{ old('survey_type', isset($booking) && $booking->survey ? $booking->survey->survey_type : '') == 'video_call' ? 'checked' : '' }}>
                  <label class="modern-radio-label" for="survey_video_call">
                    <i class="bi bi-telephone-video me-2"></i>
                    <span>Video Call</span>
                  </label>
                </div>
                <div class="modern-radio-card">
                  <input class="modern-radio-input survey-type-radio" type="radio" name="survey_type" id="survey_video_recording" value="video_recording"
                         {{ old('survey_type', isset($booking) && $booking->survey ? $booking->survey->survey_type : '') == 'video_recording' ? 'checked' : '' }}>
                  <label class="modern-radio-label" for="survey_video_recording">
                    <i class="bi bi-record-circle me-2"></i>
                    <span>Video Recording</span>
                  </label>
                </div>
                <div class="modern-radio-card">
                  <input class="modern-radio-input survey-type-radio" type="radio" name="survey_type" id="survey_list" value="list"
                         {{ old('survey_type', isset($booking) && $booking->survey ? $booking->survey->survey_type : '') == 'list' ? 'checked' : '' }}>
                  <label class="modern-radio-label" for="survey_list">
                    <i class="bi bi-list-ul me-2"></i>
                    <span>List</span>
                  </label>
                </div>
              </div>
            </div>

            {{-- Video Call Fields --}}
            <div id="video_call_fields" class="survey-type-fields" style="display: none;">
              {{-- Schedule Date and Time --}}
              <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                  <label for="schedule_date" class="form-label">Schedule Date</label>
                  <div class="date-time-wrapper">
                    <i class="bi bi-calendar-event date-time-icon"></i>
                    <input type="date" class="form-control date-time-input" id="schedule_date" name="schedule_date" 
                           value="{{ old('schedule_date', isset($booking) && $booking->survey ? $booking->survey->schedule_date?->format('Y-m-d') : '') }}">
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <label for="schedule_time" class="form-label">Schedule Time</label>
                  <div class="date-time-wrapper">
                    <i class="bi bi-clock date-time-icon"></i>
                    <input type="time" class="form-control date-time-input" id="schedule_time" name="schedule_time" 
                           step="60"
                           value="{{ old('schedule_time', isset($booking) && $booking->survey ? $booking->survey->schedule_time : '') }}">
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <div class="d-flex gap-3 flex-wrap">
                  <div class="modern-radio-card status-done">
                    <input class="modern-radio-input" type="radio" name="survey_status" id="vc_status_done" value="done"
                           {{ old('survey_status', isset($booking) && $booking->survey ? $booking->survey->status : '') == 'done' ? 'checked' : '' }}>
                    <label class="modern-radio-label" for="vc_status_done">
                      <i class="bi bi-check-circle me-2"></i>
                      <span>Done</span>
                    </label>
                  </div>
                  <div class="modern-radio-card status-pending">
                    <input class="modern-radio-input" type="radio" name="survey_status" id="vc_status_pending" value="pending"
                           {{ old('survey_status', isset($booking) && $booking->survey ? $booking->survey->status : '') == 'pending' ? 'checked' : '' }}>
                    <label class="modern-radio-label" for="vc_status_pending">
                      <i class="bi bi-clock me-2"></i>
                      <span>Pending</span>
                    </label>
                  </div>
                  <div class="modern-radio-card status-not-agreed">
                    <input class="modern-radio-input" type="radio" name="survey_status" id="vc_status_not_agreed" value="not_agreed"
                           {{ old('survey_status', isset($booking) && $booking->survey ? $booking->survey->status : '') == 'not_agreed' ? 'checked' : '' }}>
                    <label class="modern-radio-label" for="vc_status_not_agreed">
                      <i class="bi bi-x-circle me-2"></i>
                      <span>Not Agreed</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            {{-- Video Recording Fields --}}
            <div id="video_recording_fields" class="survey-type-fields" style="display: none;">
              <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <div class="d-flex gap-3 flex-wrap">
                  <div class="modern-radio-card status-done">
                    <input class="modern-radio-input" type="radio" name="survey_status" id="vr_status_done" value="done"
                           {{ old('survey_status', isset($booking) && $booking->survey ? $booking->survey->status : '') == 'done' ? 'checked' : '' }}>
                    <label class="modern-radio-label" for="vr_status_done">
                      <i class="bi bi-check-circle me-2"></i>
                      <span>Done</span>
                    </label>
                  </div>
                  <div class="modern-radio-card status-pending">
                    <input class="modern-radio-input" type="radio" name="survey_status" id="vr_status_pending" value="pending"
                           {{ old('survey_status', isset($booking) && $booking->survey ? $booking->survey->status : '') == 'pending' ? 'checked' : '' }}>
                    <label class="modern-radio-label" for="vr_status_pending">
                      <i class="bi bi-clock me-2"></i>
                      <span>Pending</span>
                    </label>
                  </div>
                  <div class="modern-radio-card status-not-agreed">
                    <input class="modern-radio-input" type="radio" name="survey_status" id="vr_status_not_agreed" value="not_agreed"
                           {{ old('survey_status', isset($booking) && $booking->survey ? $booking->survey->status : '') == 'not_agreed' ? 'checked' : '' }}>
                    <label class="modern-radio-label" for="vr_status_not_agreed">
                      <i class="bi bi-x-circle me-2"></i>
                      <span>Not Agreed</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="survey_video" class="form-label">Upload Video</label>
                
                <div class="custom-file-upload" id="video-upload-area">
                  <input type="file" class="custom-file-input" id="survey_video" name="survey_video" accept="video/*">
                  
                  <div class="file-upload-content">
                    <div class="file-upload-icon">
                      <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div class="file-upload-text">
                      <span class="file-upload-title">Click to upload or drag and drop</span>
                      <span class="file-upload-subtitle">MP4, AVI, MOV, WMV (Max 100MB)</span>
                    </div>
                  </div>
                  
                  <div class="file-selected-info" style="display: none;">
                    <div class="d-flex align-items-center gap-3">
                      <div class="file-icon">
                        <i class="bi bi-file-earmark-play-fill"></i>
                      </div>
                      <div class="flex-grow-1">
                        <div class="file-name"></div>
                        <div class="file-size"></div>
                      </div>
                      <button type="button" class="btn-remove-file">
                        <i class="bi bi-x-lg"></i>
                      </button>
                    </div>
                  </div>
                </div>

                @if(isset($booking) && $booking->survey && $booking->survey->video_path)
                  <div class="current-file-info mt-2">
                    <i class="bi bi-file-earmark-play me-2"></i>
                    <span class="text-muted">Current video: </span>
                    <a href="{{ Storage::url($booking->survey->video_path) }}" target="_blank" class="text-primary">
                      View Video <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                  </div>
                @endif
              </div>
            </div>

            {{-- List Fields --}}
            <div id="list_fields" class="survey-type-fields" style="display: none;">
              <div class="mb-3">
                <label for="survey_list_content" class="form-label">List Content <span class="text-danger">*</span></label>
                <textarea class="form-control" id="survey_list_content" name="survey_list_content" rows="5" 
                          placeholder="Enter your list items here...">{{ old('survey_list_content', isset($booking) && $booking->survey ? $booking->survey->list_content : '') }}</textarea>
              </div>
            </div>

            {{-- Notes (Common for all types) --}}
            <div class="mb-0">
              <label for="survey_notes" class="form-label">Survey Remarks (Optional)</label>
              <textarea class="form-control" id="survey_notes" name="survey_notes" rows="3" 
                        placeholder="Add any additional notes...">{{ old('survey_notes', isset($booking) && $booking->survey ? $booking->survey->notes : '') }}</textarea>
            </div>
          </div>
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
  // Debug form submission
  function debugFormSubmit(event) {
    const form = event.target;
    
    // Clean up empty date/time fields before submission
    const dateTimeInputs = form.querySelectorAll('input[type="date"], input[type="time"], input[type="datetime-local"]');
    dateTimeInputs.forEach(input => {
      if (input.value === '') {
        input.removeAttribute('name'); // Remove from submission
      }
    });
    
    const formData = new FormData(form);
    const services = [];
    
    // Collect all service data
    for (let [key, value] of formData.entries()) {
      if (key.includes('service')) {
        services.push({ key, value });
      }
    }
    
    console.log('Form submitting with services:', services);
    console.log('All form data:', Array.from(formData.entries()));
    
    // Don't prevent default - let form submit normally
    return true;
  }
  
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

    // Booking type toggle function
    function toggleBookingTypeFields() {
      const selectedValue = bookingType?.value;
      if (selectedValue === 'fixed') {
        if (fixedPriceFields) fixedPriceFields.style.display = 'block';
        if (hourlyRateFields) hourlyRateFields.style.display = 'none';
      } else if (selectedValue === 'hourly') {
        if (fixedPriceFields) fixedPriceFields.style.display = 'none';
        if (hourlyRateFields) hourlyRateFields.style.display = 'block';
      } else {
        if (fixedPriceFields) fixedPriceFields.style.display = 'none';
        if (hourlyRateFields) hourlyRateFields.style.display = 'none';
      }
    }

    // Booking type toggle - handle both native and Select2 events
    if (bookingType) {
      // Native change event (always works)
      bookingType.addEventListener('change', toggleBookingTypeFields);
      
      // Select2 change event (if Select2 is initialized)
      // Wait a bit for Select2 to initialize
      setTimeout(function() {
        if (typeof $ !== 'undefined' && $.fn.select2 && $(bookingType).hasClass('select2-hidden-accessible')) {
          // Select2 is initialized, use jQuery event
          $(bookingType).on('change', function() {
            toggleBookingTypeFields();
          });
        }
      }, 300);
    }

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

      // Set the name attribute directly on the select element
      sel.name = `services[${serviceIndex}][service_id]`;
      
      console.log('Added service row with name:', sel.name);

      btnR.addEventListener('click', () => {
        row.remove();
      });

      container.appendChild(node);
      serviceIndex++;
      
      // Initialize Select2 on the newly added select
      if (typeof $ !== 'undefined' && $.fn.select2) {
        $(sel).select2({
          theme: 'bootstrap-5',
          width: '100%',
          language: {
            noResults: function() {
              return "No results found";
            },
            searching: function() {
              return "Searching...";
            }
          },
          placeholder: function() {
            return $(sel).find('option:first').text() || 'Select an option';
          }
        });
        
        // Log when value changes
        $(sel).on('change', function() {
          console.log('Service selected:', sel.name, '=', sel.value);
        });
      } else {
        // Native change listener if Select2 not available
        sel.addEventListener('change', function() {
          console.log('Service selected:', sel.name, '=', sel.value);
        });
      }
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
    
    // Initialize booking type fields on page load
    toggleBookingTypeFields();

    // Load existing services for edit mode
    @if(isset($booking) && $booking->services->count() > 0)
      const existingServices = [
        @foreach($booking->services as $service)
          { id: '{{ $service->id }}', name: '{{ $service->name }}' },
        @endforeach
      ];
      
      console.log('Loading existing services:', existingServices);
      
      // Add rows sequentially with proper timing
      let loadIndex = 0;
      function loadNextService() {
        if (loadIndex >= existingServices.length) return;
        
        const service = existingServices[loadIndex];
        loadIndex++;
        
        // Add a service row
        btnAdd?.click();
        
        // Wait for Select2 to initialize
        setTimeout(() => {
          const rows = container.querySelectorAll('.service-row');
          const lastRow = rows[rows.length - 1];
          
          if (lastRow) {
            const select = lastRow.querySelector('.service-select');
            
            if (select) {
              // Set value using Select2 if available
              if (typeof $ !== 'undefined' && $.fn.select2 && $(select).hasClass('select2-hidden-accessible')) {
                $(select).val(service.id).trigger('change');
                console.log('✓ Pre-populated service (Select2):', select.name, '=', service.id, '(' + service.name + ')');
              } else {
                // Fallback to native select
                select.value = service.id;
                console.log('✓ Pre-populated service (native):', select.name, '=', select.value, '(' + service.name + ')');
              }
            }
          }
          
          // Load next service
          loadNextService();
        }, 150);
      }
      
      // Start loading after DOM is ready
      setTimeout(() => {
        loadNextService();
      }, 200);
    @endif

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

    /* Modern Radio Button Styles */
    .modern-radio-card {
      position: relative;
      flex: 1;
      min-width: 140px;
    }

    .modern-radio-input {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    .modern-radio-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px 20px;
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      background: white;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      font-size: 0.95rem;
      user-select: none;
      position: relative;
      overflow: hidden;
    }

    .modern-radio-label:hover {
      border-color: #3b82f6;
      background: #f0f9ff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .modern-radio-label i {
      font-size: 1.1rem;
      transition: all 0.3s ease;
    }

    .modern-radio-input:checked + .modern-radio-label {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border-color: #2563eb;
      color: white;
      box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
      transform: translateY(-2px);
    }

    .modern-radio-input:checked + .modern-radio-label i {
      transform: scale(1.2);
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    /* Checkmark indicator */
    .modern-radio-input:checked + .modern-radio-label::after {
      content: '\f26b';
      font-family: 'bootstrap-icons';
      position: absolute;
      top: 6px;
      right: 8px;
      font-size: 0.75rem;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: checkmark-pop 0.3s ease;
    }

    @keyframes checkmark-pop {
      0% {
        transform: scale(0);
        opacity: 0;
      }
      50% {
        transform: scale(1.2);
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* Status-specific colors */
    .status-done .modern-radio-input:checked + .modern-radio-label {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-color: #059669;
    }

    .status-done .modern-radio-label:hover {
      border-color: #10b981;
      background: #f0fdf4;
    }

    .status-pending .modern-radio-input:checked + .modern-radio-label {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      border-color: #d97706;
    }

    .status-pending .modern-radio-label:hover {
      border-color: #f59e0b;
      background: #fffbeb;
    }

    .status-not-agreed .modern-radio-input:checked + .modern-radio-label {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      border-color: #dc2626;
    }

    .status-not-agreed .modern-radio-label:hover {
      border-color: #ef4444;
      background: #fef2f2;
    }

    /* Dark Mode Styles */
    [data-theme="dark"] .modern-radio-label {
      background: #1f2937;
      border-color: #374151;
      color: #e5e7eb;
    }

    [data-theme="dark"] .modern-radio-label:hover {
      background: #374151;
      border-color: #3b82f6;
    }

    [data-theme="dark"] .modern-radio-input:checked + .modern-radio-label {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border-color: #2563eb;
      color: white;
    }

    [data-theme="dark"] .status-done .modern-radio-label:hover {
      background: #064e3b;
      border-color: #10b981;
    }

    [data-theme="dark"] .status-pending .modern-radio-label:hover {
      background: #78350f;
      border-color: #f59e0b;
    }

    [data-theme="dark"] .status-not-agreed .modern-radio-label:hover {
      background: #7f1d1d;
      border-color: #ef4444;
    }

    /* Responsive */
    @media (max-width: 576px) {
      .modern-radio-card {
        min-width: 100%;
      }
      
      .modern-radio-label {
        padding: 10px 16px;
        font-size: 0.9rem;
      }
    }

    /* Custom File Upload Styles */
    .custom-file-upload {
      position: relative;
      border: 2px dashed #cbd5e1;
      border-radius: 12px;
      padding: 30px;
      background: #f8fafc;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
    }

    .custom-file-upload:hover {
      border-color: #3b82f6;
      background: #eff6ff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .custom-file-upload.dragover {
      border-color: #3b82f6;
      background: #dbeafe;
      transform: scale(1.02);
    }

    .custom-file-input {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
      cursor: pointer;
    }

    .file-upload-content {
      pointer-events: none;
    }

    .file-upload-icon {
      margin-bottom: 16px;
    }

    .file-upload-icon i {
      font-size: 3rem;
      color: #64748b;
      transition: all 0.3s ease;
    }

    .custom-file-upload:hover .file-upload-icon i {
      color: #3b82f6;
      transform: translateY(-4px);
    }

    .file-upload-text {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .file-upload-title {
      font-size: 1rem;
      font-weight: 600;
      color: #1e293b;
    }

    .file-upload-subtitle {
      font-size: 0.875rem;
      color: #64748b;
    }

    .file-selected-info {
      padding: 16px;
      background: white;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
    }

    .file-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .file-icon i {
      font-size: 1.5rem;
      color: white;
    }

    .file-name {
      font-weight: 600;
      color: #1e293b;
      font-size: 0.95rem;
      word-break: break-word;
    }

    .file-size {
      font-size: 0.875rem;
      color: #64748b;
      margin-top: 2px;
    }

    .btn-remove-file {
      width: 32px;
      height: 32px;
      border: none;
      background: #fee2e2;
      color: #dc2626;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .btn-remove-file:hover {
      background: #fecaca;
      transform: scale(1.1);
    }

    .btn-remove-file i {
      font-size: 0.875rem;
    }

    .current-file-info {
      padding: 12px 16px;
      background: #f0f9ff;
      border-radius: 8px;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
    }

    .current-file-info i {
      font-size: 1.1rem;
      color: #3b82f6;
    }

    /* Dark Mode */
    [data-theme="dark"] .custom-file-upload {
      background: #1f2937;
      border-color: #374151;
    }

    [data-theme="dark"] .custom-file-upload:hover {
      background: #374151;
      border-color: #3b82f6;
    }

    [data-theme="dark"] .custom-file-upload.dragover {
      background: #1e3a8a;
    }

    [data-theme="dark"] .file-upload-icon i {
      color: #9ca3af;
    }

    [data-theme="dark"] .custom-file-upload:hover .file-upload-icon i {
      color: #60a5fa;
    }

    [data-theme="dark"] .file-upload-title {
      color: #e5e7eb;
    }

    [data-theme="dark"] .file-upload-subtitle {
      color: #9ca3af;
    }

    [data-theme="dark"] .file-selected-info {
      background: #374151;
      border-color: #4b5563;
    }

    [data-theme="dark"] .file-name {
      color: #e5e7eb;
    }

    [data-theme="dark"] .file-size {
      color: #9ca3af;
    }

    [data-theme="dark"] .current-file-info {
      background: #1e3a8a;
      color: #e5e7eb;
    }

    [data-theme="dark"] .btn-remove-file {
      background: #7f1d1d;
      color: #fca5a5;
    }

    [data-theme="dark"] .btn-remove-file:hover {
      background: #991b1b;
    }

    /* Responsive */
    @media (max-width: 576px) {
      .custom-file-upload {
        padding: 20px;
      }

      .file-upload-icon i {
        font-size: 2.5rem;
      }

      .file-upload-title {
        font-size: 0.9rem;
      }

      .file-upload-subtitle {
        font-size: 0.8rem;
      }
    }

    /* Toggle Survey Button */
    .toggle-survey-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 500;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
    }

    .toggle-survey-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .toggle-survey-btn:active {
      transform: translateY(0);
    }

    .toggle-survey-btn .toggle-icon {
      transition: transform 0.3s ease;
      font-size: 0.9rem;
    }

    .toggle-survey-btn.active .toggle-icon {
      transform: rotate(180deg);
    }

    .toggle-text {
      font-weight: 600;
    }

    /* Survey fields animation */
    #survey-fields {
      overflow: hidden;
      transition: all 0.4s ease;
    }

    /* Dark mode */
    [data-theme="dark"] .toggle-survey-btn {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    [data-theme="dark"] .toggle-survey-btn:hover {
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    /* Professional Date & Time Picker Styles */
    .date-time-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .date-time-icon {
      position: absolute;
      left: 16px;
      font-size: 1.1rem;
      color: #64748b;
      pointer-events: none;
      z-index: 2;
      transition: all 0.3s ease;
    }

    .date-time-input {
      padding-left: 48px !important;
      padding-right: 16px !important;
      height: 45px;
      border-radius: 10px;
      border: 2px solid #e2e8f0;
      background: white;
      font-size: 0.95rem;
      font-weight: 500;
      color: #1e293b;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    /* Hide native calendar icon */
    .date-time-input::-webkit-calendar-picker-indicator {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      padding: 0;
      margin: 0;
      opacity: 0;
      cursor: pointer;
    }

    .date-time-input::-webkit-datetime-edit {
      padding: 0;
    }

    /* Focus and Hover States */
    .date-time-input:hover {
      border-color: #3b82f6;
      background: #f8fafc;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .date-time-input:hover + .date-time-icon,
    .date-time-wrapper:hover .date-time-icon {
      color: #3b82f6;
      transform: scale(1.1);
    }

    .date-time-input:focus {
      border-color: #3b82f6;
      background: white;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    .date-time-input:focus + .date-time-icon,
    .date-time-wrapper:has(.date-time-input:focus) .date-time-icon {
      color: #3b82f6;
      transform: scale(1.15);
    }

    /* Placeholder styling */
    .date-time-input:invalid {
      color: #94a3b8;
    }

    /* Selected date/time styling */
    .date-time-input:valid {
      color: #1e293b;
      font-weight: 600;
    }

    /* Make entire wrapper clickable */
    .date-time-wrapper {
      cursor: pointer;
    }

    .date-time-wrapper::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      cursor: pointer;
      z-index: 1;
    }

    /* Dark Mode */
    [data-theme="dark"] .date-time-input {
      background: #1f2937;
      border-color: #374151;
      color: #e5e7eb;
    }

    [data-theme="dark"] .date-time-input:hover {
      background: #374151;
      border-color: #3b82f6;
    }

    [data-theme="dark"] .date-time-input:focus {
      background: #1f2937;
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }

    [data-theme="dark"] .date-time-icon {
      color: #9ca3af;
    }

    [data-theme="dark"] .date-time-input:hover + .date-time-icon,
    [data-theme="dark"] .date-time-wrapper:hover .date-time-icon {
      color: #60a5fa;
    }

    [data-theme="dark"] .date-time-input:focus + .date-time-icon,
    [data-theme="dark"] .date-time-wrapper:has(.date-time-input:focus) .date-time-icon {
      color: #60a5fa;
    }

    [data-theme="dark"] .date-time-input:invalid {
      color: #6b7280;
    }

    [data-theme="dark"] .date-time-input:valid {
      color: #e5e7eb;
    }

    /* Different icons colors for different field types */
    .date-time-wrapper:has(input[name="booking_date"]) .date-time-icon {
      color: #8b5cf6;
    }

    .date-time-wrapper:has(input[name="start_date"]) .date-time-icon {
      color: #10b981;
    }

    .date-time-wrapper:has(input[name="end_date"]) .date-time-icon {
      color: #ef4444;
    }

    .date-time-wrapper:has(input[name="start_time"]) .date-time-icon,
    .date-time-wrapper:has(input[name="schedule_time"]) .date-time-icon {
      color: #f59e0b;
    }

    /* Animation on value change */
    @keyframes date-value-set {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.02);
      }
      100% {
        transform: scale(1);
      }
    }

    .date-time-input:valid {
      animation: date-value-set 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .date-time-input {
        height: 42px;
        font-size: 0.9rem;
      }

      .date-time-icon {
        font-size: 1rem;
        left: 14px;
      }

      .date-time-input {
        padding-left: 42px !important;
      }
    }
  </style>

  <script>
  // Video Survey Fields Management
  document.addEventListener('DOMContentLoaded', function() {
    const surveyTypeRadios = document.querySelectorAll('.survey-type-radio');
    const surveyTypeFieldsDivs = document.querySelectorAll('.survey-type-fields');
    const toggleSurveyBtn = document.getElementById('toggleSurveyBtn');
    const surveyFields = document.getElementById('survey-fields');

    // Toggle Survey Section
    if (toggleSurveyBtn && surveyFields) {
      // Set initial state
      const isVisible = surveyFields.style.display === 'block';
      if (isVisible) {
        toggleSurveyBtn.classList.add('active');
        toggleSurveyBtn.querySelector('.toggle-text').textContent = 'No';
      }

      toggleSurveyBtn.addEventListener('click', function() {
        const isCurrentlyVisible = surveyFields.style.display === 'block';
        
        if (isCurrentlyVisible) {
          // Hide survey fields
          surveyFields.style.display = 'none';
          this.classList.remove('active');
          this.querySelector('.toggle-text').textContent = 'Yes';
        } else {
          // Show survey fields
          surveyFields.style.display = 'block';
          this.classList.add('active');
          this.querySelector('.toggle-text').textContent = 'No';
        }
      });
    }

    // Show/hide specific fields based on survey type
    function handleSurveyTypeChange() {
      const selectedType = document.querySelector('.survey-type-radio:checked');
      
      // Hide all type-specific fields first
      surveyTypeFieldsDivs.forEach(div => div.style.display = 'none');
      
      if (selectedType) {
        const typeValue = selectedType.value;
        
        // Show the corresponding fields
        if (typeValue === 'video_call') {
          document.getElementById('video_call_fields').style.display = 'block';
        } else if (typeValue === 'video_recording') {
          document.getElementById('video_recording_fields').style.display = 'block';
        } else if (typeValue === 'list') {
          document.getElementById('list_fields').style.display = 'block';
        }
      }
    }

    // Attach change event to all survey type radios
    surveyTypeRadios.forEach(radio => {
      radio.addEventListener('change', handleSurveyTypeChange);
    });

    // Initialize on page load if editing
    handleSurveyTypeChange();

    // Custom File Upload Handler
    const fileInput = document.getElementById('survey_video');
    const uploadArea = document.getElementById('video-upload-area');
    const uploadContent = uploadArea?.querySelector('.file-upload-content');
    const selectedInfo = uploadArea?.querySelector('.file-selected-info');
    const removeBtn = uploadArea?.querySelector('.btn-remove-file');

    if (fileInput && uploadArea) {
      // File select handler
      fileInput.addEventListener('change', function(e) {
        handleFileSelect(this.files[0]);
      });

      // Drag and drop handlers
      uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('dragover');
      });

      uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
      });

      uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          handleFileSelect(files[0]);
        }
      });

      // Remove file handler
      if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          fileInput.value = '';
          uploadContent.style.display = 'block';
          selectedInfo.style.display = 'none';
        });
      }
    }

    function handleFileSelect(file) {
      if (!file) return;

      // Validate file type
      const validTypes = ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv'];
      if (!validTypes.includes(file.type)) {
        alert('Please select a valid video file (MP4, AVI, MOV, WMV)');
        fileInput.value = '';
        return;
      }

      // Validate file size (100MB = 104857600 bytes)
      if (file.size > 104857600) {
        alert('File size must be less than 100MB');
        fileInput.value = '';
        return;
      }

      // Display file info
      const fileName = selectedInfo.querySelector('.file-name');
      const fileSize = selectedInfo.querySelector('.file-size');
      
      fileName.textContent = file.name;
      fileSize.textContent = formatFileSize(file.size);
      
      uploadContent.style.display = 'none';
      selectedInfo.style.display = 'block';
    }

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Make date/time pickers clickable anywhere on wrapper
    const dateTimeWrappers = document.querySelectorAll('.date-time-wrapper');
    
    dateTimeWrappers.forEach(wrapper => {
      const input = wrapper.querySelector('.date-time-input');
      
      if (input) {
        // Click on wrapper opens the picker
        wrapper.addEventListener('click', function(e) {
          // Don't trigger if already clicking on input
          if (e.target !== input) {
            input.showPicker ? input.showPicker() : input.focus();
          }
        });

        // Make wrapper tab-accessible
        wrapper.setAttribute('tabindex', '-1');
        
        // Prevent wrapper from stealing focus
        wrapper.addEventListener('focus', function() {
          input.focus();
        });
      }
    });
  });
  </script>

  @endpush
</x-admin.layouts.app>

{{-- resources/views/admin/bookings/show.blade.php --}}
<x-admin.layouts.app>
  @php
    // Soft badge palette (matches earlier pages)
    $badgeClass = match($booking->status){
      'pending'     => 'badge-pending',
      'confirmed'   => 'badge-confirmed',
      'in_progress' => 'badge-inprog',
      'completed'   => 'badge-completed',
      'cancelled'   => 'badge-cancelled',
      default       => 'badge-soft'
    };
  @endphp

  {{-- Page styles for soft badges (reuse across pages if you like) --}}
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .badge-soft{background:rgba(0,0,0,.06)}
    .badge-pending{background:#fff7ed;color:#9a3412}
    .badge-confirmed{background:#ecfeff;color:#155e75}
    .badge-inprog{background:#eef2ff;color:#3730a3}
    .badge-completed{background:#ecfdf5;color:#065f46}
    .badge-cancelled{background:#fef2f2;color:#991b1b}
    .mono{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New",monospace}
  </style>
  @endpush

  <div class="container-xxl py-3">

    {{-- Header --}}
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <h1 class="h3 mb-0">Booking Details</h1>
        <p class="text-secondary mb-0">{{ $booking->booking_reference }}</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-outline-secondary">
          <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
          <i class="bi bi-arrow-left me-2"></i>Back to Bookings
        </a>
      </div>
    </div>

    <div class="row g-4">
      {{-- Main column --}}
      <div class="col-12 col-lg-8 vstack gap-4">

        {{-- Booking Status --}}
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3">
              <div>
                <h5 class="card-title mb-1">Booking Status</h5>
                <div class="text-secondary small">{{ $booking->getStatusDescription() }}</div>
              </div>
              <span class="badge {{ $badgeClass }} px-3 py-2 text-capitalize">
                {{ str_replace('_',' ', $booking->status) }}
              </span>
            </div>

            {{-- Status Management --}}
            @if($booking->getAvailableStatusTransitions())
              <div class="border-top pt-3">
                <h6 class="mb-2">Update Status</h6>
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="d-flex gap-2 flex-wrap">
                  @csrf
                  <select name="status" class="form-select" style="max-width: 200px;">
                    <option value="">Select new status</option>
                    @foreach($booking->getAvailableStatusTransitions() as $status)
                      <option value="{{ $status }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                      </option>
                    @endforeach
                  </select>
                  <input type="text" name="notes" placeholder="Notes (optional)" class="form-control" style="max-width: 200px;">
                  <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </form>
              </div>
            @endif

            {{-- Quick Actions --}}
            <div class="border-top pt-3 mt-3">
              <h6 class="mb-2">Quick Actions</h6>
              <div class="d-flex gap-2 flex-wrap">
                @if($booking->canStart())
                  <form method="POST" action="{{ route('admin.bookings.start', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="bi bi-play-fill me-1"></i>Start Booking
                    </button>
                  </form>
                @endif

                @if($booking->canComplete())
                  <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="bi bi-check-circle-fill me-1"></i>Complete Booking
                    </button>
                  </form>
                @endif

                @if($booking->status === 'completed' && $booking->extra_hours == 0)
                  <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addExtraHoursModal">
                    <i class="bi bi-clock-fill me-1"></i>Add Extra Hours
                  </button>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Booking Information --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Booking Information</h5>
            <div class="row g-3">
              <div class="col-12 col-sm-6">
                <div class="text-secondary small">Booking Reference</div>
                <div class="mono">{{ $booking->booking_reference }}</div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="text-secondary small">Booking Date</div>
                <div>{{ $booking->booking_date->format('M d, Y') }}</div>
              </div>

              @if($booking->start_time)
                <div class="col-12 col-sm-6">
                  <div class="text-secondary small">Start Time</div>
                  <div>{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</div>
                </div>
              @endif

              @if($booking->estimated_hours)
                <div class="col-12 col-sm-6">
                  <div class="text-secondary small">Estimated Hours</div>
                  <div>{{ $booking->estimated_hours }} hours</div>
                </div>
              @endif

              @if($booking->actual_hours)
                <div class="col-12 col-sm-6">
                  <div class="text-secondary small">Actual Hours</div>
                  <div>{{ $booking->actual_hours }} hours</div>
                </div>
              @endif

              <div class="col-12">
                <div class="text-secondary small">Base Amount</div>
                <div class="fs-5 fw-semibold">£{{ number_format($booking->total_amount, 2) }}</div>
              </div>

              @if($booking->extra_hours && $booking->extra_hours > 0)
                <div class="col-12">
                  <div class="text-secondary small">Extra Hours ({{ $booking->extra_hours }} hours @ £{{ number_format($booking->extra_hours_rate, 2) }}/hour)</div>
                  <div class="fs-5 fw-semibold text-warning">+ £{{ number_format($booking->extra_hours_amount, 2) }}</div>
                </div>
              @endif

              <div class="col-12">
                <div class="text-secondary small">Total Amount</div>
                <div class="fs-4 fw-bold text-primary">£{{ number_format($booking->final_amount, 2) }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Job Details --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Job Details</h5>
            <div class="vstack gap-3">
              <div>
                <div class="text-secondary small">Job Description</div>
                <div>{{ $booking->job_description }}</div>
              </div>

              @if($booking->special_instructions)
                <div>
                  <div class="text-secondary small">Special Instructions</div>
                  <div>{{ $booking->special_instructions }}</div>
                </div>
              @endif

              @if($booking->driver_notes)
                <div>
                  <div class="text-secondary small">Driver Notes</div>
                  <div>{{ $booking->driver_notes }}</div>
                </div>
              @endif

              @if($booking->porter_notes)
                <div>
                  <div class="text-secondary small">Porter Notes</div>
                  <div>{{ $booking->porter_notes }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Location Details --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Location Details</h5>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="fw-medium mb-1">Pickup Location</div>
                <div>{{ $booking->pickup_address }}</div>
                @if($booking->pickup_postcode)
                  <div class="text-secondary small mt-1">{{ $booking->pickup_postcode }}</div>
                @endif
              </div>
              <div class="col-12 col-md-6">
                <div class="fw-medium mb-1">Delivery Location</div>
                <div>{{ $booking->delivery_address }}</div>
                @if($booking->delivery_postcode)
                  <div class="text-secondary small mt-1">{{ $booking->delivery_postcode }}</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Services --}}
        @if($booking->services->count() > 0)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">Services</h5>
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Service</th>
                      <th>Quantity</th>
                      <th>Rate</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($booking->services as $service)
                      <tr>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->pivot->quantity }}</td>
                        <td>£{{ number_format($service->pivot->unit_rate, 2) }}</td>
                        <td class="fw-medium">£{{ number_format($service->pivot->total_amount, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot class="table-light">
                    <tr>
                      <td colspan="3" class="fw-medium">Total</td>
                      <td class="fw-medium">£{{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        @endif

      </div>

      {{-- Sidebar --}}
      <aside class="col-12 col-lg-4 vstack gap-4">

        {{-- Customer --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Customer</h5>
            <div class="vstack gap-2">
              <div>
                <div class="text-secondary small">Name</div>
                <div>{{ $booking->customer->name }}</div>
              </div>
              <div>
                <div class="text-secondary small">Email</div>
                <div>{{ $booking->customer->email }}</div>
              </div>
              <div>
                <div class="text-secondary small">Phone</div>
                <div>{{ $booking->customer->phone }}</div>
              </div>
              @if($booking->customer->company_name)
                <div>
                  <div class="text-secondary small">Company</div>
                  <div>{{ $booking->customer->company_name }}</div>
                </div>
              @endif
            </div>
            <div class="mt-3">
              <a href="{{ route('admin.customers.show', $booking->customer) }}" class="link-primary small fw-medium">
                View Customer Details →
              </a>
            </div>
          </div>
        </div>

        {{-- Assignment --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Assignment</h5>
            <div class="vstack gap-3">
              <div>
                <div class="text-secondary small">Driver</div>
                @if($booking->driver)
                  <div>{{ $booking->driver->name }}</div>
                  <div class="small text-secondary">{{ $booking->driver->phone }}</div>
                @else
                  <div class="text-secondary">Not assigned</div>
                @endif
              </div>

              <div>
                <div class="text-secondary small">Porter(s)</div>
                @if($booking->porter_names)
                  <div>{{ $booking->porter_names }}</div>
                  @if($booking->hasMultiplePorters())
                    <div class="small text-secondary">Multiple porters assigned</div>
                  @elseif($booking->porter)
                    <div class="small text-secondary">{{ $booking->porter->phone }}</div>
                  @endif
                @else
                  <div class="text-secondary">Not assigned</div>
                @endif
              </div>

              <div>
                <div class="text-secondary small">Vehicle</div>
                @if($booking->vehicle)
                  <div>{{ $booking->vehicle->registration_number }}</div>
                  <div class="small text-secondary">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                @else
                  <div class="text-secondary">Not assigned</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Company Commission --}}
        @if($booking->is_company_booking)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">Company Commission</h5>
              <div class="vstack gap-2">
                <div>
                  <div class="text-secondary small">Commission Rate</div>
                  <div>{{ $booking->company_commission_rate }}%</div>
                </div>
                <div>
                  <div class="text-secondary small">Commission Amount</div>
                  <div class="fw-medium">£{{ number_format($booking->company_commission_amount, 2) }}</div>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Extra Hours --}}
        @if($booking->extra_hours && $booking->extra_hours > 0)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3">Extra Hours</h5>
              <div class="vstack gap-2">
                <div>
                  <div class="text-secondary small">Extra Hours</div>
                  <div>{{ $booking->extra_hours }} hours</div>
                </div>
                <div>
                  <div class="text-secondary small">Rate per Hour</div>
                  <div>£{{ number_format($booking->extra_hours_rate, 2) }}</div>
                </div>
                <div>
                  <div class="text-secondary small">Extra Hours Total</div>
                  <div class="fw-medium">£{{ number_format($booking->extra_hours_amount, 2) }}</div>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Timeline --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">Timeline</h5>
            <div class="vstack gap-2">
              <div>
                <div class="text-secondary small">Created</div>
                <div>{{ $booking->created_at->format('M d, Y g:i A') }}</div>
                <div class="small text-secondary">by {{ $booking->createdBy->name }}</div>
              </div>
              @if($booking->started_at)
                <div>
                  <div class="text-secondary small">Started</div>
                  <div>{{ $booking->started_at->format('M d, Y g:i A') }}</div>
                </div>
              @endif
              @if($booking->completed_at)
                <div>
                  <div class="text-secondary small">Completed</div>
                  <div>{{ $booking->completed_at->format('M d, Y g:i A') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>

      </aside>
    </div>
  </div>

  {{-- Extra Hours Modal --}}
  <div class="modal fade" id="addExtraHoursModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Extra Hours</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.bookings.extra-hours', $booking) }}">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="extra_hours" class="form-label">Extra Hours</label>
              <input type="number" min="1" class="form-control" id="extra_hours" name="extra_hours" required>
            </div>
            <div class="mb-3">
              <label for="extra_hours_rate" class="form-label">Rate per Hour</label>
              <input type="number" step="0.01" min="0" class="form-control" id="extra_hours_rate" name="extra_hours_rate" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Extra Hours</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-admin.layouts.app>

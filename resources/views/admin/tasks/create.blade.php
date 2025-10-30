{{-- resources/views/admin/tasks/create.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
  </style>
  @endpush

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
      <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
      </a>
      <div>
        <h1 class="h3 mb-1">Create New Task</h1>
        <p class="text-secondary mb-0">Add a new task for your team</p>
      </div>
    </div>

    <!-- Task Form -->
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card">
          <div class="card-body">
            <form method="POST" action="{{ route('admin.tasks.store') }}">
              @csrf

              <!-- Task Title -->
              <div class="mb-3">
                <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" 
                       class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title') }}" required>
                @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" 
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Provide detailed information about the task</div>
              </div>

              <div class="row">
                <!-- Category -->
                <div class="col-12 col-md-6 mb-3">
                  <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                  <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                    <option value="">Select Category</option>
                    <option value="finance" {{ old('category') == 'finance' ? 'selected' : '' }}>Finance</option>
                    <option value="operation" {{ old('category') == 'operation' ? 'selected' : '' }}>Operation</option>
                    <option value="hr" {{ old('category') == 'hr' ? 'selected' : '' }}>HR</option>
                    <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="it" {{ old('category') == 'it' ? 'selected' : '' }}>IT</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Priority -->
                <div class="col-12 col-md-6 mb-3">
                  <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                  <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                    <option value="">Select Priority</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                  </select>
                  @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <!-- Due Date -->
                <div class="col-12 col-md-6 mb-3">
                  <label for="due_date" class="form-label">Due Date</label>
                  <input type="date" name="due_date" id="due_date" 
                         class="form-control @error('due_date') is-invalid @enderror" 
                         value="{{ old('due_date') }}">
                  @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Status -->
                <div class="col-12 col-md-6 mb-3">
                  <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                  <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Responsible Person -->
              <div class="mb-3">
                <label for="responsible_person_id" class="form-label">Assign To</label>
                <select name="responsible_person_id" id="responsible_person_id" 
                        class="form-select @error('responsible_person_id') is-invalid @enderror">
                  <option value="">Select Staff Member</option>
                  @foreach($staffMembers as $staff)
                    <option value="{{ $staff->id }}" {{ old('responsible_person_id') == $staff->id ? 'selected' : '' }}>
                      {{ $staff->name }} ({{ ucfirst($staff->role) }})
                    </option>
                  @endforeach
                </select>
                @error('responsible_person_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Notes -->
              <div class="mb-4">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Add any additional notes or comments</div>
              </div>

              <!-- Actions -->
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-lg me-2"></i>Create Task
                </button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
                  Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Help/Info Sidebar -->
      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-info-circle me-2"></i>Task Information
            </h6>
            <div class="small text-secondary">
              <p><strong>Categories:</strong></p>
              <ul class="mb-3">
                <li><strong>Finance:</strong> Budget, invoices, payments</li>
                <li><strong>Operation:</strong> Daily operations, logistics</li>
                <li><strong>HR:</strong> Hiring, training, staff management</li>
                <li><strong>Marketing:</strong> Campaigns, promotions</li>
                <li><strong>IT:</strong> Technical tasks, system maintenance</li>
              </ul>

              <p><strong>Priority Levels:</strong></p>
              <ul class="mb-3">
                <li><strong>Urgent:</strong> Needs immediate attention</li>
                <li><strong>High:</strong> Important, complete soon</li>
                <li><strong>Medium:</strong> Standard priority</li>
                <li><strong>Low:</strong> Can be done later</li>
              </ul>

              <p><strong>Status:</strong></p>
              <ul class="mb-0">
                <li><strong>Pending:</strong> Not started yet</li>
                <li><strong>In Progress:</strong> Currently being worked on</li>
                <li><strong>Completed:</strong> Task finished</li>
                <li><strong>On Hold:</strong> Temporarily paused</li>
                <li><strong>Cancelled:</strong> Task cancelled</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-admin.layouts.app>


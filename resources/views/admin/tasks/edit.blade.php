{{-- resources/views/admin/tasks/edit.blade.php --}}
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
        <h1 class="h3 mb-1">Edit Task</h1>
        <p class="text-secondary mb-0">Update task details</p>
      </div>
    </div>

    <!-- Task Form -->
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card">
          <div class="card-body">
            <form method="POST" action="{{ route('admin.tasks.update', $task) }}">
              @csrf
              @method('PUT')

              <!-- Task Title -->
              <div class="mb-3">
                <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" 
                       class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $task->title) }}" required>
                @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" 
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
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
                    <option value="finance" {{ old('category', $task->category) == 'finance' ? 'selected' : '' }}>Finance</option>
                    <option value="operation" {{ old('category', $task->category) == 'operation' ? 'selected' : '' }}>Operation</option>
                    <option value="hr" {{ old('category', $task->category) == 'hr' ? 'selected' : '' }}>HR</option>
                    <option value="marketing" {{ old('category', $task->category) == 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="it" {{ old('category', $task->category) == 'it' ? 'selected' : '' }}>IT</option>
                    <option value="other" {{ old('category', $task->category) == 'other' ? 'selected' : '' }}>Other</option>
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
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                         value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                  @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Status -->
                <div class="col-12 col-md-6 mb-3">
                  <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                  <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ old('status', $task->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="cancelled" {{ old('status', $task->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                    <option value="{{ $staff->id }}" {{ old('responsible_person_id', $task->responsible_person_id) == $staff->id ? 'selected' : '' }}>
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
                          class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $task->notes) }}</textarea>
                @error('notes')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Add any additional notes or comments</div>
              </div>

              <!-- Actions -->
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-lg me-2"></i>Update Task
                </button>
                <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-outline-secondary">
                  Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Task Info Sidebar -->
      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="card mb-3">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-clock-history me-2"></i>Task History
            </h6>
            <div class="small text-secondary">
              <div class="mb-2">
                <strong>Created:</strong><br>
                {{ $task->created_at->format('M d, Y h:i A') }}
              </div>
              <div class="mb-2">
                <strong>Last Updated:</strong><br>
                {{ $task->updated_at->format('M d, Y h:i A') }}
              </div>
              <div>
                <strong>Created By:</strong><br>
                {{ $task->creator->name }}
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-info-circle me-2"></i>Quick Tips
            </h6>
            <div class="small text-secondary">
              <ul class="mb-0">
                <li>Update status to keep team informed</li>
                <li>Set realistic due dates</li>
                <li>Add notes for important updates</li>
                <li>Reassign if workload changes</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-admin.layouts.app>


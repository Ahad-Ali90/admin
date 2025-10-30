{{-- resources/views/admin/tasks/show.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .info-label{
      font-weight:600;
      color:#6c757d;
      margin-bottom:0.25rem;
    }
    .info-value{
      font-size:1.1rem;
    }
    .task-header{
      border-left: 4px solid;
      padding-left: 1rem;
    }
    .priority-urgent .task-header{border-left-color: #dc3545;}
    .priority-high .task-header{border-left-color: #fd7e14;}
    .priority-medium .task-header{border-left-color: #ffc107;}
    .priority-low .task-header{border-left-color: #0dcaf0;}
  </style>
  @endpush

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
      <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
      </a>
      <div class="flex-grow-1">
        <h1 class="h3 mb-1">Task Details</h1>
        <p class="text-secondary mb-0">View and manage task information</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-primary">
          <i class="bi bi-pencil me-2"></i>Edit Task
        </a>
        <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}"
              onsubmit="return confirm('Are you sure you want to delete this task?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    </div>

    <div class="row">
      <!-- Main Content -->
      <div class="col-12 col-lg-8">
        <!-- Task Header Card -->
        <div class="card mb-3 priority-{{ $task->priority }}">
          <div class="card-body">
            <div class="task-header">
              <h2 class="h4 mb-3">{{ $task->title }}</h2>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge {{ $task->getCategoryBadgeClass() }}">
                  <i class="bi bi-tag me-1"></i>{{ ucfirst($task->category) }}
                </span>
                <span class="badge {{ $task->getPriorityBadgeClass() }}">
                  <i class="bi bi-flag me-1"></i>{{ ucfirst($task->priority) }} Priority
                </span>
                <span class="badge {{ $task->getStatusBadgeClass() }}">
                  <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>{{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
                @if($task->isOverdue())
                  <span class="badge bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle me-1"></i>Overdue
                  </span>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Description Card -->
        @if($task->description)
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title mb-3">
                <i class="bi bi-file-text me-2"></i>Description
              </h5>
              <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $task->description }}</p>
            </div>
          </div>
        @endif

        <!-- Notes Card -->
        @if($task->notes)
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title mb-3">
                <i class="bi bi-sticky me-2"></i>Notes
              </h5>
              <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $task->notes }}</p>
            </div>
          </div>
        @endif

        <!-- Quick Status Update -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bi bi-arrow-repeat me-2"></i>Quick Status Update
            </h5>
            <form method="POST" action="{{ route('admin.tasks.update-status', $task) }}" class="d-flex gap-2 align-items-end">
              @csrf
              <div class="flex-grow-1">
                <label for="quick_status" class="form-label">Change Status</label>
                <select name="status" id="quick_status" class="form-select">
                  <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                  <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="on_hold" {{ $task->status == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                  <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Update
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <!-- Task Details -->
        <div class="card mb-3">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-info-circle me-2"></i>Task Details
            </h6>

            <!-- Due Date -->
            <div class="mb-3">
              <div class="info-label">
                <i class="bi bi-calendar-event me-1"></i>Due Date
              </div>
              <div class="info-value">
                @if($task->due_date)
                  {{ $task->due_date->format('M d, Y') }}
                  <br>
                  <small class="text-secondary">
                    ({{ $task->due_date->diffForHumans() }})
                  </small>
                @else
                  <span class="text-muted">No due date set</span>
                @endif
              </div>
            </div>

            <!-- Assigned To -->
            <div class="mb-3">
              <div class="info-label">
                <i class="bi bi-person me-1"></i>Assigned To
              </div>
              <div class="info-value">
                @if($task->responsiblePerson)
                  <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width:32px;height:32px;font-size:0.9rem;">
                      {{ strtoupper(substr($task->responsiblePerson->name, 0, 1)) }}
                    </div>
                    <div>
                      {{ $task->responsiblePerson->name }}
                      <br>
                      <small class="text-secondary">{{ ucfirst($task->responsiblePerson->role) }}</small>
                    </div>
                  </div>
                @else
                  <span class="text-muted">Not assigned</span>
                @endif
              </div>
            </div>

            <!-- Created By -->
            <div class="mb-0">
              <div class="info-label">
                <i class="bi bi-person-plus me-1"></i>Created By
              </div>
              <div class="info-value">
                {{ $task->creator->name }}
                <br>
                <small class="text-secondary">
                  {{ $task->created_at->format('M d, Y h:i A') }}
                </small>
              </div>
            </div>
          </div>
        </div>

        <!-- Timeline -->
        <div class="card">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <i class="bi bi-clock-history me-2"></i>Timeline
            </h6>
            <div class="small">
              <div class="d-flex gap-2 mb-3">
                <div class="text-primary">
                  <i class="bi bi-plus-circle-fill"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-semibold">Task Created</div>
                  <div class="text-secondary">
                    {{ $task->created_at->format('M d, Y h:i A') }}
                    <br>
                    <small>({{ $task->created_at->diffForHumans() }})</small>
                  </div>
                </div>
              </div>

              @if($task->updated_at != $task->created_at)
                <div class="d-flex gap-2">
                  <div class="text-warning">
                    <i class="bi bi-pencil-fill"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="fw-semibold">Last Updated</div>
                    <div class="text-secondary">
                      {{ $task->updated_at->format('M d, Y h:i A') }}
                      <br>
                      <small>({{ $task->updated_at->diffForHumans() }})</small>
                    </div>
                  </div>
                </div>
              @endif

              @if($task->status == 'completed')
                <div class="d-flex gap-2 mt-3">
                  <div class="text-success">
                    <i class="bi bi-check-circle-fill"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="fw-semibold">Task Completed</div>
                    <div class="text-secondary">
                      {{ $task->updated_at->format('M d, Y h:i A') }}
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-admin.layouts.app>


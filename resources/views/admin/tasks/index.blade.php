{{-- resources/views/admin/tasks/index.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <style>
    .card{border:0;box-shadow:0 2px 10px rgba(16,24,40,.06)}
    .task-card{
      transition: transform 0.2s;
      border-left: 4px solid transparent;
    }
    .task-card:hover{
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(16,24,40,.12);
    }
    .task-card.priority-urgent{border-left-color: #dc3545;}
    .task-card.priority-high{border-left-color: #fd7e14;}
    .task-card.priority-medium{border-left-color: #ffc107;}
    .task-card.priority-low{border-left-color: #0dcaf0;}
    .overdue-badge{
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }
  </style>
  @endpush

  <div class="container-xxl py-3">
    <!-- Header -->
    <div class="d-flex flex-column gap-2 flex-md-row align-items-md-center justify-content-md-between mb-4">
      <div>
        <h1 class="h3 mb-1">Task Tracker</h1>
        <p class="text-secondary mb-0">Manage and track team tasks efficiently</p>
      </div>
      <div class="w-100 w-md-auto">
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary w-100 w-md-auto">
          <i class="bi bi-plus-lg me-2"></i> New Task
        </a>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET">
          <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
              <label for="search" class="form-label">Search</label>
              <input type="text" name="search" id="search" value="{{ request('search') }}"
                     class="form-control" placeholder="Task name or description">
            </div>
            <div class="col-12 col-md-6 col-lg-2">
              <label for="category" class="form-label">Category</label>
              <select name="category" id="category" class="form-select">
                <option value="">All Categories</option>
                <option value="finance" {{ request('category') == 'finance' ? 'selected' : '' }}>Finance</option>
                <option value="operation" {{ request('category') == 'operation' ? 'selected' : '' }}>Operation</option>
                <option value="hr" {{ request('category') == 'hr' ? 'selected' : '' }}>HR</option>
                <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                <option value="it" {{ request('category') == 'it' ? 'selected' : '' }}>IT</option>
                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
              </select>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
              <label for="priority" class="form-label">Priority</label>
              <select name="priority" id="priority" class="form-select">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
              </select>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
              <label for="status" class="form-label">Status</label>
              <select name="status" id="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
              <label for="responsible_person" class="form-label">Assigned To</label>
              <select name="responsible_person" id="responsible_person" class="form-select">
                <option value="">All Staff</option>
                @foreach($staffMembers as $staff)
                  <option value="{{ $staff->id }}" {{ request('responsible_person') == $staff->id ? 'selected' : '' }}>
                    {{ $staff->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-12 col-md-6 col-lg-1 d-flex align-items-end">
              <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-outline-secondary w-100">
                  <i class="bi bi-funnel"></i>
                </button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-danger">
                  <i class="bi bi-x-circle"></i>
                </a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Tasks Grid -->
    @if($tasks->count() > 0)
      <div class="row g-3 mb-4">
        @foreach($tasks as $task)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card task-card priority-{{ $task->priority }} h-100">
              <div class="card-body">
                <!-- Task Header -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <h5 class="card-title mb-0">{{ $task->title }}</h5>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ route('admin.tasks.show', $task) }}">
                          <i class="bi bi-eye me-2"></i>View Details
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="{{ route('admin.tasks.edit', $task) }}">
                          <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-trash me-2"></i>Delete
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </div>

                <!-- Task Description -->
                @if($task->description)
                  <p class="text-secondary small mb-3">
                    {{ Str::limit($task->description, 100) }}
                  </p>
                @endif

                <!-- Task Meta -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge {{ $task->getCategoryBadgeClass() }}">
                    {{ ucfirst($task->category) }}
                  </span>
                  <span class="badge {{ $task->getPriorityBadgeClass() }}">
                    {{ ucfirst($task->priority) }}
                  </span>
                  <span class="badge {{ $task->getStatusBadgeClass() }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                  </span>
                  @if($task->isOverdue())
                    <span class="badge overdue-badge">
                      <i class="bi bi-exclamation-triangle me-1"></i>Overdue
                    </span>
                  @endif
                </div>

                <!-- Task Info -->
                <div class="small text-secondary mb-2">
                  <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-calendar me-2"></i>
                    <span>
                      Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                    </span>
                  </div>
                  @if($task->responsiblePerson)
                    <div class="d-flex align-items-center">
                      <i class="bi bi-person me-2"></i>
                      <span>{{ $task->responsiblePerson->name }}</span>
                    </div>
                  @endif
                </div>

                <!-- Quick Actions -->
                <div class="d-flex gap-2 mt-3">
                  <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                    <i class="bi bi-eye me-1"></i>View
                  </a>
                  <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <div class="small text-secondary order-2 order-md-1">
              Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
            </div>
            <div class="order-1 order-md-2 w-100 w-md-auto overflow-auto">
              {{ $tasks->links() }}
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="card">
        <div class="card-body text-center py-5">
          <div class="display-6 text-secondary mb-2"><i class="bi bi-list-task"></i></div>
          <h5 class="mb-1">No tasks found</h5>
          <p class="text-secondary mb-3">Get started by creating a new task for your team.</p>
          <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> New Task
          </a>
        </div>
      </div>
    @endif
  </div>
</x-admin.layouts.app>


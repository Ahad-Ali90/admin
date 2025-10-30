{{-- resources/views/admin/tasks/manage.blade.php --}}
<x-admin.layouts.app>
  @push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --info: #3b82f6;
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-600: #4b5563;
      --gray-700: #374151;
      --gray-800: #1f2937;
    }

    .task-manager {
      background: var(--gray-50);
      min-height: calc(100vh - 120px);
    }

    /* Header */
    .task-header {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.5rem;
    }

    .view-toggle {
      display: inline-flex;
      gap: 0.5rem;
    }

    .view-toggle-btn {
      padding: 0.5rem 1rem;
      border: 2px solid var(--gray-300);
      background: transparent;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--gray-600);
    }

    .view-toggle-btn.active {
      background: transparent;
      color: var(--primary);
      border-color: var(--primary);
    }

    .view-toggle-btn:hover {
      color: var(--primary);
      border-color: var(--primary);
      transform: translateY(-1px);
    }

    .view-toggle-btn i {
      margin-right: 0.5rem;
    }

    /* Filters */
    .filters-bar {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.5rem;
    }

    .filter-group {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      align-items: center;
    }

    .filter-group select,
    .filter-group input {
      border-radius: 8px;
      border: 1px solid var(--gray-300);
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }

    /* Kanban Board - 3 Columns Only */
    .kanban-board {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .kanban-column {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      min-height: 500px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .kanban-column.to-do {
      border-top: 4px solid var(--gray-600);
    }

    .kanban-column.in-progress {
      border-top: 4px solid var(--info);
    }

    .kanban-column.completed {
      border-top: 4px solid var(--success);
    }

    .kanban-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--gray-100);
    }

    .kanban-title {
      font-weight: 700;
      font-size: 1rem;
      color: var(--gray-800);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .kanban-count {
      background: var(--gray-100);
      color: var(--gray-700);
      border-radius: 20px;
      padding: 0.25rem 0.75rem;
      font-size: 0.75rem;
      font-weight: 700;
      min-width: 28px;
      text-align: center;
    }

    .kanban-tasks {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      min-height: 100px;
    }

    /* Task Card */
    .task-card {
      background: var(--gray-50);
      border-radius: 10px;
      padding: 1.25rem;
      cursor: pointer;
      transition: all 0.2s;
      border: 2px solid transparent;
      position: relative;
    }

    .task-card:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      transform: translateY(-3px);
      border-color: var(--primary);
    }

    .task-card.dragging {
      opacity: 0.5;
    }

    .task-card.priority-urgent {
      border-left: 4px solid #dc3545;
    }

    .task-card.priority-high {
      border-left: 4px solid #fd7e14;
    }

    .task-card.priority-medium {
      border-left: 4px solid #ffc107;
    }

    .task-card.priority-low {
      border-left: 4px solid #0dcaf0;
    }

    .task-title {
      font-weight: 600;
      font-size: 1rem;
      margin-bottom: 0.75rem;
      color: var(--gray-800);
      line-height: 1.5;
    }

    .task-description {
      font-size: 0.85rem;
      color: var(--gray-600);
      margin-bottom: 1rem;
      line-height: 1.6;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .task-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .task-badge {
      font-size: 0.7rem;
      padding: 0.35rem 0.65rem;
      border-radius: 6px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border: none;
      background: transparent !important;
    }

    /* Status Badge Colors - Text Only */
    .task-badge.bg-secondary {
      color: #6c757d;
    }

    .task-badge.bg-primary {
      color: #4f46e5;
    }

    .task-badge.bg-success {
      color: #10b981;
    }

    .task-badge.bg-info {
      color: #3b82f6;
    }

    .task-badge.bg-warning {
      color: #f59e0b;
    }

    .task-badge.bg-danger {
      color: #ef4444;
    }

    .task-badge.bg-dark {
      color: #1f2937;
    }

    .task-badge.text-white {
      color: #ef4444 !important;
    }

    .task-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 0.75rem;
      border-top: 1px solid var(--gray-200);
      font-size: 0.8rem;
    }

    .task-assignee {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .task-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      color: white;
      background: var(--primary);
    }

    .task-due-date {
      font-size: 0.75rem;
      color: var(--gray-600);
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .task-due-date.overdue {
      color: var(--danger);
      font-weight: 700;
    }

    /* Beautiful Table List View */
    .task-table-container {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .task-table {
      width: 100%;
      border-collapse: collapse;
    }

    .task-table thead {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
    }

    .task-table th {
      padding: 1.25rem 1.5rem;
      text-align: left;
      font-weight: 700;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .task-table tbody tr {
      border-bottom: 1px solid var(--gray-200);
      transition: all 0.2s;
      cursor: pointer;
    }

    .task-table tbody tr:hover {
      background: var(--gray-50);
      transform: scale(1.01);
    }

    .task-table td {
      padding: 1.25rem 1.5rem;
      vertical-align: middle;
    }

    .table-task-title {
      font-weight: 600;
      color: var(--gray-800);
      font-size: 0.95rem;
      margin-bottom: 0.25rem;
    }

    .table-task-desc {
      font-size: 0.8rem;
      color: var(--gray-600);
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .priority-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 0.5rem;
    }

    .priority-dot.urgent { background: #dc3545; }
    .priority-dot.high { background: #fd7e14; }
    .priority-dot.medium { background: #ffc107; }
    .priority-dot.low { background: #0dcaf0; }

    .assignee-cell {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    /* Modals */
    .modal-content {
      border-radius: 16px;
      border: none;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
      max-height: 85vh;
      display: flex;
      flex-direction: column;
    }

    .modal-header {
      border-bottom: 1px solid var(--gray-200);
      padding: 1.5rem;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      border-radius: 16px 16px 0 0;
      flex-shrink: 0;
    }

    .modal-header .btn-close {
      filter: brightness(0) invert(1);
    }

    .modal-body {
      padding: 2rem;
      overflow-y: auto;
      flex: 1 1 auto;
      max-height: calc(85vh - 140px);
    }

    .modal-body::-webkit-scrollbar {
      width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
      background: var(--gray-100);
      border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: var(--gray-400);
      border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
      background: var(--gray-500);
    }

    .modal-footer {
      border-top: 1px solid var(--gray-200);
      padding: 1.5rem;
      background: var(--gray-50);
      flex-shrink: 0;
      border-radius: 0 0 16px 16px;
    }

    .form-label {
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
    }

    .form-control,
    .form-select {
      border-radius: 8px;
      border: 1px solid var(--gray-300);
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Buttons */
    .btn-primary-custom {
      background: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-primary-custom:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
    }

    .btn-secondary-custom {
      background: transparent;
      color: var(--gray-700);
      border: 2px solid var(--gray-300);
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-secondary-custom:hover {
      border-color: var(--gray-400);
      color: var(--gray-800);
    }

    /* Task Details Sidebar */
    .task-details-sidebar {
      position: fixed;
      right: -500px;
      top: 0;
      width: 500px;
      height: 100vh;
      background: white;
      box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
      transition: right 0.3s ease;
      z-index: 1050;
      overflow-y: auto;
    }

    .task-details-sidebar.open {
      right: 0;
    }

    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1049;
      display: none;
    }

    .sidebar-overlay.show {
      display: block;
    }

    .sidebar-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--gray-200);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
    }

    .sidebar-content {
      padding: 2rem;
    }

    .detail-section {
      margin-bottom: 2rem;
    }

    .detail-label {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--gray-600);
      margin-bottom: 0.5rem;
    }

    .detail-value {
      font-size: 1rem;
      color: var(--gray-800);
    }

    /* Loading */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .loading-overlay.show {
      display: flex;
    }

    .spinner {
      width: 56px;
      height: 56px;
      border: 5px solid var(--gray-200);
      border-top-color: var(--primary);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--gray-600);
    }

    .empty-state-icon {
      font-size: 5rem;
      margin-bottom: 1.5rem;
      opacity: 0.2;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .kanban-board {
        grid-template-columns: 1fr;
      }

      .task-details-sidebar {
        width: 100%;
        right: -100%;
      }
    }

    /* Dark Mode Support */
    [data-theme="dark"] .task-manager {
      background: var(--bg-color) !important;
    }

    [data-theme="dark"] .task-header,
    [data-theme="dark"] .filters-bar {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .task-header h2,
    [data-theme="dark"] .filters-bar label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .view-toggle-btn {
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .view-toggle-btn.active {
      color: var(--primary) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .view-toggle-btn:hover {
      color: var(--primary) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .kanban-column {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .kanban-header {
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .kanban-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .kanban-count {
      background: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .task-card {
      background: var(--surface-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .task-card:hover {
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .task-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .task-description {
      color: var(--text-color) !important;
      opacity: 0.8;
    }

    [data-theme="dark"] .task-footer {
      border-top-color: var(--border-color) !important;
    }

    [data-theme="dark"] .task-due-date {
      color: var(--text-color) !important;
      opacity: 0.8;
    }

    [data-theme="dark"] .task-table-container {
      background: var(--card-bg) !important;
    }

    [data-theme="dark"] .task-table thead {
      background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
    }

    [data-theme="dark"] .task-table tbody tr {
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .task-table tbody tr:hover {
      background: var(--hover-bg) !important;
    }

    [data-theme="dark"] .task-table td {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table-task-title {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .table-task-desc {
      color: var(--text-color) !important;
      opacity: 0.8;
    }

    [data-theme="dark"] .modal-content {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .modal-body {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .modal-footer {
      background: var(--surface-bg) !important;
      border-top-color: var(--border-color) !important;
    }

    [data-theme="dark"] .modal-header {
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-label {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
      background: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-color: var(--primary) !important;
    }

    [data-theme="dark"] .btn-secondary-custom {
      color: var(--text-color) !important;
      border-color: var(--border-color) !important;
    }

    [data-theme="dark"] .btn-secondary-custom:hover {
      border-color: var(--text-color) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .task-details-sidebar {
      background: var(--card-bg) !important;
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .sidebar-header {
      border-bottom-color: var(--border-color) !important;
    }

    [data-theme="dark"] .sidebar-content {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .detail-label {
      color: var(--text-color) !important;
      opacity: 0.8;
    }

    [data-theme="dark"] .detail-value {
      color: var(--text-color) !important;
    }

    [data-theme="dark"] .empty-state {
      color: var(--text-color) !important;
      opacity: 0.7;
    }

    [data-theme="dark"] .spinner {
      border-color: var(--border-color) !important;
      border-top-color: var(--primary) !important;
    }

    [data-theme="dark"] .loading-overlay {
      background: rgba(0, 0, 0, 0.7) !important;
    }

    [data-theme="dark"] .modal-body::-webkit-scrollbar-track {
      background: var(--surface-bg) !important;
    }

    [data-theme="dark"] .modal-body::-webkit-scrollbar-thumb {
      background: var(--border-color) !important;
    }

    [data-theme="dark"] .modal-body::-webkit-scrollbar-thumb:hover {
      background: var(--hover-bg) !important;
    }

    /* Dark mode badge colors - ensure visibility */
    [data-theme="dark"] .task-badge.bg-secondary {
      color: #9ca3af !important;
    }

    [data-theme="dark"] .task-badge.bg-primary {
      color: #818cf8 !important;
    }

    [data-theme="dark"] .task-badge.bg-success {
      color: #34d399 !important;
    }

    [data-theme="dark"] .task-badge.bg-info {
      color: #60a5fa !important;
    }

    [data-theme="dark"] .task-badge.bg-warning {
      color: #fbbf24 !important;
    }

    [data-theme="dark"] .task-badge.bg-danger {
      color: #f87171 !important;
    }

    [data-theme="dark"] .task-badge.bg-dark {
      color: #d1d5db !important;
    }

    [data-theme="dark"] .task-badge.text-white {
      color: #f87171 !important;
    }
  </style>
  @endpush

  <div class="task-manager">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
      <div class="spinner"></div>
    </div>

    <!-- Task Details Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="task-details-sidebar" id="taskDetailsSidebar">
      <div class="sidebar-header">
        <h5 class="mb-0">Task Details</h5>
        <button type="button" class="btn-close" id="closeSidebar"></button>
      </div>
      <div class="sidebar-content" id="sidebarContent">
        <!-- Content loaded dynamically -->
      </div>
    </div>

    <!-- Header -->
    <div class="task-header">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h1 class="h3 mb-1 fw-bold">Task Manager</h1>
          <p class="text-secondary mb-0">Organize and track your team's tasks efficiently</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <div class="view-toggle">
            <button type="button" class="view-toggle-btn" data-view="list">
              <i class="bi bi-list-ul"></i>List
            </button>
            <button type="button" class="view-toggle-btn" data-view="kanban">
              <i class="bi bi-kanban"></i>Board
            </button>
          </div>
          <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#taskModal" id="createTaskBtn">
            <i class="bi bi-plus-lg me-2"></i>New Task
          </button>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filters-bar">
      <div class="filter-group">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search tasks..." style="max-width: 300px;">
        
        <select id="categoryFilter" class="form-select" style="max-width: 150px;">
          <option value="">All Categories</option>
          <option value="finance">Finance</option>
          <option value="operation">Operation</option>
          <option value="hr">HR</option>
          <option value="marketing">Marketing</option>
          <option value="it">IT</option>
          <option value="other">Other</option>
        </select>

        <select id="priorityFilter" class="form-select" style="max-width: 150px;">
          <option value="">All Priorities</option>
          <option value="urgent">Urgent</option>
          <option value="high">High</option>
          <option value="medium">Medium</option>
          <option value="low">Low</option>
        </select>

        <select id="responsibleFilter" class="form-select" style="max-width: 200px;">
          <option value="">All Staff</option>
          @foreach($staffMembers as $staff)
            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
          @endforeach
        </select>

        <select id="statusFilter" class="form-select" style="max-width: 150px;">
          <option value="">All Status</option>
          <option value="to_do">To Do</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>

        <button class="btn btn-secondary-custom btn-sm" id="clearFilters">
          <i class="bi bi-x-circle me-1"></i>Clear
        </button>
      </div>
    </div>

    <!-- List View (Default) -->
    <div id="listView" class="task-table-container">
      <table class="task-table">
        <thead>
          <tr>
            <th style="width: 40%;">Task</th>
            <th style="width: 12%;">Status</th>
            <th style="width: 12%;">Priority</th>
            <th style="width: 12%;">Category</th>
            <th style="width: 15%;">Assigned To</th>
            <th style="width: 9%;">Due Date</th>
          </tr>
        </thead>
        <tbody id="taskTableBody">
          <!-- Tasks loaded via AJAX -->
        </tbody>
      </table>
    </div>

    <!-- Kanban Board View (Hidden by default) -->
    <div id="kanbanView" class="kanban-board" style="display: none;">
      <!-- To Do Column -->
      <div class="kanban-column to-do">
        <div class="kanban-header">
          <div class="kanban-title">
            <i class="bi bi-circle"></i>
            To Do
          </div>
          <span class="kanban-count" data-status="to_do">0</span>
        </div>
        <div class="kanban-tasks" data-status="to_do" id="kanban-to_do">
          <!-- Tasks loaded via AJAX -->
        </div>
      </div>

      <!-- In Progress Column -->
      <div class="kanban-column in-progress">
        <div class="kanban-header">
          <div class="kanban-title">
            <i class="bi bi-arrow-repeat"></i>
            In Progress
          </div>
          <span class="kanban-count" data-status="in_progress">0</span>
        </div>
        <div class="kanban-tasks" data-status="in_progress" id="kanban-in_progress">
          <!-- Tasks loaded via AJAX -->
        </div>
      </div>

      <!-- Completed Column -->
      <div class="kanban-column completed">
        <div class="kanban-header">
          <div class="kanban-title">
            <i class="bi bi-check-circle"></i>
            Completed
          </div>
          <span class="kanban-count" data-status="completed">0</span>
        </div>
        <div class="kanban-tasks" data-status="completed" id="kanban-completed">
          <!-- Tasks loaded via AJAX -->
        </div>
      </div>
    </div>
  </div>

  <!-- Task Create/Edit Modal -->
  <div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="taskModalTitle">Create New Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="taskForm">
          <input type="hidden" id="taskId" name="task_id">
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12">
                <label for="taskTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="taskTitle" name="title" required>
              </div>

              <div class="col-12">
                <label for="taskDescription" class="form-label">Description</label>
                <textarea class="form-control" id="taskDescription" name="description" rows="3"></textarea>
              </div>

              <div class="col-md-4">
                <label for="taskCategory" class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="taskCategory" name="category" required>
                  <option value="">Select Category</option>
                  <option value="finance">Finance</option>
                  <option value="operation">Operation</option>
                  <option value="hr">HR</option>
                  <option value="marketing">Marketing</option>
                  <option value="it">IT</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="col-md-4">
                <label for="taskPriority" class="form-label">Priority <span class="text-danger">*</span></label>
                <select class="form-select" id="taskPriority" name="priority" required>
                  <option value="">Select Priority</option>
                  <option value="low">Low</option>
                  <option value="medium" selected>Medium</option>
                  <option value="high">High</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>

              <div class="col-md-4">
                <label for="taskStatus" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="taskStatus" name="status" required>
                  <option value="to_do" selected>To Do</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="taskDueDate" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="taskDueDate" name="due_date">
              </div>

              <div class="col-md-6">
                <label for="taskResponsible" class="form-label">Assign To</label>
                <select class="form-select" id="taskResponsible" name="responsible_person_id">
                  <option value="">Select Staff Member</option>
                  @foreach($staffMembers as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                  @endforeach
                </select>
              </div>

              <div class="col-12">
                <label for="taskNotes" class="form-label">Notes</label>
                <textarea class="form-control" id="taskNotes" name="notes" rows="2" placeholder="Add any additional notes..."></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary-custom">
              <i class="bi bi-check-lg me-2"></i><span id="submitBtnText">Create Task</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script>
    // AJAX Setup
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Global Variables
    let allTasks = [];
    let currentView = 'list'; // Default to list view
    let isEditMode = false;

    // Initialize
    $(document).ready(function() {
      console.log('Initializing task manager...');
      initializeEventListeners();
      initializeDragAndDrop();
      loadTasks();
      
      // Set list view as active by default
      $('.view-toggle-btn[data-view="list"]').addClass('active');
    });

    // Load Tasks
    function loadTasks() {
      console.log('Loading tasks...');
      showLoading();
      
      const filters = {
        search: $('#searchInput').val(),
        category: $('#categoryFilter').val(),
        priority: $('#priorityFilter').val(),
        responsible_person: $('#responsibleFilter').val(),
        status: $('#statusFilter').val()
      };

      $.ajax({
        url: '{{ route("admin.tasks.index") }}',
        method: 'GET',
        data: filters,
        success: function(response) {
          console.log('Tasks loaded:', response.tasks.length);
          allTasks = response.tasks;
          renderTasks();
          hideLoading();
        },
        error: function(xhr) {
          console.error('Error loading tasks:', xhr);
          hideLoading();
          showNotification('Error loading tasks', 'error');
        }
      });
    }

    // Render Tasks
    function renderTasks() {
      console.log('Rendering tasks in', currentView, 'view');
      if (currentView === 'kanban') {
        renderKanbanView();
      } else {
        renderListView();
      }
    }

    // Render Kanban View
    function renderKanbanView() {
      console.log('Rendering kanban view');
      // Clear all columns
      $('.kanban-tasks').empty();
      
      // Reset counts
      $('.kanban-count').text('0');

      // Group tasks by status
      const tasksByStatus = {
        to_do: [],
        in_progress: [],
        completed: []
      };

      allTasks.forEach(task => {
        if (tasksByStatus[task.status]) {
          tasksByStatus[task.status].push(task);
        }
      });

      // Render each status column
      Object.keys(tasksByStatus).forEach(status => {
        const tasks = tasksByStatus[status];
        const container = $(`#kanban-${status}`);
        
        // Update count
        $(`.kanban-count[data-status="${status}"]`).text(tasks.length);

        if (tasks.length === 0) {
          container.html('<div class="empty-state"><div class="empty-state-icon"><i class="bi bi-inbox"></i></div><p class="small mb-0 text-muted">No tasks</p></div>');
        } else {
          tasks.forEach(task => {
            container.append(createTaskCard(task));
          });
        }
      });
    }

    // Create Task Card
    function createTaskCard(task) {
      const overdueClass = task.is_overdue ? 'overdue' : '';
      const assigneeHtml = task.responsible_person 
        ? `<div class="task-assignee">
             <div class="task-avatar">${task.responsible_person.initials}</div>
             <span class="small">${task.responsible_person.name.split(' ')[0]}</span>
           </div>`
        : '<span class="small text-muted">Unassigned</span>';

      const dueDateHtml = task.due_date_formatted
        ? `<div class="task-due-date ${overdueClass}">
             <i class="bi bi-calendar-event"></i>
             ${task.due_date_formatted}
           </div>`
        : '<span class="small text-muted">No due date</span>';

      return `
        <div class="task-card priority-${task.priority}" data-task-id="${task.id}">
          <div class="task-title">${escapeHtml(task.title)}</div>
          ${task.description ? `<div class="task-description">${escapeHtml(task.description)}</div>` : ''}
          <div class="task-meta">
            <span class="task-badge ${task.category_badge_class}">${task.category}</span>
            <span class="task-badge ${task.priority_badge_class}">${task.priority}</span>
            ${task.is_overdue ? '<span class="task-badge bg-danger text-white">⚠ Overdue</span>' : ''}
          </div>
          <div class="task-footer">
            ${assigneeHtml}
            ${dueDateHtml}
          </div>
        </div>
      `;
    }

    // Render List View (Table)
    function renderListView() {
      console.log('Rendering list view');
      const tbody = $('#taskTableBody');
      tbody.empty();

      if (allTasks.length === 0) {
        tbody.html(`
          <tr>
            <td colspan="6" class="text-center py-5">
              <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <h5>No tasks found</h5>
                <p class="text-secondary">Create a new task to get started</p>
              </div>
            </td>
          </tr>
        `);
        return;
      }

      allTasks.forEach(task => {
        tbody.append(createTableRow(task));
      });
    }

    // Create Table Row
    function createTableRow(task) {
      const statusLabels = {
        to_do: 'To Do',
        in_progress: 'In Progress',
        completed: 'Completed'
      };

      const priorityDots = {
        urgent: 'urgent',
        high: 'high',
        medium: 'medium',
        low: 'low'
      };

      const assigneeHtml = task.responsible_person 
        ? `<div class="assignee-cell">
             <div class="task-avatar">${task.responsible_person.initials}</div>
             <span>${task.responsible_person.name}</span>
           </div>`
        : '<span class="text-muted">Unassigned</span>';

      const dueDateClass = task.is_overdue ? 'text-danger fw-bold' : '';

      return `
        <tr data-task-id="${task.id}" onclick="showTaskDetails(${task.id})">
          <td>
            <div class="table-task-title">${escapeHtml(task.title)}</div>
            ${task.description ? `<div class="table-task-desc">${escapeHtml(task.description)}</div>` : ''}
          </td>
          <td>
            <span class="task-badge ${task.status_badge_class}">${statusLabels[task.status] || task.status}</span>
          </td>
          <td>
            <span class="priority-dot ${priorityDots[task.priority]}"></span>
            <span class="text-capitalize">${task.priority}</span>
          </td>
          <td>
            <span class="task-badge ${task.category_badge_class}">${task.category}</span>
          </td>
          <td>${assigneeHtml}</td>
          <td class="${dueDateClass}">
            ${task.due_date_formatted || 'No date'}
            ${task.is_overdue ? '<br><small class="badge bg-danger">Overdue</small>' : ''}
          </td>
        </tr>
      `;
    }

    // Initialize Event Listeners
    function initializeEventListeners() {
      console.log('Initializing event listeners...');
      
      // View Toggle
      $('.view-toggle-btn').on('click', function(e) {
        e.preventDefault();
        const view = $(this).data('view');
        console.log('View button clicked:', view);
        switchView(view);
      });

      // Filters
      $('#searchInput, #categoryFilter, #priorityFilter, #responsibleFilter, #statusFilter').on('change', function() {
        loadTasks();
      });

      // Search with debounce
      let searchTimeout;
      $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadTasks(), 300);
      });

      // Clear Filters
      $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('#priorityFilter').val('');
        $('#responsibleFilter').val('');
        $('#statusFilter').val('');
        loadTasks();
      });

      // Create Task Button
      $('#createTaskBtn').on('click', function() {
        resetForm();
        isEditMode = false;
        $('#taskModalTitle').text('Create New Task');
        $('#submitBtnText').text('Create Task');
      });

      // Task Form Submit
      $('#taskForm').on('submit', function(e) {
        e.preventDefault();
        saveTask();
      });

      // Task Card Click (for kanban)
      $(document).on('click', '.task-card', function() {
        const taskId = $(this).data('task-id');
        showTaskDetails(taskId);
      });

      // Close Sidebar
      $('#closeSidebar, #sidebarOverlay').on('click', function() {
        closeSidebar();
      });
    }

    // Switch View
    function switchView(view) {
      console.log('Switching from', currentView, 'to', view);
      currentView = view;
      
      // Update button states
      $('.view-toggle-btn').removeClass('active');
      $(`.view-toggle-btn[data-view="${view}"]`).addClass('active');

      // Toggle view visibility
      if (view === 'kanban') {
        $('#listView').hide();
        $('#kanbanView').css('display', 'grid');
      } else {
        $('#kanbanView').hide();
        $('#listView').show();
      }
      
      // Re-render tasks for the new view
      renderTasks();
    }

    // Initialize Drag and Drop
    function initializeDragAndDrop() {
      $('.kanban-tasks').each(function() {
        new Sortable(this, {
          group: 'tasks',
          animation: 150,
          ghostClass: 'dragging',
          onEnd: function(evt) {
            const taskId = $(evt.item).data('task-id');
            const newStatus = $(evt.to).data('status');
            updateTaskStatus(taskId, newStatus);
          }
        });
      });
    }

    // Update Task Status
    function updateTaskStatus(taskId, status) {
      $.ajax({
        url: `{{ url('admin/tasks') }}/${taskId}/update-status`,
        method: 'POST',
        data: { status: status },
        success: function(response) {
          showNotification(response.message, 'success');
          loadTasks();
        },
        error: function(xhr) {
          showNotification('Error updating task status', 'error');
          loadTasks();
        }
      });
    }

    // Save Task
    function saveTask() {
      const formData = {
        title: $('#taskTitle').val(),
        description: $('#taskDescription').val(),
        category: $('#taskCategory').val(),
        priority: $('#taskPriority').val(),
        due_date: $('#taskDueDate').val(),
        status: $('#taskStatus').val(),
        responsible_person_id: $('#taskResponsible').val() || null,
        notes: $('#taskNotes').val()
      };

      const taskId = $('#taskId').val();
      const url = taskId 
        ? `{{ url('admin/tasks') }}/${taskId}` 
        : '{{ route("admin.tasks.store") }}';
      const method = taskId ? 'PUT' : 'POST';

      showLoading();

      $.ajax({
        url: url,
        method: method,
        data: formData,
        success: function(response) {
          hideLoading();
          showNotification(response.message, 'success');
          $('#taskModal').modal('hide');
          loadTasks();
          resetForm();
        },
        error: function(xhr) {
          hideLoading();
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            let errorMsg = '';
            Object.values(xhr.responseJSON.errors).forEach(errors => {
              errorMsg += errors.join('<br>') + '<br>';
            });
            showNotification(errorMsg, 'error');
          } else {
            showNotification('Error saving task', 'error');
          }
        }
      });
    }

    // Show Task Details
    function showTaskDetails(taskId) {
      showLoading();
      
      $.ajax({
        url: `{{ url('admin/tasks') }}/${taskId}`,
        method: 'GET',
        success: function(response) {
          hideLoading();
          const task = response.task;
          renderTaskDetails(task);
          openSidebar();
        },
        error: function(xhr) {
          hideLoading();
          showNotification('Error loading task details', 'error');
        }
      });
    }

    // Render Task Details
    function renderTaskDetails(task) {
      const statusLabels = {
        to_do: 'To Do',
        in_progress: 'In Progress',
        completed: 'Completed'
      };

      const assigneeHtml = task.responsible_person 
        ? `<div class="d-flex align-items-center gap-2">
             <div class="task-avatar" style="width: 40px; height: 40px; font-size: 1rem;">${task.responsible_person.initials}</div>
             <div>
               <div class="fw-semibold">${task.responsible_person.name}</div>
               <div class="small text-secondary">${task.responsible_person.role}</div>
             </div>
           </div>`
        : '<span class="text-secondary">Not assigned</span>';

      const html = `
        <div class="mb-4">
          <h4 class="mb-3 fw-bold">${escapeHtml(task.title)}</h4>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="task-badge ${task.status_badge_class}">${statusLabels[task.status]}</span>
            <span class="task-badge ${task.category_badge_class}">${task.category}</span>
            <span class="task-badge ${task.priority_badge_class}">${task.priority}</span>
            ${task.is_overdue ? '<span class="task-badge bg-danger text-white">⚠ Overdue</span>' : ''}
          </div>
        </div>

        ${task.description ? `
          <div class="detail-section">
            <div class="detail-label">Description</div>
            <div class="detail-value" style="white-space: pre-wrap;">${escapeHtml(task.description)}</div>
          </div>
        ` : ''}

        <div class="detail-section">
          <div class="detail-label">Due Date</div>
          <div class="detail-value">${task.due_date_formatted || 'No due date'} ${task.due_date_human ? `<br><small class="text-secondary">(${task.due_date_human})</small>` : ''}</div>
        </div>

        <div class="detail-section">
          <div class="detail-label">Assigned To</div>
          <div class="detail-value">${assigneeHtml}</div>
        </div>

        ${task.notes ? `
          <div class="detail-section">
            <div class="detail-label">Notes</div>
            <div class="detail-value" style="white-space: pre-wrap;">${escapeHtml(task.notes)}</div>
          </div>
        ` : ''}

        <div class="detail-section">
          <div class="detail-label">Created By</div>
          <div class="detail-value">${task.creator.name}</div>
          <div class="small text-secondary">${task.created_at}</div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary-custom flex-grow-1" onclick="editTask(${task.id})">
            <i class="bi bi-pencil me-2"></i>Edit Task
          </button>
          <button class="btn btn-outline-danger" onclick="deleteTask(${task.id})">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      `;

      $('#sidebarContent').html(html);
    }

    // Edit Task
    function editTask(taskId) {
      closeSidebar();
      showLoading();

      $.ajax({
        url: `{{ url('admin/tasks') }}/${taskId}`,
        method: 'GET',
        success: function(response) {
          hideLoading();
          const task = response.task;
          
          isEditMode = true;
          $('#taskModalTitle').text('Edit Task');
          $('#submitBtnText').text('Update Task');
          
          $('#taskId').val(task.id);
          $('#taskTitle').val(task.title);
          $('#taskDescription').val(task.description);
          $('#taskCategory').val(task.category);
          $('#taskPriority').val(task.priority);
          $('#taskDueDate').val(task.due_date);
          $('#taskStatus').val(task.status);
          $('#taskResponsible').val(task.responsible_person_id);
          $('#taskNotes').val(task.notes);
          
          $('#taskModal').modal('show');
        },
        error: function(xhr) {
          hideLoading();
          showNotification('Error loading task', 'error');
        }
      });
    }

    // Delete Task
    function deleteTask(taskId) {
      if (!confirm('Are you sure you want to delete this task?')) {
        return;
      }

      closeSidebar();
      showLoading();

      $.ajax({
        url: `{{ url('admin/tasks') }}/${taskId}`,
        method: 'DELETE',
        success: function(response) {
          hideLoading();
          showNotification(response.message, 'success');
          loadTasks();
        },
        error: function(xhr) {
          hideLoading();
          showNotification('Error deleting task', 'error');
        }
      });
    }

    // Reset Form
    function resetForm() {
      $('#taskForm')[0].reset();
      $('#taskId').val('');
      $('#taskPriority').val('medium');
      $('#taskStatus').val('to_do');
    }

    // Sidebar Functions
    function openSidebar() {
      $('#taskDetailsSidebar').addClass('open');
      $('#sidebarOverlay').addClass('show');
      $('body').css('overflow', 'hidden');
    }

    function closeSidebar() {
      $('#taskDetailsSidebar').removeClass('open');
      $('#sidebarOverlay').removeClass('show');
      $('body').css('overflow', '');
    }

    // Loading Functions
    function showLoading() {
      $('#loadingOverlay').addClass('show');
    }

    function hideLoading() {
      $('#loadingOverlay').removeClass('show');
    }

    // Notification
    function showNotification(message, type = 'success') {
      const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
      const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
      
      const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 350px; box-shadow: 0 8px 20px rgba(0,0,0,0.2); border-radius: 12px;">
          <i class="bi bi-${icon} me-2"></i>${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `);
      
      $('body').append(alert);
      
      setTimeout(() => {
        alert.fadeOut(() => alert.remove());
      }, 5000);
    }

    // Utility Functions
    function escapeHtml(text) {
      if (!text) return '';
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }
  </script>
  @endpush
</x-admin.layouts.app>

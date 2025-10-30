<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

  {{-- Bootstrap 5 + Icons (CDN). Replace with Vite build if you prefer --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Optional: your app.css/js via Vite (keep if you have custom styles/scripts) --}}
  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    /* Light/Dark Mode Theme */
    :root {
      --bs-primary: #4f46e5;
      --bs-primary-rgb: 79, 70, 229;
      --bg-color: #ffffff;
      --text-color: #1f2937;
      --card-bg: #ffffff;
      --border-color: #e5e7eb;
      --surface-bg: #f9fafb;
      --navbar-bg: #ffffff;
      --input-bg: #ffffff;
      --hover-bg: #f3f4f6;
    }

    [data-theme="dark"] {
      --bg-color: #111827;
      --text-color: #f9fafb;
      --card-bg: #1f2937;
      --border-color: #374151;
      --surface-bg: #0f172a;
      --navbar-bg: #1f2937;
      --input-bg: #374151;
      --hover-bg: #374151;
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-color);
      transition: all 0.3s ease;
    }

    .navbar-brand .abbr-sm { display: none; }
    @media (max-width:576px) {
      .navbar-brand .full { display: none; }
      .navbar-brand .abbr-sm { display: inline; }
    }

    .avatar-circle {
      width: 36px; height: 36px; border-radius: 50%;
      display: inline-flex; align-items: center; justify-content: center;
      background: #dee2e6; color: #495057; font-weight: 600;
    }

    /* Navbar */
    .navbar-light {
      background-color: var(--navbar-bg) !important;
      border-bottom: 1px solid var(--border-color) !important;
    }

    .nav-link {
      color: var(--text-color) !important;
    }

    .navbar-brand {
      color: var(--text-color) !important;
    }

    /* Surface */
    .surface {
      background: var(--surface-bg);
      min-height: 100vh;
    }

    /* Cards */
    .card {
      background-color: var(--card-bg) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-color) !important;
    }

    .card-header, .card-footer {
      background-color: var(--card-bg) !important;
      border-color: var(--border-color) !important;
    }

    /* Forms */
    select.form-select {
      border-radius: 6px !important;
      height: 42px !important;
      border: 1px solid var(--border-color) !important;
      background-color: var(--input-bg) !important;
      color: var(--text-color) !important;
    }

    [type=text], input:where(:not([type])), [type=email], [type=url], [type=password],
    [type=number], [type=date], [type=datetime-local], [type=month], [type=search],
    [type=tel], [type=time], [type=week], [multiple], textarea, select {
      border: 1px solid var(--border-color) !important;
      background-color: var(--input-bg) !important;
      color: var(--text-color) !important;
    }

    /* Outline Buttons */
    .btn-primary {
      background: transparent !important;
      border: 2px solid var(--bs-primary) !important;
      color: var(--bs-primary) !important;
    }

    .btn-primary:hover {
      background: var(--bs-primary) !important;
      color: #ffffff !important;
    }

    .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info,
    .btn-outline-primary, .btn-outline-secondary, .btn-outline-success,
    .btn-outline-danger, .btn-outline-warning, .btn-outline-info {
      background: transparent !important;
    }

    /* Tables */
    .table {
      color: var(--text-color) !important;
      background-color: transparent !important;
      --bs-table-bg: transparent !important;
      --bs-table-striped-bg: transparent !important;
      --bs-table-hover-bg: var(--hover-bg) !important;
      --bs-table-color: var(--text-color) !important;
    }

    .table thead {
      background-color: transparent !important;
    }

    .table tbody {
      background-color: transparent !important;
    }

    .table tbody tr {
      background-color: transparent !important;
      color: var(--text-color) !important;
    }

    .table tbody tr:hover {
      background-color: var(--hover-bg) !important;
      color: var(--text-color) !important;
    }

    .table tbody td {
      background-color: transparent !important;
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }

    .table thead th {
      background-color: var(--hover-bg) !important;
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }

    .table-light {
      background-color: var(--hover-bg) !important;
      --bs-table-bg: var(--hover-bg) !important;
    }

    .table-hover > tbody > tr:hover {
      --bs-table-accent-bg: var(--hover-bg) !important;
      background-color: var(--hover-bg) !important;
    }

    .table-responsive {
      background-color: transparent !important;
    }

    /* Ensure all text in tables is visible in dark mode */
    [data-theme="dark"] .table,
    [data-theme="dark"] .table tbody,
    [data-theme="dark"] .table tbody tr,
    [data-theme="dark"] .table tbody td,
    [data-theme="dark"] .table thead th {
      color: #f9fafb !important;
    }

    [data-theme="dark"] .table .fw-medium,
    [data-theme="dark"] .table .fw-bold {
      color: #ffffff !important;
    }

    /* Status badges in table */
    .status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    /* Navigation Dropdown */
    .navbar-nav .dropdown-menu {
      background-color: var(--card-bg) !important;
      border: 1px solid var(--border-color) !important;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar-nav .dropdown-item {
      color: var(--text-color) !important;
    }

    .navbar-nav .dropdown-item:hover {
      background-color: var(--hover-bg) !important;
      color: var(--bs-primary) !important;
      padding-left: 1.25rem;
    }

    .navbar-nav .dropdown-item.active {
      background-color: var(--hover-bg) !important;
      color: var(--bs-primary) !important;
      font-weight: 500;
    }

    .navbar-nav .dropdown-divider {
      border-color: var(--border-color) !important;
    }

    .nav-link.active {
      color: var(--bs-primary) !important;
      font-weight: 500;
    }

    /* Modals */
    .modal-content {
      background-color: var(--card-bg) !important;
      color: var(--text-color) !important;
      border: 1px solid var(--border-color) !important;
    }

    .modal-header, .modal-footer {
      border-color: var(--border-color) !important;
    }

    /* Alerts */
    .alert {
      border: 1px solid var(--border-color) !important;
    }

    /* Theme Toggle Button */
    .theme-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--bs-primary);
      color: white;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .theme-toggle:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .theme-icon {
      transition: all 0.3s ease;
    }

    [data-theme="dark"] .theme-icon.sun {
      display: inline;
    }

    [data-theme="dark"] .theme-icon.moon {
      display: none;
    }

    [data-theme="light"] .theme-icon.sun {
      display: none;
    }

    [data-theme="light"] .theme-icon.moon {
      display: inline;
    }
  </style>

  @stack('styles')
</head>
<body>

  {{-- Top Nav --}}
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container-xxl">
      <a class="navbar-brand fw-bold text-dark" href="{{ route('admin.dashboard') }}">
        <span class="full">TBR Transport Admin</span>
        <span class="abbr-sm">TBR Admin</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"
              aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="adminNav">
        {{-- Left: main nav --}}
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          
          {{-- Dashboard --}}
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
              <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </a>
          </li>
          
          {{-- Bookings & Calendar --}}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.bookings.*', 'admin.calendar.*') ? 'active' : '' }}" 
               href="#" id="bookingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-calendar-check me-1"></i>Bookings
            </a>
            <ul class="dropdown-menu" aria-labelledby="bookingsDropdown">
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" 
                   href="{{ route('admin.bookings.index') }}">
                  <i class="bi bi-list-ul me-2"></i>View Bookings
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}" 
                   href="{{ route('admin.calendar.index') }}">
                  <i class="bi bi-calendar3 me-2"></i>Calendar
                </a>
              </li>
            </ul>
          </li>
          
          {{-- Finance & Reports --}}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.profit-loss.*', 'admin.leads.*', 'admin.finance.*') ? 'active' : '' }}" 
               href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-graph-up me-1"></i>Finance & Reports
            </a>
            <ul class="dropdown-menu" aria-labelledby="financeDropdown">
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.profit-loss.*') ? 'active' : '' }}" 
                   href="{{ route('admin.profit-loss.index') }}">
                  <i class="bi bi-cash-stack me-2"></i>Profit & Loss
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}" 
                   href="{{ route('admin.leads.index') }}">
                  <i class="bi bi-graph-up-arrow me-2"></i>Lead Tracker
                </a>
              </li>
            </ul>
          </li>
          
          {{-- Tasks --}}
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}"
               href="{{ route('admin.tasks.manage') }}">
              <i class="bi bi-check2-square me-1"></i>Tasks
            </a>
          </li>
          
          {{-- Management --}}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.customers.*', 'admin.companies.*', 'admin.vehicles.*', 'admin.staff.*', 'admin.service_types.*') ? 'active' : '' }}" 
               href="#" id="managementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-gear me-1"></i>Management
            </a>
            <ul class="dropdown-menu" aria-labelledby="managementDropdown">
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" 
                   href="{{ route('admin.customers.index') }}">
                  <i class="bi bi-people me-2"></i>Customers
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" 
                   href="{{ route('admin.companies.manage') }}">
                  <i class="bi bi-building me-2"></i>Companies
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}" 
                   href="{{ route('admin.vehicles.manage') }}">
                  <i class="bi bi-truck me-2"></i>Vehicles
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" 
                   href="{{ route('admin.staff.manage') }}">
                  <i class="bi bi-person-badge me-2"></i>Staff
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.service_types.*') ? 'active' : '' }}" 
                   href="{{ route('admin.service_types.manage') }}">
                  <i class="bi bi-box-seam me-2"></i>Service Types
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.lead-sources.*') ? 'active' : '' }}" 
                   href="{{ route('admin.lead-sources.manage') }}">
                  <i class="bi bi-megaphone me-2"></i>Lead Sources
                </a>
              </li>
            </ul>
          </li>

        </ul>

        {{-- Right: user dropdown --}}
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item d-flex align-items-center me-2">
            <span class="me-2 small text-muted">{{ Auth::user()->name }}</span>
            <span class="badge text-bg-light border">
              {{ ucfirst(Auth::user()->role) }}
            </span>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="avatar-circle">{{ strtoupper(Str::substr(Auth::user()->name,0,1)) }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                  <i class="bi bi-person me-2"></i> Profile
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                  </button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  {{-- Page Content --}}
  <main class="surface py-4">
    <div class="container-xxl">

      {{-- Flash alerts --}}
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      {{ $slot }}
    </div>
  </main>

  {{-- Theme Toggle Button --}}
  <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()">
    <i class="bi bi-sun-fill theme-icon sun"></i>
    <i class="bi bi-moon-fill theme-icon moon"></i>
  </button>

  {{-- Bootstrap 5 JS Bundle (includes Popper) --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  {{-- Theme Toggle Script --}}
  <script>
    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', savedTheme);
    });

    // Toggle theme function
    function toggleTheme() {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
    }
  </script>
  
  @stack('scripts')
</body>
</html>

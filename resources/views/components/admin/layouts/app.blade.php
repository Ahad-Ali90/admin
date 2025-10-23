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
    :root{
      --bs-primary:#4f46e5;                      /* indigo */
      --bs-primary-rgb:79,70,229;
    }
    .navbar-brand .abbr-sm{ display:none; }
    @media (max-width:576px){
      .navbar-brand .full{ display:none; }
      .navbar-brand .abbr-sm{ display:inline; }
    }
    .avatar-circle{
      width: 36px; height: 36px; border-radius: 50%;
      display: inline-flex; align-items:center; justify-content:center;
      background:#dee2e6; color:#495057; font-weight:600;
    }
    /* Subtle card look for main sections if you want to wrap content later */
    .surface { background:#f8f9fb; min-height:100vh; }
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
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
               href="{{ route('admin.bookings.index') }}">
              Bookings
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
               href="{{ route('admin.customers.index') }}">
              Customers
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}"
               href="{{ route('admin.vehicles.manage') }}">
              Vehicles
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
               href="{{ route('admin.staff.manage') }}">
              Staff
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.service_types.*') ? 'active' : '' }}"
              href="{{ route('admin.service_types.manage') }}">
              Service Types
            </a>
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
 <!-- Bootstrap 5 JS (needs Popper inside the bundle) -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
  @stack('scripts')

  {{-- Bootstrap JS (needs Popper included) --}}
 

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

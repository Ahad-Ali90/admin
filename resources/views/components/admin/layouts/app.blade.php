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
  
  {{-- Select2 for searchable dropdowns --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin" ></script>

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

    /* Enhanced Cards */
    .card {
      background-color: var(--card-bg) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-color) !important;
      border-radius: 12px !important;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
      transition: all 0.3s ease !important;
    }
    
    .card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
      transform: translateY(-2px) !important;
    }

    .card-header {
      background-color: #f9fafb !important;
      border-color: var(--border-color) !important;
      border-radius: 12px 12px 0 0 !important;
      padding: 1rem 1.5rem !important;
      font-weight: 600 !important;
    }
    
    .card-footer {
      background-color: #f9fafb !important;
      border-color: var(--border-color) !important;
      border-radius: 0 0 12px 12px !important;
    }

    /* Enhanced Forms & Inputs */
    select.form-select {
      border-radius: 0 !important;
      height: 44px !important;
      border: 2px solid #d1d5db !important;
      background-color: var(--input-bg) !important;
      color: var(--text-color) !important;
      transition: all 0.3s ease !important;
      font-size: 0.95rem !important;
      padding: 0.5rem 1rem !important;
    }
    
    select.form-select:focus {
      border-color: #4f46e5 !important;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
      outline: none !important;
    }

    [type=text], input:where(:not([type])), [type=email], [type=url], [type=password],
    [type=number], [type=date], [type=datetime-local], [type=month], [type=search],
    [type=tel], [type=time], [type=week] {
      border: 2px solid #d1d5db !important;
      background-color: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-radius: 0 !important;
      height: 44px !important;
      padding: 0.625rem 1rem !important;
      transition: all 0.3s ease !important;
      font-size: 0.95rem !important;
    }
    
    textarea {
      border: 2px solid #d1d5db !important;
      background-color: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-radius: 8px !important;
      padding: 0.625rem 1rem !important;
      transition: all 0.3s ease !important;
      font-size: 0.95rem !important;
    }
    
    [type=text]:focus, input:where(:not([type])):focus, [type=email]:focus, 
    [type=url]:focus, [type=password]:focus, [type=number]:focus, [type=date]:focus,
    [type=datetime-local]:focus, [type=month]:focus, [type=search]:focus,
    [type=tel]:focus, [type=time]:focus, [type=week]:focus, textarea:focus {
      border-color: #4f46e5 !important;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
      outline: none !important;
      background-color: var(--input-bg) !important;
    }
    
    [type=text]::placeholder, input::placeholder, textarea::placeholder {
      color: #9ca3af !important;
      opacity: 0.7 !important;
    }
    
    select, [multiple] {
      border: 2px solid #d1d5db !important;
      background-color: var(--input-bg) !important;
      color: var(--text-color) !important;
      border-radius: 0 !important;
      height: 44px !important;
    }
    
    .form-control:not(textarea) {
      border-radius: 0 !important;
      height: 44px !important;
    }
    
    textarea.form-control {
      border-radius: 8px !important;
    }

    /* Enhanced Buttons */
    .btn-primary {
      background: #4f46e5 !important;
      border: 2px solid #4f46e5 !important;
      color: #ffffff !important;
      border-radius: 8px !important;
      padding: 0.625rem 1.25rem !important;
      font-weight: 600 !important;
      transition: all 0.3s ease !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    .btn-primary:hover {
      background: #4338ca !important;
      border-color: #4338ca !important;
      color: #ffffff !important;
      transform: translateY(-1px) !important;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    }
    
    .btn-secondary {
      background: #6b7280 !important;
      border-color: #6b7280 !important;
      color: #ffffff !important;
      border-radius: 8px !important;
      padding: 0.625rem 1.25rem !important;
      font-weight: 600 !important;
      transition: all 0.3s ease !important;
    }
    
    .btn-success {
      background: #10b981 !important;
      border-color: #10b981 !important;
      color: #ffffff !important;
      border-radius: 8px !important;
      padding: 0.625rem 1.25rem !important;
      font-weight: 600 !important;
      transition: all 0.3s ease !important;
    }
    
    .btn-danger {
      background: #ef4444 !important;
      border-color: #ef4444 !important;
      color: #ffffff !important;
      border-radius: 8px !important;
      padding: 0.625rem 1.25rem !important;
      font-weight: 600 !important;
      transition: all 0.3s ease !important;
    }
    
    .btn-outline-primary {
      background: transparent !important;
      border: 2px solid #4f46e5 !important;
      color: #4f46e5 !important;
      border-radius: 8px !important;
      padding: 0.625rem 1.25rem !important;
      font-weight: 600 !important;
      transition: all 0.3s ease !important;
    }
    
    .btn-outline-primary:hover {
      background: #4f46e5 !important;
      color: #ffffff !important;
      transform: translateY(-1px) !important;
    }

    .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info,
    .btn-outline-primary, .btn-outline-secondary, .btn-outline-success,
    .btn-outline-danger, .btn-outline-warning, .btn-outline-info {
      background: transparent !important;
    }

    /* Enhanced Tables */
    .table {
      color: var(--text-color) !important;
      background-color: transparent !important;
      --bs-table-bg: transparent !important;
      --bs-table-striped-bg: transparent !important;
      --bs-table-hover-bg: #f9fafb !important;
      --bs-table-color: var(--text-color) !important;
      border-radius: 8px !important;
      overflow: hidden !important;
    }

    .table thead {
      background-color: #f3f4f6 !important;
    }

    .table tbody {
      background-color: transparent !important;
    }

    .table tbody tr {
      background-color: transparent !important;
      color: var(--text-color) !important;
      transition: all 0.2s ease !important;
    }

    .table tbody tr:hover {
      background-color: #f9fafb !important;
      color: var(--text-color) !important;
      transform: scale(1.01) !important;
    }

    .table tbody td {
      background-color: transparent !important;
      border-color: #e5e7eb !important;
      color: var(--text-color) !important;
      padding: 1rem !important;
      vertical-align: middle !important;
    }

    .table thead th {
      background-color: #f3f4f6 !important;
      border-color: #e5e7eb !important;
      color: #1f2937 !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      font-size: 0.75rem !important;
      letter-spacing: 0.5px !important;
      padding: 1rem !important;
    }
    
    .table-light {
      --bs-table-bg: #f3f4f6 !important;
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

    /* Dark mode table header background */
    [data-theme="dark"] .table thead {
      background-color: #374151 !important;
    }

    [data-theme="dark"] .table thead th {
      background-color: #374151 !important;
      color: #f9fafb !important;
      border-color: #4b5563 !important;
    }

    [data-theme="dark"] .table .fw-medium,
    [data-theme="dark"] .table .fw-bold {
      color: #ffffff !important;
    }

    /* Dark mode table row hover */
    [data-theme="dark"] .table tbody tr:hover {
      background-color: #374151 !important;
      color: #f9fafb !important;
    }

    [data-theme="dark"] .table tbody tr:hover td {
      color: #f9fafb !important;
    }

    [data-theme="dark"] .table-hover > tbody > tr:hover {
      background-color: #374151 !important;
      color: #f9fafb !important;
    }

    [data-theme="dark"] .table-hover > tbody > tr:hover td {
      color: #f9fafb !important;
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
    
    /* Select2 Styling for Light Mode */
    .select2-container--bootstrap-5 .select2-selection {
      background-color: var(--input-bg) !important;
      border-color: var(--border-color) !important;
      color: var(--text-color) !important;
      border-radius: 0 !important;
      height: 44px !important;
    }
    
    .select2-container--bootstrap-5 .select2-selection__rendered {
      line-height: 42px !important;
      color: var(--text-color) !important;
    }
    
    .select2-container--bootstrap-5 .select2-dropdown {
      background-color: #ffffff !important;
      border-color: var(--border-color) !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
    
    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
      background-color: #ffffff !important;
      border-color: #d1d5db !important;
      color: #1f2937 !important;
      padding: 0.5rem !important;
    }
    
    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
      border-color: #4f46e5 !important;
      outline: none !important;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }
    
    .select2-container--bootstrap-5 .select2-results__option {
      background-color: #ffffff !important;
      color: #1f2937 !important;
      padding: 0.5rem 0.75rem !important;
    }
    
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
      background-color: #4f46e5 !important;
      color: #ffffff !important;
    }
    
    .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
      background-color: #e0e7ff !important;
      color: #4f46e5 !important;
      font-weight: 600 !important;
    }
    
    .select2-container--bootstrap-5 .select2-results__option--selectable {
      color: #1f2937 !important;
    }
    
    .select2-container--bootstrap-5 .select2-results__option--selectable:hover {
      background-color: #f3f4f6 !important;
      color: #1f2937 !important;
    }
    
    .select2-container--bootstrap-5 .select2-selection__arrow {
      color: var(--text-color) !important;
    }
    
    /* Select2 Styling for Dark Mode */
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
      background-color: var(--input-bg) !important;
      border-color: var(--border-color) !important;
      color: #f9fafb !important;
      border-radius: 0 !important;
      height: 44px !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-selection__rendered {
      line-height: 42px !important;
      color: #f9fafb !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown {
      background-color: #1f2937 !important;
      border-color: #374151 !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-search--dropdown {
      background-color: #1f2937 !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
      background-color: #374151 !important;
      border-color: #4b5563 !important;
      color: #f9fafb !important;
      padding: 0.5rem !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
      border-color: #6366f1 !important;
      outline: none !important;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
      background-color: #374151 !important;
      color: #f9fafb !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results {
      background-color: #1f2937 !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option {
      background-color: #1f2937 !important;
      color: #f9fafb !important;
      padding: 0.5rem 0.75rem !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--highlighted {
      background-color: #6366f1 !important;
      color: #ffffff !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
      background-color: #4b5563 !important;
      color: #ffffff !important;
      font-weight: 600 !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--selectable {
      color: #f9fafb !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--selectable:hover {
      background-color: #374151 !important;
      color: #ffffff !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-selection__arrow {
      color: #f9fafb !important;
    }
    
    [data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--loading {
      color: #9ca3af !important;
    }
    
    /* Text Muted - Red Color */
    .text-muted {
      color: #dc2626 !important;
    }
    
    [data-theme="dark"] .text-muted {
      color: #ef4444 !important;
    }
    
    /* TinyMCE Styling */
    .tox-tinymce {
      border: 2px solid #eee;
      border-radius: 0px !important;
    }
    
    /* TinyMCE Code Editor Height */
    .tox-dialog__body-content .tox-textarea {
      min-height: 400px !important;
    }
    
    .tox-dialog--width-lg {
      max-width: 800px !important;
    }
    
    /* TinyMCE Dark Mode */
    [data-theme="dark"] .tox-tinymce {
      border: 2px solid var(--border-color) !important;
    }
    
    [data-theme="dark"] .tox .tox-toolbar,
    [data-theme="dark"] .tox .tox-toolbar__overflow,
    [data-theme="dark"] .tox .tox-toolbar__primary {
      background-color: #2b2b2b !important;
    }
    
    [data-theme="dark"] .tox .tox-statusbar {
      background-color: #2b2b2b !important;
      border-top-color: var(--border-color) !important;
    }
    
    /* TinyMCE Code Editor Dark Mode */
    [data-theme="dark"] .tox-dialog__body-content .tox-textarea {
      background-color: #1a1a1a !important;
      color: #f9fafb !important;
      border-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .tox .tox-dialog {
      background-color: var(--card-bg) !important;
      border-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .tox .tox-dialog__header {
      background-color: var(--card-bg) !important;
      border-bottom-color: var(--border-color) !important;
      color: var(--text-color) !important;
    }
    
    [data-theme="dark"] .tox .tox-dialog__title {
      color: var(--text-color) !important;
    }
    
    [data-theme="dark"] .tox .tox-dialog__footer {
      background-color: var(--card-bg) !important;
      border-top-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .tox .tox-label,
    [data-theme="dark"] .tox .tox-toolbar-label {
      color: var(--text-color) !important;
    }
    [data-theme="dark"] .card-body {
    border-radius: 15px !important;
}
[data-theme="dark"] .table-hover tbody tr:hover {
      background: var(--hover-bg) !important;
    }

    /* ========================================
       GLOBAL DATE & TIME PICKER STYLES
       ======================================== */
    
    /* Wrapper for icon + input */
    input[type="date"],
    input[type="time"],
    input[type="datetime-local"] {
      padding-left: 40px !important;
      height: 45px;
      border-radius: 10px;
      border: 2px solid #e2e8f0;
      background: white;
      font-size: 0.95rem;
      font-weight: 500;
      color: #1e293b;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
    }

    /* Hide native calendar/clock icon */
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator,
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
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

    /* Hover States */
    input[type="date"]:hover,
    input[type="time"]:hover,
    input[type="datetime-local"]:hover {
      border-color: #3b82f6;
      background: #f8fafc;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    /* Focus States */
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="datetime-local"]:focus {
      border-color: #3b82f6;
      background: white;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    /* Placeholder styling */
    input[type="date"]:invalid,
    input[type="time"]:invalid,
    input[type="datetime-local"]:invalid {
      color: #94a3b8;
    }

    /* Selected date/time styling */
    input[type="date"]:valid,
    input[type="time"]:valid,
    input[type="datetime-local"]:valid {
      color: #1e293b;
      font-weight: 600;
    }

    /* Dark Mode for Date/Time Pickers */
    [data-theme="dark"] input[type="date"],
    [data-theme="dark"] input[type="time"],
    [data-theme="dark"] input[type="datetime-local"] {
      background: #1f2937;
      border-color: #374151;
      color: #e5e7eb;
    }

    [data-theme="dark"] input[type="date"]:hover,
    [data-theme="dark"] input[type="time"]:hover,
    [data-theme="dark"] input[type="datetime-local"]:hover {
      background: #374151;
      border-color: #3b82f6;
    }

    [data-theme="dark"] input[type="date"]:focus,
    [data-theme="dark"] input[type="time"]:focus,
    [data-theme="dark"] input[type="datetime-local"]:focus {
      background: #1f2937;
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }

    [data-theme="dark"] input[type="date"]:invalid,
    [data-theme="dark"] input[type="time"]:invalid,
    [data-theme="dark"] input[type="datetime-local"]:invalid {
      color: #6b7280;
    }

    [data-theme="dark"] input[type="date"]:valid,
    [data-theme="dark"] input[type="time"]:valid,
    [data-theme="dark"] input[type="datetime-local"]:valid {
      color: #e5e7eb;
    }

    /* ========================================
       END DATE & TIME PICKER STYLES
       ======================================== */
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
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.terms.*') ? 'active' : '' }}" 
                   href="{{ route('admin.terms.manage') }}">
                  <i class="bi bi-file-text me-2"></i>Terms & Conditions
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" 
                   href="{{ route('admin.reviews.manage') }}">
                  <i class="bi bi-star me-2"></i>Reviews
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

  {{-- jQuery (required for Select2) --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  
  {{-- Bootstrap 5 JS Bundle (includes Popper) --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  {{-- Select2 for searchable dropdowns --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  {{-- Theme Toggle Script --}}
  <script>
    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
      const savedTheme = localStorage.getItem('theme') || 'dark';
      document.documentElement.setAttribute('data-theme', savedTheme);
      
      // Initialize Select2 on all select elements
      initializeSelect2();
    });

    // Toggle theme function
    function toggleTheme() {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      
      // Reinitialize Select2 after theme change
      setTimeout(() => {
        initializeSelect2();
      }, 100);
    }
    
    // Initialize Select2 on all select elements
    function initializeSelect2() {
      // Destroy existing Select2 instances
      $('.select2-hidden-accessible').select2('destroy');
      
      // Initialize Select2 on all select elements
      $('select.form-select, select').each(function() {
        // Skip if already initialized or if it's a specific element that shouldn't have Select2
        if ($(this).hasClass('select2-hidden-accessible')) {
          return;
        }
        
        // Skip select elements that are inside modals or templates (they'll be initialized when shown)
        if ($(this).closest('.modal, template').length > 0) {
          return;
        }
        
        $(this).select2({
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
            return $(this).find('option:first').text() || 'Select an option';
          }
        });
      });
    }
    
    // Reinitialize Select2 when dynamic content is added
    document.addEventListener('DOMContentLoaded', function() {
      // Watch for dynamically added select elements
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          mutation.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
              // Check if the added node is a select or contains selects
              const selects = node.matches && node.matches('select') 
                ? [node] 
                : node.querySelectorAll ? node.querySelectorAll('select') : [];
              
              selects.forEach(function(select) {
                if (!$(select).hasClass('select2-hidden-accessible') && 
                    !$(select).closest('.modal, template').length) {
                  $(select).select2({
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
                      return $(select).find('option:first').text() || 'Select an option';
                    }
                  });
                }
              });
            }
          });
        });
      });
      
      observer.observe(document.body, {
        childList: true,
        subtree: true
      });
    });

    document.addEventListener('DOMContentLoaded', function () {
  // Function to initialize TinyMCE with theme
  function initTinyMCE() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    
    tinymce.init({
      selector: '#content',
      height: 500,
      menubar: 'file edit view insert format tools table help',
      plugins: 'link image media codesample table lists advlist autoresize code paste',
      toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | code',
      branding: false,
      
      // Dark mode support
      skin: isDark ? 'oxide-dark' : 'oxide',
      content_css: isDark ? 'dark' : 'default',

      // URLs in content should be absolute (no relative or base64)
      convert_urls: false,
      relative_urls: false,
      remove_script_host: false,
      paste_data_images: false,

      // Image upload -> Laravel route
      automatic_uploads: true,
      images_upload_url: '{{ route('tinymce.upload') }}',
      file_picker_types: 'image',

      // Add CSRF header for Laravel
      images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
          const xhr = new XMLHttpRequest();
          xhr.open('POST', '{{ route('tinymce.upload') }}');
          xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
          xhr.upload.onprogress = (e) => {
            progress(e.loaded / e.total * 100);
          };
          xhr.onload = function () {
            if (xhr.status < 200 || xhr.status >= 300) {
              return reject('HTTP Error: ' + xhr.status);
            }
            try {
              const json = JSON.parse(xhr.responseText);
              if (!json || typeof json.location !== 'string') {
                return reject('Invalid JSON: ' + xhr.responseText);
              }
              resolve(json.location); // TinyMCE will insert this URL
            } catch (e) {
              reject('Parse Error: ' + e.message);
            }
          };
          xhr.onerror = function () {
            reject('Image upload failed due to a XHR Transport error.');
          };
          const formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          xhr.send(formData);
        });
      },
    });
  }
  
  // Initialize TinyMCE
  initTinyMCE();
  
  // Reinitialize TinyMCE when theme changes
  const themeObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
        // Remove existing TinyMCE instance
        if (tinymce.get('content')) {
          const content = tinymce.get('content').getContent();
          tinymce.get('content').remove();
          
          // Reinitialize with new theme
          setTimeout(() => {
            initTinyMCE();
            // Restore content after reinitialization
            setTimeout(() => {
              if (tinymce.get('content')) {
                tinymce.get('content').setContent(content);
              }
            }, 100);
          }, 100);
        }
      }
    });
  });
  
  themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-theme']
  });
});
  </script>

  {{-- Global Date/Time Picker Click Handler --}}
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Make date/time pickers clickable anywhere on wrapper
    const dateTimeWrappers = document.querySelectorAll('.date-time-wrapper');
    
    dateTimeWrappers.forEach(wrapper => {
      const input = wrapper.querySelector('.date-time-input');
      
      if (input) {
        // Click on wrapper opens the picker
        wrapper.addEventListener('click', function(e) {
          // Don't trigger if already clicking on input
          if (e.target !== input) {
            if (input.showPicker) {
              try {
                input.showPicker();
              } catch (err) {
                input.focus();
              }
            } else {
              input.focus();
            }
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

    // Auto-apply to all date/time inputs (for backward compatibility)
    const allDateTimeInputs = document.querySelectorAll('input[type="date"], input[type="time"], input[type="datetime-local"]');
    
    allDateTimeInputs.forEach(input => {
      // If not already in a wrapper, make it clickable
      if (!input.closest('.date-time-wrapper')) {
        input.addEventListener('click', function(e) {
          if (this.showPicker) {
            try {
              this.showPicker();
            } catch (err) {
              // Fallback for browsers that don't support showPicker
            }
          }
        });
      }
    });
  });
  </script>
  
  @stack('scripts')
</body>
</html>

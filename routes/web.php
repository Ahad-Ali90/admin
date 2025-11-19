<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingExpenseController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\LeadTrackerController;
use App\Http\Controllers\Admin\ProfitLossController;
use App\Http\Controllers\Admin\LeadSourceController;
use App\Http\Controllers\Admin\TermsAndConditionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\ServiceTypeController;
use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Public Booking Routes (no authentication required)
    Route::prefix('track')->name('public.booking.')->group(function () {
        Route::get('/', [PublicBookingController::class, 'lookup'])->name('lookup');
        Route::post('/search', [PublicBookingController::class, 'search'])->name('search');
        Route::get('/{reference}', [PublicBookingController::class, 'show'])->name('show');
    });
    Route::post('/tinymce/upload', [DashboardController::class, 'tinymce'])->name('tinymce.upload');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/bookings', [CalendarController::class, 'getBookings'])->name('calendar.bookings');
    
    // Finance / Profit & Loss
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    
    // Profit & Loss Report
    Route::get('/profit-loss', [ProfitLossController::class, 'index'])->name('profit-loss.index');
    
    // Lead Tracker
    Route::get('/leads', [LeadTrackerController::class, 'index'])->name('leads.index');
    
    // Tasks (AJAX Single-Page)
    Route::get('/tasks/manage', [TaskController::class, 'manage'])->name('tasks.manage');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    
    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::get('/bookings/{booking}/print', [BookingController::class, 'print'])->name('bookings.print');
    Route::post('/bookings/{booking}/extra-hours', [BookingController::class, 'addExtraHours'])->name('bookings.extra-hours');
    Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::post('/bookings/{booking}/start', [BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    

    // Booking Expenses
    Route::get('/bookings/{booking}/expenses', [BookingExpenseController::class, 'index'])->name('bookings.expenses');
    Route::post('/bookings/{booking}/expenses', [BookingExpenseController::class, 'store'])->name('bookings.expenses.store');
    Route::put('/bookings/{booking}/expenses/{expense}', [BookingExpenseController::class, 'update'])->name('bookings.expenses.update');
    Route::delete('/bookings/{booking}/expenses/{expense}', [BookingExpenseController::class, 'destroy'])->name('bookings.expenses.destroy');
    
    // Customers
    Route::resource('customers', CustomerController::class);
    
    // Companies (AJAX CRUD)
    Route::get('/companies/manage', [CompanyController::class, 'manage'])->name('companies.manage');
    Route::get('/companies/{company}/details', [CompanyController::class, 'details'])->name('companies.details');
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    
    Route::get('/service-types/manage', [ServiceTypeController::class, 'manage'])->name('service_types.manage');

    // JSON CRUD
    Route::get('/service-types',          [ServiceTypeController::class, 'index'])->name('service_types.index');
    Route::post('/service-types',         [ServiceTypeController::class, 'store'])->name('service_types.store');
    Route::get('/service-types/{id}',     [ServiceTypeController::class, 'show'])->name('service_types.show');
    Route::put('/service-types/{id}',     [ServiceTypeController::class, 'update'])->name('service_types.update');
    Route::delete('/service-types/{id}',  [ServiceTypeController::class, 'destroy'])->name('service_types.destroy');
    
    // Lead Sources (AJAX CRUD)
    Route::get('/lead-sources/manage', [LeadSourceController::class, 'manage'])->name('lead-sources.manage');
    Route::get('/lead-sources', [LeadSourceController::class, 'index'])->name('lead-sources.index');
    Route::post('/lead-sources', [LeadSourceController::class, 'store'])->name('lead-sources.store');
    Route::get('/lead-sources/{id}/details', [LeadSourceController::class, 'details'])->name('lead-sources.details');
    Route::get('/lead-sources/{id}', [LeadSourceController::class, 'show'])->name('lead-sources.show');
    Route::put('/lead-sources/{id}', [LeadSourceController::class, 'update'])->name('lead-sources.update');
    Route::delete('/lead-sources/{id}', [LeadSourceController::class, 'destroy'])->name('lead-sources.destroy');
    
    // Terms and Conditions
    Route::get('/terms/manage', [TermsAndConditionController::class, 'manage'])->name('terms.manage');
    Route::resource('terms', TermsAndConditionController::class);
    
    // Reviews
    Route::get('/reviews/manage', [ReviewController::class, 'manage'])->name('reviews.manage');
    Route::get('/reviews/bookings', [ReviewController::class, 'getBookings'])->name('reviews.bookings');
    Route::resource('reviews', ReviewController::class);
    
    // Invoices
    Route::get('/bookings/{booking}/invoice/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/bookings/{booking}/invoice/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'show'])->name('invoices.show');

    // Single-page UI
    Route::get('/vehicles/manage', [VehicleController::class, 'manage'])->name('vehicles.manage');
    Route::get('/vehicles/{vehicle}/details', [VehicleController::class, 'details'])->name('vehicles.details');
    
    // Vehicle Expenses
    Route::get('/vehicles/{vehicle}/expenses', [\App\Http\Controllers\Admin\VehicleExpenseController::class, 'index'])->name('vehicles.expenses');
    Route::post('/vehicles/{vehicle}/expenses', [\App\Http\Controllers\Admin\VehicleExpenseController::class, 'store'])->name('vehicles.expenses.store');
    Route::put('/vehicles/{vehicle}/expenses/{expense}', [\App\Http\Controllers\Admin\VehicleExpenseController::class, 'update'])->name('vehicles.expenses.update');
    Route::delete('/vehicles/{vehicle}/expenses/{expense}', [\App\Http\Controllers\Admin\VehicleExpenseController::class, 'destroy'])->name('vehicles.expenses.destroy');

    // JSON CRUD
    Route::get('/vehicles',        [VehicleController::class, 'index'])->name('vehicles.index');   // JSON list
    Route::post('/vehicles',       [VehicleController::class, 'store'])->name('vehicles.store');   // create
    Route::get('/vehicles/{id}',   [VehicleController::class, 'show'])->name('vehicles.show');     // get one
    Route::put('/vehicles/{id}',   [VehicleController::class, 'update'])->name('vehicles.update'); // update
    Route::delete('/vehicles/{id}',[VehicleController::class, 'destroy'])->name('vehicles.destroy');// delete

        // Single-page UI
    Route::get('/staff/manage', [StaffController::class, 'manage'])->name('staff.manage');

    // JSON CRUD
    Route::get('/staff',         [StaffController::class, 'index'])->name('staff.index');         // list (JSON)
    Route::post('/staff',        [StaffController::class, 'store'])->name('staff.store');         // create
    Route::get('/staff/{id}',    [StaffController::class, 'show'])->whereNumber('id')->name('staff.show');      // one
    Route::put('/staff/{id}',    [StaffController::class, 'update'])->whereNumber('id')->name('staff.update');  // update
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->whereNumber('id')->name('staff.destroy');// delete
});

// Legacy dashboard route (redirect to admin)
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

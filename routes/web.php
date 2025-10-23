<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\ServiceTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/extra-hours', [BookingController::class, 'addExtraHours'])->name('bookings.extra-hours');
    Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::post('/bookings/{booking}/start', [BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    
    // Customers
    Route::resource('customers', CustomerController::class);
    
    
    Route::get('/service-types/manage', [ServiceTypeController::class, 'manage'])->name('service_types.manage');

    // JSON CRUD
    Route::get('/service-types',          [ServiceTypeController::class, 'index'])->name('service_types.index');
    Route::post('/service-types',         [ServiceTypeController::class, 'store'])->name('service_types.store');
    Route::get('/service-types/{id}',     [ServiceTypeController::class, 'show'])->name('service_types.show');
    Route::put('/service-types/{id}',     [ServiceTypeController::class, 'update'])->name('service_types.update');
    Route::delete('/service-types/{id}',  [ServiceTypeController::class, 'destroy'])->name('service_types.destroy');

    // Single-page UI
    Route::get('/vehicles/manage', [VehicleController::class, 'manage'])->name('vehicles.manage');

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

<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AppointmentManagementController;
use App\Models\Car;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'carCount' => Car::count(),
    ]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'user'])
    ->middleware('auth')
    ->name('user.dashboard');

Route::get('/admin-dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('sales', SaleController::class)->only([
        'create', 'store', 'edit', 'update', 'destroy',
    ]);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('appointments', [AppointmentManagementController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{appointment}/edit', [AppointmentManagementController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{appointment}', [AppointmentManagementController::class, 'update'])->name('appointments.update');
        Route::patch('appointments/{appointment}/update-status', [AppointmentManagementController::class, 'updateStatus'])->name('appointments.update-status');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::resource('cars', CarController::class);
    Route::resource('issues', IssueController::class);
    Route::resource('appointments', AppointmentController::class)->only([
        'index', 'create', 'store', 'show',
    ]);
    Route::resource('sales', SaleController::class)->only([
        'index', 'show',
    ]);
});

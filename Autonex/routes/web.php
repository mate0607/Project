<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
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
    Route::resource('cars', CarController::class);
    Route::resource('issues', IssueController::class);
    Route::resource('sales', SaleController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('appointments', AppointmentController::class)->only([
        'index', 'create', 'store', 'show',
    ]);
});

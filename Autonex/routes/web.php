<?php

use App\Http\Controllers\Admin\AppointmentManagementController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Nyitooldal
Route::get('/', function () {
    return view('welcome');
});

// Laravel alap auth route-ok (login, register, logout, stb.).
Auth::routes();

// Legacy home route: az auth scaffolding erre iranyit bejelentkezes utan.
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Felhasznaloi dashboard (auth kotelezett).
Route::get('/dashboard', [DashboardController::class, 'user'])
    ->middleware('auth')
    ->name('user.dashboard');

// Admin dashboard (kulon admin jogosultsag ellenorzessel).
Route::get('/admin-dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

// Admin-only route csoport: mutalo jellegu, modosito muveletek.
Route::middleware(['auth', 'admin'])->group(function () {
    // Sales eroforrasbol csak admin altal vegezheto muveletek.
    Route::resource('sales', SaleController::class)->only([
        'create', 'store', 'edit', 'update', 'destroy',
    ]);
    Route::delete('sales/{sale}/images/{image}', [SaleController::class, 'destroyImage'])->name('sales.images.destroy');

    // Admin idopontkezelo modul dedikalt route nevekkel.
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('appointments', [AppointmentManagementController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{appointment}', [AppointmentManagementController::class, 'show'])->name('appointments.show');
        Route::get('appointments/{appointment}/edit', [AppointmentManagementController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{appointment}', [AppointmentManagementController::class, 'update'])->name('appointments.update');
        Route::patch('appointments/{appointment}/update-status', [AppointmentManagementController::class, 'updateStatus'])->name('appointments.update-status');
        Route::delete('service-photos/{photo}', [AppointmentManagementController::class, 'destroyPhoto'])->name('service-photos.destroy');

        Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
        Route::post('notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
        Route::delete('notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    });
});

// Bejelentkezett felhasznalok altal elerheto route-ok.
Route::middleware(['auth'])->group(function () {
    // Profil beallitasok.
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Sajat jarmuvek teljes CRUD.
    Route::resource('cars', CarController::class);

    // Idopontoknal csak a felhasznalo altal hasznalt route-ok maradnak nyitva.
    Route::resource('appointments', AppointmentController::class)->only([
        'index', 'create', 'store', 'show',
    ]);

    // Sales piacter oldalak: listazas + megtekintes minden auth usernek.
    Route::resource('sales', SaleController::class)->only([
        'index', 'show',
    ]);
});


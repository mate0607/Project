<?php

use App\Http\Controllers\VehicleDataController;
use Illuminate\Support\Facades\Route;

Route::prefix('vehicles')->group(function () {
    Route::get('types', [VehicleDataController::class, 'types']);
    Route::get('brands', [VehicleDataController::class, 'brands']);
    Route::get('models', [VehicleDataController::class, 'models']);
    Route::get('body-types', [VehicleDataController::class, 'bodyTypes']);
});

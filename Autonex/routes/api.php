<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\IssueController;

Route::name('api.')->group(function () {
	Route::apiResource('cars', CarController::class);
	Route::apiResource('sales', SaleController::class);
	Route::apiResource('appointments', AppointmentController::class);
	Route::apiResource('messages', MessageController::class);
	Route::apiResource('issues', IssueController::class);
});
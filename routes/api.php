<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StaffAuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::post('/staff/login', [StaffAuthController::class, 'login']);

// Protected routes (require valid Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:staff')->group(function () {
    Route::post('/packages', [PackageController::class, 'store']);
    Route::put('/packages/{id}', [PackageController::class, 'update']);
    Route::delete('/packages/{id}', [PackageController::class, 'destroy']);
    Route::post('/staff/register', [StaffAuthController::class, 'register']);
    Route::get('/staff/profile', [StaffAuthController::class, 'profile']);
    Route::post('/staff/logout', [StaffAuthController::class, 'logout']);
});

Route::middleware('auth:staff')->group(function () {
    Route::get('/staff/profile', [StaffAuthController::class, 'profile']);
    Route::post('/staff/logout', [StaffAuthController::class, 'logout']);
});


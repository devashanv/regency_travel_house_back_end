<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ComplaintController;


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::post('/customers/register', [CustomerController::class, 'register']);
Route::post('/customers/login', [CustomerController::class, 'login']);

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/customers/logout', [CustomerController::class, 'logout']); 
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{customer}', [CustomerController::class, 'show']);
    Route::put('/customers/{customer}', [CustomerController::class, 'update']);
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']); // Get all bookings
    Route::post('/bookings', [BookingController::class, 'store']); // Create a new booking
    Route::get('/bookings/{booking}', [BookingController::class, 'show']); // Get a specific booking
    Route::put('/bookings/{booking}', [BookingController::class, 'update']); // Update a booking
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']); // Delete a booking
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show']);
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update']);
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy']);
});

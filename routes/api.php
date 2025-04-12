<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\DestinationController;
// use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ItineraryController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);

Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::post('/customers/register', [CustomerController::class, 'register']);
Route::post('/customers/login', [CustomerController::class, 'login']);
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{id}', [DestinationController::class, 'show']);

Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);
Route::get('/packages/{package_id}/itineraries', [ItineraryController::class, 'index']);

// Protected Customer routes (require valid Sanctum token)
Route::middleware('auth:sanctum')->get('/booking/confirmed', [BookingController::class, 'confirmed']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/bookings', [BookingController::class, 'index']); // customer view
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    // Route::get('/booking/confirmed', [BookingController::class,'confirmed']);




    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/customer/profile', [CustomerAuthController::class, 'profile']);
    Route::put('/customer/profile', [CustomerController::class, 'profile']);
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout']);

    Route::get('/wishlist', [WishlistController::class, 'index']); //done
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);

    Route::get('/customer/loyalty', [CustomerAuthController::class, 'loyaltyPoints']);
    Route::get('/customer/loyalty/history', [CustomerAuthController::class, 'loyaltyHistory']);
});


// Protected Staff routes
Route::middleware('auth:staff')->group(function () {
    Route::post('/packages', [PackageController::class, 'store']);
    Route::put('/packages/{id}', [PackageController::class, 'update']);
    Route::delete('/packages/{id}', [PackageController::class, 'destroy']);
    Route::post('/staff/register', [StaffAuthController::class, 'register']);
    Route::get('/staff/profile', [StaffAuthController::class, 'profile']);
    Route::post('/staff/logout', [StaffAuthController::class, 'logout']);
    // Route::get('/admin/bookings', [AdminBookingController::class, 'index']);
    // Route::get('/admin/bookings/{id}', [AdminBookingController::class, 'show']);
    // Route::put('/admin/bookings/{id}', [AdminBookingController::class, 'update']);
    Route::get('/staff/profile', [StaffAuthController::class, 'profile']);
    Route::post('/staff/logout', [StaffAuthController::class, 'logout']);
    Route::post('/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{id}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{id}', [DestinationController::class, 'destroy']);
    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::put('/itineraries/{id}', [ItineraryController::class, 'update']);
    Route::delete('/itineraries/{id}', [ItineraryController::class, 'destroy']);
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

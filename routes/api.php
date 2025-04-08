<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\WishlistController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{id}', [DestinationController::class, 'show']);
Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);

// Protected Customer routes (require valid Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/bookings', [BookingController::class, 'index']); // customer view
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/customer/profile', [CustomerAuthController::class, 'profile']);
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
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
    Route::get('/admin/bookings', [AdminBookingController::class, 'index']);
    Route::get('/admin/bookings/{id}', [AdminBookingController::class, 'show']);
    Route::put('/admin/bookings/{id}', [AdminBookingController::class, 'update']);
    Route::get('/staff/profile', [StaffAuthController::class, 'profile']);
    Route::post('/staff/logout', [StaffAuthController::class, 'logout']);
    Route::post('/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{id}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{id}', [DestinationController::class, 'destroy']);
});


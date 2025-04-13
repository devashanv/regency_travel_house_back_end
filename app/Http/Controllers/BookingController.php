<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Casts\Json;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Loyalty;
use Illuminate\Support\Facades\DB;
use Laravel\Pail\ValueObjects\Origin\Console;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $customer = Auth::user();
        $bookings = Booking::with('package')->where('customer_id', $customer->id)->get();
        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
{
    $customer = Auth::user();

    $request->validate([
        'package_id' => 'required|exists:packages,id',
        'travel_date' => 'required|date|after:today',
        'number_of_travelers' => 'required|integer|min:1',
        'points_to_redeem' => 'nullable|numeric|min:0'
    ]);

    $package = Package::findOrFail($request->package_id);
    $basePrice = $package->price_per_person * $request->number_of_travelers;
    $redeem = floatval($request->points_to_redeem ?? 0);

    // Step 1: Check redemption eligibility
    if ($redeem > 0) {
        $confirmedCount = $customer->bookings()
            ->where('status', 'confirmed')
            ->orWhere('status', 'completed')
            ->count();

        if ($confirmedCount < 2) {
            return response()->json([
                'message' => 'Redemption requires at least 2 confirmed or completed bookings.'
            ], 403);
        }

        // Step 2: Fetch valid points
        $validEarned = $customer->loyaltyHistory()
            ->where('last_updated', '>', now()->subYears(2))
            ->sum('points_earned');

        $totalRedeemed = $customer->loyaltyHistory()->sum('points_redeemed');
        $availablePoints = $validEarned - $totalRedeemed;

        $redeem = min($redeem, $availablePoints, $basePrice);
    }

    $finalPrice = $basePrice - $redeem;

    DB::beginTransaction();

    try {
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'booking_date' => now(),
            'travel_date' => $request->travel_date,
            'number_of_travelers' => $request->number_of_travelers,
            'total_price' => $finalPrice,
            'status' => 'pending',
            'payment_reference' => null
        ]);

        if ($redeem > 0) {
            $customer->decrement('loyalty_points', $redeem);

            Loyalty::create([
                'customer_id' => $customer->id,
                'points_earned' => 0,
                'points_redeemed' => $redeem,
                'last_updated' => now()
            ]);
        }

        DB::commit();

        return response()->json($booking, 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Booking failed', 'error' => $e->getMessage()], 500);
    }
}
}

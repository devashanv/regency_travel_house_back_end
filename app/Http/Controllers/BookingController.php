<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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


    // public function show(int $id): JsonResponse
    // {
    //     $booking = Booking::with('package')->find($id);
    //     if (!$booking || $booking->customer_id !== Auth::id()) {
    //         return response()->json(['message' => 'Not found'], 404);
    //     }
    //     return response()->json($booking);
    // }

    public function show($id): JsonResponse
    {
        $id = (int) $id;
        $booking = Booking::with('package')->find($id);
        if (!$booking || $booking->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($booking);
    }

    public function confirmed(): JsonResponse
    {
        $customer = Auth::user(); 

        $confirmBookings = Booking::with('package') 
            ->where('customer_id', $customer->id)
            ->where('status', 'confirmed')
            ->whereDate('travel_date', '>=', now())
            ->orderBy('travel_date')
            ->get();

        return response()->json($confirmBookings);
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

        $package = \App\Models\Package::findOrFail($request->package_id);
        $basePrice = $package->price_per_person * $request->number_of_travelers;
        $redeem = $request->points_to_redeem ?? 0;

        // Cap redemption to customer's available points
        $redeem = min($redeem, $customer->loyalty_points);

        // Cap redemption to booking total (no negative totals)
        $redeem = min($redeem, $basePrice);

        $finalPrice = $basePrice - $redeem;

        DB::beginTransaction();

        try {
            // Create the booking
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

            // If redeeming points, deduct and log
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

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $customer = Auth::user();
        $bookings = Booking::with('package')->where('customer_id', $customer->id)->get();
        return response()->json($bookings);
    }

    public function show(int $id): JsonResponse
    {
        $booking = Booking::with('package')->find($id);
        if (!$booking || $booking->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($booking);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'travel_date' => 'required|date|after:today',
            'number_of_travelers' => 'required|integer|min:1'
        ]);

        $package = \App\Models\Package::find($request->package_id);
        $price = $package->price_per_person * $request->number_of_travelers;

        $booking = Booking::create([
            'customer_id' => Auth::id(),
            'package_id' => $package->id,
            'booking_date' => now(),
            'travel_date' => $request->travel_date,
            'number_of_travelers' => $request->number_of_travelers,
            'total_price' => $price,
            'status' => 'pending',
            'payment_reference' => null
        ]);

        return response()->json($booking, 201);
    }
}

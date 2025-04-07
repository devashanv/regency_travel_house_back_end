<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    private function isCustomer(): bool
    {
        return Auth::guard('sanctum')->check();
    }
    


    public function index():JsonResponse
    {
        $bookings = Booking::with(['customer', 'package'])->get();
        return response()->json($bookings, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):JsonResponse
    {
        if (!$this->isCustomer()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'booking_date' => 'required|date',
            'travel_date' => 'required|date|after_or_equal:booking_date',
            'number_of_travelers' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'payment_reference' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking = Booking::create($request->all());

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }


    public function show(Booking $booking)
    {
        $booking->load(['customer', 'package']);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        return response()->json($booking, 200);
    }


    public function update(Request $request, Booking $booking)
    {
        if (!$this->isCustomer()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes|exists:customers,id',
            'package_id' => 'sometimes|exists:packages,id',
            'booking_date' => 'sometimes|date',
            'travel_date' => 'sometimes|date|after_or_equal:booking_date',
            'number_of_travelers' => 'sometimes|integer|min:1',
            'total_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string',
            'payment_reference' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking->update($request->all());

        return response()->json([
            'message' => 'Booking updated successfully',
            'data' => $booking
        ]);
    }

    public function destroy(Booking $booking)
    {
        if (!$this->isCustomer()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $booking = Booking::find($booking->id);

        if (!$booking) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }
}

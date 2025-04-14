<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Loyalty;


class AdminBookingController extends Controller
{
    public function index(): JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        if ($staff->role !== 'Admin' && $staff->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $bookings = Booking::with(['customer', 'package'])->orderBy('created_at', 'desc')->get();

        return response()->json($bookings);
    }

    public function show(int $id): JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        if ($staff->role !== 'Admin' && $staff->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::with(['customer', 'package'])->find($id);

        return $booking
            ? response()->json($booking)
            : response()->json(['message' => 'Booking not found'], 404);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        if (!in_array($staff->role, ['Admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::with('customer')->find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $request->validate([
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'payment_reference' => 'nullable|string|max:255'
        ]);

        $oldStatus = $booking->status;
        $booking->update($request->only(['status', 'payment_reference']));

        $newStatus = $request->status;
        $customer = $booking->customer;

        // Loyalty logic ONLY when transitioning to confirmed/completed from a non-qualified state
        if (in_array($newStatus, ['confirmed', 'completed']) && !in_array($oldStatus, ['confirmed', 'completed'])) {
            $pointsEarned = $booking->total_price * 0.1;

            $customer->increment('loyalty_points', $pointsEarned);

            Loyalty::create([
                'customer_id' => $customer->id,
                'points_earned' => $pointsEarned,
                'points_redeemed' => 0,
                'last_updated' => now()
            ]);
        }

        return response()->json([
            'message' => 'Booking updated successfully.',
            'booking' => $booking->fresh(['customer', 'package'])
        ]);
    }
    
    //Admin dashboard
    public function summary(): JsonResponse
    {
        return response()->json([
            'total_packages' => \App\Models\Package::count(),
            'upcoming_bookings' => \App\Models\Booking::whereDate('travel_date', '>', now())->count(),
            'pending_quotes' => \App\Models\Quote::where('status', 'pending')->count(),
            'loyalty_customers' => \App\Models\Loyalty::where('points_earned', '>', 0)->count(),
            'registered_customers' => \App\Models\Customer::count(),
        ]);
    }
}

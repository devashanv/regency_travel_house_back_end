<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Quote;
use App\Models\Customer;

class DashboardController extends Controller
{
    // Dashboard Stats (Role-Based)
    public function staffOverview(Request $request): JsonResponse
    {
        $user = auth('staff')->user();

        $data = [
            'total_packages' => Package::count(),
            'upcoming_bookings' => Booking::where('start_date', '>=', now())->count(),
            'total_customers' => Customer::count(),
        ];

        if ($user->role === 'Admin' || $user->role === 'Manager') {
            $data['pending_quotes'] = Quote::where('status', 'pending')->count();
            $data['loyalty_customers'] = Customer::where('loyalty_points', '>', 0)->count();
        }

        return response()->json($data);
    }

    // Optional: Recent Activities
    public function recentActivities(): JsonResponse
    {
        return response()->json([
            'recent_bookings' => Booking::latest()->take(5)->get(),
            'recent_quotes' => Quote::latest()->take(5)->get(),
        ]);
    }

    // Optional: Get Logged In Staff Role
    public function getRole(): JsonResponse
    {
        return response()->json([
            'role' => auth('staff')->user()->role,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'pending_quotes' => Quote::where('status', 'pending')->count(),
            'total_customers' => Customer::count(),
            'total_packages' => Package::count(),
            'top_packages' => Booking::select('package_id', DB::raw('count(*) as bookings'))
                ->groupBy('package_id')
                ->orderByDesc('bookings')
                ->take(5)
                ->with('package')
                ->get()
                ->map(function ($item) {
                    return [
                        'package_name' => $item->package->name ?? 'N/A',
                        'bookings' => $item->bookings,
                    ];
                })
        ]);
    }
}

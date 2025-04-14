<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    // Get all wishlist items for the logged-in customer
    public function index()
    {
        $customer = Auth::user();

        $wishlist = Wishlist::with('package')
            ->where('customer_id', $customer->id)
            ->orderBy('added_on', 'desc')
            ->get();

        return response()->json($wishlist);
    }

    // Add a new package to wishlist
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $customer = Auth::user();

        // Prevent duplicates
        if (Wishlist::where('customer_id', $customer->id)
            ->where('package_id', $request->package_id)->exists()) {
            return response()->json(['message' => 'Already in wishlist'], 409);
        }

        $wishlist = Wishlist::create([
            'customer_id' => $customer->id,
            'package_id' => $request->package_id,
            'added_on' => now(),
        ]);

        return response()->json(['message' => 'Added to wishlist', 'wishlist' => $wishlist], 201);
    }

    // Remove a wishlist item
    public function destroy($id)
    {
        $customer = Auth::user();

        $wishlist = Wishlist::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist item not found'], 404);
        }

        $wishlist->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminQuoteController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        if (!in_array($staff->role, ['Admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quotes = Quote::with(['customer', 'package', 'respondedBy'])->orderBy('created_at', 'desc')->get();
        return response()->json($quotes);
    }

    public function show($id)
    {
        $staff = Auth::guard('staff')->user();

        if (!in_array($staff->role, ['Admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quote = Quote::with(['customer', 'package', 'respondedBy'])->find($id);

        return $quote
            ? response()->json($quote)
            : response()->json(['message' => 'Quote not found'], 404);
    }

    public function respond(Request $request, $id)
    {
        $staff = Auth::guard('staff')->user();

        if (!in_array($staff->role, ['Admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'estimated_price' => 'required|numeric|min:0',
            'status' => 'required|in:responded,rejected'
        ]);

        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json(['message' => 'Quote not found'], 404);
        }

        if ($quote->status !== 'pending') {
            return response()->json(['message' => 'This quote has already been responded to.'], 409);
        }

        $quote->update([
            'estimated_price' => $request->estimated_price,
            'status' => $request->status,
            'responded_by' => $staff->id
        ]);

        return response()->json([
            'message' => 'Quote responded successfully.',
            'quote' => $quote->load('customer', 'package', 'respondedBy')
        ]);
    }
}

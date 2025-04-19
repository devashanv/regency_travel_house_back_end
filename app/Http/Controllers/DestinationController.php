<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Destination::all());
    }

    public function show(int $id): JsonResponse
    {
        $destination = Destination::with('packages')->find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        return response()->json($destination);
    }

    public function store(Request $request): JsonResponse
    {
        if (Auth::guard('staff')->user()?->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_string' => 'nullable|string',
            'best_time_to_visit' => 'nullable|string|max:255',
        ]);

        $destination = Destination::create($validated);
        return response()->json($destination, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (Auth::guard('staff')->user()?->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'region' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_string' => 'nullable|string',
            'best_time_to_visit' => 'nullable|string|max:255',
        ]);

        $destination->update($validated);
        return response()->json($destination);
    }

    public function destroy(int $id): JsonResponse
    {
        if (Auth::guard('staff')->user()?->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $destination->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

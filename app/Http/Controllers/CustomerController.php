<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class CustomerController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'password' => 'required|string|min:8|confirmed', 
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'country_of_residence' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $customer = Customer::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'country_of_residence' => $validated['country_of_residence'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ]);

        $token = $customer->createToken('customer_token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('customer_token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function index()
    {
        return response()->json(Customer::all());
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'id' => $customer->id,
            'full_name' => $customer->full_name,
            'email' => $customer->email,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'country_of_residence' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $customer->update($validated);

        return response()->json($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}

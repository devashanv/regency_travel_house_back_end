<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        Booking::create([
            'customer_id' => 5, // Assuming customer with ID 1 exists
            'package_id' => 1, // Assuming package with ID 1 exists
            'booking_date' => '2025-04-01',
            'travel_date' => '2025-04-10',
            'number_of_travelers' => 2,
            'total_price' => 500.00,
            'status' => 'confirmed',
            'payment_reference' => 'PAY123456',
        ]);

        Booking::create([
            'customer_id' => 4, // Assuming customer with ID 2 exists
            'package_id' => 2, // Assuming package with ID 2 exists
            'booking_date' => '2025-04-05',
            'travel_date' => '2025-04-15',
            'number_of_travelers' => 4,
            'total_price' => 1000.00,
            'status' => 'pending',
            'payment_reference' => 'PAY654321',
        ]);


    }
}

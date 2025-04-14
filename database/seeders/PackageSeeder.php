<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::create([
            'destination_id' => 1, // Bali
            'title' => 'Beach Resort Escape',
            'description' => 'Relax at a luxurious beach resort in Bali. Enjoy the sun, sand, and spa treatments.',
            'price_per_person' => 300.00,
            'duration_days' => 5,
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-06',
            'available_slots' => 20,
            'image_url' => 'https://example.com/images/bali_resort.jpg',
            'is_featured' => true,
        ]);

        Package::create([
            'destination_id' => 2, // Paris
            'title' => 'Romantic Getaway in Paris',
            'description' => 'Spend a romantic weekend in Paris, visiting iconic landmarks like the Eiffel Tower and Notre-Dame.',
            'price_per_person' => 800.00,
            'duration_days' => 3,
            'start_date' => '2025-07-01',
            'end_date' => '2025-07-03',
            'available_slots' => 15,
            'image_url' => 'https://example.com/images/paris_getaway.jpg',
            'is_featured' => true,
        ]);

        Package::create([
            'destination_id' => 3, // Tokyo
            'title' => 'Tokyo City Adventure',
            'description' => 'Experience the vibrant life of Tokyo with shopping, sightseeing, and sushi.',
            'price_per_person' => 500.00,
            'duration_days' => 7,
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-07',
            'available_slots' => 25,
            'image_url' => 'https://example.com/images/tokyo_adventure.jpg',
            'is_featured' => false,
        ]);
    }
}

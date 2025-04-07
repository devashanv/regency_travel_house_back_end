<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        Destination::create([
            'name' => 'Bali',
            'country' => 'Indonesia',
            'description' => 'A tropical paradise known for its beautiful beaches and vibrant culture.',
        ]);

        Destination::create([
            'name' => 'Paris',
            'country' => 'France',
            'description' => 'The city of love, famous for its landmarks like the Eiffel Tower and Louvre Museum.',
        ]);

        Destination::create([
            'name' => 'Tokyo',
            'country' => 'Japan',
            'description' => 'A bustling metropolis blending modern skyscrapers with traditional temples.',
        ]);
    }
}

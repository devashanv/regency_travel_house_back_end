<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class Customereeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            // 'phone' => '0712345678',
            // 'address' => '123 Main Street',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            // 'phone' => '0776543210',
            // 'address' => '456 Oak Avenue',
            'password' => Hash::make('password123'),
        ]);

    }
}

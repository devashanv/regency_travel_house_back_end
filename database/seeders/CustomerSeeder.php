<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a Faker instance
        $faker = Faker::create();

        // Create 10 fake customer records
        foreach (range(1, 10) as $index) {
            Customer::create([
                'full_name' => $faker->name, // Generates a fake name
                'email' => $faker->unique()->safeEmail, // Generates a unique email
                'password' => Hash::make('password123'), // Hashed password for all customers
                'phone' => $faker->phoneNumber, // Generates a fake phone number
                'address' => $faker->address, // Generates a fake address
                'country_of_residence' => $faker->country, // Generates a fake country
                'date_of_birth' => $faker->date, // Generates a fake date of birth
            ]);
        }
    }
}

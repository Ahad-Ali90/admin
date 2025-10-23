<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tbrtransport.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '01234567890',
            'address' => '123 Admin Street, London',
            'status' => 'active',
        ]);

        // Create Booking Grabber
        User::create([
            'name' => 'Booking Manager',
            'email' => 'booking@tbrtransport.com',
            'password' => Hash::make('password'),
            'role' => 'booking_grabber',
            'phone' => '01234567891',
            'address' => '123 Booking Street, London',
            'status' => 'active',
        ]);

        // Create Drivers
        User::create([
            'name' => 'John Driver',
            'email' => 'john.driver@tbrtransport.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '01234567892',
            'address' => '123 Driver Street, London',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Mike Smith',
            'email' => 'mike.smith@tbrtransport.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '01234567893',
            'address' => '124 Driver Street, London',
            'status' => 'active',
        ]);

        // Create Porters
        User::create([
            'name' => 'Sarah Porter',
            'email' => 'sarah.porter@tbrtransport.com',
            'password' => Hash::make('password'),
            'role' => 'porter',
            'phone' => '01234567894',
            'address' => '123 Porter Street, London',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'David Wilson',
            'email' => 'david.wilson@tbrtransport.com',
            'password' => Hash::make('password'),
            'role' => 'porter',
            'phone' => '01234567895',
            'address' => '124 Porter Street, London',
            'status' => 'active',
        ]);
    }
}

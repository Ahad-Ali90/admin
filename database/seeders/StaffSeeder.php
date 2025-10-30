<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = [
            // Drivers
            [
                'name' => 'James Anderson',
                'email' => 'james.driver@company.com',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'phone' => '07700 100001',
                'address' => '12 Fleet Street, London, EC4A 2DY',
                'status' => 'active',
            ],
            [
                'name' => 'Robert Thompson',
                'email' => 'robert.driver@company.com',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'phone' => '07700 100003',
                'address' => '45 Park Lane, Manchester, M1 4BT',
                'status' => 'active',
            ],
            [
                'name' => 'William Harris',
                'email' => 'william.driver@company.com',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'phone' => '07700 100005',
                'address' => '78 High Road, Birmingham, B4 7SL',
                'status' => 'active',
            ],
            
            // Porters
            [
                'name' => 'Daniel Martinez',
                'email' => 'daniel.porter@company.com',
                'password' => Hash::make('password123'),
                'role' => 'porter',
                'phone' => '07700 200001',
                'address' => '23 Station Road, Leeds, LS1 4HY',
                'status' => 'active',
            ],
            [
                'name' => 'Christopher Lee',
                'email' => 'chris.porter@company.com',
                'password' => Hash::make('password123'),
                'role' => 'porter',
                'phone' => '07700 200003',
                'address' => '56 Church Street, Glasgow, G2 3PQ',
                'status' => 'active',
            ],
            [
                'name' => 'Matthew Clark',
                'email' => 'matthew.porter@company.com',
                'password' => Hash::make('password123'),
                'role' => 'porter',
                'phone' => '07700 200005',
                'address' => '89 Market Street, Liverpool, L1 6BH',
                'status' => 'active',
            ],
            
            // Booking Grabbers
            [
                'name' => 'Sophie Bennett',
                'email' => 'sophie.admin@company.com',
                'password' => Hash::make('password123'),
                'role' => 'booking_grabber',
                'phone' => '07700 300001',
                'address' => '34 Office Plaza, London, SW1A 2AA',
                'status' => 'active',
            ],
            [
                'name' => 'Oliver Wright',
                'email' => 'oliver.admin@company.com',
                'password' => Hash::make('password123'),
                'role' => 'booking_grabber',
                'phone' => '07700 300003',
                'address' => '67 Business Centre, Manchester, M2 5DB',
                'status' => 'active',
            ],
        ];

        foreach ($staff as $member) {
            User::create($member);
        }

        $this->command->info('8 staff members created successfully!');
        $this->command->info('- 3 Drivers');
        $this->command->info('- 3 Porters');
        $this->command->info('- 2 Booking Grabbers');
        $this->command->info('');
        $this->command->info('Login credentials for all staff:');
        $this->command->info('Password: password123');
    }
}

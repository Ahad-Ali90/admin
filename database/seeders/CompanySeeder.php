<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Swift Logistics Ltd',
                'email' => 'bookings@swiftlogistics.co.uk',
                'phone' => '020 7946 0958',
                'address' => '45 Commercial Road, London, E1 1LF',
            ],
            [
                'name' => 'Prime Movers UK',
                'email' => 'contact@primemovers.co.uk',
                'phone' => '0161 496 0345',
                'address' => '78 Business Park, Manchester, M1 4BT',
            ],
            [
                'name' => 'Express Removals Group',
                'email' => 'info@expressremovals.com',
                'phone' => '0121 234 5678',
                'address' => '23 Industrial Estate, Birmingham, B5 6QR',
            ],
            [
                'name' => 'Metro Transport Solutions',
                'email' => 'bookings@metrotransport.co.uk',
                'phone' => '0141 567 8901',
                'address' => '156 Transport Hub, Glasgow, G2 3PQ',
            ],
            [
                'name' => 'Capital Courier Services',
                'email' => 'admin@capitalcourier.co.uk',
                'phone' => '020 3456 7890',
                'address' => '92 Logistics Centre, London, SW1A 2AA',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }

        $this->command->info('5 companies created successfully!');
    }
}

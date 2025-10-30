<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '07700 900123',
                'address' => '42 Baker Street, Marylebone, London',
                'postcode' => 'NW1 6XE',
                'customer_source' => 'google',
                'notes' => 'Regular customer, prefers morning slots',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@email.com',
                'phone' => '07700 900234',
                'address' => '15 Oxford Road, Manchester',
                'postcode' => 'M1 5QA',
                'customer_source' => 'referral',
                'notes' => 'Moving house, needs packing service',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@email.com',
                'phone' => '07700 900345',
                'address' => '78 High Street, Birmingham',
                'postcode' => 'B4 7SL',
                'customer_source' => 'website',
                'notes' => 'Office relocation specialist',
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@email.com',
                'phone' => '07700 900456',
                'address' => '23 Park Avenue, Leeds',
                'postcode' => 'LS1 4HY',
                'customer_source' => 'facebook',
                'notes' => 'First time customer, needs guidance',
            ],
            [
                'name' => 'David Taylor',
                'email' => 'david.taylor@email.com',
                'phone' => '07700 900567',
                'address' => '56 Queen Street, Edinburgh',
                'postcode' => 'EH2 3NS',
                'customer_source' => 'advertisement',
                'notes' => 'VIP customer, premium service required',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('5 customers created successfully!');
    }
}

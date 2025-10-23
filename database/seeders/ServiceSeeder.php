<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'House Removals',
                'description' => 'Complete house moving service including packing and unpacking',
                'hourly_rate' => 45.00,
                'pricing_type' => 'hourly',
                'unit' => 'hour',
                'is_active' => true,
            ],
            [
                'name' => 'Office Relocation',
                'description' => 'Professional office moving service with minimal downtime',
                'hourly_rate' => 55.00,
                'pricing_type' => 'hourly',
                'unit' => 'hour',
                'is_active' => true,
            ],
            [
                'name' => 'Packing Service',
                'description' => 'Professional packing and unpacking service',
                'hourly_rate' => 25.00,
                'pricing_type' => 'hourly',
                'unit' => 'hour',
                'is_active' => true,
            ],
            [
                'name' => 'Furniture Assembly',
                'description' => 'Professional furniture assembly and disassembly',
                'hourly_rate' => 35.00,
                'pricing_type' => 'hourly',
                'unit' => 'hour',
                'is_active' => true,
            ],
            [
                'name' => 'Storage Solutions',
                'description' => 'Secure storage options for your belongings',
                'fixed_rate' => 120.00,
                'pricing_type' => 'fixed',
                'unit' => 'month',
                'is_active' => true,
            ],
            [
                'name' => 'Single Item Delivery',
                'description' => 'Delivery of single items or small loads',
                'fixed_rate' => 80.00,
                'pricing_type' => 'fixed',
                'unit' => 'delivery',
                'is_active' => true,
            ],
            [
                'name' => 'Man and Van Service',
                'description' => 'Quick and efficient man and van service',
                'hourly_rate' => 40.00,
                'pricing_type' => 'hourly',
                'unit' => 'hour',
                'is_active' => true,
            ],
            [
                'name' => 'Cleaning Service',
                'description' => 'Post-move cleaning service',
                'hourly_rate' => 30.00,
                'pricing_type' => 'hourly',
                'unit' => 'hour',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

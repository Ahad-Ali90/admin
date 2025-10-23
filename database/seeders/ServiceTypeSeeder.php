<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceTypes = [
            'House Removals',
            'Office Relocation',
            'Packing Service',
            'Furniture Assembly',
            'Storage Solutions',
            'Single Item Delivery',
            'Man and Van Service',
            'Cleaning Service',
            'Piano Moving',
            'Antique Moving',
            'Commercial Moving',
            'International Moving',
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::create([
                'name' => $serviceType,
            ]);
        }
    }
}
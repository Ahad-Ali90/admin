<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeadSource;

class LeadSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            ['name' => 'Website', 'slug' => 'website', 'icon' => 'bi-globe', 'color' => '#4f46e5', 'sort_order' => 1],
            ['name' => 'Phone', 'slug' => 'phone', 'icon' => 'bi-telephone', 'color' => '#10b981', 'sort_order' => 2],
            ['name' => 'Email', 'slug' => 'email', 'icon' => 'bi-envelope', 'color' => '#f59e0b', 'sort_order' => 3],
            ['name' => 'WhatsApp', 'slug' => 'whatsapp', 'icon' => 'bi-whatsapp', 'color' => '#25d366', 'sort_order' => 4],
            ['name' => 'Facebook', 'slug' => 'facebook', 'icon' => 'bi-facebook', 'color' => '#1877f2', 'sort_order' => 5],
            ['name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'bi-instagram', 'color' => '#e4405f', 'sort_order' => 6],
            ['name' => 'Google Ads', 'slug' => 'google-ads', 'icon' => 'bi-google', 'color' => '#ea4335', 'sort_order' => 7],
            ['name' => 'Referral', 'slug' => 'referral', 'icon' => 'bi-people', 'color' => '#8b5cf6', 'sort_order' => 8],
            ['name' => 'Walk-in', 'slug' => 'walk-in', 'icon' => 'bi-person-walking', 'color' => '#6b7280', 'sort_order' => 9],
            ['name' => 'Other', 'slug' => 'other', 'icon' => 'bi-three-dots', 'color' => '#9ca3af', 'sort_order' => 10],
        ];

        foreach ($sources as $source) {
            LeadSource::updateOrCreate(
                ['slug' => $source['slug']],
                $source
            );
        }

        $this->command->info('✅ Lead sources seeded successfully!');
    }
}

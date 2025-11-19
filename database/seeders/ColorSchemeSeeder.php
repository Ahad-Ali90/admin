<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ColorScheme;

class ColorSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            // Buttons
            ['key' => 'btn-primary-bg', 'value' => '#4f46e5', 'category' => 'buttons', 'description' => 'Primary button background'],
            ['key' => 'btn-primary-hover', 'value' => '#4338ca', 'category' => 'buttons', 'description' => 'Primary button hover'],
            ['key' => 'btn-primary-text', 'value' => '#ffffff', 'category' => 'buttons', 'description' => 'Primary button text'],
            ['key' => 'btn-secondary-bg', 'value' => '#6b7280', 'category' => 'buttons', 'description' => 'Secondary button background'],
            ['key' => 'btn-success-bg', 'value' => '#10b981', 'category' => 'buttons', 'description' => 'Success button background'],
            ['key' => 'btn-danger-bg', 'value' => '#ef4444', 'category' => 'buttons', 'description' => 'Danger button background'],
            ['key' => 'btn-warning-bg', 'value' => '#f59e0b', 'category' => 'buttons', 'description' => 'Warning button background'],
            ['key' => 'btn-info-bg', 'value' => '#06b6d4', 'category' => 'buttons', 'description' => 'Info button background'],
            
            // Cards
            ['key' => 'card-bg', 'value' => '#ffffff', 'category' => 'cards', 'description' => 'Card background'],
            ['key' => 'card-border', 'value' => '#e5e7eb', 'category' => 'cards', 'description' => 'Card border'],
            ['key' => 'card-shadow', 'value' => '0 1px 3px rgba(0,0,0,0.1)', 'category' => 'cards', 'description' => 'Card shadow'],
            ['key' => 'card-header-bg', 'value' => '#f9fafb', 'category' => 'cards', 'description' => 'Card header background'],
            
            // Tables
            ['key' => 'table-header-bg', 'value' => '#f3f4f6', 'category' => 'tables', 'description' => 'Table header background'],
            ['key' => 'table-header-text', 'value' => '#1f2937', 'category' => 'tables', 'description' => 'Table header text'],
            ['key' => 'table-row-hover', 'value' => '#f9fafb', 'category' => 'tables', 'description' => 'Table row hover'],
            ['key' => 'table-border', 'value' => '#e5e7eb', 'category' => 'tables', 'description' => 'Table border'],
            ['key' => 'table-stripe', 'value' => '#fafafa', 'category' => 'tables', 'description' => 'Table striped row'],
            
            // Inputs
            ['key' => 'input-bg', 'value' => '#ffffff', 'category' => 'inputs', 'description' => 'Input background'],
            ['key' => 'input-border', 'value' => '#d1d5db', 'category' => 'inputs', 'description' => 'Input border'],
            ['key' => 'input-focus-border', 'value' => '#4f46e5', 'category' => 'inputs', 'description' => 'Input focus border'],
            ['key' => 'input-focus-shadow', 'value' => '0 0 0 3px rgba(79, 70, 229, 0.1)', 'category' => 'inputs', 'description' => 'Input focus shadow'],
            ['key' => 'input-text', 'value' => '#1f2937', 'category' => 'inputs', 'description' => 'Input text color'],
            ['key' => 'input-placeholder', 'value' => '#9ca3af', 'category' => 'inputs', 'description' => 'Input placeholder'],
            
            // Text
            ['key' => 'text-primary', 'value' => '#1f2937', 'category' => 'text', 'description' => 'Primary text color'],
            ['key' => 'text-secondary', 'value' => '#6b7280', 'category' => 'text', 'description' => 'Secondary text color'],
            ['key' => 'text-muted', 'value' => '#9ca3af', 'category' => 'text', 'description' => 'Muted text color'],
            ['key' => 'text-link', 'value' => '#4f46e5', 'category' => 'text', 'description' => 'Link color'],
            ['key' => 'text-link-hover', 'value' => '#4338ca', 'category' => 'text', 'description' => 'Link hover color'],
            
            // Backgrounds
            ['key' => 'bg-primary', 'value' => '#ffffff', 'category' => 'backgrounds', 'description' => 'Primary background'],
            ['key' => 'bg-secondary', 'value' => '#f9fafb', 'category' => 'backgrounds', 'description' => 'Secondary background'],
            ['key' => 'bg-surface', 'value' => '#f3f4f6', 'category' => 'backgrounds', 'description' => 'Surface background'],
            
            // Borders
            ['key' => 'border-primary', 'value' => '#e5e7eb', 'category' => 'borders', 'description' => 'Primary border'],
            ['key' => 'border-secondary', 'value' => '#d1d5db', 'category' => 'borders', 'description' => 'Secondary border'],
            
            // Alerts
            ['key' => 'alert-success-bg', 'value' => '#d1fae5', 'category' => 'alerts', 'description' => 'Success alert background'],
            ['key' => 'alert-success-text', 'value' => '#065f46', 'category' => 'alerts', 'description' => 'Success alert text'],
            ['key' => 'alert-danger-bg', 'value' => '#fee2e2', 'category' => 'alerts', 'description' => 'Danger alert background'],
            ['key' => 'alert-danger-text', 'value' => '#991b1b', 'category' => 'alerts', 'description' => 'Danger alert text'],
            ['key' => 'alert-warning-bg', 'value' => '#fef3c7', 'category' => 'alerts', 'description' => 'Warning alert background'],
            ['key' => 'alert-warning-text', 'value' => '#92400e', 'category' => 'alerts', 'description' => 'Warning alert text'],
            ['key' => 'alert-info-bg', 'value' => '#dbeafe', 'category' => 'alerts', 'description' => 'Info alert background'],
            ['key' => 'alert-info-text', 'value' => '#1e40af', 'category' => 'alerts', 'description' => 'Info alert text'],
            
            // Navigation
            ['key' => 'nav-bg', 'value' => '#ffffff', 'category' => 'navigation', 'description' => 'Navigation background'],
            ['key' => 'nav-text', 'value' => '#1f2937', 'category' => 'navigation', 'description' => 'Navigation text'],
            ['key' => 'nav-active', 'value' => '#4f46e5', 'category' => 'navigation', 'description' => 'Active nav item'],
            ['key' => 'nav-hover', 'value' => '#f3f4f6', 'category' => 'navigation', 'description' => 'Navigation hover'],
        ];

        foreach ($colors as $color) {
            ColorScheme::updateOrCreate(
                ['key' => $color['key']],
                $color
            );
        }
    }
}

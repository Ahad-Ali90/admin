<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ColorScheme;
use Illuminate\Support\Facades\Cache;

class ColorSchemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.admin.layouts.app', function ($view) {
            $colors = Cache::remember('color_schemes_css', 3600, function () {
                return ColorScheme::where('is_active', true)
                    ->pluck('value', 'key')
                    ->toArray();
            });
            
            $view->with('colorSchemes', $colors);
        });
    }
}

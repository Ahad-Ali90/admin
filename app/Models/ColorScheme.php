<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    protected $fillable = [
        'key',
        'value',
        'category',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all active color schemes grouped by category
     */
    public static function getActiveByCategory()
    {
        return static::where('is_active', true)
            ->orderBy('category')
            ->orderBy('key')
            ->get()
            ->groupBy('category');
    }

    /**
     * Get color value by key
     */
    public static function getValue($key, $default = null)
    {
        $scheme = static::where('key', $key)
            ->where('is_active', true)
            ->first();
        
        return $scheme ? $scheme->value : $default;
    }

    /**
     * Get all colors as CSS variables array
     */
    public static function getCssVariables()
    {
        return static::where('is_active', true)
            ->pluck('value', 'key')
            ->toArray();
    }
}

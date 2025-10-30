<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LeadSource extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leadSource) {
            if (empty($leadSource->slug)) {
                $leadSource->slug = Str::slug($leadSource->name);
            }
        });

        static::updating(function ($leadSource) {
            if ($leadSource->isDirty('name') && empty($leadSource->slug)) {
                $leadSource->slug = Str::slug($leadSource->name);
            }
        });
    }

    // Scope for active sources
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered sources
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Relationship with bookings
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'lead_source_id');
    }
}

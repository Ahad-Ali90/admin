<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'hourly_rate',
        'fixed_rate',
        'pricing_type',
        'unit',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'fixed_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
                    ->withPivot(['quantity', 'unit_rate', 'total_amount', 'notes'])
                    ->withTimestamps();
    }

    // Helper methods
    public function getDisplayPriceAttribute(): string
    {
        if ($this->pricing_type === 'hourly') {
            return "£{$this->hourly_rate}/hour";
        } elseif ($this->pricing_type === 'fixed') {
            return "£{$this->fixed_rate}";
        }
        return "£{$this->hourly_rate}/{$this->unit}";
    }

    public function calculateTotal(int $quantity = 1): float
    {
        if ($this->pricing_type === 'fixed') {
            return $this->fixed_rate;
        }
        return $this->hourly_rate * $quantity;
    }
}

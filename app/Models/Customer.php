<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'postcode',
        'customer_type',
        'company_name',
        'customer_source',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'customer_type' => 'string',
        ];
    }

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    // Helper methods
    public function getTotalBookingsAttribute(): int
    {
        return $this->bookings()->count();
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->invoices()->where('status', 'paid')->sum('total_amount');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceType extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'string',
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_services', 'service_id', 'booking_id')
                    ->withPivot(['quantity', 'unit_rate', 'total_amount', 'notes'])
                    ->withTimestamps();
    }
}
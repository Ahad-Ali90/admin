<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'postcode',
        'fleet_type',
        'fleet_size',
        'insurance_verified',
        'insurance_expiry_date',
        'registration_verified',
        'registration_number',
        'verification_status',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'insurance_verified' => 'boolean',
            'insurance_expiry_date' => 'date',
            'registration_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Helper methods
    public function getVerificationStatusBadgeAttribute(): string
    {
        return match($this->verification_status) {
            'verified' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }
}

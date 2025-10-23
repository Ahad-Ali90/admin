<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcontractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'manager_name',
        'owner_name',
        'email',
        'phone',
        'website',
        'address',
        'postcode',
        'city',
        'business_type',
        'registration_number',
        'vat_number',
        'years_in_business',
        'fleet_size',
        'insurance_verified',
        'insurance_expiry_date',
        'licenses_verified',
        'verification_status',
        'service_areas',
        'services_offered',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'insurance_verified' => 'boolean',
            'insurance_expiry_date' => 'date',
            'licenses_verified' => 'boolean',
            'is_active' => 'boolean',
            'service_areas' => 'array',
            'services_offered' => 'array',
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

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->postcode}";
    }
}

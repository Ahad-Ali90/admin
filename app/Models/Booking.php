<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'created_by',
        'driver_id',
        'porter_id',
        'porter_ids',
        'vehicle_id',
        'booking_reference',
        'status',
        'booking_date',
        'start_time',
        'end_time',
        'estimated_hours',
        'actual_hours',
        'extra_hours',
        'pickup_address',
        'delivery_address',
        'pickup_postcode',
        'delivery_postcode',
        'job_description',
        'special_instructions',
        'driver_notes',
        'porter_notes',
        'total_amount',
        'is_company_booking',
        'company_commission_rate',
        'company_commission_amount',
        'extra_hours_rate',
        'extra_hours_amount',
        'is_manual_amount',
        'manual_amount',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'porter_ids' => 'array',
            'is_company_booking' => 'boolean',
            'company_commission_rate' => 'decimal:2',
            'company_commission_amount' => 'decimal:2',
            'extra_hours_rate' => 'decimal:2',
            'extra_hours_amount' => 'decimal:2',
            'is_manual_amount' => 'boolean',
            'manual_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function porter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'porter_id');
    }

    public function porters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_porters', 'booking_id', 'porter_id')
                    ->withTimestamps();
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class, 'booking_services', 'booking_id', 'service_id')
                    ->withPivot(['quantity', 'unit_rate', 'total_amount', 'notes'])
                    ->withTimestamps();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(CustomerFeedback::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Helper methods
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getDurationAttribute(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInHours($this->completed_at);
        }
        return null;
    }

    public function isAssigned(): bool
    {
        return $this->driver_id && 
               ($this->porter_ids && count($this->porter_ids) > 0) && 
               $this->vehicle_id;
    }

    public function canStart(): bool
    {
        return $this->status === 'confirmed' && $this->isAssigned();
    }

    public function canComplete(): bool
    {
        return $this->status === 'in_progress';
    }

    // Status Management Methods
    public function canTransitionTo(string $newStatus): bool
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [], // No transitions from completed
            'cancelled' => ['pending'], // Can reactivate cancelled bookings
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    public function getAvailableStatusTransitions(): array
    {
        $transitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => ['pending'],
        ];

        return $transitions[$this->status] ?? [];
    }

    public function getStatusRequirements(string $status): array
    {
        $requirements = [
            'confirmed' => [
                'driver_id' => 'Driver must be assigned',
                'porter_ids' => 'At least one porter must be assigned',
                'vehicle_id' => 'Vehicle must be assigned',
            ],
            'in_progress' => [
                'driver_id' => 'Driver must be assigned',
                'porter_ids' => 'At least one porter must be assigned',
                'vehicle_id' => 'Vehicle must be assigned',
            ],
            'completed' => [
                'started_at' => 'Booking must have been started',
            ],
        ];

        return $requirements[$status] ?? [];
    }


    public function getStatusDescription(): string
    {
        $descriptions = [
            'pending' => 'Booking is waiting for assignment of driver, porter(s), and vehicle',
            'confirmed' => 'Booking is confirmed with all assignments. Ready to start.',
            'in_progress' => 'Booking is currently in progress',
            'completed' => 'Booking has been completed successfully',
            'cancelled' => 'Booking has been cancelled',
        ];

        return $descriptions[$this->status] ?? 'Unknown status';
    }

    public function getStatusColor(): string
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // New helper methods for enhanced features
    public function getAssignedPortersAttribute()
    {
        if ($this->porter_ids && is_array($this->porter_ids)) {
            return User::whereIn('id', $this->porter_ids)->get();
        }
        return collect();
    }

    public function calculateExtraHoursAmount(): float
    {
        if ($this->extra_hours && $this->extra_hours_rate) {
            return $this->extra_hours * $this->extra_hours_rate;
        }
        return 0;
    }

    public function calculateCompanyCommission(): float
    {
        if ($this->is_company_booking && $this->company_commission_rate) {
            return ($this->total_amount * $this->company_commission_rate) / 100;
        }
        return 0;
    }

    public function getFinalAmountAttribute(): float
    {
        $baseAmount = $this->total_amount;
        $extraAmount = $this->calculateExtraHoursAmount();
        
        return $baseAmount + $extraAmount;
    }

    public function hasMultiplePorters(): bool
    {
        return $this->porter_ids && count($this->porter_ids) > 1;
    }

    public function getPorterNamesAttribute(): string
    {
        if ($this->porter_ids && is_array($this->porter_ids)) {
            $porters = User::whereIn('id', $this->porter_ids)->pluck('name')->toArray();
            return implode(', ', $porters);
        }
        return $this->porter ? $this->porter->name : '';
    }
}

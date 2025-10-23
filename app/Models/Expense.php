<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
        'category',
        'description',
        'amount',
        'receipt_number',
        'supplier',
        'vehicle_id',
        'staff_id',
        'booking_id',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Helper methods
    public function getCategoryBadgeAttribute(): string
    {
        return match($this->category) {
            'fuel' => 'danger',
            'maintenance' => 'warning',
            'staff_pay' => 'info',
            'insurance' => 'primary',
            'vehicle_finance' => 'secondary',
            'office' => 'dark',
            'marketing' => 'success',
            default => 'light'
        };
    }

    public function getPaymentMethodBadgeAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'success',
            'card' => 'info',
            'bank_transfer' => 'primary',
            'cheque' => 'warning',
            default => 'secondary'
        };
    }
}

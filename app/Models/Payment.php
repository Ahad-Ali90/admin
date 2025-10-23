<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'customer_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Helper methods
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingExpense extends Model
{
    protected $fillable = [
        'booking_id',
        'expense_type',
        'description',
        'amount',
        'paid_to_user_id',
        'notes',
        'expense_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function paidToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_to_user_id');
    }

    public function getExpenseTypeLabelAttribute(): string
    {
        return match($this->expense_type) {
            'driver_payment' => 'Driver Payment',
            'porter_payment' => 'Porter Payment',
            'congestion_charge' => 'Congestion Charge',
            'ulez_charge' => 'ULEZ Charge',
            'toll_charge' => 'Toll Charge',
            'extra_waiting' => 'Extra Waiting Time',
            'fuel' => 'Fuel',
            'other' => 'Other',
            default => 'Unknown'
        };
    }
}

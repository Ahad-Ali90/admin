<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'rating',
        'comments',
        'follow_up_needed',
        'follow_up_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'follow_up_needed' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper methods
    public function getRatingStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingBadgeAttribute(): string
    {
        return match(true) {
            $this->rating >= 5 => 'success',
            $this->rating >= 4 => 'info',
            $this->rating >= 3 => 'warning',
            default => 'danger'
        };
    }
}

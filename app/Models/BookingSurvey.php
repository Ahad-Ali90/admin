<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'survey_type',
        'schedule_date',
        'schedule_time',
        'status',
        'list_content',
        'video_path',
        'notes',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    /**
     * Get the booking that owns the survey
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the survey type label
     */
    public function getSurveyTypeLabelAttribute(): string
    {
        return match($this->survey_type) {
            'video_call' => 'Video Call',
            'video_recording' => 'Video Recording',
            'list' => 'List',
            default => 'N/A',
        };
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'done' => 'Done',
            'pending' => 'Pending',
            'not_agreed' => 'Not Agreed',
            default => 'N/A',
        };
    }

    /**
     * Get the status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'done' => 'success',
            'pending' => 'warning',
            'not_agreed' => 'danger',
            default => 'secondary',
        };
    }
}

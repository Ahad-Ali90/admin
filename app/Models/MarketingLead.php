<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start_date',
        'platform',
        'followers_start',
        'followers_end',
        'followers_growth',
        'posts_count',
        'total_engagement',
        'total_reach',
        'leads_generated',
        'ad_spend',
        'bookings_from_social',
        'cost_per_lead',
        'cost_per_booking',
        'new_customers',
        'repeat_customers',
        'repeat_job_percentage',
        'best_performing_channel',
        'customer_source_breakdown',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'week_start_date' => 'date',
            'ad_spend' => 'decimal:2',
            'cost_per_lead' => 'decimal:2',
            'cost_per_booking' => 'decimal:2',
            'repeat_job_percentage' => 'decimal:2',
            'customer_source_breakdown' => 'array',
        ];
    }

    // Helper methods
    public function getROIAttribute(): float
    {
        if ($this->ad_spend <= 0) return 0;
        return ($this->bookings_from_social * 100) / $this->ad_spend; // Simplified ROI
    }
}

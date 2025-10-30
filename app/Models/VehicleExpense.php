<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'expense_type',
        'title',
        'description',
        'amount',
        'expense_date',
        'receipt_number',
        'vendor',
        'mileage_at_expense',
        'notes',
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

    // Helper methods
    public function getExpenseTypeLabelAttribute(): string
    {
        return match($this->expense_type) {
            'fuel' => 'Fuel',
            'maintenance' => 'Maintenance',
            'repair' => 'Repair',
            'insurance' => 'Insurance',
            'mot' => 'MOT',
            'tax' => 'Tax',
            'cleaning' => 'Cleaning',
            'parking' => 'Parking',
            'toll' => 'Toll',
            'fine' => 'Fine',
            'service' => 'Service',
            'parts' => 'Parts',
            'tyres' => 'Tyres',
            'other' => 'Other',
            default => 'Unknown'
        };
    }

    public function getExpenseTypeIconAttribute(): string
    {
        return match($this->expense_type) {
            'fuel' => 'bi-fuel-pump',
            'maintenance' => 'bi-wrench',
            'repair' => 'bi-tools',
            'insurance' => 'bi-shield-check',
            'mot' => 'bi-clipboard-check',
            'tax' => 'bi-receipt',
            'cleaning' => 'bi-droplet',
            'parking' => 'bi-p-square',
            'toll' => 'bi-signpost',
            'fine' => 'bi-exclamation-triangle',
            'service' => 'bi-gear',
            'parts' => 'bi-box-seam',
            'tyres' => 'bi-circle',
            'other' => 'bi-three-dots',
            default => 'bi-cash'
        };
    }

    public function getExpenseTypeColorAttribute(): string
    {
        return match($this->expense_type) {
            'fuel' => 'primary',
            'maintenance' => 'warning',
            'repair' => 'danger',
            'insurance' => 'success',
            'mot' => 'info',
            'tax' => 'secondary',
            'cleaning' => 'info',
            'parking' => 'secondary',
            'toll' => 'secondary',
            'fine' => 'danger',
            'service' => 'warning',
            'parts' => 'primary',
            'tyres' => 'dark',
            'other' => 'secondary',
            default => 'secondary'
        };
    }
}

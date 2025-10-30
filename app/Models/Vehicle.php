<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'make',
        'model',
        'year',
        'vehicle_type',
        'color',
        'capacity_cubic_meters',
        'max_weight_kg',
        'status',
        'mot_expiry_date',
        'insurance_expiry_date',
        'last_service_date',
        'next_service_due',
        'mileage',
        'purchase_price',
        'monthly_insurance',
        'monthly_finance',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'mot_expiry_date' => 'date',
            'insurance_expiry_date' => 'date',
            'last_service_date' => 'date',
            'next_service_due' => 'date',
            'purchase_price' => 'decimal:2',
            'monthly_insurance' => 'decimal:2',
            'monthly_finance' => 'decimal:2',
        ];
    }

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function vehicleExpenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    // Helper methods
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available' => 'success',
            'in_use' => 'warning',
            'maintenance' => 'danger',
            'retired' => 'secondary',
            default => 'secondary'
        };
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isMotExpiring(int $days = 30): bool
    {
        return $this->mot_expiry_date->isBefore(now()->addDays($days));
    }

    public function isInsuranceExpiring(int $days = 30): bool
    {
        return $this->insurance_expiry_date->isBefore(now()->addDays($days));
    }

    public function needsService(): bool
    {
        return $this->next_service_due && $this->next_service_due->isBefore(now());
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->year} {$this->make} {$this->model} ({$this->registration_number})";
    }

    // Expense Analytics
    public function getTotalExpensesAttribute(): float
    {
        return $this->vehicleExpenses()->sum('amount');
    }

    public function getExpensesByTypeAttribute(): array
    {
        return $this->vehicleExpenses()
            ->selectRaw('expense_type, SUM(amount) as total')
            ->groupBy('expense_type')
            ->pluck('total', 'expense_type')
            ->toArray();
    }

    public function getMonthlyExpenseAverageAttribute(): float
    {
        $firstExpense = $this->vehicleExpenses()->orderBy('expense_date')->first();
        if (!$firstExpense) {
            return 0;
        }
        
        $monthsSinceFirst = max(1, $firstExpense->expense_date->diffInMonths(now()));
        return $this->getTotalExpensesAttribute() / $monthsSinceFirst;
    }

    public function getTotalBookingsAttribute(): int
    {
        return $this->bookings()->count();
    }

    public function getTotalRevenueAttribute(): float
    {
        $totalRevenue = 0;
        foreach ($this->bookings as $booking) {
            $totalRevenue += $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
        }
        return $totalRevenue;
    }

    public function getNetProfitAttribute(): float
    {
        return $this->getTotalRevenueAttribute() - $this->getTotalExpensesAttribute();
    }
}

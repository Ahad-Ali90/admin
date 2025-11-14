<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'address',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Helper methods for analytics

    /**
     * Get total number of bookings
     */
    public function getTotalBookingsAttribute(): int
    {
        return $this->bookings()->count();
    }

    /**
     * Get total revenue from all bookings (final total fare + extra hours)
     */
    public function getTotalRevenueAttribute(): float
    {
        $totalRevenue = 0;
        foreach ($this->bookings as $booking) {
            $totalRevenue += $booking->getFinalTotalFare() + ($booking->extra_hours_amount ?? 0);
        }
        return $totalRevenue;
    }

    /**
     * Get total commission paid to company
     */
    public function getTotalCommissionPaidAttribute(): float
    {
        return $this->bookings()->sum('company_commission_amount');
    }

    /**
     * Get total commission pending (from pending/confirmed/in_progress bookings)
     */
    public function getTotalCommissionPendingAttribute(): float
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->sum('company_commission_amount');
    }

    /**
     * Get net profit (total revenue - total commission)
     */
    public function getNetProfitAttribute(): float
    {
        return $this->getTotalRevenueAttribute() - $this->getTotalCommissionPaidAttribute();
    }

    /**
     * Get completed bookings count
     */
    public function getCompletedBookingsAttribute(): int
    {
        return $this->bookings()->where('status', 'completed')->count();
    }

    /**
     * Get pending bookings count
     */
    public function getPendingBookingsAttribute(): int
    {
        return $this->bookings()->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count();
    }

    /**
     * Get cancelled bookings count
     */
    public function getCancelledBookingsAttribute(): int
    {
        return $this->bookings()->where('status', 'cancelled')->count();
    }

    /**
     * Get average commission per booking
     */
    public function getAverageCommissionAttribute(): float
    {
        $count = $this->getTotalBookingsAttribute();
        return $count > 0 ? $this->getTotalCommissionPaidAttribute() / $count : 0;
    }
}

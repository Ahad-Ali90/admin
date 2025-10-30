<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role-based methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBookingGrabber(): bool
    {
        return $this->role === 'booking_grabber';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isPorter(): bool
    {
        return $this->role === 'porter';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['driver', 'porter']);
    }

    // Relationships
    public function createdBookings()
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    public function driverBookings()
    {
        return $this->hasMany(Booking::class, 'driver_id');
    }

    public function porterBookings()
    {
        return $this->hasMany(Booking::class, 'porter_id');
    }

    public function assignedBookings()
    {
        if ($this->isDriver()) {
            return $this->driverBookings();
        } elseif ($this->isPorter()) {
            return $this->porterBookings();
        }
        return $this->createdBookings();
    }

    public function recordedPayments()
    {
        return $this->hasMany(Payment::class, 'recorded_by');
    }

    public function recordedExpenses()
    {
        return $this->hasMany(Expense::class, 'recorded_by');
    }

    public function staffExpenses()
    {
        return $this->hasMany(Expense::class, 'staff_id');
    }

    public function reviewedFeedback()
    {
        return $this->hasMany(CustomerFeedback::class, 'reviewed_by');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'responsible_person_id');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
}

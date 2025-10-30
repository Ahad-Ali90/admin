<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'category',
        'priority',
        'responsible_person_id',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relationships
    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'responsible_person_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Status badges
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'to_do' => 'bg-secondary',
            'in_progress' => 'bg-primary',
            'completed' => 'bg-success',
            default => 'bg-secondary',
        };
    }
    
    // Get status label
    public function getStatusLabel()
    {
        return match($this->status) {
            'to_do' => 'To Do',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            default => ucfirst($this->status),
        };
    }

    // Priority badges
    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            'low' => 'bg-info',
            'medium' => 'bg-warning',
            'high' => 'bg-danger',
            'urgent' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    // Category badges
    public function getCategoryBadgeClass()
    {
        return match($this->category) {
            'finance' => 'bg-success',
            'operation' => 'bg-primary',
            'hr' => 'bg-info',
            'marketing' => 'bg-warning',
            'it' => 'bg-secondary',
            'other' => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    // Check if task is overdue
    public function isOverdue()
    {
        return $this->due_date < now() && $this->status !== 'completed';
    }
}


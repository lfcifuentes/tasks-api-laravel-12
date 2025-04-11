<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\TaskDeletedNotification;
use App\Notifications\TaskUpdatedNotification;
use App\Notifications\TaskAssignedNotification;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'due_date' => 'datetime',
        'status' => TaskStatus::class,
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function timeLogs()
    {
        return $this->hasMany(TaskTimeLog::class);
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    /**
     * Send notification when task is deleted
     */
    public function sendDeleteNotification(): void
    {
        $this->createdBy->notify(new TaskDeletedNotification($this));
    }

    /**
     * Send notification when task is updated
     */
    public function sendUpdateNotification(): void
    {
        $this->assignedTo->notify(new TaskUpdatedNotification($this));
        $this->createdBy->notify(new TaskUpdatedNotification($this));
    }

    /**
     * Send notification when task is created
     */
    public function sendCreationNotification(): void
    {
        $this->assignedTo->notify(new TaskAssignedNotification($this));
        $this->createdBy->notify(new TaskCreatedNotification($this));
    }
}

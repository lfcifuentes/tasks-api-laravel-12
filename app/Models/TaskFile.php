<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'file_path',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }
        if (!Storage::disk('public')->exists($this->file_path)) {
            return null;
        }
        return asset('storage/' . $this->file_path);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Policies;

use App\Models\TaskFile;
use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\Response;

class TaskFilePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Task $task): bool
    {
        return $user->id === $task->created_by || $user->id === $task->assigned_to;
    }
}

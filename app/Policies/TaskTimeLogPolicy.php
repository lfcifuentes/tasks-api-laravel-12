<?php

namespace App\Policies;

use App\Models\TaskTimeLog;
use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\Response;

class TaskTimeLogPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_to;
    }
}

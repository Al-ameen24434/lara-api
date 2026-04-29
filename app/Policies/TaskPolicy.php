<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Can this user VIEW this task?
     * Simple rule: you can only view YOUR OWN tasks.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
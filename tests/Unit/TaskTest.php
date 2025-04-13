<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Task;
use App\Models\Comment;
use App\Models\TaskFile;
use App\Models\TaskTimeLog;
use App\Enums\TaskStatus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * * Test if a task can have a comments.
     */
    public function test_task_can_have_comments(): void
    {
        $task = Task::factory()->create();
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
        ]);

        $this->assertTrue($task->comments->contains($comment));
        $this->assertEquals(1, $task->comments->count());
    }

    /**
     * Tes if a task can have a owner.
     */
    public function test_task_can_have_owner(): void
    {
        $task = Task::factory()->create();
        $user = User::factory()->create();

        $task->created_by = $user->id;
        $task->save();

        $this->assertEquals($user->id, $task->created_by);
    }
    /**
     * Test if a task can have a assigned user.
     */
    public function test_task_can_have_assigned_user(): void
    {
        $task = Task::factory()->create();
        $user = User::factory()->create();

        $task->assigned_to = $user->id;
        $task->save();

        $this->assertEquals($user->id, $task->assigned_to);
    }
    /**
     * Test if a task can have a time logs.
     */
    public function test_task_can_have_time_logs(): void
    {
        $task = Task::factory()->create();
        $timeLog = TaskTimeLog::factory()->create([
            'task_id' => $task->id,
        ]);

        $this->assertTrue($task->timeLogs->contains($timeLog));
        $this->assertEquals(1, $task->timeLogs->count());
    }
    /**
     * Test if a task can have a files.
     */
    public function test_task_can_have_files(): void
    {
        $task = Task::factory()->create();
        $file = TaskFile::factory()->create([
            'task_id' => $task->id,
        ]);

        $this->assertTrue($task->files->contains($file));
        $this->assertEquals(1, $task->files->count());
    }
    /**
     * Test if a task can have a status.
     */
    public function test_task_can_have_status(): void
    {
        $task = Task::factory()->create();
        $task->status = TaskStatus::COMPLETED;
        $task->save();

        $this->assertEquals(
            TaskStatus::COMPLETED,
            $task->status
        );
    }
    /**
     * Test if a task can have a due date.
     */
    public function test_task_can_have_due_date(): void
    {
        $task = Task::factory()->create();
        $task->due_date = now()->addDays(5);
        $task->save();

        $this->assertEquals(now()->addDays(5)->toDateString(), $task->due_date->toDateString());
    }
}

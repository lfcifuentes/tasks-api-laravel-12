<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskTimeLog;
use App\Models\TaskFile;
use App\Models\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_user_can_have_created_tasks(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        $this->assertTrue($user->createdTasks->contains($task));
        $this->assertEquals(1, $user->createdTasks->count());
    }

    public function test_user_can_have_assigned_tasks(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'assigned_to' => $user->id
        ]);

        $this->assertTrue($user->assignedTasks->contains($task));
        $this->assertEquals(1, $user->assignedTasks->count());
    }

    public function test_user_can_have_time_logs(): void
    {
        $user = User::factory()->create();
        $timeLog = TaskTimeLog::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertTrue($user->timeLogs->contains($timeLog));
        $this->assertEquals(1, $user->timeLogs->count());
    }

    public function test_user_can_have_files(): void
    {
        $user = User::factory()->create();
        $file = TaskFile::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertTrue($user->files->contains($file));
        $this->assertEquals(1, $user->files->count());
    }

    public function test_user_can_have_comments(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->comments->contains($comment));
        $this->assertEquals(1, $user->comments->count());
    }

}

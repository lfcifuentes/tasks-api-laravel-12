<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    // list comments for a task
    public function index(Request $request, Task $task)
    {
        // Fetch comments for the task
        $comments = $task->comments()
            ->orderBy('created_at', 'desc')
            ->with([
                'user',
            ])
            ->paginate();

        return CommentResource::collection($comments);
    }

    // store a new comment for a task
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $comment = new Comment([
            'user_id' => auth()->id(),
            'comment' => $validated['comment']
        ]);

        $task->comments()->save($comment);

        return new CommentResource(
            $comment->load('user')
        );
    }
}

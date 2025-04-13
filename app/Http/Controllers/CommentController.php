<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // list comments for a task
    public function index(Request $request, Task $task)
    {
        // Fetch comments for the task
        $comments = Comment::where('task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return response()->json($comments);
    }

    // store a new comment for a task
    public function store(Request $request, Task $task)
    {
        // Validate the request
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        // Create a new comment
        $comment = new Comment();
        $comment->task_id = $task->id;
        $comment->user_id = auth()->id();
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json($comment, 201);
    }
}

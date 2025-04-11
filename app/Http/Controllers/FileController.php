<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Store a newly uploaded file for a task
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('create-task-file', $task);

        $request->validate([
            'file' => [
                'required',
                'file',
                'max:2048', // 2MB max
                'mimes:jpeg,png,pdf,doc,docx,xls,xlsx',
            ],
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Generate UUID filename
            $filename = Str::uuid() . '.' . $extension;

            // Store the file
            $path = Storage::disk('public')->putFileAs(
                "tasks/{$task->id}",
                $file,
                $filename
            );

            if (!$path) {
                throw new \Exception('File upload failed');
            }

            // Create the file record
            $taskFile = $task->files()->create([
                'user_id' => auth()->id(),
                'file_path' => $path,
                'original_name' => $originalName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'data' => $taskFile->load('user')
            ], 201);

        } catch (\Exception $e) {
            // Clean up any uploaded file if record creation fails
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'message' => 'File upload failed',
                'error' => app()->isProduction() ? 'Server error' : $e->getMessage()
            ], 500);
        }
    }

    public function index(Task $task)
    {
        $filesTwo = $task->files()
            ->orderBy('created_at', 'desc')
            ->with([
                'user',
                'task'
            ])
            ->paginate(2);
        return response()->json($filesTwo, 200);
    }
}

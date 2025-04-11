<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Class TaskController
 *
 * @package App\Http\Controllers
 *
 * API controller for managing tasks. Handles CRUD operations and authorization.
 */
class TaskController extends Controller
{
    /**
     * Display a paginated list of tasks
     *
     * @return JsonResponse
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Task Title",
     *       "description": "Task Description",
     *       "status": "pending",
     *       "due_date": "2024-04-20",
     *       "created_by": 1,
     *       "assigned_to": 2
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "total": 10
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $tasks = Task::paginate();
        return response()->json($tasks);
    }

    /**
     * Display the specified task
     *
     * @param Task $task The task to show
     * @return JsonResponse
     * @throws AuthorizationException If user is not authorized to view the task
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Task Title",
     *   "description": "Task Description",
     *   "status": "pending",
     *   "due_date": "2024-04-20",
     *   "created_by": 1,
     *   "assigned_to": 2
     * }
     * @response 403 {"message": "This action is unauthorized."}
     * @response 404 {"message": "Task not found."}
     */
    public function show(Task $task): JsonResponse
    {
        Gate::authorize('view', $task);
        return response()->json($task);
    }

    /**
     * Store a newly created task
     *
     * @param Request $request The HTTP request
     * @return JsonResponse
     *
     * @bodyParam title string required The title of the task. Example: "Complete project documentation"
     * @bodyParam description string required The task description. Example: "Write comprehensive API documentation"
     * @bodyParam due_date date required The due date (YYYY-MM-DD). Must not be in the past. Example: "2024-04-20"
     * @bodyParam assigned_to integer required The ID of the user assigned to the task. Example: 2
     *
     * @response 201 {
     *   "id": 1,
     *   "title": "Complete project documentation",
     *   "description": "Write comprehensive API documentation",
     *   "status": "pending",
     *   "due_date": "2024-04-20",
     *   "created_by": 1,
     *   "assigned_to": 2
     * }
     * @response 422 {"message": "The given data was invalid."}
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'assigned_to' => 'required|exists:users,id',
        ]);

        $validatedData['status'] = TaskStatus::PENDING;
        $validatedData['created_by'] = $request->user()->id;

        $task = Task::create($validatedData);

        return response()->json($task, 201);
    }

    /**
     * Update the specified task
     *
     * @param Request $request The HTTP request
     * @param Task $task The task to update
     * @return JsonResponse
     * @throws AuthorizationException If user is not authorized to update the task
     *
     * @bodyParam title string The title of the task. Example: "Updated task title"
     * @bodyParam description string The task description. Example: "Updated task description"
     * @bodyParam due_date date The due date (YYYY-MM-DD). Must not be in the past. Example: "2024-04-20"
     * @bodyParam assigned_to integer The ID of the user assigned to the task. Example: 2
     * @bodyParam status string The task status (pending, in_progress, completed). Example: "in_progress"
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Updated task title",
     *   "description": "Updated task description",
     *   "status": "in_progress",
     *   "due_date": "2024-04-20",
     *   "created_by": 1,
     *   "assigned_to": 2
     * }
     * @response 403 {"message": "This action is unauthorized."}
     * @response 404 {"message": "Task not found."}
     * @response 422 {"message": "The given data was invalid."}
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('update', $task);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'due_date' => ['sometimes', 'required', 'date', 'after_or_equal:today'],
            'assigned_to' => 'sometimes|required|exists:users,id',
            'status' => 'sometimes|required|in:' . implode(',', TaskStatus::getValues()),
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }

    /**
     * Remove the specified task
     *
     * @param Task $task The task to delete
     * @return JsonResponse
     * @throws AuthorizationException If user is not authorized to delete the task
     *
     * @response 204 ""
     * @response 403 {"message": "This action is unauthorized."}
     * @response 404 {"message": "Task not found."}
     */
    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }
}

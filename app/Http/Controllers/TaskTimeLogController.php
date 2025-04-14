<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskTimeLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\TaskTimeLogResource;

/**
 * Class TaskTimeLogController
 *
 * @package App\Http\Controllers
 *
 * Controller for managing time tracking logs for tasks.
 * Handles creating and listing time logs with proper authorization.
 */
class TaskTimeLogController extends Controller
{
    /**
     * Display a paginated list of time logs for a specific task
     *
     * @param Request $request The HTTP request
     * @param Task $task The task to get time logs for
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "task_id": 1,
     *       "user_id": 2,
     *       "minutes": 60,
     *       "start_time": "2024-04-11 10:00:00",
     *       "end_time": "2024-04-11 11:00:00",
     *       "created_at": "2024-04-11T10:00:00.000000Z",
     *       "updated_at": "2024-04-11T10:00:00.000000Z",
     *       "user": {
     *         "id": 2,
     *         "name": "John Doe"
     *       },
     *       "task": {
     *         "id": 1,
     *         "title": "Task Title"
     *       }
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "total": 10
     *   }
     * }
     * @response 403 {"message": "This action is unauthorized."}
     */
    public function index(Request $request, Task $task)
    {
        $timeLogs = TaskTimeLog::where('task_id', $task->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate();

        return TaskTimeLogResource::collection($timeLogs);
    }

    /**
     * Store a newly created time log
     *
     * @param Request $request The HTTP request
     * @param Task $task The task to log time for
     *
     * @throws AuthorizationException If user is not authorized
     *
     * @bodyParam start_time datetime required The start time of the work period (Y-m-d H:i:s). Example: 2024-04-11 10:00:00
     * @bodyParam end_time datetime required The end time of the work period (Y-m-d H:i:s). Must be after start_time. Example: 2024-04-11 11:00:00
     *
     * @response 201 {
     *   "id": 1,
     *   "task_id": 1,
     *   "user_id": 2,
     *   "minutes": 60,
     *   "start_time": "2024-04-11 10:00:00",
     *   "end_time": "2024-04-11 11:00:00",
     *   "created_at": "2024-04-11T10:00:00.000000Z",
     *   "updated_at": "2024-04-11T10:00:00.000000Z",
     *   "user": {
     *     "id": 2,
     *     "name": "John Doe"
     *   },
     *   "task": {
     *     "id": 1,
     *     "title": "Task Title"
     *   }
     * }
     * @response 403 {"message": "This action is unauthorized."}
     * @response 422 {"message": "The given data was invalid."}
     */
    public function store(Request $request, Task $task)
    {
        Gate::authorize('create-task-time-log', $task);
        // Validate the request
        $request->validate([
            'start_time' => 'required|date|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date|date_format:Y-m-d H:i:s|after:start_time',
        ]);

        // Create a new time log
        $timeLog = TaskTimeLog::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'minutes' => Carbon::parse($request->start_time)->diffInMinutes(Carbon::parse($request->end_time))
        ]);
        $timeLog->load('task', 'user');
        // update the task's total time
        $task->total_time_spent += $timeLog->minutes;
        $task->save();

        return response()->json(
            new TaskTimeLogResource($timeLog),
            201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\TaskResource;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * GET /api/tasks — List all tasks for the logged-in user
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()
                         ->tasks()
                         ->latest()
                         ->paginate(10);

        return response()->json($tasks);
    }

    /**
     * POST /api/tasks — Create a new task
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed', // must be one of these
            'due_date'    => 'nullable|date|after:today',         // must be a future date
        ]);

        // Create task linked to the authenticated user
        // array_merge adds user_id to the validated data
        $task = Task::create(array_merge($validated, [
            'user_id' => $request->user()->id,
        ]));

        return response()->json([
            'message' => 'Task created successfully',
            'task'    => $task,
        ], 201);
        
    }

    /**
     * GET /api/tasks/{task} — Get a single task
     */
    public function show(Request $request, Task $task): JsonResponse
    {
            return response()->json($task);
        
    }

    /**
     * PUT /api/tasks/{task} — Update a task
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255', // 'sometimes' = only validate if field is present
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:pending,in_progress,completed',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'task'    => $task,
        ]);
    }

    /**
     * DELETE /api/tasks/{task} — Delete a task
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
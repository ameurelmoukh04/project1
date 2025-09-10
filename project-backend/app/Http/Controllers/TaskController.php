<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Events\TaskCreated;
use App\Services\TaskService;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $user_id = auth()->id();

        $tasks = $this->taskService->getUserTasks($user_id);

        return response()->json([
            'tasks' => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = auth()->id();
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            // 'description' => 'nullable|string',
            // 'status' => 'nullable|string|in:pending,in_progress,done'
        ]);

        // $task = Task::create([
        //     'user_id' => $user_id,
        //     'title' => $request->title,
        //     // 'description' => $request->description ?? null,
        //     // 'status' => $request->status ?? 'pending',
        // ]);
        $task = $this->taskService->storeUserTask($user_id,$request->all());

        event(new TaskCreated($task));

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'sometimes|string|min:3|max:255',
            'description' => 'sometimes|string|nullable',
            'status' => 'sometimes|string|in:pending,in_progress,done'
        ]);

        $task = Task::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $task->update($request->only(['title', 'description', 'status']));

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}

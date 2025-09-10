<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
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
    
    public function store(Request $request)
    {
        $user_id = auth()->id();
        $request->validate([
            'title' => 'required|string|min:3|max:255',
        ]);
        $task = $this->taskService->storeUserTask($user_id,$request->all());

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'sometimes|string|min:3|max:255',
        ]);

        $task = $this->taskService->updateUserTask($id, auth()->id(), $request->only(['title','description','status']));

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    public function destroy(string $id)
    {
        $this->taskService->deleteUserTask($id, auth()->id());

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }

}

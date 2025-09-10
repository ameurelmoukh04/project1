<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAllByUserId(int $userId)
    {
        return Task::where('user_id', $userId)->get();
    }
    public function storeNewTask($user_id,$task){
        $title = $task['title'] ?? null;
        return $task = Task::create([
            'user_id' => $user_id,
            'title' => $title,
            // 'description' => $request->description ?? null,
            // 'status' => $request->status ?? 'pending',
        ]);
    }
    public function findTask($taskId, $userId)
    {
        return Task::where('id', $taskId)
                   ->where('user_id', $userId)
                   ->firstOrFail();
    }
     public function updateTask(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task)
    {
        return $task->delete();
    }

}

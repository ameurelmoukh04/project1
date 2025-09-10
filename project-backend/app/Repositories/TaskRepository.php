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
}

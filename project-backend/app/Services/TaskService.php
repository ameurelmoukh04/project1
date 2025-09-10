<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Models\Notification;
use App\Events\TaskCreated;


class TaskService
{

    protected $taskRepository;
    
    public function __construct(TaskRepository $taskRepository){
        $this->taskRepository = $taskRepository;
    }
    public function getUserTasks(int $userId)
    {
        return $this->taskRepository->getAllByUserId($userId);
    }
    public function storeUserTask($user_id,$task)
    {
        $task = $this->taskRepository->storeNewTask($user_id, $task);

        $notification = Notification::create([
            'user_id' => $user_id,
            'message' => 'a new task created'
        ]);
                event(new TaskCreated($task));
        return $task;
    }

    public function updateUserTask($taskId, $userId, array $data)
    {
        $task = $this->taskRepository->findTask($taskId, $userId);
        return $this->taskRepository->updateTask($task, $data);
    }

    public function deleteUserTask($taskId, $userId)
    {
        $task = $this->taskRepository->findTask($taskId, $userId);
        return $this->taskRepository->deleteTask($task);
    }
}

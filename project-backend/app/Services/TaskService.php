<?php

namespace App\Services;

use App\Repositories\TaskRepository;

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
        return $task;
    }
}

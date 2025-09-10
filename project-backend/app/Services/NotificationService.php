<?php

namespace App\Services;

use App\Repositories\NotificationRepository;
use App\Models\Notification;
use App\Events\TaskCreated;


class NotificationService
{

    protected $notificationRepository;
    
    public function __construct(NotificationRepository $notificationRepository){
        $this->notificationRepository = $notificationRepository;
    }
    public function getUserNotifications(int $userId)
    {
        return $this->notificationRepository->getAllByUserId($userId);
    }
    public function deleteUserNotification($taskId, $userId)
    {
        $notification = $this->notificationRepository->findNotification($taskId, $userId);
        return $this->notificationRepository->deleteNotification($notification);
    }
}

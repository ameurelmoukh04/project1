<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    public function getAllByUserId(int $userId)
    {
        return Notification::where('user_id', $userId)
                                     ->orderBy('created_at', 'desc')
                                     ->get();
    }
    public function findNotification($notificationId, $userId)
    {
        return $notification = Notification::where('id', $notificationId)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
    }
    public function deleteNotification(Notification $notification)
    {
        return $notification->delete();
    }

}

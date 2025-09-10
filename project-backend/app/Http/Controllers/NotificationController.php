<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{

    protected $NotificationService;

    /**
     * Injection de dépendance du Service.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getUserNotifications($user->id);

        return response()->json([
            'notifications' => $notifications
        ]);
    }
        public function destroy(string $id)
    {
        $notifications = $this->notificationService->deleteUserNotification($id, auth()->id());
        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }
}

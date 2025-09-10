<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // or auth()->user()
        $notifications = Notification::where('user_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }
        public function destroy(string $id)
    {
        $notification = Notification::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }
}

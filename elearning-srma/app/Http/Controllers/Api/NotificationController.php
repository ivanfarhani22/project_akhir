<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get latest 10 notifications for current user
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'icon' => $notif->icon ?? 'fas fa-bell',
                    'time' => $notif->created_at->diffForHumans(),
                    'unread' => !$notif->is_read,
                    'url' => $notif->action_url
                ];
            });

        // Count unread
        $unread_count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read'
        ]);
    }

    public function clearAll()
    {
        $user = auth()->user();
        
        Notification::where('user_id', $user->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications cleared'
        ]);
    }
}

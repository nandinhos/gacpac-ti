<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->notifications();

        // Filter by read status
        if ($request->has('read')) {
            if ($request->read === 'true') {
                $query->whereNotNull('read_at');
            } elseif ($request->read === 'false') {
                $query->whereNull('read_at');
            }
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->orderBy('created_at', 'desc')
                               ->paginate(20);

        // Transform notifications to include formatted data
        $transformedNotifications = $notifications->getCollection()->map(function ($notification) {
            $data = $notification->data;
            $data['id'] = $notification->id;
            $data['read'] = !is_null($notification->read_at);
            $data['created_at'] = $notification->created_at;
            $data['timeAgo'] = $notification->created_at->diffForHumans();
            return $data;
        });

        return response()->json([
            'notifications' => $transformedNotifications,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ]
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($notificationId);

        $notification->markAsRead();

        return response()->json(['message' => 'Notificação marcada como lida']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Todas as notificações foram marcadas como lidas']);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $count = $user->unreadNotifications->count();

        return response()->json(['count' => $count]);
    }
}

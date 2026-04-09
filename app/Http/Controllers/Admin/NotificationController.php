<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotification::orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    public function getUnread()
    {
        $notifications = AdminNotification::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $unreadCount = AdminNotification::where('is_read', false)->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }

    public function deleteAll()
    {
        AdminNotification::truncate();
        
        return response()->json(['success' => true]);
    }
}

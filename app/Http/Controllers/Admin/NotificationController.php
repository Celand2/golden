<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', [
            'notifications' => Notification::with('user')->orderByDesc('created_at')->paginate(20),
            'unreadCount' => Notification::where('is_read', false)->count(),
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }
}

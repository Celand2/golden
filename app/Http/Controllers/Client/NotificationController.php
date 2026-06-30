<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return view('client.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $user->notifications()->where('is_read', false)->count(),
        ]);
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($notificationId);

        $notification->update(['is_read' => true]);

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function delete(Request $request, $notificationId)
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($notificationId);

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }
}

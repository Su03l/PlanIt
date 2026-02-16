<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // this for http responses
    use HttpResponses;

    // this for show all notifications request and return response
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(15);

        return $this->success($notifications);
    }

    // this for show unread notifications request and return response
    public function unread(Request $request)
    {
        $notifications = $request->user()->unreadNotifications()->latest()->paginate(15);

        return $this->success($notifications);
    }

    // this for mark notification as read request and return response
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->success(null, 'Notification marked as read.');
    }

    // this for mark all notifications as read request and return response
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->success(null, 'All notifications marked as read.');
    }
}

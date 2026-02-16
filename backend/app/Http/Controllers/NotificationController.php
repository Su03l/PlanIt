<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        // جلب التنبيهات مع التصفح (Pagination) والترتيب من الأحدث
        $notifications = $request->user()->notifications()->latest()->paginate(15);

        return $this->success($notifications);
    }

    public function unread(Request $request)
    {
        // جلب التنبيهات غير المقروءة فقط مرتبة من الأحدث
        $notifications = $request->user()->unreadNotifications()->latest()->paginate(15);

        return $this->success($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->success(null, 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->success(null, 'All notifications marked as read.');
    }
}

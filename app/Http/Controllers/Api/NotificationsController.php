<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\NotificationTransformer;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $this->user->notifications()->paginate($request->pageSize);
        return $this->responsePaginate($notifications, new NotificationTransformer());
    }

    public function stats()
    {
        return $this->responseData([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    public function read()
    {
        $this->user()->markAsRead();
        return $this->responseSuccess('操作成功');
    }
}

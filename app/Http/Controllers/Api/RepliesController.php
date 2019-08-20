<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Reply;
use App\Transformers\ReplyTransformer;
use App\Http\Requests\Api\ReplyRequest;

class RepliesController extends Controller
{
    public function store(Request $request, Reply $reply)
    {
        $this->validate($request, [
            'topic_id' => 'required',
            'content' => 'required',
        ]);
        $reply->topic_id = $request->topic_id;
        $reply->content = $request->input('content');
        $reply->user_id = $this->user()->id;
        $reply->save();
        return $this->responseItem($reply, new ReplyTransformer());
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $reply = Reply::find($request->id);
        $this->authorize('destroy', $reply);
        $reply->delete();
        return $this->responseSuccess('操作成功');
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'topic_id' => 'required',
        ]);
        $topic = Topic::find($request->topic_id);
        $replies = $topic->replies()->paginate($request->pageSize);
        return $this->responsePaginate($replies, new ReplyTransformer());
    }

    public function userIndex(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required'
        ]);
        $user = User::find($request->user_id);
        $replies = $user->replies()->paginate($request->pageSize);
        return $this->responsePaginate($replies, new ReplyTransformer());
    }
}

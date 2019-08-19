<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function store(Request $request, Topic $topic)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->responseItem($topic, new TopicTransformer());
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
        $topic = Topic::find($request->id);
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return $this->responseSuccess('修改成功');
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $topic = Topic::find($request->id);
        $this->authorize('destroy', $topic);
        $topic->delete();
        return $this->responseSuccess('删除成功');
    }
}

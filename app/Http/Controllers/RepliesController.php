<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


	public function store(ReplyRequest $request,Reply $reply)
	{
        //$reply->content = $request->get('content');
        // 替换评论中 @user 的内容
        // $reply->content = $this->parse($request->content);
        $reply->content = $reply->parse($request->get('content'));
        $reply->mention_ids = $reply->mentionUserIds();
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

		return redirect()->to($reply->topic->link())->with('success', '創建成功');
	}

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->to($reply->topic->link())->with('success', '刪除成功');
	}
}
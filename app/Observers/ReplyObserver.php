<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function updating(Reply $reply)
    {
        //
    }
    public function deleted(Reply $reply)
    {
        if ($reply->topic->reply_count > 0) {
            $reply->topic->decrement('reply_count', 1);
        }
    }
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $reply->topic->increment('reply_count', 1);

        // 通知作者话题被回复了
        $topic->user->notify(new TopicReplied($reply));
        // 通知回复中 @ 到的人被提及了
        $mentionIds = explode(",", $reply->mention_ids);
        $mentionUsers = User::whereIn('id', $mentionIds)->get();
        foreach ($mentionUsers as $mentionUser) {
            // 过滤 @ 原作者
            if ($mentionUser->id != $topic->user->id) {
                $mentionUser->notify(new TopicReplied($reply, 'mention'));
            }
        }
    }
}
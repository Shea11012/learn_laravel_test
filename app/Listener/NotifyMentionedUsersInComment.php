<?php

namespace App\Listener;

use App\Event\PostComment;
use App\Models\User;
use App\Notifications\YouWereMentionedInComment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsersInComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostComment  $event
     * @return void
     */
    public function handle(PostComment $event)
    {
        User::whereIn('name',$event->comment->invitedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentionedInComment($event->comment));
            });
    }
}

<?php

namespace App\Listeners;

use App\Events\PublishQuestion;
use App\Models\User;
use App\Notifications\YouWereInvited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyInvitedUsers
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
     * @param  PublishQuestion  $event
     * @return void
     */
    public function handle(PublishQuestion $event)
    {
        User::whereIn('name',$event->question->invitedUsers())
            ->get()
            ->each(function ($user) use($event) {
               $user->notify(new YouWereInvited($event->question));
            });
    }
}

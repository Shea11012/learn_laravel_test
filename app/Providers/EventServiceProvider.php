<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PublishQuestion;
use App\Listeners\NotifyInvitedUsers;
use App\Event\PostComment;
use App\Listener\NotifyMentionedUsersInComment;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PublishQuestion::class => [
            NotifyInvitedUsers::class,
        ],
        PostComment::class => [
            NotifyMentionedUsersInComment::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

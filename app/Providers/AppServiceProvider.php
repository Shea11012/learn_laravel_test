<?php

namespace App\Providers;

use App\Models\Question;
use App\Observers\QuestionObserver;
use App\Translator\BaiduSlugTranslator;
use App\Translator\Translator;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Manager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Question::observe(QuestionObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Translator::class,BaiduSlugTranslator::class);
    }
}

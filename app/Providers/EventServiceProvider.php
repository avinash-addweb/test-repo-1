<?php

namespace App\Providers;

use App\Events\LessonWatched;
use App\Events\CommentWritten;
use Illuminate\Auth\Events\Registered;
use App\Listeners\checkLessonWatchedAchievement;
use App\Listeners\checkCommentsWrittenAchievement;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
      Registered::class     => [
        SendEmailVerificationNotification::class,
      ],
      LessonWatched::class  => [
        checkLessonWatchedAchievement::class
      ],
      CommentWritten::class => [
        checkCommentsWrittenAchievement::class
      ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

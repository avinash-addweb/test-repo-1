<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserBadge;
use App\Events\LessonWatched;
use App\Models\UserAchievement;
use App\Services\UserBadgeService;
use App\Events\AchievementUnlocked;
use App\Services\UserAchievementService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class checkLessonWatchedAchievement
{

    public ?UserAchievementService $userAchievementService = null;
    public ?UserBadgeService $userBadgeService = null;

    /**
     * Create the event listener.
     */
    public function __construct(UserAchievementService $userAchievementService, UserBadgeService $userBadgeService)
    {
        $this->userAchievementService = $userAchievementService;
        $this->userBadgeService       = $userBadgeService;
    }

    /**
     * Handle the LessonWatched event.
     *
     * @param  LessonWatched  $event  The LessonWatched event instance.
     *
     * @return void
     */
    public function handle(LessonWatched $event): void
    {
        $user               = $event->user;
        $watchedLessonCount = $user->watched()->count();

        $this->userAchievementService->assignAchievement($user, UserAchievement::TYPE_LESSON, $watchedLessonCount);

        $this->userBadgeService->assignBadge($user);

    }
}

<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserBadge;
use App\Events\LessonWatched;
use App\Services\UserService;
use App\Events\CommentWritten;
use App\Models\UserAchievement;
use App\Services\UserBadgeService;
use App\Events\AchievementUnlocked;
use App\Services\UserAchievementService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class checkCommentsWrittenAchievement
 *
 * This class is responsible for checking if the number of comments written by a user satisfies the achievement
 * criteria.
 */
class checkCommentsWrittenAchievement
{

    public ?UserAchievementService $userAchievementService = null;
    public ?UserService $userService = null;

    public ?UserBadgeService $userBadgeService = null;

    /**
     * Create the event listener.
     */
    public function __construct(
      UserAchievementService $userAchievementService,
      UserService $userService,
      UserBadgeService $userBadgeService
    ) {
        $this->userAchievementService = $userAchievementService;
        $this->userService            = $userService;
        $this->userBadgeService       = $userBadgeService;
    }


    /**
     * Handle the CommentWritten event.
     *
     * @param  CommentWritten  $event  The CommentWritten event instance.
     *
     * @return void
     */
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user    = $this->userService->find($comment->user_id);

        $commentsWrittenCount = $user->comments()->count();

        $this->userAchievementService->assignAchievement($user, UserAchievement::TYPE_COMMENT, $commentsWrittenCount);

        $this->userBadgeService->assignBadge($user);

    }


}

<?php

namespace App\Services;

use App\Models\User;
use App\Services\BaseService;
use App\Models\UserAchievement;
use App\Events\AchievementUnlocked;

class UserAchievementService extends BaseService
{
    public function __construct()
    {
        $this->object = new UserAchievement();
    }


    /**
     * Assigns an achievement to a user.
     *
     * @param  User  $user    The user to whom the achievement will be assigned.
     * @param  int   $type    The type of the achievement.
     * @param  int   $number  The number of the achievement.
     *
     * @return void
     */
    public function assignAchievement(User $user, int $type, int $number): void
    {
        $achievementConfig = config('achievement.lesson');
        $achievementStatus = $achievementConfig[$number] ?? null;

        if($achievementStatus) {
            $this->create([
              'user_id' => $user->id,
              'type'    => $type,
              'number'  => $number,
            ]);

            AchievementUnlocked::dispatch($user, $achievementStatus);
        }
    }

}
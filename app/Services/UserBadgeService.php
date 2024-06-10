<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBadge;
use App\Events\BadgeUnlocked;


class UserBadgeService extends BaseService
{
    public function __construct()
    {
        $this->object = new UserBadge();
    }


    /**
     * Assigns a badge to the given user based on their badge count.
     *
     * @param  User  $user  The user to assign the badge to.
     *
     * @return void
     */
    public function assignBadge(User $user): void
    {

        $badgeCount  = $user->badges()->count();
        $badgeConfig = config('achievement.badges');

        // Assigning the default badge first
        if($badgeCount == 0) {
            $this->create([
              'user_id' => $user->id,
              'number'  => 0
            ]);

            BadgeUnlocked::dispatch($user, $badgeConfig[0]);

            $badgeCount++;
        }

        // Check for the new badge status
        $badgeStatus = $badgeConfig[$badgeCount] ?? null;

        if($badgeStatus) {
            $this->create([
              'user_id' => $user->id,
              'number'  => $badgeCount
            ]);

            BadgeUnlocked::dispatch($user, $badgeStatus);
        }
    }

}
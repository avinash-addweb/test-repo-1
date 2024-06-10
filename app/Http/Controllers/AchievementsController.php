<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Comment;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\DB;

class AchievementsController extends Controller
{
    /**
     * Retrieves the achievement and badge data for a given user.
     *
     * @param  User  $user  The User object representing the user for whom to retrieve the data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the achievement and badge data.
     * @throws \Exception
     */
    public function index(User $user): \Illuminate\Http\JsonResponse
    {

        $latestAchievements = $this->fetchLatestAchievements($user);

        $badges = $this->fetchLatestBadge($user);

        $achievementData = $this->getAchievementData($latestAchievements);

        $badgeData = $this->getBadgeData($badges);

        return response()->json([
          'unlocked_achievements'          => $achievementData['unlocked'],
          'next_available_achievements'    => $achievementData['next'],
          'current_badge'                  => $badgeData['current'],
          'next_badge'                     => $badgeData['next'],
          'remaining_to_unlock_next_badge' => ($badgeData['next'] !== '') ? $badgeData['next_badge_achievement'] - count($achievementData['unlocked']) : 0
        ]);
    }


    /**
     * Fetches the latest achievements of a specific user.
     *
     * @param  User  $user  The user for which to fetch the latest achievements.
     *
     * @return array An array containing the latest achievements of the user.
     *
     * @throws \Exception If an error occurred while fetching the achievements.
     *
     */
    public function fetchLatestAchievements(User $user): array
    {
        return UserAchievement::select(['type', 'number'])
                              ->where('user_id', $user->id)
                              ->orderBy('created_at', 'DESC')
                              ->get()->unique('type')->keyBy('type')->toArray();
    }


    /**
     * Fetches the latest badge of a specific user.
     *
     * @param  User  $user  The user for which to fetch the latest badge.
     *
     * @return UserBadge|null The latest badge of the user, or null if the user does not have any badges.
     *
     * @throws \Exception If an error occurred while fetching the latest badge.
     *
     */
    protected function fetchLatestBadge(User $user): ?UserBadge
    {
        return $user->latestBadge;
    }

    /**
     * Retrieves achievement data based on the latest achievements of a user.
     *
     * @param  array  $latestAchievements                                      An array containing the latest
     *                                                                         achievements of the user.
     *
     * @return array  An associative array containing unlocked and next achievements.
     *
     * @throws \Exception  If an error occurs during the retrieval of achievement data.
     */
    public function getAchievementData($latestAchievements): array
    {
        $achievementConfig = config('achievement');
        $achievementTypes  = [UserAchievement::TYPE_LESSON => 'lesson', UserAchievement::TYPE_COMMENT => 'comments'];
        $achievementData   = ['unlocked' => [], 'next' => []];

        foreach ($achievementTypes as $key => $type) {

            $index = 0;
            foreach ($achievementConfig[$type] as $number => $achievement) {
                if(isset($latestAchievements[$key]['number']) && $number <= $latestAchievements[$key]['number']) {
                    $achievementData['unlocked'][] = $achievement;

                    if($number == $latestAchievements[$key]['number']) {
                        $achievementValues = array_values($achievementConfig[$type]);
                        if(isset($achievementValues[$index + 1])) {
                            $achievementData['next'][] = $achievementValues[$index + 1];
                        }
                        break;
                    }
                }

                $index++;
            }
        }

        return $achievementData;
    }

    /**
     * Retrieves badge data based on the given badges.
     *
     * @param  array|null  $badges  The badges to retrieve data for.
     *
     * @return array An array containing badge data.
     *
     * @throws \Exception If an error occurred while retrieving the badge data.
     */
    public function getBadgeData($badges): array
    {
        $achievementConfig = config('achievement');

        $badgeData = [
          'current'                => '', 'next' => current($achievementConfig['badges']),
          'next_badge_achievement' => array_key_first($achievementConfig['badges'])
        ];

        $nextBadge = 0;

        if($badges !== null) {
            foreach ($achievementConfig['badges'] as $number => $badge) {
                $nextBadge++;
                if($number == $badges->number) {

                    $badgesValues = array_values($achievementConfig['badges']);

                    $badgeData['current']                = $badge;
                    $badgeData['next']                   = '';
                    $badgeData['next_badge_achievement'] = 0;
                    if(isset($badgesValues[$nextBadge])) {

                        $badgeData['next']                   = $badgesValues[$nextBadge];
                        $badgeData['next_badge_achievement'] = array_search($badgesValues[$nextBadge],
                          $achievementConfig['badges']);
                    }

                    break;
                }
            }
        }

        return $badgeData;
    }

}
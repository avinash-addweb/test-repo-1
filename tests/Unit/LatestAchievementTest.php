<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserAchievement;
use App\Http\Controllers\AchievementsController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LatestAchievementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetchLatestAchievements method.
     *
     * @return void
     */
    public function testFetchLatestAchievements()
    {
        $user         = User::factory()->create();
        $achievements = UserAchievement::factory()->count(3)->create(['user_id' => $user->id]);

        $achievementController = new AchievementsController;
        $result                = $achievementController->fetchLatestAchievements($user);


        $this->assertCount(1, $result);
        $this->assertDatabaseHas('user_achievements', [
          'type'   => $achievements[0]['type'],
          'number' => $achievements[0]['number']
        ]);

        $this->assertDatabaseHas('user_achievements', [
          'type'   => $achievements[1]['type'],
          'number' => $achievements[1]['number']
        ]);

        $this->assertDatabaseHas('user_achievements', [
          'type'   => $achievements[2]['type'],
          'number' => $achievements[2]['number']
        ]);
    }
}
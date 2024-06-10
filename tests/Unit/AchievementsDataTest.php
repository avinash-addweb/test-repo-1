<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\UserAchievement;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\AchievementsController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AchievementsDataTest extends TestCase
{
    use WithFaker;

    /**
     * The AchievementsController instance.
     *
     * @var AchievementsController
     */
    protected $controller;

    /**
     * Setting up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AchievementsController;
    }

    /**
     * Test for getAchievementData method.
     *
     * @group get_achievement_data
     */
    public function testGetAchievementData()
    {
        config()->set('achievement', [
          'lesson'   => [
            1  => 'First Lesson Watched',
            5  => 'Fifth Lesson Watched',
            10 => 'Tenth Lesson Watched'
          ],
          'comments' => [
            1  => 'First Comment Written',
            5  => 'Fifth Comment Written',
            10 => 'Tenth Comment Written'
          ]
        ]);

        $latestAchievements = [
          UserAchievement::TYPE_LESSON  => ['type' => UserAchievement::TYPE_LESSON, 'number' => 5],
          UserAchievement::TYPE_COMMENT => ['type' => UserAchievement::TYPE_COMMENT, 'number' => 1],
        ];

        $callback = \Closure::bind(function($latestAchievements) {
            return $this->getAchievementData($latestAchievements);
        }, $this->controller, get_class($this->controller));

        $achievementData = $callback($latestAchievements);

        // assert 'unlocked' achievements
        $this->assertCount(3, $achievementData['unlocked']);
        $this->assertSame('First Lesson Watched', $achievementData['unlocked'][0]);
        $this->assertSame('Fifth Lesson Watched', $achievementData['unlocked'][1]);
        $this->assertSame('First Comment Written', $achievementData['unlocked'][2]);

        // assert 'next' achievements
        $this->assertCount(2, $achievementData['next']);
        $this->assertSame('Tenth Lesson Watched', $achievementData['next'][0]);
        $this->assertSame('Fifth Comment Written', $achievementData['next'][1]);
    }
}
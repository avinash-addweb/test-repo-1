<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{User, UserBadge};
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\AchievementsController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BadgeDataTest extends TestCase
{
    use WithoutMiddleware;

    protected $controller;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = new AchievementsController();
        $this->user       = User::factory()->make();

        Config::set('achievement', [
          'lesson'   => [1 => 'first_lesson', 5 => 'five_lessons'],
          'comments' => [1 => 'first_comment', 5 => 'five_comments'],
          'badges'   => [1 => 'first_badge', 5 => 'five_badges']
        ]);
    }

    /**
     * Test getBadgeData method
     */
    public function testGetBadgeData()
    {
        $badges = UserBadge::factory()->make(['number' => 1, 'user_id' => 1]);

        $response = $this->controller->getBadgeData($badges);

        $this->assertEquals('five_badges', $response['next']);
        $this->assertEquals(5, $response['next_badge_achievement']);
        $this->assertEquals('first_badge', $response['current']);
    }

    /**
     * Test getBadgeData method when there's no badges
     */
    public function testGetBadgeDataNoBadges()
    {
        $response = $this->controller->getBadgeData(null);

        $this->assertEquals('first_badge', $response['next']);
        $this->assertEquals(1, $response['next_badge_achievement']);
        $this->assertEquals('', $response['current']);
    }
}
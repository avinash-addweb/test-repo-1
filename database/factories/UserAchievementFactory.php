<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Comment;
use App\Models\UserBadge;
use App\Models\UserAchievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAchievementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAchievement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'type'    => '1',
          'user_id' => User::factory(),
          'number'  => '1',
        ];
    }
}

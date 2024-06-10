<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Comment;
use App\Models\UserBadge;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserBadgeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserBadge::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'body'    => $this->faker->text(),
          'user_id' => User::factory(),
        ];
    }
}

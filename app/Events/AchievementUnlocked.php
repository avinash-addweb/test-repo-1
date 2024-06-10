<?php

namespace App\Events;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public string $achievement;
    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $achievement)
    {
        $this->achievement = $achievement;
        $this->user        = $user;
    }
}

<?php

namespace App\Events;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BadgeUnlocked
{
    use Dispatchable, SerializesModels;

    public string $badge;
    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $badge)
    {
        $this->badge = $badge;
        $this->user  = $user;
    }
}

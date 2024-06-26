<?php

namespace Tests\Feature;

use App\Models\User;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user     = User::factory()->create();
        $response = $this->get("/users/{$user->id}/achievements");
        
        $response->assertStatus(200)
                 ->assertExactJson([
                   'unlocked_achievements'          => [],
                   'next_available_achievements'    => [],
                   'current_badge'                  => '',
                   'next_badge'                     => 'Beginner',
                   'remaining_to_unlock_next_badge' => 0
                 ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lessons = Lesson::factory()
                        ->count(20)
                        ->create();

        $users = User::factory()
                    ->count(20)
                    ->create();

    }
}

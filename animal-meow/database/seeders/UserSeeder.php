<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\TwitterUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->has(TwitterUser::factory()->count(1))
            ->has(Post::factory()->count(100))
            ->count(100)
            ->create();
    }
}

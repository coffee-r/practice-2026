<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TwitterUser>
 */
class TwitterUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'twitter_id' => Str::random(30),
            'nickname' => Str::random(20),
            'access_token' => Str::random(50),
            'access_token_time_limit' => fake()->dateTimeBetween($startDate = '-2 year', $endDate = '+2 year'),
            'refresh_token' => Str::random(50),
        ];
    }
}

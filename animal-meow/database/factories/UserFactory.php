<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => null,
            'email_verified_at' => null,
            'password' => null,
            'remember_token' => null,
            'avatar_image_url' => 'http://abs.twimg.com/sticky/default_profile_images/default_profile.png',
        ];
    }
}

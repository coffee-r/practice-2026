<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'name_furigana' => 'テストユーザー',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'Password1234',
            'postal_code' => '0000000',
            'address_prefectures' => '神奈川県',
            'address_municipalities' => Str::random(20),
            'address_others' => Str::random(20),
            'tel' => '09012345678',
            'birthday_year' => '2000',
            'birthday_month' => '12',
            'gender' => '男性',
            'email_magazine_subscription' => 1,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

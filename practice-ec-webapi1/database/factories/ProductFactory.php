<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => Str::random(30),
            'description' => Str::random(100),
            'category_id' => rand(0, 99999),
            'price_with_tax' => rand(0, 99999),
            'tax' => rand(0, 99999),
            'point_price' => rand(0, 99999),
            'review_score_average' => rand(1, 5)
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartProduct>
 */
class CartProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cart_id' => rand(0, 99999),
            'product_id' => rand(0, 99999),
            'product_name' => Str::random(30),
            'product_price_with_tax' => rand(0, 99999),
            'product_tax' => rand(0, 99999),
            'product_point_price' => rand(0, 99999),
            'product_quantity' => rand(0, 100),
        ];
    }
}

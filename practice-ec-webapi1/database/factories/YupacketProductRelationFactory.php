<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\YupacketProductRelation>
 */
class YupacketProductRelationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'yupacket_product_id' => rand(1, 100),
            'non_yupacket_product_id' => rand(101, 200),
        ];
    }
}

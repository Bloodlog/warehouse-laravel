<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->sentence() . '' . fake()->colorName(),
            'article' => fake()->countryISOAlpha3() . random_int(2, 8),
            'quantity_type' => Product::TYPE_DEFAULT,
            'category_id' => 1,
        ];
    }
}

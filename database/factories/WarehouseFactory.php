<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->sentence(),
        ];
    }
}

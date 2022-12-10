<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContractorFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->company(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MovementFactory extends Factory
{
    public function definition()
    {
        return [
            'invoice_id' => 1,
            'warehouse_id' => 1,
            'product_id' => 1,
            'movement_currency' => 1,
            'amount' => random_int(1, 100),
            'price' => 87.00,
            'vat_percent' => 13.00,
            'vat_sum' => 13,
            'final_total' => 100
        ];
    }
}

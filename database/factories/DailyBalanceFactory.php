<?php

namespace Database\Factories;

use App\Models\DailyBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyBalanceFactory extends Factory
{
    public function definition()
    {
        return [
            'warehouse_id' => 1,
            'product_id' => 1,
            'quantity' => random_int(1, 1000),
            'report_date' => now()->format(DailyBalance::FORMAT_DATE),
        ];
    }

}

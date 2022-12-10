<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'invoice_date' => fake()->dateTime->format(Invoice::FORMAT_INVOICE_DATE),
            'user_id' => 1,
            'warehouse_id' => 1,
            'invoices_type' => Invoice::TYPE_ADD,
            'published' => false
        ];
    }
}

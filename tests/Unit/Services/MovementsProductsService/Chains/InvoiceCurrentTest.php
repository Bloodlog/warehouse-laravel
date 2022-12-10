<?php

namespace Tests\Unit\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\MovementsProductsService\Chains\InvoiceCurrent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceCurrentTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccess()
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movement = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $invoiceCurrent = new InvoiceCurrent();
        $invoiceCurrent->handle($invoice);

        $this->assertDatabaseHas('daily_balances', [
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'quantity' => $movement->amount,
            'report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE),
        ]);
    }
}

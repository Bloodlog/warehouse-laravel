<?php

namespace Tests\Unit\Jobs;

use App\Jobs\MovementProductsInvoiceJob;
use App\Models\DailyBalance;
use App\Models\Invoice;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovementProductsInvoiceJobTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessJobCalculateOneInvoice()
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movement = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $job = new MovementProductsInvoiceJob($invoice);
        dispatch($job);

        $this->assertDatabaseHas('daily_balances', [
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'quantity' => $movement->amount,
            'report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE),
        ]);
    }
}

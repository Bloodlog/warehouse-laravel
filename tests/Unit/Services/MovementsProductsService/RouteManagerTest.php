<?php

namespace Tests\Unit\Services\MovementsProductsService;

use App\Models\DailyBalance;
use App\Models\Invoice;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\MovementsProductsService\RouteManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteManagerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessRoute()
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movement = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $routeManager = new RouteManager();
        $routeManager->run($invoice);

        $this->assertDatabaseHas('daily_balances', [
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'quantity' => $movement->amount,
            'report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE),
        ]);
    }


    public function testSuccessJobOneInvoiceAddingProductWithDifferentQuantities() {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movement = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);
        $movement2 = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $routeManager = new RouteManager();
        $routeManager->run($invoice);

        $this->assertDatabaseHas('daily_balances', [
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'quantity' => $movement->amount + $movement2->amount,
            'report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE),
        ]);
    }

    public function testSuccessJobCalculateOneInvoiceWithOtherBalanceAndInvoiceInCurrentDay()
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        // InvoiceBefore
        $invoiceBefore = Invoice::factory()->create(['published' => true, 'invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movementBefore = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoiceBefore->id]);

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movement = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $routeManager = new RouteManager();
        $routeManager->run($invoice);

        $this->assertDatabaseHas('daily_balances', [
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'quantity' => $movementBefore->amount + $movement->amount,
            'report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE),
        ]);
    }

    public function testSuccessJobCalculateOneSubInvoiceWithOtherBalanceAndInvoiceInCurrentDay()
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        // InvoiceBefore
        $invoiceBefore = Invoice::factory()->create(['published' => true, 'invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $movementBefore = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoiceBefore->id]);

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE), 'invoices_type' => Invoice::TYPE_SUB]);
        $movement = Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $routeManager = new RouteManager();
        $routeManager->run($invoice);

        $this->assertDatabaseHas('daily_balances', [
            'product_id' => $movement->product_id,
            'warehouse_id' => $movement->warehouse_id,
            'quantity' => $movementBefore->amount - $movement->amount,
            'report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE),
        ]);
    }
}

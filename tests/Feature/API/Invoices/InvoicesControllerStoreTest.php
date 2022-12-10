<?php

namespace Tests\Feature\API\Invoices;

use App\Http\Controllers\API\InvoicesController;
use App\Jobs\MovementProductsInvoiceJob;
use App\Models\Invoice;
use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    public function testFailedEmptyProductsStore()
    {
        $invoice = Invoice::factory()->make();

        $this->postJson(action([InvoicesController::class, 'store']), $invoice->toArray())
            ->assertJsonValidationErrorFor('movements');
    }

    /**
     * Save invoice with product
     * @return void
     */
    public function testSuccessStoreWithProduct()
    {
        $this->actingAs(User::factory()->create());
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $invoice = Invoice::factory()->make();
        $movement = Movement::factory()->make(['product_id' => $product->id, 'warehouse_id' => $warehouse->id]);
        $data = $invoice->toArray();
        $data['movements'] = [$movement->toArray()];

        $this->postJson(action([InvoicesController::class, 'store']), $data)
            ->assertOk();

        $this->assertDatabaseHas('invoices', $invoice->toArray());
        $this->assertDatabaseHas('movements', $movement->toArray());

    }

    /**
     * Change status invoice
     * @return void
     */
    public function testSuccessSetPublish()
    {
        $this->expectsJobs(MovementProductsInvoiceJob::class);
        $this->actingAs(User::factory()->create());
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        Movement::factory()->create(['product_id' => $product->id, 'warehouse_id' => $warehouse->id, 'invoice_id' => $invoice->id]);

        $this->putJson(action([InvoicesController::class, 'setPublish'], ['invoice' => $invoice->id]), ['published' => true])
            ->assertOk();

        $this->assertDatabaseHas('invoices', array_merge($invoice->getAttributes(), ['published' => 1]));
    }
}

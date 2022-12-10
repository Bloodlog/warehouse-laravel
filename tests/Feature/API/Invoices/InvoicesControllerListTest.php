<?php

namespace Tests\Feature\API\Invoices;

use App\Http\Controllers\API\InvoicesController;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesControllerListTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessList()
    {
        $invoices = Invoice::factory(5)->create();

        $this->getJson(action([InvoicesController::class, 'list']))
            ->assertOk()
            ->assertSeeText($invoices[0]->name)
            ->assertSeeText($invoices[1]->name);
    }
}

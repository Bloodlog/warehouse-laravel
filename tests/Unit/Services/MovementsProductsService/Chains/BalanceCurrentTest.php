<?php

namespace Tests\Unit\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;
use App\Services\MovementsProductsService\RouteManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceCurrentTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccess()
    {
        $invoice = Invoice::factory()->create(['invoice_date' => now()->format(Invoice::FORMAT_INVOICE_DATE)]);
        $balance = DailyBalance::factory()->create(['report_date' => now()->format(DailyBalance::FORMAT_DATE)]);

        $routeManager = new RouteManager();
        $routeManager->run($invoice);

        $this->assertDatabaseMissing('daily_balances', ['id' => $balance->id]);
        $this->assertDatabaseCount('daily_balances', 0);
    }
}

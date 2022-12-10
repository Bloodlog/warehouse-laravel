<?php

namespace Tests\Feature\API\Reports\StockBalances;

use App\Http\Controllers\API\Reports\StockBalancesController;
use App\Models\DailyBalance;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockBalancesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessReport()
    {
        DailyBalance::factory(20)->create();

        $this->getJson(action([StockBalancesController::class, 'list']))
            ->assertOk()
            ->assertJsonCount(20);
    }

    public function testSuccessReportForLastDay()
    {
        DailyBalance::factory(20)->create(['report_date' => now()->subDays(6)]);

        $this->getJson(action([StockBalancesController::class, 'list']))
            ->assertOk()
            ->assertJsonCount(20);
    }

    public function testSuccessReportWarehouse()
    {
        $warehouse = Warehouse::factory()->create();
        $warehouse2 = Warehouse::factory()->create();
        DailyBalance::factory(15)->create(['warehouse_id' => $warehouse->id]);
        DailyBalance::factory(15)->create(['warehouse_id' => $warehouse2->id]);

        $this->getJson(action([StockBalancesController::class, 'warehouseList'], ['warehouse' => $warehouse->id]))
            ->assertOk()
            ->assertJsonCount(15);
    }
}

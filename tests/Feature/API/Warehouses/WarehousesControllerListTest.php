<?php

namespace Tests\Feature\API\Warehouses;

use App\Http\Controllers\API\WarehousesController;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehousesControllerListTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessList()
    {
        $warehouses = Warehouse::factory(5)->create();

        $this->getJson(action([WarehousesController::class, 'list']))
            ->assertOk()
            ->assertSeeText($warehouses[0]->name)
            ->assertSeeText($warehouses[1]->name);
    }
}

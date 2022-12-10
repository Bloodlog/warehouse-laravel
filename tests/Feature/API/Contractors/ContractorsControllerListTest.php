<?php

namespace Tests\Feature\API\Contractors;

use App\Http\Controllers\API\ContractorsController;
use App\Models\Contractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractorsControllerListTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessList()
    {
        $contractors = Contractor::factory(5)->create();

        $this->getJson(action([ContractorsController::class, 'list']))
            ->assertOk()
            ->assertSeeText($contractors[0]->name)
            ->assertSeeText($contractors[1]->name);
    }
}

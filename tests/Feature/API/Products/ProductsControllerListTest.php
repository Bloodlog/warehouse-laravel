<?php

namespace Tests\Feature\API\Products;

use App\Http\Controllers\API\ProductsController;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsControllerListTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessList()
    {
        $products = Product::factory(5)->create();

        $this->getJson(action([ProductsController::class, 'list']))
            ->assertOk()
            ->assertSeeText($products[0]->name)
            ->assertSeeText($products[1]->name);
    }
}

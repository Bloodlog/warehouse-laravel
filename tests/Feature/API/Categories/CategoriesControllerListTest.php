<?php

namespace Tests\Feature\API\Categories;

use App\Http\Controllers\API\CategoriesController;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesControllerListTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessList()
    {
        $category = Category::factory(5)->create();

        $this->getJson(action([CategoriesController::class, 'list']))
        ->assertOk()
        ->assertSeeText($category[0]->name)
        ->assertSeeText($category[1]->name);
    }
}

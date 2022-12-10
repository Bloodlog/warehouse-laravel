<?php

use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\API\ContractorsController;
use App\Http\Controllers\API\InvoicesController;
use App\Http\Controllers\API\MovementsController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\Reports\StockBalancesController;
use App\Http\Controllers\API\WarehousesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('warehouses/list',[WarehousesController::class, 'list'])->name('warehouses.list');
Route::resource('warehouses', WarehousesController::class);

Route::get('categories/list',[CategoriesController::class, 'list'])->name('categories.list');
Route::resource('categories', CategoriesController::class);

Route::get('products/list',[ProductsController::class, 'list'])->name('products.list');
Route::resource('products', ProductsController::class);

Route::get('contractors/list',[ContractorsController::class, 'list'])->name('contractors.list');
Route::resource('contractors', ContractorsController::class);

Route::get('invoices/list', [InvoicesController::class, 'list'])->name('invoices.list');
Route::resource('invoices', InvoicesController::class);
Route::put('invoices/{invoice}/publish', [InvoicesController::class, 'setPublish'])->name('invoices.publish');

Route::get('reports/list', [StockBalancesController::class, 'list'])->name('reports.list');
Route::get('reports/warehouses/{warehouse}/list', [StockBalancesController::class, 'warehouseList'])->name('reports.warehouses.list');

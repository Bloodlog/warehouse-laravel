<?php

namespace App\Services\MovementsProductsService;

use App\Models\Invoice;
use App\Services\MovementsProductsService\Chains\AbstractHandler;
use App\Services\MovementsProductsService\Chains\BalanceAfter;
use App\Services\MovementsProductsService\Chains\BalanceBefore;
use App\Services\MovementsProductsService\Chains\BalanceCurrent;
use App\Services\MovementsProductsService\Chains\InvoiceAfter;
use App\Services\MovementsProductsService\Chains\InvoiceCurrent;

class RouteManager
{

    /**
     * @var AbstractHandler[]
     */
    private $coins = [
        BalanceBefore::class,
        BalanceCurrent::class,
        InvoiceCurrent::class,
        BalanceAfter::class,
        InvoiceAfter::class
    ];

    public function run(Invoice $invoice): void
    {
        foreach ($this->coins as $coin) {
            (new $coin)->handle($invoice);
        }
    }
}

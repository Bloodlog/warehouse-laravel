<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\Invoice;

interface Handler
{
    public function handle(Invoice $invoice);

    public function setNext(Handler $handler): Handler;
}

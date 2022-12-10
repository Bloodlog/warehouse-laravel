<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;
use App\Models\Movement;
use Illuminate\Database\Eloquent\Collection;

class AbstractHandler implements Handler
{
    /**
     * @var Handler
     */
    private $nextHandler;

    /**
     * @var array[]
     */
    public $balance = [];

    /**
     * @param Handler $handler
     * @return Handler
     */
    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    /**
     * @param Invoice $invoice
     * @return null
     */
    public function handle(Invoice $invoice)
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($invoice);
        }

        return null;
    }

    /**
     * @param Invoice $invoice
     * @return Collection
     */
    public function calculateInvoice(Invoice $invoice)
    {
        /** @var Collection $movements */
        $movements = $invoice->movements;

        return $movements->groupBy('product_id')->flatMap(function ($items) use ($invoice) {
            $quantity = $items->sum('amount');

            return $items->map(function (Movement $item) use ($quantity, $invoice) {
                return [
                    'warehouse_id' => $item->warehouse_id,
                    'product_id' => $item->product_id,
                    'quantity' => $this->solveType($invoice->invoices_type, $quantity),
                    'report_date' => $invoice->invoice_date->copy()->format(DailyBalance::FORMAT_DATE)
                ];
            });
        })->unique('product_id');
    }

    /**
     * @param $type
     * @param $quantity
     * @return int
     */
    public function solveType($type, $quantity): int
    {
        if ($type === Invoice::TYPE_SUB) {
            return -$quantity;
        }

        return $quantity;
    }

    /**
     * @param \Illuminate\Support\Collection $movements
     * @return void
     */
    function updateBalance(\Illuminate\Support\Collection $movements): void
    {
        $movements->each(function ($item) {
            $productId = $item['product_id'];
            $balanceQuantity = array_key_exists($productId, $this->balance) ? $this->balance[$productId]['quantity'] : 0;
            $this->balance[$productId] = [
                'warehouse_id' => $item['warehouse_id'],
                'product_id' => $item['product_id'],
                'quantity' => $balanceQuantity + $item['quantity'],
            ];
        });
    }
}

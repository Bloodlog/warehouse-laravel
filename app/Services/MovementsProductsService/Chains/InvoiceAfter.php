<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;

class InvoiceAfter extends AbstractHandler
{
    public function handle(Invoice $invoice)
    {
        $fromDate = $invoice->invoice_date->copy()->addDay()->startOfDay();
        $otherInvoicesCurrentInvoice = Invoice::whereDate('invoice_date', '>=', $fromDate)
            ->where(['warehouse_id' => $invoice->warehouse_id])
            ->where(['published' => true])
            ->limit(1)
            ->get();
        if (!$otherInvoicesCurrentInvoice->isEmpty()) {
            $invoicesAfter = Invoice::whereDate('invoice_date', '>=', $fromDate)
                ->where(['warehouse_id' => $invoice->warehouse_id])
                ->where(['published' => true])
                ->get();

            $invoicesAfter->each(function ($item) {
                $this->updateBalance($this->calculateInvoice($item));
            });

            foreach ($this->balance as $item) {
                DailyBalance::create(array_merge($item, ['report_date' => now()->format(DailyBalance::FORMAT_DATE)]));
            }
        }

        return parent::handle($invoice);
    }
}

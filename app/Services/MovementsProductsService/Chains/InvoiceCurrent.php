<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;

class InvoiceCurrent extends AbstractHandler
{

    public function handle(Invoice $invoice)
    {
        $fromDate = $invoice->invoice_date->copy()->startOfDay();
        $toDate = $invoice->invoice_date->copy()->endOfDay();
        $otherInvoicesCurrentInvoice =  Invoice::whereBetween('invoice_date', [$fromDate, $toDate])
            ->where('id', '!=', $invoice->id)
            ->where(['warehouse_id' => $invoice->warehouse_id])
            ->where(['published' => true])
            ->get();
        if (!$otherInvoicesCurrentInvoice->isEmpty()) {
            $otherInvoicesCurrentInvoice->each(function ($item) {
                $this->updateBalance($this->calculateInvoice($item));
            });
        }

        $this->updateBalance($this->calculateInvoice($invoice));
        foreach ($this->balance as $item) {
            DailyBalance::create(array_merge($item, ['report_date' => $invoice->invoice_date->format(DailyBalance::FORMAT_DATE)]));
        }

        return parent::handle($invoice);
    }
}

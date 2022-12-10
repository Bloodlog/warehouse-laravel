<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;

class BalanceBefore extends AbstractHandler
{

    public function handle(Invoice $invoice)
    {
        $lastBalance = DailyBalance::select('report_date')
            ->whereDate('report_date', '<=', $invoice->invoice_date->copy()->subDay()->endOfDay())
            ->where(['warehouse_id' => $invoice->warehouse_id])
            ->orderBy('report_date', 'DESC')
            ->get();
        if (!$lastBalance->isEmpty()) {
            $this->updateBalance(DailyBalance::where('report_date', $lastBalance->report_date)
                ->where(['warehouse_id' => $invoice->warehouse_id])
                ->get());
        }

        return parent::handle($invoice);
    }
}

<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

class BalanceAfter extends AbstractHandler
{

    public function handle(Invoice $invoice)
    {
        /** @var Collection $balanceAfterRow */
        $balanceAfterRow = DailyBalance::select('report_date')
            ->whereDate('report_date', '>=', $invoice->invoice_date->copy()->addDay()->startOfDay())
            ->where(['warehouse_id' => $invoice->warehouse_id])
            ->limit(1)
            ->get();
        if ($balanceAfterRow->isNotEmpty()) {
            DailyBalance::where('report_date', $balanceAfterRow->report_date)
                ->whereDate('report_date', '>=', $invoice->invoice_date->copy()->startOfDay())
                ->where(['warehouse_id' => $invoice->warehouse_id])
                ->delete();

            return parent::handle($invoice);
        }

        return $this->balance;
    }
}

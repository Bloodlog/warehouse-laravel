<?php

namespace App\Services\MovementsProductsService\Chains;

use App\Models\DailyBalance;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

class BalanceCurrent extends AbstractHandler
{

    public function handle(Invoice $invoice)
    {
        /** @var Collection $currentReport */
        $currentReport = DailyBalance::select('id')->where('report_date', $invoice->invoice_date->format(DailyBalance::FORMAT_DATE))
            ->where(['warehouse_id' => $invoice->warehouse_id])
            ->get();
        if (!$currentReport->isEmpty()) {
            $ids = $currentReport->pluck('id');
            DailyBalance::whereIn('id', $ids)->delete();
        }

        return parent::handle($invoice);
    }
}

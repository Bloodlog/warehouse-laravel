<?php

namespace App\Http\Controllers\API\Reports;

use App\Http\Controllers\Controller;
use App\Models\DailyBalance;
use App\Models\Warehouse;

class StockBalancesController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $report = DailyBalance::where('report_date', now()->format(DailyBalance::FORMAT_DATE))->get();
        if ($report->isEmpty()) {
            $reportDate = DailyBalance::select('report_date')->orderBy('report_date', 'DESC')->first();
            $report = DailyBalance::where('report_date', $reportDate->report_date)->get();
        }

        return response()->json($report);
    }

    /**
     * @param Warehouse $warehouse
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouseList(Warehouse $warehouse)
    {
        $report = DailyBalance::where('report_date', now()->format(DailyBalance::FORMAT_DATE))
            ->where(['warehouse_id' => $warehouse->id])
            ->get();
        if ($report->isEmpty()) {
            $reportDate = DailyBalance::select('report_date')
                ->where(['warehouse_id' => $warehouse->id])
                ->orderBy('report_date', 'DESC')
                ->first();
            $report = DailyBalance::where('report_date', $reportDate->report_date
                ->where(['warehouse_id' => $warehouse->id])
            )->get();
        }

        return response()->json($report);
    }
}

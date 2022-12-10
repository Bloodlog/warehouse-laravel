<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\InvoiceRequest;
use App\Jobs\MovementProductsInvoiceJob;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return response()->json(Invoice::paginate(10));
    }

    public function store(InvoiceRequest $request)
    {
        $invoice = Invoice::create($request->all());
        $invoice->movements()->createMany($request->movements);

        return response()->json(['success']);
    }

    public function update(Invoice $invoice, InvoiceRequest $request)
    {
        if ($invoice->published) {
            return response()->json('Нет доступа');
        }
        $invoice->update($request->all());
        $invoice->movements()->delete();
        $invoice->movements()->createMany($request->movements);

        return response()->json(['success']);
    }

    public function setPublish(Invoice $invoice, Request $request)
    {
        $invoice->setPublish($request->published);
        MovementProductsInvoiceJob::dispatchIf($invoice->isDirty('published'), $invoice);
        $invoice->save();

        return response()->json(['success']);
    }
}

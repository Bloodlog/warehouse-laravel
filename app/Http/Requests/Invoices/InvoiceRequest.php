<?php

namespace App\Http\Requests\Invoices;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'invoices_type' => 'required|integer',
            'user_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'contractor_id' => 'nullable|integer',
            'invoice_date' => 'required|date_format:' . Invoice::FORMAT_INVOICE_DATE,
            'published' => 'boolean',

            'movements' =>'array|required',
            'movements.*.product_id' =>'required|integer',
            'movements.*.warehouse_id' =>'required|integer',
            'movements.*.movement_currency' =>'required|integer',
            'movements.*.amount' =>'required|integer',
            'movements.*.price' =>'required|integer',
            'movements.*.vat_percent' =>'required|integer',
            'movements.*.vat_sum' =>'required|integer',
            'movements.*.final_total' =>'required|integer',
        ];
    }
}

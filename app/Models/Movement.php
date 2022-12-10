<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property integer $movement_currency
 * @property integer $amount
 * @property float $price
 * @property float $vat_percent
 * @property float $vat_sum
 * @property float $final_total
 */
class Movement extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'movement_currency',
        'amount',
        'price',
        'vat_percent',
        'vat_sum',
        'final_total',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property integer $quantity
 * @property Carbon $report_date
 */
class DailyBalance extends Model
{
    use HasFactory;

    public const FORMAT_DATE = 'Y-m-d';

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'report_date',
    ];
    protected $casts = [
        'report_date' => 'date:' . self::FORMAT_DATE,
    ];
}

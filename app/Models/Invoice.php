<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $name
 * @property integer $invoices_type
 * @property integer $user_id
 * @property integer $warehouse_id
 * @property integer $contractor_id
 * @property boolean $published
 * @property Carbon $invoice_date
 */
class Invoice extends Model
{
    use SoftDeletes,
        HasFactory;

    public const FORMAT_INVOICE_DATE = 'Y-m-d H:i:s';

    public const TYPE_ADD = 0;

    public const TYPE_SUB = 1;

    protected $fillable = [
        'name',
        'invoices_type',
        'user_id',
        'warehouse_id',
        'contractor_id',
        'invoice_date',
    ];

    protected $casts = [
        'invoice_date' => 'date:' . self::FORMAT_INVOICE_DATE,
        'published' => 'boolean',
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setPublish(bool $publish)
    {
        $this->published = $publish;
    }
}

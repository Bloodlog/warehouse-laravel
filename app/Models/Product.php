<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes,
        HasFactory;

    public const TYPE_DEFAULT = 0;

    protected $fillable = [

    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDefault($query)
    {
        return $query->where('type', self::TYPE_DEFAULT);
    }
}

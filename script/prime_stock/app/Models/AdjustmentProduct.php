<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'adjustment_id',
        'product_id',
        'quantity',
        'type',
        'variation_id',
        'variation_value',
    ];

    /**
     * Relation with Product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

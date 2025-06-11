<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'product_id',
        'quantity',
        'variation_id',
        'variation_value',
    ];

    /**
     * Relation With product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relation with transfer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}

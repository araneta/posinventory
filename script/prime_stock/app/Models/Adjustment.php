<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_id',
        'warehouse_id',
        'total_products',
        'date',
        'note',
    ];

    /**
     * Relation With Warehouse
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WareHouse::class);
    }

    /**
     * Relation With Product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdjustmentProduct::class);
    }

    /**
     * Auto added tracking id 8 character
     */
    public static function boot(): void
    {
        parent::boot();
        self::creating(static function ($model) {
            $model->tracking_id = strtoupper(substr(md5(uniqid('', true)), 0, 8));
        });
    }

    /**
     * Scope Search
     */

    public function scopeSearch($query, $search): \Illuminate\Database\Eloquent\Builder
    {
        if (!$search) {
            return $query;
        }
        return $query->where('tracking_id', 'like', '%' . $search . '%');
    }

}

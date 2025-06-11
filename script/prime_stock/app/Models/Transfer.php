<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_no',
        'from_warehouse_id',
        'to_warehouse_id',
        'total_product',
        'date',
        'note',
    ];

    /**
     * Auto added tracking id 8 character
     */
    public static function boot(): void
    {
        parent::boot();
        self::creating(static function ($model) {
            $model->tracking_no = strtoupper(substr(md5(uniqid('', true)), 0, 8));
        });
    }

    /**
     * Relation with from warehouse
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromWarehouse()
    {
        return $this->belongsTo(WareHouse::class, 'from_warehouse_id');
    }

    /**
     * Relation with to warehouse
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toWarehouse()
    {
        return $this->belongsTo(WareHouse::class, 'to_warehouse_id');
    }

    /**
     * Relation with product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(TransferProduct::class);
    }

    /**
     * Scope Search
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('tracking_no', 'like', '%' . $search . '%');
    }
}

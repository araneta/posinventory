<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'status',
        'staff_id'
    ];

    /**
     * Append attribute
     * @var string[]
     */
    protected $appends = ['stock_quantity'];

    /**
     * Releted with adjustment
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    /**
     * get stock quantity
     * @return int
     */
    public function getStockQuantityAttribute()
    {
        return 0;
    }

    /**
     * Scope Search
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        if($search == null) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%');
    }

    /**
     * Releted with staff
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

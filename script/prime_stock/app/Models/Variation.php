<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'values'];
    /**
     * Casting
     * @var string[]
     */
    protected $casts = [
        'values' => 'array'
    ];

    /**
     * Releted with products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variation')
            ->withPivot('price', 'sale_price', 'alert_quantity', 'quantity', 'value');
    }

    /**
     * Scope Search
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}

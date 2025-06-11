<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'category_id',
        'brand_id',
        'unit_id',
        'sku',
        'alert_quantity',
        'note',
        'status',
        'quantity',
        'price',
        'sale_price',
        'product_type',
        'code',
    ];

    /**
     * Relation with Category
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation with brand
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relation with unit
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Relation with Purchase
     * @return BelongsToMany
     */
    public function purchases(): BelongsToMany
    {
        return $this->belongsToMany(Purchase::class)->withPivot('quantity', 'price', 'total_price', 'variation_id', 'variation_value');
    }

    /**
     * Relation with sales
     * @return BelongsToMany
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity', 'price', 'total_price', 'variation_id', 'variation_value');
    }

    /**
     * Relation with adjustment
     * @return BelongsToMany
     */
    public function adjustments(): BelongsToMany
    {
        return $this->belongsToMany(AdjustmentProduct::class);
    }

    /**
     * Relation with transfers
     * @return BelongsToMany
     */
    public function transfers(): BelongsToMany
    {
        return $this->belongsToMany(Transfer::class);
    }

    /**
     * Relation with returns
     * @return BelongsToMany
     */
    public function returns(): BelongsToMany
    {
        return $this->belongsToMany(ProductReturn::class);
    }

    /**
     * Total Sale
     */
    public function totalSale(): int
    {
        return $this->sales->sum('pivot.quantity');
    }

    /**
     * Total Sale Varian Product
     */

     public function totalSaleVariant($variation_id, $variation_value)
     {
        return $this->sales->where('pivot.variation_id', $variation_id)->where('pivot.variation_value', $variation_value)->sum('pivot.quantity');
     }

    /**
     * Relation with variants
     * @return BelongsToMany
     */
    public function variants()
    {
        return $this->belongsToMany(Variation::class, 'product_variation')
            ->withPivot('price', 'sale_price', 'alert_quantity', 'quantity', 'value');
    }

    /**
     * Total Stock Alert Quantity for variants product
     */
    public function totalStockAlertQuantity(): int
    {
        return $this->variants->sum('pivot.alert_quantity');
    }

    /**
     * Scope Search
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        if(!$search){
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('sku', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%');
    }

    /**
     * Total Sale Quantity
     */
    public function totalSaleQuantity(): int
    {
        return $this->sales->sum('pivot.quantity');
    }

    /**
     * Total Purchase Quantity
     */
    public function totalPurchaseQuantity(): int
    {
        return $this->purchases->sum('pivot.quantity');
    }

    /**
     * Total Sale Price
     */
    public function totalSalePrice(): int
    {
        return $this->sales->sum('pivot.total_price');
    }

    /**
     * Total Purchase Price
     */
    public function totalPurchasePrice(): int
    {
        return $this->purchases->sum('pivot.total_price');
    }

    /**
     * Scope Category
     */
    public function scopeCategory($query, $category)
    {
        if(!$category){
            return $query;
        }
        return $query->where('category_id', $category);
    }

    /**
     * Scope Brand
     */
    public function scopeBrand($query, $brand)
    {
        if(!$brand){
            return $query;
        }
        return $query->where('brand_id', $brand);
    }

    /**
     * Scope Unit
     */
    public function scopeUnit($query, $unit)
    {
        if(!$unit){
            return $query;
        }
        return $query->where('unit_id', $unit);
    }

    /**
     * Scope Status
     */
    public function scopeStatus($query, $status)
    {
        if(!$status){
            return $query;
        }
        $status = $status === 'active' ? 1 : 0;
        return $query->where('status', $status);
    }

    /**
     * Scope Type
     */
    public function scopeType($query, $type)
    {
        if(!$type){
            return $query;
        }
        return $query->where('product_type', $type);
    }

}

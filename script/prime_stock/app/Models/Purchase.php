<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'supplier_id',
        'date',
        'warehouse_id',
        'total_price',
        'discount',
        'tax_amount',
        'payable_amount',
        'paying_amount',
        'paying_date',
        'status',
        'purchase_note',
        'return_note',
        'return_product',
        'return_discount',
        'return_amount',
        'receivable_amount',
        'received_amount',
        'received_date',
        'return_status',
        'return_date',
    ];

    /**
     * Relation with supplier
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relation with warehouse
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(WareHouse::class);
    }

    /**
     * Relation with products
     * @return BelongsToMany
     */
    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price', 'total_price', 'variation_id', 'variation_value');
    }

    /**
     * Relation with returns
     * @return HasMany
     */
    public function returns() : HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    /**
     * Due Amount
     * @return mixed
     */
    public function dueAmount()
    {
        return $this->payable_amount - $this->paying_amount;
    }

    /**
     * Return Due Amount
     * @return mixed
     */
    public function returnDueAmount()
    {
        return $this->receivable_amount - $this->received_amount;
    }

    // in transaction table type purchase and type_id related with purchase id
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'type_id')->where('type', 'purchase');
    }

    /**
     * Return transaction
     */
    public function returnTransaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'type_id')->where('type', 'purchase_return');
    }

    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if(!$search) {
            return $query;
        }
        return $query->where('invoice_no', 'like', '%'.$search.'%')
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('total_price', 'like', '%'.$search.'%')
            ->orWhere('payable_amount', 'like', '%'.$search.'%')
            ->orWhere('paying_amount', 'like', '%'.$search.'%')
            ->orWhere('paying_date', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('purchase_note', 'like', '%'.$search.'%')
            ->orWhere('return_note', 'like', '%'.$search.'%')
            ->orWhere('return_product', 'like', '%'.$search.'%')
            ->orWhere('return_discount', 'like', '%'.$search.'%')
            ->orWhere('return_amount', 'like', '%'.$search.'%')
            ->orWhere('receivable_amount', 'like', '%'.$search.'%')
            ->orWhere('received_amount', 'like', '%'.$search.'%')
            ->orWhere('received_date', 'like', '%'.$search.'%')
            ->orWhere('return_status', 'like', '%'.$search.'%')
            ->orWhere('return_date', 'like', '%'.$search.'%');
    }

    /**
     * Scope Warehouse
     */
    public function scopeWarehouse($query, $warehouse)
    {
        if(!$warehouse) {
            return $query;
        }
        return $query->where('warehouse_id', $warehouse);
    }

    /**
     * Scope Supplier
     */
    public function scopeSupplier($query, $supplier)
    {
        if(!$supplier) {
            return $query;
        }
        return $query->where('supplier_id', $supplier);
    }

    /**
     * Scope Status
     */
    public function scopeStatus($query, $status)
    {
        if(!$status) {
            return $query;
        }
        return $query->where('status', $status);
    }

    /**
     * Scope Return Status
     */
    public function scopeReturnStatus($query, $status)
    {
        if(!$status) {
            return $query;
        }
        return $query->where('return_status', $status);
    }

    /**
     * Scope Date
     */
    public function scopeDate($query, $date)
    {
        if(!$date) {
            return $query;
        }
        $date = explode(' to ', $date);
        $from = date('Y-m-d', strtotime(trim($date[0])));
        $to = date('Y-m-d', strtotime(trim($date[1])));
        return $query->whereBetween('date', [$from, $to]);
    }

    /**
     * scope Return Date
     */
    public function scopeReturnDate($query, $date)
    {
        if(!$date) {
            return $query;
        }
        $date = explode(' to ', $date);
        $from = date('Y-m-d', strtotime(trim($date[0])));
        $to = date('Y-m-d', strtotime(trim($date[1])));
        return $query->whereBetween('return_date', [$from, $to]);
    }

    /**
     * Total Tax Amount
     */
    public function totalTaxAmount(): float
    {
        return taxAmount($this->total_price, $this->tax_amount);
    }

    /**
     * Total Purchase Amount
     */
    public function totalPurchaseAmount()
    {
        return $this->payable_amount - $this->return_amount;
    }
}

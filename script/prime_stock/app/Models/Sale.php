<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'date',
        'warehouse_id',
        'total_price',
        'discount',
        'tax_amount',
        'receivable_amount',
        'received_amount',
        'received_date',
        'status',
        'sale_note',
        'return_note',
        'return_product',
        'return_discount',
        'return_amount',
        'payable_amount',
        'paying_amount',
        'return_date',
        'paying_date',
        'paying_status',
    ];

    /**
     * Relation with customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relation with Warehouse
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class);
    }

    /**
     * Relation with products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price', 'total_price', 'variation_id', 'variation_value');
    }

    /**
     * Due Amount
     * @return mixed
     */
    public function dueAmount()
    {
        return $this->receivable_amount - $this->received_amount;
    }

    /**
     * Returns
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function returns()
    {
        return $this->belongsToMany(Product::class, 'product_sale_return')->withPivot('quantity', 'price', 'total_price',  'variation_id', 'variation_value');
    }

    /**
     * Return Due Amount
     * @return mixed
     */
    public function returnDueAmount()
    {
        return $this->payable_amount - $this->paying_amount;
    }

    /**
     * Transaction
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'type_id')->where('type', 'sale');
    }

    /**
     * Return Transaction
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function returnTransaction()
    {
        return $this->hasOne(Transaction::class, 'type_id')->where('type', 'sale_return');
    }

    /**
     * Search Scope
     */
    public function scopeSearch($query, $search)
    {
        if(!$search) {
            return $query;
        }
        return $query->where('invoice_no', 'like', '%'.$search.'%')
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('total_price', 'like', '%'.$search.'%')
            ->orWhere('discount', 'like', '%'.$search.'%')
            ->orWhere('tax_amount', 'like', '%'.$search.'%')
            ->orWhere('receivable_amount', 'like', '%'.$search.'%')
            ->orWhere('received_amount', 'like', '%'.$search.'%')
            ->orWhere('received_date', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('sale_note', 'like', '%'.$search.'%')
            ->orWhere('return_note', 'like', '%'.$search.'%')
            ->orWhere('return_product', 'like', '%'.$search.'%')
            ->orWhere('return_discount', 'like', '%'.$search.'%')
            ->orWhere('return_amount', 'like', '%'.$search.'%')
            ->orWhere('payable_amount', 'like', '%'.$search.'%')
            ->orWhere('paying_amount', 'like', '%'.$search.'%')
            ->orWhere('return_date', 'like', '%'.$search.'%')
            ->orWhere('paying_date', 'like', '%'.$search.'%')
            ->orWhere('paying_status', 'like', '%'.$search.'%');
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
     * Scope Customer
     */
    public function scopeCustomer($query, $customer)
    {
        if(!$customer) {
            return $query;
        }
        return $query->where('customer_id', $customer);
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
        return $query->where('paying_status', $status);
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
        return $query->whereBetween('date', $date);
    }

    /**
     * Scope Return Date
     */
    public function scopeReturnDate($query, $date)
    {
        if(!$date) {
            return $query;
        }
        $date = explode(' to ', $date);
        return $query->whereBetween('return_date', $date);
    }

    /**
     * Total Tax Amount
     */
    public function totalTaxAmount()
    {
        return taxAmount($this->total_price, $this->tax_amount);
    }

    /**
     * Total Sale Amount
     */
    public function totalSaleAmount()
    {
        return $this->receivable_amount - $this->return_amount;
    }
}

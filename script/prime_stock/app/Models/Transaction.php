<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'account_id', 'amount', 'date', 'notes', 'type', 'type_id'];

    /**
     * Relation with account
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Auto generate transaction_id with 8 characters prefix trx-
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_id = 'trx-' . substr(md5(uniqid('', true)), 0, 8);
        });
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

        return $query->where('transaction_id', 'like', '%'.$search.'%')
            ->orWhere('amount', 'like', '%'.$search.'%')
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('notes', 'like', '%'.$search.'%');
    }

    /**
     * Relation with purchase
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'type_id');
    }

    /**
     * Relation with sale
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'type_id');
    }

    /**
     * Scope Account
     */
    public function scopeAccount($query, $account)
    {
        if(!$account){
            return $query;
        }

        return $query->where('account_id', $account);
    }

    /**
     * Scope Type
     */
    public function scopeType($query, $type)
    {
        if(!$type){
            return $query;
        }

        return $query->where('type', $type);
    }

    /**
     * Scope Date
     */
    public function scopeDate($query, $date)
    {
        if(!$date){
            return $query;
        }
        $date = explode(' to ', $date);
        return $query->whereBetween('date', $date);
    }

    /**
     * Scope supplier
     */
    public function scopeSupplier($query, $supplier)
    {
        if(!$supplier){
            return $query;
        }

        return $query->whereHas('purchase', function($query) use ($supplier){
            $query->where('supplier_id', $supplier);
        });
    }

    /**
     * Scope customer
     */
    public function scopeCustomer($query, $customer)
    {
        if(!$customer){
            return $query;
        }

        return $query->whereHas('sale', function($query) use ($customer){
            $query->where('customer_id', $customer);
        });
    }

    /**
     * Scope Warehouse
     */
    public function scopeWarehouse($query, $warehouse)
    {
        if(!$warehouse){
            return $query;
        }

        return $query->whereHas('purchase', function($query) use ($warehouse){
            $query->where('warehouse_id', $warehouse);
        })->orWhereHas('sale', function($query) use ($warehouse){
            $query->where('warehouse_id', $warehouse);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    protected $appends = ['total_payable', 'total_receivable'];

    /**
     * Relation with sales
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get Total payable
     * @return int|mixed
     */
    public function getTotalPayableAttribute()
    {
        $payable_amount = $this->sales()->sum('payable_amount');
        $paid_amount = $this->sales()->sum('paying_amount');
        return $payable_amount - $paid_amount;
    }

    /**
     * Get Total Receivable
     * @return int|mixed
     */
    public function getTotalReceivableAttribute()
    {
        $receivable_amount = $this->sales()->sum('receivable_amount');
        $received_amount = $this->sales()->sum('received_amount');
        return $receivable_amount - $received_amount;
    }

    /**
     * Get Total Payable Data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function payableData()
    {
        return $this->sales()->where('paying_status',  'due')->where('return_product', '!=', 0)->get();
    }

    /**
     * Get Receivable Data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function receivableData()
    {
        return $this->sales()->where('status',  'due')->get();
    }

    /**
     * Get Total Receiable
     * @return mixed
     */
    public function totalReceivable()
    {
        return $this->total_receivable - $this->total_payable;
    }

    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%');
        }
        return $query;
    }
}

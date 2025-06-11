<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
    ];

    protected $appends = ['total_payable', 'total_receivable'];

    /**
     * Relation with purchase
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get Total Payable
     * @return int|mixed
     */
    public function getTotalPayableAttribute()
    {
        $payable_amount = $this->purchases()->sum('payable_amount');
        $paid_amount = $this->purchases()->sum('paying_amount');
        return $payable_amount - $paid_amount;
    }

    /**
     * Get Total Receivable
     * @return int|mixed
     */
    public function getTotalReceivableAttribute()
    {
        $receivable_amount = $this->purchases()->sum('receivable_amount');
        $received_amount = $this->purchases()->sum('received_amount');
        return $receivable_amount - $received_amount;
    }

    /**
     * Payable Data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function payableData()
    {
        return $this->purchases()->where('status',  'due')->get();
    }

    /**
     * Receivable Data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function receivableData()
    {
        return $this->purchases()->where('return_status',  'due')->where('return_product', '!=', 0)->get();
    }

    /**
     * Total Payable
     * @return mixed
     */
    public function totalPayabale()
    {
        return $this->total_payable - $this->total_receivable;
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
        return $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orWhere('phone', 'like', '%'.$search.'%')
            ->orWhere('company', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%');
    }
}

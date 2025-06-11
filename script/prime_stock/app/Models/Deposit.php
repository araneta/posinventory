<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'amount', 'date', 'notes'];

    /**
     * Relation with account
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relation with transaction
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'account_id', 'id');
    }

    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->where('amount', 'like', '%' . $search . '%')
            ->orWhere('date', 'like', '%' . $search . '%');
    }
}

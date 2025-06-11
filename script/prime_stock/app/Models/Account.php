<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'account_number', 'opening_balance', 'note'];

    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('account_number', 'like', '%' . $search . '%');
    }
}

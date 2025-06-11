<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'date',
        'amount',
        'note',
    ];

    /**
     * Scope Type
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'type_id');
    }

    /**
     * Scope Search
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->where('date', 'like', '%' . $search . '%')
            ->orWhere('amount', 'like', '%' . $search . '%')
            ->orWhere('note', 'like', '%' . $search . '%');
    }

    /**
     * Scope Date
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeDate($query, $date)
    {
        if (!$date) {
            return $query;
        }
        $date = explode(' to ', $date);
        return $query->whereBetween('date', $date);
    }
}

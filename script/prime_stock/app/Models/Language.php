<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'flag',
        'name',
        'locale',
        'is_default',
        'status',
    ];


    /**
     * scope search
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('locale', 'like', '%' . $search . '%');
    }
}

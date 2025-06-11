<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'subject', 'content', 'type'];

    /**
     * Scope a query to search email template.
     */

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('subject', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%');
    }
}

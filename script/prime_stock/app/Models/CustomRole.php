<?php

namespace App\Models;
use Spatie\Permission\Models\Role;

class CustomRole extends Role
{

    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%');
    }


}
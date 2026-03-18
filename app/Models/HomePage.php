<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
class HomePage extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

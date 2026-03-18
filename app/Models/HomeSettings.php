<?php

// app/Models/HomeSettings.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSettings extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';

    protected $fillable = [
        'name',
        // Add other fillable fields as needed
    ];

    public function degrees()
    {
        return $this->hasMany(Degree::class);
    }
}

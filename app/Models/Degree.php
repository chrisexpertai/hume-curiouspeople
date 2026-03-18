<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $table = 'degrees';

    protected $fillable = [
        'school_id',
        'name',
        // Add other fillable fields as needed
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

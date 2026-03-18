<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content']; // Add other necessary fields for your replies

    // Define the relationship between Reply and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship between Reply and Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

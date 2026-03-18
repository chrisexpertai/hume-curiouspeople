<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'content', 'status'];

    // Define the relationship between Ticket and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship between Ticket and Replies
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}

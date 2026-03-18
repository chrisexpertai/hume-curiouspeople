<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Course;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'course_id', 'content'];

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

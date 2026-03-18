<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOccupation extends Model
{
    protected $table = 'users_occupations';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

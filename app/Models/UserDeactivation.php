<?php

// app/Models/UserDeactivation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeactivation extends Model
{
    protected $table = 'user_deactivations';

    protected $fillable = [
        'user_id',
        'deactivated_at',
    ];

    protected $dates = [
        'deactivated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCss extends Model
{
    protected $table = 'custom_css'; // Specify the correct table name
    protected $fillable = ['css_code'];
}
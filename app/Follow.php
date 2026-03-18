<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    public static $requested = "requested";
    public static $accepted = "accepted";
    public static $rejected = "rejected";
    
    public $timestamps = false;

    protected $guarded = ['id'];

}

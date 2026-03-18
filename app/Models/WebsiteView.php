<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class WebsiteView extends Model
{
    protected $fillable = ['ip_address', 'user_agent']; // Example additional attributes

    // Add any additional methods or relationships here
    public static function count()
    {

        $sql = "SELECT 
                DATE_FORMAT(w.`updated_at`, '%Y-%m-%d') AS `date`,
                COUNT(*) AS visits_user
                FROM
                website_views w
                GROUP BY DATE_FORMAT(w.`updated_at`, '%Y-%m-%d')
                ORDER BY w.updated_at DESC";
        $visits = DB::select($sql);
        return $visits;
    }
}

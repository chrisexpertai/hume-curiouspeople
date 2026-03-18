<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Section extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function items()
    {
        $query = $this->hasMany(Content::class)->orderBy('sort_order', 'asc');

        if (Auth::check()) {
            $query->with('is_completed');
        }

        return $query;
    }

    public function getDripAttribute()
    {
        $dripData = [
            'is_lock' => false,
            'message' => null,
        ];

        $currentTime = Carbon::now()->timestamp;

        if ($this->unlock_date && strtotime($this->unlock_date) > $currentTime) {
            $unlockDate = Carbon::createFromTimeString($this->unlock_date)->format(get_option('date_format'));

            $dripData['is_lock'] = true;
            $dripData['message'] = 'The content will become available at ' . $unlockDate;
        }

        // If Lock by Days
        if ($this->unlock_days && $this->unlock_days > 0 && Auth::check()) {
            $user = Auth::user();
            $enrollmentDate = $user->isEnrolled($this->course_id) ? $user->enrolled_at : Carbon::now();

            $unlockDate = Carbon::parse($enrollmentDate)->addDays($this->unlock_days);
            $now = Carbon::now();

            if ($unlockDate->gt($now)) {
                $diffDays = $unlockDate->diffInDays($now);
                $dripData['is_lock'] = true;
                $dripData['message'] = "The content will become available in {$diffDays} days";
            }
        }

        return (object)$dripData;
    }
}

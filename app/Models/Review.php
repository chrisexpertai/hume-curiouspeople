<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'course_id', 'review', 'rating', 'status', 'updated_at', 'created_at'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('photo_query');
    }

    public function save_and_sync($data = []){
        {
        if (is_array($data) && count($data)) {
            $this->update($data);
        } else {
            $this->save();
        }

        $this->updateCourseRating();
      

        return $this;
    }
}


    private function updateCourseRating()
    {
        $course = $this->course;

        $ratingCount = $course->reviews->count();

        $ratingVal = $ratingCount > 0 ? $course->reviews->sum('rating') / $ratingCount : 0;

        $starCounts = $this->getStarCounts($course);

        $course->fill([
            'rating_value' => $ratingVal,
            'rating_count' => $ratingCount,
        ] + $starCounts)->save();
    }

    private function getStarCounts($course)
    {
        return [
            'five_star_count' => $course->reviews()->whereRating(5)->count(),
            'four_star_count' => $course->reviews()->whereRating(4)->count(),
            'three_star_count' => $course->reviews()->whereRating(3)->count(),
            'two_star_count' => $course->reviews()->whereRating(2)->count(),
            'one_star_count' => $course->reviews()->whereRating(1)->count(),
        ];
    }
}

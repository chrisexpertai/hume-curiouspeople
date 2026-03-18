<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\User;

class StudentProgressController extends Controller
{

    public function index($course_id = null){
        $title = __t('students_progress_report');

        if ($course_id){
            return view(('front.dashboard.student-progress.index'), compact('title', 'course_id'));
        }


        return view(('front.dashboard.student-progress.courses'), compact('title', 'course_id'));
    }

    public function details($course_id, $user_id){
        $title = __t('students_progress_report');
        $course = Course::find($course_id);
        $user = User::find($user_id);

        return view(('front.dashboard.student-progress.details'), compact('title', 'course', 'user'));
    }

}

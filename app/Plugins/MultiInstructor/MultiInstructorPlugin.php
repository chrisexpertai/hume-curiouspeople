<?php

namespace App\Plugins\MultiInstructor;

use App\Module\PluginBase;

class MultiInstructorPlugin extends PluginBase {

    public $name = 'Multi Instructor';
    public $slug = 'multi_instructor';
    public $url = 'https://piksera.com'; 
    public $description = 'Manage multiple instructors for Piksera LMS.';
    public $author = 'Piksera LMS';
    public $author_url = 'https://piksera.com';  
    public $version = '1.0.1';
    public $lms_version = '1.0.1';

    public function boot(){
        $this->enableRoutes();
        $this->enableViews();
    }

    public function add_course_nav_item($nav_items){
        $nav_items['multi_instructor_search'] = [
            'name' => 'Instructors',
            'icon' => '<i class="la la-male"></i>',
            'is_active' => request()->is('dashboard/courses/*/instructors*'),
        ];

        return $nav_items;
    }

}

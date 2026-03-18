<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\UserOccupation;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    public function index()
    {
        $title = __t('home_page_title');
         $new_courses = Course::publish()->orderBy('created_at', 'desc')->take(12)->get();
        $featured_courses = Course::publish()->whereIsFeatured(1)->orderBy('featured_at', 'desc')->take(6)->get();
        $popular_courses = Course::publish()->whereIsPopular(1)->orderBy('popular_added_at', 'desc')->take(8)->get();
        $instructors = User::instructor()->active()->get();
        $posts = Post::all();
        $categories = Category::parent()->with('sub_categories')->get();
        // Access the authenticated user using the auth() helper
        $auth_user = auth()->user();



        // Check if the authenticated user is not null before accessing enrolled courses
        $enrolledCourses = $auth_user ? $auth_user->enrolls()->paginate(8) : null;

        // Load the 'courses' relationship for the authenticated user
        if ($auth_user) {
            $auth_user->load('courses');
        }

        // return view(('front.index'), compact('title', 'categories', 'new_courses', 'posts', 'featured_courses', 'enrolledCourses', 'popular_courses', 'instructors', 'auth_user'));
        // return view('frontend.login_to');
        return redirect()->route('login');
    }


    public function courses(Request $r){
        $title = __t('courses');
        $categoryModel = new Category();
        $categories = $categoryModel->parent()->with('sub_categories')->get();                $topics = Category::whereCategoryId($r->category)->get();
        $courses = Course::query();
        $courses = $courses->publish();



        if ($r->path() === 'featured-courses'){
            $title = __t('featured_courses');
            $courses = $courses->where('is_featured', 1);
        }elseif ($r->path() === 'popular-courses'){
            $title = __t('popular_courses');
            $courses = $courses->where('is_popular', 1);
        }

        if ($r->q){
            $courses = $courses->where('title', 'LIKE', "%{$r->q}%");
        }
        if ($r->category){
            $courses = $courses->where('second_category_id', $r->category);
        }
        if ($r->topic){
            $courses = $courses->where('category_id', $r->topic);
        }


        if ($r->has('level')) {
            // Ensure that $r->price is an array
            $level = is_array($r->level) ? $r->level : [$r->level];

            // Filter courses based on selected price plans
            $courses = $courses->whereIn('level', $level);
        } 


if ($r->rating) {
    $courses = $courses->where('rating_value', '>=', $r->rating);
}



        /**
         * Find by Video Duration
         */
        if ($r->video_duration === '0_2'){
            $durationEnd = (60 * 60 * 3) - 1; //02:59:59
            $courses = $courses->where('total_video_time','<=', $durationEnd);
        }elseif ($r->video_duration === '3_5'){
            $durationStart = (60 * 60 * 3) ;
            $durationEnd = (60 * 60 * 6) -1;
            $courses = $courses->whereBetween('total_video_time',[$durationStart, $durationEnd]);
        }elseif ($r->video_duration === '6_10'){
            $durationStart = (60 * 60 * 6) ;
            $durationEnd = (60 * 60 * 11) -1;
            $courses = $courses->whereBetween('total_video_time',[$durationStart, $durationEnd]);
        }elseif ($r->video_duration === '11_20'){
            $durationStart = (60 * 60 * 11) ;
            $durationEnd = (60 * 60 * 21) -1;
            $courses = $courses->whereBetween('total_video_time',[$durationStart, $durationEnd]);
        }elseif ($r->video_duration === '21'){
            $durationStart = (60 * 60 * 21) ;
            $courses = $courses->where('total_video_time', '>=', $durationStart);
        }

        switch ($r->sort){
            case 'most-reviewed' :
                $courses = $courses->orderBy('rating_count', 'desc');
                break;
            case 'highest-rated' :
                $courses = $courses->orderBy('rating_value', 'desc');
                break;
            case 'newest' :
                $courses = $courses->orderBy('published_at', 'desc');
                break;
            case 'price-low-to-high' :
                $courses = $courses->orderBy('price', 'asc');
                break;
            case 'price-high-to-low' :
                $courses = $courses->orderBy('price', 'desc');
                break;
            default:

                if ($r->path() === 'featured-courses'){
                    $courses = $courses->orderBy('featured_at', 'desc');
                }elseif ($r->path() === 'popular-courses'){
                    $courses = $courses->orderBy('popular_added_at', 'desc');
                }
                else{
                    $courses = $courses->orderBy('created_at', 'desc');
                }
        }

        $per_page = $r->perpage ? $r->perpage : 10;
        $courses = $courses->paginate($per_page);

        return view(('front.courses'), compact('title', 'courses',  'categories', 'topics'));
    }

    // HomeController.php


    public function searchInstructors(Request $request)
    {
        $title = __t('find_tutor');
        $user = auth()->user();

        // $userOccupations = UserOccupation::where('user_id', $user->id)->pluck('category_id')->toArray();


        $searchQuery = $request->input('q');
        $instructors = User::instructor()
            ->active()
            ->where('name', 'LIKE', "%{$searchQuery}%")
            ->paginate(10); // Set the number of items per page directly here


        return view(theme('tutor.search'), compact('title', 'instructors', 'searchQuery'));
    }

    public function languageDemo(){
        return view('languageDemo');
    }



public function showInstructor(User $instructor)
{
    $title = $instructor->name . ' - Instructor Profile';

    return view(theme('tutor.show'), compact('title', 'instructor'));
}

    public function BecomeInstructor(){

        $title = '';

        return view(('front.become_instructor'), compact('title'));


    }


    public function Pricing(){

        $title = '';

        return view(('front.pricing'), compact('title'));


    }


    public function clearCache(){
        Artisan::call('debugbar:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        if (function_exists('exec')){
            exec('rm ' . storage_path('logs/*'));
        }
        $this->rrmdir(storage_path('logs/'));

        return redirect(route('home'));
    }

    public function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        $this->rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            //rmdir($dir);
        }
    }

}

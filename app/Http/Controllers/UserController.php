<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Sale;
use App\Models\User;
use App\Models\Course;
use App\Models\Follow;
use App\Models\Review;
use App\Models\Reward;
use App\Models\Content;
use App\Models\Product;
use App\Models\Section;
use App\Models\Webinar;
use App\Models\Category;
use App\Models\UserMeta;
use App\Models\Documents;
use App\Models\Functions;
use App\Models\Attachment;
use App\Models\ForumTopic;
use App\Models\Newsletter;
use App\Models\UserZoomApi;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\UserOccupation;
use App\Models\RewardAccounting;
use App\Models\SubscriptionPlan;
use App\Models\TeacherApplication;
use Illuminate\Support\Facades\DB;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function profile($id){
        $user =  User::find($id);
        if (! $user){
            abort(404);
        }

        $message = 'Viewed profile of user: ' . $user->name;


        // Fetch the user's chosen occupations
        $userOccupations = UserOccupation::where('user_id', $user->id)->pluck('category_id')->toArray();

        $title = $user->name;


        $educations = $user->education()->get(); // Retrieve all education entries for the user


        $followings = $user->following();
        $followers = $user->followers();

        $authUserIsFollower = false;
        if (auth()->check()) {
            $authUserIsFollower = $followers->where('follower', auth()->id())
                ->where('status', Follow::$accepted)
                ->first();
        }



        $data = [

            'user' => $user,
            'title' => $title,
            'userFollowers' => $followers,
            'userFollowing' => $followings,
            'userIsFollower' => $authUserIsFollower,
            'educations' => $educations,
             'userOccupations' => $userOccupations, // Pass the user's chosen occupations to the view
        ];

        return view(theme() . '.profile', $data);
    }



    public function getEducation()
{
    $user = Auth::user();
    $educations = $user->education()->get(); // Retrieve all education entries for the user

    return view('user.education_partial', compact('educations'));
}

    public function showPricing()
    {
        $plans = SubscriptionPlan::all();

        return view('pricing', ['plans' => $plans]);
    }

    public function purchaseSubscription(Request $request)
    {

            // Send notification
            $message = 'Purchased subscription';
            $this->sendNotification($message, 'subscription_purchased');

        $this->validate($request, [
            'plan_id' => 'required|exists:subscription_plans,id',
            'duration_months' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $durationInMonths = $request->duration_months;

        // Perform any additional validation or actions here

        // Update the user's subscription details
        $user->purchaseSubscription($plan->id, $durationInMonths);

        // You might want to perform additional actions, such as granting access to courses.

        return redirect()->route('dashboard')->with('success', 'Subscription purchased successfully!');
    }



    public function followToggle($id)
   {
        $authUser = auth()->user();
        $user = User::where('id', $id)->first();

        $followStatus = false;
        $follow = Follow::where('follower', $authUser->id)
            ->where('user_id', $user->id)
            ->first();

        if (empty($follow)) {
            Follow::create([
                'follower' => $authUser->id,
                'user_id' => $user->id,
                'status' => Follow::$accepted,
            ]);

            $followStatus = true;
        } else {
            $follow->delete();
        }

        return response()->json([
            'code' => 200,
            'follow' => $followStatus
        ], 200);
    }


   public function availableTimes(Request $request, $id)
    {
        $timestamp = $request->get('timestamp');
        $dayLabel = $request->get('day_label');
        $date = $request->get('date');

        $user = User::where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$user) {
            abort(404);
        }

        $meeting = Meeting::where('creator_id', $user->id)
            ->with(['meetingTimes'])
            ->first();

        $resultMeetingTimes = [];

        if (!empty($meeting->meetingTimes)) {

            if (empty($dayLabel)) {
                $dayLabel = Carbon::createFromTimestampUTC($timestamp, 'l', false, false);
            }

            $dayLabel = mb_strtolower($dayLabel);

            $meetingTimes = $meeting->meetingTimes()->where('day_label', $dayLabel)->get();

            if (!empty($meetingTimes) and count($meetingTimes)) {

                foreach ($meetingTimes as $meetingTime) {
                    $can_reserve = true;

                    $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
                        ->where('day', $date)
                        ->first();

                    if ($reserveMeeting && ($reserveMeeting->locked_at || $reserveMeeting->reserved_at)) {
                        $can_reserve = false;
                    }

                    /*if ($timestamp + $secondTime < time()) {
                        $can_reserve = false;
                    }*/

                    $resultMeetingTimes[] = [
                        "id" => $meetingTime->id,
                        "time" => $meetingTime->time,
                        "description" => $meetingTime->description,
                        "can_reserve" => $can_reserve,
                        'meeting_type' => $meetingTime->meeting_type
                    ];
                }
            }
        }

        return response()->json([
            'times' => $resultMeetingTimes
        ], 200);
    }



    public function review($id){
        $review = Review::find($id);
        $title = 'Review by '. $review->user->name;

        return view(theme('review'), compact('review', 'title'));
    }

    public function updateWishlist(Request $request){
        $course_id = $request->course_id;

        $user = Auth::user();
        if ( ! $user->wishlist->contains($course_id)){
            $user->wishlist()->attach($course_id);
            $response = ['success' => 1, 'state' => 'added'];
        }else{
            $user->wishlist()->detach($course_id);
            $response = ['success' => 1, 'state' => 'removed'];
        }

        $addedWishList = DB::table('wishlists')->where('user_id', $user->id)->pluck('course_id');

        $user->update_option('wishlists', $addedWishList);

        return $response;
    }

    public function changePassword(){
        $title = __a('change_password');
        return view('admin.change_password', compact('title'));
    }

    public function changePasswordPost(Request $request){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }
        $rules = [
            'old_password'  => 'required',
            'new_password'  => 'required|confirmed',
            'new_password_confirmation'  => 'required',
        ];
        $this->validate($request, $rules);

        $old_password = $request->old_password;
        $new_password = $request->new_password;

        if(Auth::check()) {
            $logged_user = Auth::user();

            if(Hash::check($old_password, $logged_user->password)) {
                $logged_user->password = Hash::make($new_password);
                $logged_user->save();
                return redirect()->back()->with('success', __a('password_changed_msg'));
            }
            return redirect()->back()->with('error', __a('wrong_old_password'));
        }
    }

    public function deleteProfile()
{
    return view('front.dashboard.settings.delete_profile');
}


public function destroy(Request $request)
{
    $request->validate([
        'confirmation' => 'required|accepted', // Make sure the checkbox is checked
    ]);

    $user = Auth::user();

    // Store the current user type before deactivation
    $user->user_type_before_deactivation = $user->user_type;

    // Set user_type to "inactive"
    $user->user_type = 'inactive';

    // Set active status to false
    $user->active = false;

    // Save the changes
    $user->save();

    // Log the user out after deactivating the account
    Auth::logout();

    // Store deactivation timestamp in the new table
    $user->deactivation()->create(['deactivated_at' => now()]);

    return redirect('/')->with('success', 'Your account has been deactivated.');
}


public function showReactivateForm()
{
    return view('front.dashboard.settings.reactivate');
}

public function reactivateAccount()
{
    $user = Auth::user();

    // Check if the user had a previous role before deactivation
    $previousRole = $user->user_type_before_deactivation;

    // If the user had a previous role, set their user_type back to that role
    if ($previousRole) {
        $user->user_type = $previousRole;
    } else {
        // If no previous role is found, set the user_type to a default role
        $user->user_type = 'student';
    }

    // Clear the user_type_before_deactivation attribute
    $user->user_type_before_deactivation = null;

      // Set active status to false
      $user->active = true;
    // Save the changes

    $user->save();

    // Delete the deactivation record
    $user->deactivation()->delete();

    // Redirect the user to the dashboard route
    return Redirect::route('dashboard')->with('success', 'Your account has been reactivated.');
}


    public function users(Request $request){
        $ids = (array) $request->bulk_ids;

        if ( is_array($ids) && in_array(1, $ids)){
            return back()->with('error', __a('admin_non_removable'));
        }

        //Update
        if ($request->bulk_action_btn === 'update_status' && $request->status && is_array($ids) && count($ids)){
            User::whereIn('id', $ids )->update(['active_status' => $request->status]);
            return back()->with('success', __a('bulk_action_success'));
        }

        if ($request->bulk_action_btn === 'delete' && is_array($ids) && count($ids)){
            if(config('app.is_demo')) return back()->with('error', __a('demo_restriction'));

            User::whereIn('id', $ids )->delete();
            return back()->with('success', __a('bulk_action_success'));
        }

        $users = User::query();
        if ($request->q){
            $users = $users->where(function($q)use($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%");
            });
        }

        if ($request->filter_user_group){
            $users = $users->where('user_type', $request->filter_user_group);
        }
        if ($request->filter_status){
            $users = $users->where('active_status', $request->filter_status);
        }


        $title = __a('users');
        $users = $users->orderBy('id', 'desc')->paginate(100);

        return view('admin.users.index', compact('title', 'users'));
    }

    public function viewStudents(Request $request)
{
    $query = $request->input('query');

    $perPage = $request->input('per_page', 10); // Number of items per page, default is 10

    $students = User::where('user_type', 'student')
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%$query%")
                          ->orWhere('city', 'like', "%$query%");
                    })
                    ->withCount(['enrolledCourses', 'payments'])
                    ->with('subscriptionPlan')
                    ->orderBy('id', 'desc')
                    ->paginate($perPage);

    // Calculate total amount spent by each student
    foreach ($students as $student) {
        $totalAmountSpent = $student->payments->sum('amount');
        $student->totalAmountSpent = $totalAmountSpent;
    }

    $title = 'Student Cards';

    return view('admin.students.index', compact('title', 'students'));
}



public function viewInstructors(Request $request)
{
    $ids = (array) $request->bulk_ids;

    $title = 'Instructors Cards';
    $query = User::where('user_type', 'instructor');

    // Search functionality
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%'); // Adjust other fields as needed
    }

     //Update
     if ($request->bulk_action_btn === 'update_status' && $request->status && is_array($ids) && count($ids)){
        User::whereIn('id', $ids )->update(['active_status' => $request->status]);
        return back()->with('success', __a('bulk_action_success'));
    }

    if ($request->bulk_action_btn === 'delete' && is_array($ids) && count($ids)){
        if(config('app.is_demo')) return back()->with('error', __a('demo_restriction'));

        User::whereIn('id', $ids )->delete();
        return back()->with('success', __a('bulk_action_success'));
    }

    $instructors = $query->paginate(10); // Adjust the number per page as needed

    return view('admin.instructors.index', compact('title', 'instructors'));
}




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create User';
        return view('admin.users.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'user_type' => 'required', // Add more validation rules as needed
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->user_type = $request->input('user_type');
        // Add more fields as needed

        $user->save();



        return redirect()->route('users')->with('success', 'User created successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $title = 'Edit User: ' . $user->name;

        return view('admin.users.edit', compact('user', 'title'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:8',
        'user_type' => 'required', // Add more validation rules as needed
    ]);

    $user = User::findOrFail($id);
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Check if password field is not empty before updating
    if (!empty($request->input('password'))) {
        $user->password = bcrypt($request->input('password'));
    }

    $user->user_type = $request->input('user_type');

    $user->save();

    return redirect()->route('users')->with('success', 'User updated successfully!');
}




    public function addAdministratorForm()
    {
        $title = 'Add Administrator';
        return view('admin.add_administrator', compact('title'));
    }


public function storeAdministrator(Request $request)
{
    // Validate and store administrator logic here
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        // Add more validation rules as needed
    ]);

    $administrator = new User();
    $administrator->name = $request->input('name');
    $administrator->email = $request->input('email');
    $administrator->password = bcrypt($request->input('password'));
    $administrator->user_type = 'admin';
    $administrator->save();

    return redirect()->route('users')->with('success', 'Administrator added successfully!');
}

    public function administratorBlockUnblock(Request $request)
    {
        // Block/unblock administrator logic here
        $administrator = User::find($request->input('administrator_id'));

        if ($administrator) {
            $administrator->status = !$administrator->status; // Toggle the status
            $administrator->save();

            $action = $administrator->status ? 'unblocked' : 'blocked';

            return redirect()->route('users')->with('success', "Administrator $action successfully!");
        }

        return redirect()->route('users')->with('error', 'Administrator not found!');
    }



    // Add the missing function for showing user details (you can customize it based on your requirements)
    public function showUserDetails($id)
    {
        $user = User::find($id);
        $title = 'User Details: ' . $user->name;

        return view('user.details', compact('user', 'title'));
    }







  public function contactInfo(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'user_type' => 'required|in:student,instructor',
        ]);

        $user = User::find($request->get('user_id'));

        if (!empty($user)) {
            $itemId = $request->get('item_id');
            $userType = $request->get('user_type');
            $description = null;
            $location = null;

            if (!empty($itemId)) {
                $reserve = ReserveMeeting::where('id', $itemId)
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id);

                        if (!empty($user->meeting)) {
                            $query->orWhere('meeting_id', $user->meeting->id);
                        }
                    })->first();

                if (!empty($reserve)) {
                    if ($userType == 'student') {
                        $description = $reserve->description;
                    } elseif (!empty($reserve->meetingTime)) {
                        $description = $reserve->meetingTime->description;
                    }

                    if ($reserve->meeting_type == 'in_person') {
                        $userMetas = $user->userMetas;

                        if (!empty($userMetas)) {
                            foreach ($userMetas as $meta) {
                                if ($meta->name == 'address') {
                                    $location = $meta->value;
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'code' => 200,
                'avatar' => $user->getAvatar(),
                'name' => $user->name,
                'email' => !empty($user->email) ? $user->email : '-',
                'phone' => !empty($user->mobile) ? $user->mobile : '-',
                'description' => $description,
                'location' => $location,
            ], 200);
        }

        return response()->json([], 422);
    }


    public function offlineToggle(Request $request)
    {
        $user = auth()->user();

        $message = $request->get('message');
        $toggle = $request->get('toggle');
        $toggle = (!empty($toggle) and $toggle == 'true');

        $user->offline = $toggle;
        $user->offline_message = $message;

        $user->save();

        return response()->json([
            'code' => 200
        ], 200);
    }

    // UserController.php
    public function showTeacherApplicationForm()
    {
        $title = 'Apply for Teacher';

        // Assuming you are using Auth::user() to get the authenticated user
        $user = Auth::user();

        // If $user is not defined, you might want to handle it accordingly
        if (!$user) {
            // Redirect or display an error message
            return redirect()->route('login')->with('error', 'Please log in to apply for teacher.');
        }

        // Pass the $user variable to the view
        return view('front.become_instructor', compact('title', 'user'));
    }


    public function submitTeacherApplication(Request $request)
    {

        $user = Auth::user();

        // Validate the incoming request data
        $request->validate([

        ]);
        $userId = auth()->id();

        // Create the teacher application record
        TeacherApplication::create([
            'user_id' => auth()->id(),
            'contact_number' => $request->input('contact_number'),
            'address' => $request->input('address'),
            'date_of_birth' => $request->input('date_of_birth'),
            'highest_degree' => $request->input('highest_degree'),
            'institution' => $request->input('institution'),
            'field_of_study' => $request->input('field_of_study'),
            'year_of_graduation' => $request->input('year_of_graduation'),
            'teaching_experience' => $request->input('teaching_experience'),
            'previous_positions' => $request->input('previous_positions'),
            'subjects_taught' => $request->input('subjects_taught'),
            'institutions_worked' => $request->input('institutions_worked'),
            'teaching_certificates' => $request->input('teaching_certificates'),
            'training_attended' => $request->input('training_attended'),
            'teaching_philosophy' => $request->input('teaching_philosophy'),
            'lms_familiarity' => $request->input('lms_familiarity'),
            'edu_technology_experience' => $request->input('edu_technology_experience'),
            'software_proficiency' => $request->input('software_proficiency'),
            'references' => $request->input('references'),
            'additional_questions' => $request->input('additional_questions'),
            'preferences' => $request->input('preferences'),
            'availability' => $request->input('availability'),
        ]);
      $userIds = [$userId]; // Assuming $userId is the ID of the user who submitted the application
     $message = 'User submitted an application for becoming an instructor.';
     $type = 'teacher_application_submitted';

    // Generate links for users and admins
    $userLink = route('dashboard',);
    $adminLink = route('admin.instructors.requests',);

     $this->sendNotification($userIds, $message, $userLink, $adminLink, $type);

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Teacher application submitted successfully!');
    }




    public function sendNotification($userIds, $message, $userLink, $adminLink, $type)
    {
        // Create and store notifications for users with the user link
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'message' => $message,
                'link' => $userLink,
                'type' => $type,
            ]);
        }

        // Check if admin and send a separate notification with the admin link
        $admins = User::where('user_type', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => $message,
                'link' => $adminLink,
                'type' => $type,
            ]);
        }

     }



     public function viewInstructorRequests(Request $request)
     {
         $title = 'Instructor Requests';
         $query = TeacherApplication::where('status', 'pending')->with('user'); // Include the 'user' relationship

         // Search functionality
         if ($request->has('search')) {
             $search = $request->input('search');
             $query->where(function ($q) use ($search) {
                 $q->whereHas('user', function ($query) use ($search) {
                     $query->where('name', 'like', '%' . $search . '%'); // Search within the 'name' column of the 'users' table
                 })
                 ->orWhere('field_of_study', 'like', '%' . $search . '%')
                 ->orWhere('created_at', 'like', '%' . $search . '%'); // Add other fields as needed
             });
         }

         // Sorting functionality
         if ($request->has('sort_by')) {
             $sortBy = $request->input('sort_by');
             switch ($sortBy) {
                 case 'newest':
                     $query->orderBy('created_at', 'desc');
                     break;
                 case 'oldest':
                     $query->orderBy('created_at', 'asc');
                     break;
                 case 'accepted':
                     $query->orderBy('status', 'asc');
                     break;
                 case 'rejected':
                     $query->orderBy('status', 'desc');
                     break;
                 default:
                     // Default sorting
                     $query->orderBy('created_at', 'desc');
             }
         } else {
             // Default sorting
             $query->orderBy('created_at', 'desc');
         }

         $requests = $query->paginate(10); // Adjust the number per page as needed

         return view('admin.instructors.requests', compact('title', 'requests'));
     }





public function showInstructorRequestDetails($id)
{
    $request = TeacherApplication::findOrFail($id);
    $title = 'Instructor Request Details';

    return view('admin.instructors.details', compact('title', 'request'));
}

public function approveInstructorRequest($id)
{
    $request = TeacherApplication::findOrFail($id);
    $request->status = 'approved';
    $request->save();

    // Optionally, you can update the user's user_type here
    $user = $request->user;
    $user->user_type = 'instructor';
    $user->save();

    return redirect()->route('admin.instructors.requests')->with('success', 'Instructor request approved successfully!');
}

public function declineInstructorRequest($id)
{
    $request = TeacherApplication::findOrFail($id);
    $request->status = 'declined';
    $request->save();

    return redirect()->route('admin.instructors.requests')->with('success', 'Instructor request declined!');
}


}

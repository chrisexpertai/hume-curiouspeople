<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\Models\Follow;

use App\Models\School;
use App\Models\Earning;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Education;
use Illuminate\Http\Request;
use App\Models\UserOccupation;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class DashboardController extends Controller
{

    public function dashboard()
    {
        $title = __t('dashboard');


        $auth_user = auth()->user();
        $user = Auth::user();
        $userPlan = $user->subscription_plan ? SubscriptionPlan::find($user->subscription_plan) : null;

        $userData = $this->getUserData($user); // Pass the authenticated user to the method


        $enrolledCourses = $auth_user->enrolls()->paginate(8);

        $followedUsers = $auth_user->followers;



        $chartData = null;
        if ($user->isInstructor) {
            /**
             * Format Date Name
             */
            $start_date = date("Y-m-01");
            $end_date = date("Y-m-t");

            $begin = new \DateTime($start_date);
            $end = new \DateTime($end_date . ' + 1 day');
            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            $datesPeriod = array();
            foreach ($period as $dt) {
                $datesPeriod[$dt->format("Y-m-d")] = 0;
            }

            /**
             * Query This Month
             */

            $sql = "SELECT SUM(instructor_amount) as total_earning,
              DATE(created_at) as date_format
              from earnings
              WHERE instructor_id = {$user->id} AND payment_status = 'success'
              AND (created_at BETWEEN '{$start_date}' AND '{$end_date}')
              GROUP BY date_format
              ORDER BY created_at ASC ;";
            $getEarnings = DB::select($sql);

            $total_earning = array_pluck($getEarnings, 'total_earning');
            $queried_date = array_pluck($getEarnings, 'date_format');


            $dateWiseSales = array_combine($queried_date, $total_earning);

            $chartData = array_merge($datesPeriod, $dateWiseSales);
            foreach ($chartData as $key => $salesCount) {
                unset($chartData[$key]);
                //$formatDate = date('d M', strtotime($key));
                $formatDate = date('d', strtotime($key));
                $chartData[$formatDate] = $salesCount;
            }
        }

        $filteredChartData = null; // Initialize $filteredChartData here


        if ($user->isInstructor) {

            // Assuming $chartData contains earnings data for the entire year
            $currentMonth = date('m'); // Get the current month
            $filteredChartData = array_filter($chartData, function ($key) use ($currentMonth) {
                return date('m', strtotime($key)) == $currentMonth;
            }, ARRAY_FILTER_USE_KEY);

            // If you want to ensure that all days of the month are included, you can fill in missing days with zero earnings
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
            $period = new \DatePeriod(
                new \DateTime($startOfMonth),
                new \DateInterval('P1D'),
                new \DateTime($endOfMonth)
            );
            foreach ($period as $date) {
                $dateKey = $date->format('Y-m-d');
                if (!isset($filteredChartData[$dateKey])) {
                    $filteredChartData[$dateKey] = 0;
                }
            }
            ksort($filteredChartData); // Sort the array by date

        }





        // Return the view with all necessary data, including $filteredChartData
        return view(('front.dashboard.index'), compact('title', 'user', 'filteredChartData', 'userPlan', 'userData', 'chartData', 'enrolledCourses', 'followedUsers'));
    }

    public function getUserData($user)
    {
        $userData = [];

        if ($user->isInstructor) {
            // Retrieve user's earnings data for the current month
            $earnings = Earning::where('instructor_id', $user->id)
                ->where('payment_status', 'success')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->get();

            // Calculate total earnings for the current month
            $totalEarnings = $earnings->sum('instructor_amount');

            // Retrieve user's earnings data for the last month
            $lastMonthEarnings = Earning::where('instructor_id', $user->id)
                ->where('payment_status', 'success')
                ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                ->get();

            // Calculate total earnings for the last month
            $totalLastMonthEarnings = $lastMonthEarnings->sum('instructor_amount');

            // Calculate the percentage change in earnings compared to the last month
            if ($totalLastMonthEarnings != 0) {
                $earningChange = ($totalEarnings - $totalLastMonthEarnings) / $totalLastMonthEarnings * 100;
                $earningChangeFormatted = number_format($earningChange, 2);
            } else {
                $earningChangeFormatted = 'N/A';
            }



            // Add the data to the $userData array
            $userData['currentMonthEarnings'] = $totalEarnings;
            $userData['currentMonthChange'] = $earningChangeFormatted . '%';
            $userData['lastMonthEarnings'] = $totalLastMonthEarnings;
            $userData['lastMonthChange'] = $earningChangeFormatted . '%';
        }

        return $userData;
    }

    public function index()
    {
        $title = __t('dashboard');


        $authUser = auth()->user();


        // Access the authenticated user using the auth() helper
        $auth_user = auth()->user();



        // Paginate only the enrolled courses
        $enrolledCourses = $auth_user->enrolls()->paginate(8);

        // Fetch the list of users that the authenticated user is following
        $followedUsers = $auth_user->following;


        $user = Auth::user();

        $chartData = null;
        if ($user->isInstructor) {
            /**
             * Format Date Name
             */
            $start_date = date("Y-m-01");
            $end_date = date("Y-m-t");

            $begin = new \DateTime($start_date);
            $end = new \DateTime($end_date . ' + 1 day');
            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            $datesPeriod = array();
            foreach ($period as $dt) {
                $datesPeriod[$dt->format("Y-m-d")] = 0;
            }

            /**
             * Query This Month
             */

            $sql = "SELECT SUM(instructor_amount) as total_earning,
              DATE(created_at) as date_format
              from earnings
              WHERE instructor_id = {$user->id} AND payment_status = 'success'
              AND (created_at BETWEEN '{$start_date}' AND '{$end_date}')
              GROUP BY date_format
              ORDER BY created_at ASC ;";
            $getEarnings = DB::select($sql);

            $total_earning = array_pluck($getEarnings, 'total_earning');
            $queried_date = array_pluck($getEarnings, 'date_format');


            $dateWiseSales = array_combine($queried_date, $total_earning);

            $chartData = array_merge($datesPeriod, $dateWiseSales);
            foreach ($chartData as $key => $salesCount) {
                unset($chartData[$key]);
                //$formatDate = date('d M', strtotime($key));
                $formatDate = date('d', strtotime($key));
                $chartData[$formatDate] = $salesCount;
            }
        }

        return view(('front.dashboard.index'), compact('title', 'chartData', 'enrolledCourses', 'followedUsers'));
    }
    public function profileSettings()
    {
        $title = __t('profile_settings');
        $user = Auth::user();

        $userOccupations = UserOccupation::where('user_id', $user->id)->pluck('category_id')->toArray();

        $categories = Category::parent()->get(); // Assuming parent categories are the main occupations
        $userOccupation = UserOccupation::where('user_id', auth()->id())->first(); // Get the user's occupation

        // Get the user's education records
        $userEducations = $user->education ?? collect();

        $educations = $user->education()->get(); // Retrieve all education entries for the user


        // Pass $userEducations variable to the view
        return view(theme('dashboard.settings.profile'), compact('title', 'educations', 'categories', 'userOccupation', 'userOccupations', 'userEducations'));
    }



    public function profileSettingsPost(Request $request)
    {
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email',
            'job_title' => 'max:220',
        ];
        $this->validate($request, $rules);

        $user = Auth::user();

        // Update user's profile information
        $input = $request->except('_token', 'social', 'occupation', 'school_name', 'degree');
        $user->update($input);
        $user->update_option('social', $request->social);

        // Update or create user's education records
        $schools = $request->input('school_name', []);
        $degrees = $request->input('degree', []);

        foreach ($schools as $key => $school) {
            // Check if the education record exists
            $education = $user->education()->where('id', $key)->first();

            if ($education) {
                // If the record exists, update its fields
                $education->update([
                    'school_name' => $school,
                    'degree' => $degrees[$key] ?? null,
                    // Add other education fields as needed
                ]);
            } else {
                // If the record does not exist, create a new record
                $user->education()->create([
                    'school_name' => $school,
                    'degree' => $degrees[$key] ?? null,
                    // Add other education fields as needed
                ]);
            }
        }

        // Store selected occupations in the users_occupations table
        $occupations = $request->input('occupation');

        // Check if $occupations is not null and is an array
        if (!is_null($occupations) && is_array($occupations)) {
            UserOccupation::where('user_id', $user->id)->delete(); // Remove existing occupations for the user

            foreach ($occupations as $occupation) {
                UserOccupation::create([
                    'user_id'     => $user->id,
                    'category_id' => $occupation,
                ]);
            }
        }

        return back()->with('success', __t('success'));
    }


    public function addEducation(Request $request)
    {
        $request->validate([
            'schoolName' => 'required',
            'degree' => 'required',
        ]);

        $user = Auth::user();
        $education = $user->education()->create([
            'school_name' => $request->input('schoolName'),
            'degree' => $request->input('degree'),
        ]);

        // Build the HTML markup for the newly added education entry
        $html = '<p class="form-control mb-2" type="text" value="' . $education->school_name . '">' . $education->school_name . ' • ' . $education->degree . '</p>';

        // Return the HTML markup as the response
        return $html;
    }
    public function deleteEducation(Request $request)
    {
        $educationId = $request->input('education_id');

        // Find the education item by ID
        $education = Education::find($educationId);

        if ($education) {
            // Delete the education item
            $education->delete();

            // Return success response
            return response()->json(['success' => true]);
        } else {
            // Return error response if education item not found
            return response()->json(['success' => false, 'message' => 'Education item not found'], 404);
        }
    }


    public function getUserEducation()
    {
        $user = Auth::user();
        $education = $user->education()->latest()->first(); // Retrieve the latest education entry for the user

        return view('front.partials.educations', compact('education'));
    }



    public function editEducation(Request $request)
    {
        $request->validate([
            'schoolName' => 'required',
            'degree' => 'required',
        ]);

        $educationId = $request->input('education_id');
        $education = Education::find($educationId);
        $education->update([
            'school_name' => $request->input('schoolName'),
            'degree' => $request->input('degree'),
        ]);

        return redirect()->back()->with('success', 'Education updated successfully.');
    }




    public function resetPassword()
    {
        $title = __t('reset_password');
        return view(theme('dashboard.settings.reset_password'), compact('title'));
    }

    public function resetPasswordPost(Request $request)
    {
        if (config('app.is_demo')) {
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }
        $rules = [
            'old_password'  => 'required',
            'new_password'  => 'required|confirmed',
            'new_password_confirmation'  => 'required',
        ];
        $this->validate($request, $rules);

        $old_password = clean_html($request->old_password);
        $new_password = clean_html($request->new_password);

        if (Auth::check()) {
            $logged_user = Auth::user();

            if (Hash::check($old_password, $logged_user->password)) {
                $logged_user->password = Hash::make($new_password);
                $logged_user->save();
                return redirect()->back()->with('success', __t('password_changed_msg'));
            }
            return redirect()->back()->with('error', __t('wrong_old_password'));
        }
    }
    public function enrolledCourses()
    {
        $title = __t('enrolled_courses');

        // Access the authenticated user using the auth() helper
        $auth_user = auth()->user();

        $followedUsers = $auth_user->following;


        // Paginate only the enrolled courses
        $enrolledCourses = $auth_user->enrolls()->paginate(8);

        return view(theme('dashboard.enrolled_courses'), compact('title', 'followedUsers', 'enrolledCourses'));
    }



    public function myReviews()
    {
        $title = __t('my_reviews');
        return view(theme('dashboard.my_reviews'), compact('title'));
    }

    public function wishlist()
    {
        $title = __t('wishlist');
        return view(theme('dashboard.wishlist'), compact('title'));
    }

    public function purchaseHistory()
    {
        $title = __t('purchase_history');
        return view(theme('dashboard.purchase_history'), compact('title'));
    }

    public function purchaseView($id)
    {
        $title = __a('purchase_view');
        $payment = Payment::find($id);
        return view(theme('dashboard.purchase_view'), compact('title', 'payment'));
    }
}

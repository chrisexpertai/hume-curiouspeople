<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Follow;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\ReserveMeeting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_INACTIVE = 'inactive';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isUser()
    {
        return $this->user_type === 'instructor, student' || $this->isAdmin();
    }

    public function hasPurchasedSubscription($planId)
    {
        return $this->subscriptionPayments()
            ->where('status', 'success')
            ->where('subscription_plan_id', $planId)
            ->where('subscribed_at', '>=', Carbon::now()->subMonths($plan->duration_months))
            ->exists();
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function occupation()
    {
        return $this->belongsTo(Category::class, 'occupation');
    }


    public function education()
    {
        return $this->hasOne(Education::class);
    }

    public function deactivation()
    {
        return $this->hasOne(UserDeactivation::class);
    }



    public function scopeActive($query)
    {
        return $query->where('active_status', 1)->with('photo_query');
    }

    public function scopeInstructor($query)
    {
        return $query->where('user_type', 'instructor');
    }



    public function userMetas()
    {
        return $this->hasMany('App\Models\UserMeta');
    }

    public function isTeacherApplied()
    {
        // Assuming you have a relationship named teacherApplication
        return $this->teacherApplication()->exists();
    }

    /**
     * Define the relationship with the teacher application.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacherApplication()
    {
        return $this->hasOne(TeacherApplication::class);
    }


    public function subscription_plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan');
    }

    public function subscription()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan');
    }

    public function purchaseSubscription($planId, $durationInMonths)
    {
        $startDate = now();
        $endDate = $startDate->addMonths($durationInMonths);

        $this->update([
            'subscription_plan' => $planId,
            'subscription_start_date' => $startDate,
            'subscription_end_date' => $endDate,
        ]);

        // Perform any additional actions, such as granting access to courses.
    }
    public function subscriptions()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan');
    }
    public function expireSubscription()
    {
        $this->subscription_end_date = now(); // Set the subscription end date to the current time
        $this->plan_expired = true;
        $this->save();
    }

    public function isSubscribed($planId = null)
    {
        // Check if the user has an active subscription
        return !$this->expired && (!$this->subscription_end_date || now()->between(
            $this->subscription_start_date,
            $this->subscription_end_date
        )
        ) && ($planId === null || $this->subscription_plan === $planId);
    }

    public function hasSubscriptionExpired()
    {
        return $this->expired || now() > $this->subscription_end_date;
    }

    public function isSubscribedToPlan($planId)
    {
        return $this->subscription_plan == $planId && !$this->hasSubscriptionExpired();
    }


    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->orderBy('created_at', 'desc');
    }


    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function get_reviews()
    {
        return $this->belongsToMany(Review::class, 'course_user', 'user_id', 'course_id', 'id', 'course_id');
    }


    public function meeting()
    {
        return $this->hasOne('App\Models\Meeting', 'creator_id', 'id');
    }

    public function hasMeeting()
    {
        return Meeting::where('disabled', false)
            ->where('creator_id', $this->id)
            ->first();
    }

    public function ReserveMeetings()
    {
        return $this->hasMany('App\Models\ReserveMeeting', 'user_id', 'id');
    }

    public function getTotalHoursTutoring()
    {
        $seconds = 0;

        if (!empty($this->meeting)) {
            $meetingId = $this->meeting->id;

            $reserves = ReserveMeeting::where('meeting_id', $meetingId)
                ->where('status', 'finished')
                ->get();

            if (!empty($reserves)) {
                foreach ($reserves as $reserve) {
                    $meetingTime = $reserve->meetingTime;

                    if ($meetingTime) {
                        $time = explode('-', $meetingTime->time);
                        $start = strtotime($time[0]);
                        $end = strtotime($time[1]);
                        $seconds += $end - $start;
                    }
                }
            }
        }

        $hours = $seconds ? $seconds / (60 * 60) : 0;

        return round($hours, 0, PHP_ROUND_HALF_UP);
    }


    /**
     * Define a many-to-many relationship for users who are followers of this user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follower')
            ->wherePivot('status', Follow::$accepted);
    }

    /**
     * Define a many-to-many relationship for users whom this user is following.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower', 'user_id')
            ->wherePivot('status', Follow::$accepted);
    }

    public function instructor_discussions()
    {
        return $this->belongsToMany(Discussion::class, 'course_user', 'user_id', 'course_id', 'id', 'course_id')->with('user', 'user.photo_query')->where('discussion_id', 0);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Course::class, 'wishlists');
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class, 'instructor_id')->where('payment_status', 'success');
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class)->orderBy('created_at', 'desc');
    }

    public function purchases()
    {
        return $this->hasMany(Payment::class)->orderBy('created_at', 'desc');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function my_quiz_attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function getGetRatingAttribute()
    {
        $sql = "SELECT COUNT(reviews.id) AS rating_count,
                   AVG(reviews.rating) AS rating_avg
            FROM reviews
            INNER JOIN course_user ON reviews.course_id = course_user.course_id
            WHERE course_user.user_id = {$this->id} AND reviews.status = 1";

        $rating = DB::selectOne($sql);

        if ($rating) {
            $rating->rating_avg = number_format($rating->rating_avg, 2);
            return $rating;
        }

        return null; // handle the case where no rating is found
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function isInstructor()
    {
        return $this->user_type === 'instructor' || $this->isAdmin();
    }

    public function getIsInstructorAttribute()
    {
        return $this->isInstructor();
    }

    public function isOrganization()
    {
        return $this->role_name === Role::$organization;
    }

    public function photo_query()
    {
        return $this->belongsTo(Media::class, 'photo');
    }

    public function getGetPhotoAttribute()
    {
        if ($this->photo) {
            $url = media_image_uri($this->photo_query)->avatar;
            return "{$url}";
        }

        $arr = explode(' ', trim($this->name));

        if (count($arr) > 1) {
            $first_char = substr($arr[0], 0, 1);
            $second_char = substr($arr[1], 0, 1);
        } else {
            $first_char = substr($arr[0], 0, 1);
            $second_char = substr($arr[0], 1, 1);
        }

        $textPhoto = strtoupper($first_char . $second_char);

        $bg_color = '#' . substr(md5($textPhoto), 0, 6);
        $textPhoto = "/images/avatar.png";

        return $textPhoto;
    }

    public function enrolls()
    {
        return $this->belongsToMany(Course::class, 'enrolls')->wherePivot('status', '=', 'success');
    }

    public function isEnrolled($course_id = 0)
    {
        $user = Auth::user();

        $course = Course::findOrFail($course_id);

        if ($user->user_type === 'admin') {
            return true; // Admin has access
        }

        if ($course_id === 0) {
            return false;
        }

        // Check if the user is the course owner
        if ($course->user_id === $user->id) {
            return true;
        }

        // Check if the user is enrolled
        $isEnrolled = DB::table('enrolls')
            ->whereUserId($this->id)
            ->whereCourseId($course_id)
            ->whereStatus('success')
            ->orderBy('enrolled_at', 'desc')
            ->first();

        return $isEnrolled ? true : false;
    }




    public function isInstructorInCourse($course_id)
    {
        return $this->courses()->whereCourseId($course_id)->first();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * @param null $course_id
     * @return bool
     *
     * Complete Course
     */
    public function complete_course($course_id = null)
    {
        if (!$course_id) {
            return false;
        }

        $is_completed = Complete::whereCompletedCourseId($course_id)->whereUserId($this->id)->first();

        if ($is_completed) {
            return $is_completed;
        }

        $data = [
            'user_id' => $this->id,
            'completed_course_id' => $course_id,
            'completed_at' => Carbon::now()->toDateTimeString(),
        ];

        return Complete::create($data);
    }

    public function is_completed_course($course_id)
    {
        $is_completed = Complete::whereCompletedCourseId($course_id)->whereUserId($this->id)->first();
        return $is_completed;
    }

    public function get_option($key = null, $default = null)
    {
        if ($this->options) {
            $options = (array) json_decode($this->options, true);
            $value = data_get($options, $key);

            if ($value) {
                return $value;
            }
        }

        return $default;
    }



    public function update_option($key = null, $value = '')
    {
        if ($key) {
            $options = (array) json_decode($this->options, true);
            data_set($options, $key, $value);
            $this->update(['options' => $options]);
        }
    }

    public function zoomApi()
    {
        return $this->hasOne('App\Models\UserZoomApi', 'user_id', 'id');
    }

    public function student_enrolls()
    {
        return $this->belongsToMany(Enroll::class, 'course_user', 'user_id', 'course_id', 'id', 'course_id')->whereStatus('success');
    }

    public function enroll_sync()
    {
        $enrolledCourse = (array) $this->enrolls()->pluck('course_id')->all();
        $enrolledCourse = array_unique($enrolledCourse);
        $this->update_option('enrolled_courses', $enrolledCourse);

        return $this;
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrolls')->wherePivot('status', '=', 'success');
    }

    /**
     * Earning Related
     */

    public function getEarningAttribute()
    {
        $salesAmount = $this->earnings->sum('amount');
        $earnings = $this->earnings->sum('instructor_amount');
        $commission = $this->earnings->sum('admin_amount');
        $withdrawsSum = $this->withdraws->where('status', '!=', 'rejected')->sum('amount');
        $withdrawsTotal = $this->withdraws->where('status', 'approved')->sum('amount');
        $balance = $earnings - $withdrawsSum;

        $data = [
            'sales_amount' => $salesAmount,
            'commission' => $commission,
            'earnings' => $earnings,
            'balance' => $balance,
            'withdrawals' => $withdrawsTotal,
        ];

        return (object) $data;
    }

    public function getWithdrawMethodAttribute()
    {
        $method = $this->get_option('withdraw_preference');
        $methodKey = data_get($method, 'method');

        if (!$methodKey) {
            return null;
        }

        $savedMethod = active_withdraw_methods($methodKey);
        $savedMethod['method_key'] = $methodKey;
        $formFields = data_get($savedMethod, 'form_fields');

        if (is_array($formFields) && count($formFields)) {
            foreach ($formFields as $formKey => $formValue) {
                $formValue['value'] = data_get($method, $methodKey . '.' . $formKey);
                $formFields[$formKey] = $formValue;
            }
        }

        $savedMethod['form_fields'] = $formFields;
        $savedMethod['admin_form_fields'] = get_option("withdraw_methods.{$methodKey}");

        return (object) $savedMethod;
    }

    public function get_attempt($quiz_id)
    {
        $attempt = Attempt::where('user_id', $this->id)->where('quiz_id', $quiz_id)->first();
        return $attempt;
    }
}

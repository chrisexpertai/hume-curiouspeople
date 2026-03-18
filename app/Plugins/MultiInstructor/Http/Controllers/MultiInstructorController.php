<?php

namespace App\Plugins\MultiInstructor\Http\Controllers;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MultiInstructorController extends Controller
{
    /**
     * Show the form for managing instructors for a course.
     *
     * @param  int  $course_id
     * @return \Illuminate\View\View
     */
    public function instructorSettings($course_id)
    {
        // Retrieve course and other necessary data
        $course = Course::find($course_id);

        if (!$course) {
            abort(404);
        }

        // Check if the current user is the owner of the course
        if ($course->user_id !== Auth::id()) {
            // If the current user is not the owner, check if they are attached as instructors
            $instructorIds = $course->instructors->pluck('id')->toArray();

            if (!in_array(Auth::id(), $instructorIds)) {
                // If the current user is neither the owner nor attached as an instructor, redirect to a 404 page
                abort(404);
            }
        }

        $instructors = $course->instructors()->where('users.id', '!=', $course->user_id)->get();

        return view('front.dashboard.courses.instructors', compact('course', 'instructors'));
    }


    /**
     * Handle instructor settings form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $course_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function instructorSettingsPost(Request $request, $course_id)
    {
        // Handle the form submission and update instructors data
        // This could be for any additional actions when form is submitted
        // For now, let's redirect back with success message
        return back()->with('success', 'Instructors updated successfully.');
    }

   /**
 * Search for instructors by name or email.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function multiInstructorSearch(Request $request)
{
    $searchQuery = $request->input('q');

    $instructors = User::where('user_type', 'instructor')
        ->where(function ($query) use ($searchQuery) {
            $query->where('name', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchQuery . '%');
        })
        ->get();


    // Include profile link in the response
    $instructorData = [];
    foreach ($instructors as $instructor) {
        $courses_count = $instructor->courses()->publish()->count();
        $students_count = $instructor->student_enrolls->count();
        $instructor_rating = $instructor->get_rating;
        $instructorData[] = [
            'id' => $instructor->id,
            'name' => $instructor->name,
            'job_title' => $instructor->job_title,
            'photo' => $instructor->get_photo,
            'profile_link' => route('profile', $instructor), // Add this line
            'courses_count' => $courses_count,
            'students_count' => $students_count,
            'instructor_rating' => $instructor_rating,
        ];

    }


    return response()->json(['success' => true, 'instructors' => $instructorData]);
}
/**
 * Attach an instructor to a course.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $course_id
 * @return \Illuminate\Http\JsonResponse
 */
/**
 * Attach an instructor to a course.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $course_id
 * @return \Illuminate\Http\JsonResponse
 */
public function attachInstructor(Request $request, $course_id)
{
    $course = Course::find($course_id);

    if (!$course) {
        return response()->json(['error' => 'Course not found.'], 404);
    }

    // Validate incoming request
    $validator = Validator::make($request->all(), [
        'instructor_id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 400);
    }

    $instructorId = $request->input('instructor_id');

    // Check if the instructor is already attached to the course
    if ($course->instructors()->where('users.id', $instructorId)->exists()) {
        return response()->json(['error' => 'Instructor is already attached to the course.'], 400);
    }

    // Attach instructor to the course
    $course->instructors()->attach($instructorId);

    // Fetch updated list of instructors
    $instructors = $course->instructors()->where('users.id', '!=', $course->user_id)->get();

    // Fetch newly attached instructor data
    $instructor = User::findOrFail($instructorId); // Assuming $instructorId is valid

    // Prepare instructor data to send to frontend
    $instructorData = [
        'id' => $instructor->id,
        'name' => $instructor->name,
        'profile_link' => route('profile', $instructor), // Assuming you have a route for the user's profile
        'photo' => $instructor->get_photo, // Assuming this is the method to get the user's photo
        'courses_count' => $instructor->courses()->publish()->count(),
        'students_count' => $instructor->student_enrolls->count(),
        'instructor_rating' => $instructor->get_rating,
    ];

    // Return JSON response with updated instructors and new instructor data
    return response()->json(['success' => 'Instructor added successfully.', 'instructor' => $instructorData, 'instructors' => $instructors]);
}

    /**
     * Remove an instructor from a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeInstructor(Request $request, $course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['error' => 'Course not found.'], 404);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $instructorId = $request->input('instructor_id');

        // Detach instructor from the course
        $course->instructors()->detach($instructorId);

        // Fetch updated list of instructors
        $instructors = $course->instructors()->where('users.id', '!=', $course->user_id)->get();

        // Return JSON response with updated instructors
        return response()->json(['success' => 'Instructor removed successfully.', 'instructors' => $instructors]);
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use App\Models\Content;
use App\Models\Section;
use App\Models\Category;
use App\Models\Documents;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    /**
     * @return string
     *
     * View Course
     */
    public function view($slug)
    {
        // Retrieve the course, including sections and items
        $course = Course::whereSlug($slug)->with('sections', 'sections.items', 'sections.items.attachments')->first();

        // If course not found, return 404
        if (!$course) {
            abort(404);
        }


        // Retrieve the authenticated user
        $user = Auth::user();
        $faqs = Faq::where('course_id', $course->id)->get();

        // If the course is not published and the user is not an instructor in the course, return 404
        if ($course->status != 1 && (!$user || !$user->isInstructorInCourse($course->id))) {
            abort(404);
        }



        // For other users, check if the course is published
        $title = $course->title;
        $isEnrolled = false;
        if (Auth::check()) {
            $user = Auth::user();

            $enrolled = $user->isEnrolled($course->id);
            if ($enrolled) {
                $isEnrolled = $enrolled;
            }
        }
        return view(theme('course'), compact('course', 'faqs', 'title', 'isEnrolled'));
    }

    /**
     * @param $slug
     * @param $lecture_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * View lecture in full width mode.
     */
    public function lectureView($slug, $lecture_id)
    {
        $lecture = Content::find($lecture_id);
        $course = $lecture->course;
        $title = $lecture->title;
        $user = Auth::user();

        $isEnrolled = false;
        $isOpen = (bool) $lecture->is_preview;

        // Check if the user is the course owner
        if ($user && $course->user_id === $user->id) {
            // Course owner is considered as enrolled
            $isEnrolled = true;
            $isOpen = true; // Course owner has access to preview content
        } else {
            // Handle subscription check only if content is not free preview
            if (!$isOpen) {
                $isSubscribed = $user->isSubscribedToPlan($course->subscription_plan_id);
                // Check if the selected price plan is "subscription"
                if ($course->price_plan == 'subscription') {
                    // Check if the user has an active subscription to the course's plan
                    if ($isSubscribed) {
                        // Check if the user is explicitly enrolled in the course
                        if (!$user->isEnrolled($course->id)) {
                            // Redirect the user to enroll if not enrolled
                            return redirect(route('course', $course->slug))
                                ->with('error', __('You need to enroll in this course to access this content.'));
                        }

                        // User is both subscribed and enrolled
                        $isEnrolled = true;
                        $isOpen = true;
                    } else {
                        // Redirect the user to subscribe if not subscribed
                        return redirect(route('subscription-plans.show', ['id' => $course->subscription_plan_id]))
                            ->with('error', __('You need to purchase this plan to access this content.'));
                    }
                } else {
                    // Handle other cases (free or paid) as before
                    if ($course->paid && $user) {
                        $isEnrolled = $user->isEnrolled($course->id);
                        if ($course->paid && $isEnrolled) {
                            $isOpen = true;
                        }
                    } elseif ($course->free) {
                        if ($course->require_enroll && $user) {
                            $isEnrolled = $user->isEnrolled($course->id);
                            if ($isEnrolled) {
                                $isOpen = true;
                            }
                        } elseif ($course->require_login) {
                            if ($user) {
                                $isOpen = true;
                            }
                        } else {
                            $isOpen = true;
                        }
                    }
                }
            }
        }

        // Mark, If a user is Super Admin.
        if (Auth::check() && $user->isAdmin()) {
            $isOpen = true;
        }

        if ($lecture->drip->is_lock) {
            $isOpen = true;
        }

        return view(theme('lecture'), compact('course', 'title', 'isEnrolled', 'lecture', 'isOpen'));
    }




    public function assignmentView($slug, $assignment_id)
    {
        $assignment = Content::find($assignment_id);
        $course = $assignment->course;

        $title = $assignment->title;
        $has_submission = $assignment->has_submission;

        $user = Auth::user();
        $isEnrolled = false;
        $isSubscribed = false;

        // Check if the user is the course owner
        if ($user && $course->user_id === $user->id) {
            // Course owner is considered as enrolled
            $isEnrolled = true;
        } else {
            // Check if the selected price plan is "subscription"
            if ($course->price_plan == 'subscription') {
                // Check if the user has an active subscription to the course's plan
                if ($user && $user->isSubscribedToPlan($course->subscription_plan_id)) {
                    // Check if the user is explicitly enrolled in the course
                    if (!$user->isEnrolled($course->id)) {
                        // Redirect the user to enroll if not enrolled
                        return redirect(route('course', $course->slug))
                            ->with('error', __('You need to enroll in this course to access this content.'));
                    }
                    // User is both subscribed and enrolled
                    $isEnrolled = true;
                } else {
                    // Redirect the user to subscribe if not subscribed or logged in
                    return redirect(route('subscription-plans.show', ['id' => $course->subscription_plan_id]))
                        ->with('error', __('You need to purchase this plan to access this content.'));
                }
            } else {
                // Handle other cases (free or paid)
                if ($course->paid && $user) {
                    $isEnrolled = $user->isEnrolled($course->id);
                } elseif ($course->free && $course->require_enroll && $user) {
                    $isEnrolled = $user->isEnrolled($course->id);
                }
            }
        }

        // Check if the user is enrolled (including free enrollment)
        if ($user && ($user->isEnrolled($course->id) || $course->free && $course->require_enroll)) {
            $isEnrolled = true;
        }

        // Mark, If a user is Super Admin.
        if ($user && $user->isAdmin()) {
            $isEnrolled = true;
        }

        return view(theme('assignment'), compact('course', 'title', 'isEnrolled', 'assignment', 'has_submission'));
    }



    public function assignmentSubmitting(Request $request, $slug, $assignment_id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $assignment = Content::find($assignment_id);

        $submission = $assignment->has_submission;
        if ($submission) {
            if ($submission->status === 'submitting') {

                $submission->text_submission = clean_html($request->assignment_text);
                $submission->status = 'submitted';
                $submission->save();
                complete_content($assignment, $user);

                /**
                 * Save Attachments if any
                 *
                 * @todo, check attachment size, if exceed, delete those attachments
                 */
                $attachments = array_filter((array) $request->assignment_attachments);
                if (is_array($attachments) && count($attachments)) {
                    foreach ($attachments as $media_id) {
                        $hash = strtolower(str_random(13) . substr(time(), 4) . str_random(13));
                        Attachment::create(['assignment_submission_id' => $submission->id, 'user_id' => $user_id, 'media_id' => $media_id, 'hash_id' => $hash]);
                    }
                }
            }
        } else {
            $course = $assignment->course;
            $data = [
                'user_id' => $user_id,
                'course_id' => $course->id,
                'assignment_id' => $assignment_id,
                'status' => 'submitting',
            ];
            AssignmentSubmission::create($data);
        }

        return redirect()->back();
    }


    public function create()
    {
        $title = __t('create_new_course');
        $categories = Category::parent()->get();

        return view(theme('dashboard.courses.create_course'), compact('title', 'categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
        ];

        $this->validate($request, $rules);

        $user_id = Auth::user()->id;
        $slug = unique_slug($request->title);
        $now = Carbon::now()->toDateTimeString();

        $category = Category::find($request->category_id);
        $data = [
            'user_id'           => $user_id,
            'title'             => clean_html($request->title),
            'slug'              => $slug,
            'short_description' => clean_html($request->short_description),
            'price_plan'        => 'free',
            'category_id'       => $request->category_id,
            'parent_category_id' => $category->category_id,
            'second_category_id' => $category->id,
            'thumbnail_id'      => $request->thumbnail_id,
            'level'             => $request->level,
            'last_updated_at'   => $now,
        ];

        /**
         * save video data
         */
        $video_source = $request->input('video.source');
        if ($video_source === '-1') {
            $data['video_src'] = null;
        } else {
            $data['video_src'] = json_encode($request->video);
        }

        $course = Course::create($data);

        $now = Carbon::now()->toDateTimeString();
        if ($course) {
            $course->instructors()->attach($user_id, ['added_at' => $now]);
        }

        return redirect(route('edit_course_information', $course->id));
    }

    public function information($course_id)
    {
        $title = __t('information');
        $course = Course::find($course_id);
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }
        $categories = Category::parent()->get();
        $topics = Category::whereCategoryId($course->second_category_id)->get();
        return view(theme('dashboard.courses.information'), compact('title', 'course', 'categories', 'topics'));
    }
    public function fetchDocumentContent()
    {
        $user_id = Auth::user()->id;
        $document = Documents::where('user_id', $user_id)->first();

        return response()->json(['doc_detail' => $document->doc_detail]);
    }


    public function savePopupDocument(Request $request)
    {
        $user_id = Auth::user()->id;
        $now = Carbon::now()->toDateTimeString();

        $data = [
            'user_id'    => $user_id,
            'doc_detail' => clean_html($request->popupDesc),

        ];

        $document = Documents::updateOrCreate(
            ['user_id' => $user_id],
            $data
        );

        return response()->json(['status' => 'success']);
    }


    public function createDocument()
    {

        $title = __t('create_document');

        $user_id = Auth::user()->id;
        $document = Documents::where('user_id', $user_id)->first();
        //print_r($document);
        if (!is_object($document)) {
            $now = Carbon::now()->toDateTimeString();
            $data = [
                'user_id'    => $user_id,
                'doc_detail' => '',
            ];

            // Convert the 'add_dt' value to a string representation and enclose it in single quotes

            $document = Documents::create($data);
        }
        //  $document = Documents::find($user_id,'user_id')->first();
        // print_r($document->id);
        return view(theme('dashboard.create_document'), compact('title', 'document'));
    }
    public function saveDocument(Request $request)
    {
        $user_id = Auth::user()->id;
        $now = Carbon::now()->toDateTimeString();

        if ($request->docid == "") {
            $data = [
                'user_id'    => $user_id,
                'doc_detail' => clean_html($request->docdesc),

            ];
            $document = Documents::create($data);
        } else {
            $data = [
                'user_id'    => $user_id,
                'doc_detail' => clean_html($request->docdesc),

            ];
            $document = Documents::whereId($request->docid)->update($data);
        }

        return response()->json(['status' => 'success']);
    }

    public function informationPost(Request $request, $course_id)
    {
        $rules = [
            'title'             => 'required|max:120',
            'short_description' => 'max:225',
            'category_id'       => 'required',
            'topic_id'          => 'sometimes|required', // 'sometimes' makes it optional
        ];
        $this->validate($request, $rules);

        $course = Course::find($course_id);
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }
        $category = Category::find($request->category_id);

        $data = [
            'title'             => clean_html($request->title),
            'short_description' => clean_html($request->short_description),
            'description'       => clean_html($request->description),
            'benefits'          => clean_html($request->benefits),
            'requirements'      => clean_html($request->requirements),
            'thumbnail_id'      => $request->thumbnail_id,
            'category_id'       => $request->topic_id,
            'parent_category_id' => $category->category_id,
            'second_category_id' => $category->id,
            'level'             => $request->level,
            'tags'               => clean_html($request->tags),
        ];
        /**
         * save video data
         */
        $video_source = $request->input('video.source');
        if ($video_source === '-1') {
            $data['video_src'] = null;
        } else {
            $data['video_src'] = json_encode($request->video);
        }

        $course->update($data);

        if ($request->save === 'save_next')
            return redirect(route('edit_course_curriculum', $course_id));
        return redirect()->back();
    }

    public function additional($course_id)
    {
        $title = __t('additional');

        // Find the course by ID
        $course = Course::find($course_id);

        // Check if the course exists and if the authenticated user is the instructor
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        // Get the authenticated user
        $user = auth()->user();

        // Get the FAQs associated with the course and the user
        $faqs = Faq::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->get();

        return view(theme('dashboard.courses.additional'), compact('title', 'faqs', 'course'));
    }

    public function additionalPost(Request $request, $course_id)
    {
        // Validation rules
        $rules = [
            //  'reviewer_message' => 'nullable|string|max:255',
        ];

        // Validate the incoming request data
        $this->validate($request, $rules);

        // Find the course by ID
        $course = Course::find($course_id);

        // Check if the course exists and if the authenticated user is the instructor
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        // Process the incoming request data and update the course with the additional information
        $data = [
            //      'reviewer_message' => clean_html($request->reviewer_message),
            'tags'               => clean_html($request->tags),

        ];

        // Update the course with the additional information
        $course->update($data);

        // Redirect the user
        return redirect()->back()->with('success', 'Additional information saved successfully.');
    }



    public function curriculum($course_id)
    {
        $title = __t('curriculum');
        $course = Course::find($course_id);
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        return view(theme('dashboard.courses.curriculum'), compact('title', 'course'));
    }


    public function deleteCourse($courseId)
    {
        $course = Course::findOrFail($courseId);

        // Check if the authenticated user is an instructor of this course or an admin
        if ($course->instructors()->where('user_id', Auth::id())->exists() || Auth::user()->is_admin) {
            // Soft delete the course
            $course->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Course moved to trash.');
        } else {
            // Redirect back with an error message
            return redirect()->back()->with('error', 'You are not authorized to delete this course.');
        }
    }




    public function newSection($course_id)
    {
        $title = __t('curriculum');
        $course = Course::find($course_id);
        return view(theme('dashboard.courses.new_section'), compact('title', 'course'));
    }

    public function newSectionPost(Request $request, $course_id)
    {
        $rules = [
            'section_name' => 'required',
        ];
        $this->validate($request, $rules);

        Section::create(
            [
                'course_id' => $course_id,
                'section_name' => clean_html($request->section_name)
            ]
        );
        return redirect(route('edit_course_curriculum', $course_id));
    }

    /**
     * @param Request $request
     * @param $id
     * @throws \Illuminate\Validation\ValidationException
     *
     * Update the section
     */
    public function updateSection(Request $request, $id)
    {
        $rules = [
            'section_name' => 'required',
        ];
        $this->validate($request, $rules);

        Section::whereId($id)->update(['section_name' => clean_html($request->section_name)]);
    }

    public function deleteSection(Request $request)
    {
        if (config('app.is_demo')) return ['success' => false, 'msg' => __t('demo_restriction')];

        $section = Section::find($request->section_id);
        $course = $section->course;

        Content::query()->where('section_id', $request->section_id)->delete();
        $section->delete();
        $course->sync_everything();

        return ['success' => true];
    }
    public function newLecture(Request $request, $course_id)
    {
        $rules = [
            'title' => 'required'
        ];

        $validation = Validator::make($request->input(), $rules);

        if ($validation->fails()) {
            $errors = $validation->errors()->toArray();

            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error) {
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";

            return ['success' => false, 'error_msg' => $error_msg];
        }

        $user_id = Auth::user()->id;

        $lesson_slug = unique_slug($request->title, 'Content');
        $sort_order = next_curriculum_item_id($course_id);

        $data = [
            'user_id'       => $user_id,
            'course_id'     => $course_id,
            'section_id'    => $request->section_id,
            'title'         => clean_html($request->title),
            'slug'          => $lesson_slug,
            'text'          => clean_html($request->description),
            'item_type'     => 'lecture',
            'status'        => 1,
            'sort_order'   => $sort_order,
            'is_preview'    => $request->is_preview,
        ];

        $lecture = Content::create($data);
        $lecture->save_and_sync();

        return ['success' => true, 'item_id' => $lecture->id];
    }


    public function loadContents(Request $request)
    {
        $section = Section::find($request->section_id);

        $html = view_template_part('dashboard.courses.section-items', compact('section'));

        return ['success' => true, 'html' => $html];
    }

    public function updateLecture(Request $request, $course_id, $item_id)
    {
        $rules = [
            'title' => 'required'
        ];
        $validation = Validator::make($request->input(), $rules);

        if ($validation->fails()) {
            $errors = $validation->errors()->toArray();
            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error) {
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";
            return ['success' => false, 'error_msg' => $error_msg];
        }

        $user_id = Auth::user()->id;

        $lesson_slug = unique_slug($request->title, 'Content', $item_id);
        $data = [
            'title'         => clean_html($request->title),
            'slug'          => $lesson_slug,
            'text'          => clean_html($request->description),
            'is_preview'    => clean_html($request->is_preview),
        ];

        /**
         * save video data
         */
        $video_source = $request->input('video.source');
        if ($video_source === '-1') {
            $data['video_src'] = null;
        } else {
            $data['video_src'] = json_encode($request->video);
        }

        $item = Content::find($item_id);
        $item->save_and_sync($data);

        /**
         * Save Attachments if any
         */
        $attachments = array_filter((array) $request->attachments);
        if (is_array($attachments) && count($attachments)) {
            foreach ($attachments as $media_id) {
                $hash = strtolower(str_random(13) . substr(time(), 4) . str_random(13));
                Attachment::create(['belongs_course_id' => $item->course_id, 'content_id' => $item->id, 'user_id' => $user_id, 'media_id' => $media_id, 'hash_id' => $hash]);
            }
        }

        return ['success' => true];
    }




    public function editItem(Request $request)
    {
        $item_id = $request->item_id;
        $item = Content::find($item_id);

        $form_html = '';

        if ($item->item_type === 'lecture') {
            $form_html = view_partials('dashboard.courses.edit_lecture_form', compact('item'));
        } elseif ($item->item_type === 'quiz') {
            $form_html = view_partials('dashboard.courses.quiz.edit_quiz', compact('item'));
        } elseif ($item->item_type === 'assignment') {
            $form_html = view_partials('dashboard.courses.edit_assignment_form', compact('item'));
        }

        return ['success' => true, 'form_html' => $form_html];
    }

    public function deleteItem(Request $request)
    {
        $item_id = $request->item_id;
        Content::destroy($item_id);
        return ['success' => true];
    }
    public function pricing($course_id)
    {
        $title = __t('course_pricing');
        $course = Course::find($course_id);

        // Fetch subscription plans
        $subscriptionPlans = SubscriptionPlan::all();

        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        return view(theme('dashboard.courses.pricing'), compact('title', 'course', 'subscriptionPlans'));
    }

    public function subscribeEnroll(Request $request)
    {
        $course_id = $request->course_id;

        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect(route('login'));
        }

        $user = Auth::user();
        $course = Course::find($course_id);

        // Check if the user has an active subscription to the same plan as the course
        if ($user->isSubscribedToPlan($course->subscription_plan_id)) {
            // Check if the user is already enrolled in the course
            $isEnrolled = $user->isEnrolled($course_id);

            if (!$isEnrolled) {
                $carbon = Carbon::now()->toDateTimeString();
                $user->enrolls()->attach($course_id, ['status' => 'success', 'enrolled_at' => $carbon]);
                $user->enroll_sync();

                return redirect(route('course', $course->slug));
            } else {
                // User is already enrolled in the course
                return redirect(route('course', $course->slug))->with('error', 'You are already enrolled in this course.');
            }
        } else {
            // Redirect or handle the case where the user does not have an active subscription to the course's plan
            return redirect(route('subscription-plans.show', ['id' => $course->subscription_plan_id]))
                ->with('error', 'You need an active subscription to enroll in this course.');
        }
    }



    public function pricingSet(Request $request, $course_id)
    {
        if ($request->price_plan == 'paid') {
            $rules = [
                'price' => 'required|numeric',
            ];

            if ($request->sale_price) {
                $rules['sale_price'] = 'numeric';
            }

            $this->validate($request, $rules);
        }

        $course = Course::find($course_id);

        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        $data = [
            'price_plan'        => $request->price_plan,
            'price'             => clean_html($request->price),
            'sale_price'        => clean_html($request->sale_price),
            'require_login'     => $request->require_login,
            'require_enroll'    => $request->require_enroll,
        ];

        // Check if the selected price plan is "subscription"
        if ($request->price_plan == 'subscription') {
            // Add logic to handle subscription plans here
            $data['subscription_plan_id'] = $request->subscription_plan_id; // Assuming subscription_plan_id is the foreign key in the courses table
        } else {
            $data['subscription_plan_id'] = null; // Reset subscription_plan_id if the price plan is not "subscription"
        }

        $course->update($data);

        return back();
    }





    public function drip($course_id)
    {
        $title = __t('drip_content');
        $course = Course::find($course_id);
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        return view(theme('dashboard.courses.drip'), compact('title', 'course'));
    }



    public function dripPost(Request $request, $course_id)
    {

        $sections = $request->section;
        foreach ($sections as $sectionId => $section) {
            Section::whereId($sectionId)->update(array_except($section, 'content'));

            $contents = array_get($section, 'content');
            foreach ($contents as $contentId => $content) {
                Content::whereId($contentId)->update(array_except($content, 'content'));
            }
        }

        return back()->with('success', __t('drip_preference_saved'));
    }



    public function publish($course_id)
    {
        $title = __t('publish_course');
        $course = Course::find($course_id);
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }

        return view(theme('dashboard.courses.publish'), compact('title', 'course'));
    }

    public function publishPost(Request $request, $course_id)
    {
        $course = Course::find($course_id);
        if (!$course || !$course->i_am_instructor) {
            abort(404);
        }
        if ($request->publish_btn == 'publish') {
            if (get_option("lms_options.instructor_can_publish_course")) {
                $course->status = 1;
            } else {
                $course->status = 2;
            }
        } elseif ($request->publish_btn == 'unpublish') {
            $course->status = 4;
        }

        $course->save();

        return back();
    }


    /**
     * Course Free Enroll
     */

    public function freeEnroll(Request $request)
    {
        $course_id = $request->course_id;

        if (!Auth::check()) {
            return redirect(route('login'));
        }

        $user = Auth::user();
        $course = Course::find($course_id);

        $isEnrolled = $user->isEnrolled($course_id);

        if (!$isEnrolled) {
            $carbon = Carbon::now()->toDateTimeString();
            $user->enrolls()->attach($course_id, ['status' => 'success', 'enrolled_at' => $carbon]);
            $user->enroll_sync();
        }

        return redirect(route('course', $course->slug));
    }

    /**
     * Content Complete, such as Lecture
     * return to next after complete
     * stay current page if there is no next.
     */
    public function contentComplete($content_id)
    {
        $content = Content::find($content_id);
        $user = Auth::user();

        complete_content($content, $user);

        $go_content = $content->next;
        if (!$go_content) {
            $go_content = $content;
        }

        return redirect(route('single_' . $go_content->item_type, [$go_content->course->slug, $go_content->id]));
    }

    public function complete(Request $request, $course_id)
    {
        $user = Auth::user();
        $user->complete_course($course_id);
        
        return redirect(route('dashboard'));
        // return back();
    }

    public function attachmentDownload($hash)
    {
        $attachment = Attachment::whereHashId($hash)->first();
        if (!$attachment ||  !$attachment->media) {
            abort(404);
        }

        /**
         * If Assignment Submission Attachment, download it right now
         */
        if ($attachment->assignment_submission_id) {
            if (Auth::check()) {
                return $this->forceDownload($attachment->media);
            }
            abort(404);
        }

        $item = $attachment->belongs_item;

        if ($item && $item->item_type === 'lecture' && $item->is_preview) {
            return $this->forceDownload($attachment->media);
        }

        if (!Auth::check()) {
            abort(404);
        }
        $user = Auth::user();

        $course = $attachment->course;

        if (!$user->isEnrolled($course->id)) {
            abort(404);
        }

        return $this->forceDownload($attachment->media);
    }

    public function forceDownload($media)
    {
        $slug_ext = $media->slug_ext;

        if (substr($media->mime_type, 0, 5) == 'image') {
            $slug_ext = 'images/' . $slug_ext;
        }

        $path = ROOT_PATH . "/uploads/{$slug_ext}";

        return response()->download($path);
    }

    public function writeReview(Request $request, $id)
    {
        if ($request->rating_value < 1) {
            return back();
        }
        if (!$id) {
            $id = $request->course_id;
        }

        $user = Auth::user();

        $data = [
            'user_id'       => $user->id,
            'course_id'     => $id,
            'review'        => clean_html($request->review),
            'rating'        => $request->rating_value,
            'status'        => 1,
        ];

        $review = has_review($user->id, $id);
        if (!$review) {
            $review = Review::create($data);
        }
        $review->save_and_sync($data);

        return back();
    }

    /**
     * My Courses page from Dashboard
     */

    public function myCourses()
    {
        $title = __t('my_courses');
        return view(theme('dashboard.my_courses'), compact('title'));
    }

    public function myCoursesReviews()
    {
        $title = __t('my_courses_reviews');
        return view(theme('dashboard.my_courses_reviews'), compact('title'));
    }

    public function multiInstructorSearch(Request $request)
    {
        $searchQuery = $request->input('q');

        $instructors = User::where('user_type', 'instructor')
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchQuery . '%');
            })
            ->get();

        return response()->json(['instructors' => $instructors]);
    }


    public function removeInstructor(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $instructorId = $request->input('instructor_id');

        // Check if the instructor exists in the course's instructor list
        if ($course->instructors->contains($instructorId)) {
            // Remove the instructor from the course
            $course->instructors()->detach($instructorId);

            return redirect()->back()->with('success', 'Instructor removed successfully.');
        } else {
            return redirect()->back()->with('error', 'Instructor not found in this course.');
        }
    }
}

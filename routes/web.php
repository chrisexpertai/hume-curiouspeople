<?php

use Auth\ResetPasswordController;
use Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\UserTypeMiddleware;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Plugins\MultiInstructor\Http\Controllers\MultiInstructorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/upload/image', [App\Http\Controllers\UploadController::class, 'uploadImage'])->name('upload.image');

Route::get('/reactivate-account', 'App\Http\Controllers\UserController@reactivateAccount')->name('reactivate.account');
Route::post('/reactivate-account', 'App\Http\Controllers\UserController@reactivateAccount')->name('reactivate.account');


Route::get('admin/subscription-payments', [SubscriptionPaymentController::class, 'index'])->name('admin.subscription-payments');
Route::post('admin/subscription-payments/update-status', [SubscriptionPaymentController::class, 'updateStatus'])->name('admin.subscription-payments.update-status');

Route::get('/subscription/checkout', function () {
    return view('pay');
})->name('subscription.checkout');



Route::get('install', 'App\Http\Controllers\InstallationController@installations')->name('install');
Route::post('install', 'App\Http\Controllers\InstallationController@installationPost');
Route::get('install/final', 'App\Http\Controllers\InstallationController@installationFinal')->name('final');

Route::post('install/final', 'App\Http\Controllers\InstallationController@installationFinalPost')->name('finalPost');



Route::group(['prefix' => 'login'], function () {
    //Social login route
    Route::get('facebook', 'App\Http\Controllers\AuthController@redirectFacebook')->name('facebook_redirect');
    Route::get('facebook/callback', 'App\Http\Controllers\AuthController@callbackFacebook')->name('facebook_callback');

    Route::get('google', 'App\Http\Controllers\AuthController@redirectGoogle')->name('google_redirect');
    Route::get('google/callback', 'App\Http\Controllers\AuthController@callbackGoogle')->name('google_callback');

    Route::get('twitter', 'App\Http\Controllers\AuthController@redirectTwitter')->name('twitter_redirect');
    Route::get('twitter/callback', 'App\Http\Controllers\AuthController@callbackTwitter')->name('twitter_callback');

    Route::get('linkedin', 'App\Http\Controllers\AuthController@redirectLinkedIn')->name('linkedin_redirect');
    Route::get('linkedin/callback', 'App\Http\Controllers\AuthController@callbackLinkedIn')->name('linkin_callback');
});

Route::middleware([UserTypeMiddleware::class . ':inactive'])->group(function () {
    // Routes accessible only to users with user_type "inactive"
    Route::get('/reactivate', 'App\Http\Controllers\UserController@showReactivateForm')->name('reactivate');
});
/**
 * Authentication
 */
Route::post('/menus/update-order', [MenuController::class, 'updateOrder'])->name('menus.updateOrder');
Route::match(['post', 'delete'], '/menus/update-order', [MenuController::class, 'updateOrder'])->name('menus.updateOrder');


// Subscription Plans Routes
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscription-plans.index');

Route::get('/subscription-plans/{id}', [SubscriptionController::class, 'show'])->name('subscription-plans.show');
Route::post('/assign-subscription-plan/{plan_id}', [SubscriptionController::class, 'assignSubscriptionPlan'])
    ->name('assign-subscription-plan');

// Add the renew subscription route
Route::post('/renew-subscription/{plan_id}', [SubscriptionController::class, 'renewSubscription'])
    ->name('renew-subscription');
// Add the switch subscription route
Route::post('/switch-subscription-plan/{plan_id}', [SubscriptionController::class, 'changeSubscriptionPlan'])
    ->name('switch-subscription-plan');
Route::post('/pricing-set/{course_id}', [CourseController::class, 'pricingSet'])->name('pricing-set');
Route::post('/subscribe-enroll', [CourseController::class, 'subscribeEnroll'])->name('subscribe_enroll');
Route::get('/subscribe-enroll', [CourseController::class, 'subscribeEnroll'])->name('subscribe_enroll');

Route::post('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription'])
    ->name('cancel-subscription');
Route::post('/send-notification', [NotificationController::class, 'sendNotification']);


//  Route::get('login', 'App\Http\Controllers\AuthController@login')->name('login')->middleware('guest');
//  Route::post('login', 'App\Http\Controllers\AuthController@loginPost');
//  Route::get('/verification', 'VerificationController@index');
//  Route::post('/verification', 'App\Http\Controllers\VerificationController@confirmCode');
//  Route::get('/verification/resend', 'App\Http\Controllers\erificationController@resendCode');
//  Route::any('logout', 'App\Http\Controllers\AuthController@logoutPost')->name('logout');

//  Route::get('register', 'App\Http\Controllers\AuthController@register')->name('register')->middleware('guest');
//  Route::post('register', 'App\Http\Controllers\AuthController@registerPost');

//  Route::get('forgot-password', 'App\Http\Controllers\AuthController@forgotPassword')->name('forgot_password');
//  Route::post('forgot-password', 'App\Http\Controllers\AuthController@sendResetToken');
//  Route::get('forgot-password/reset/{token}', 'App\Http\Controllers\AuthController@passwordResetForm')->name('reset_password_link');
//  Route::post('forgot-password/reset/{token}', 'App\Http\Controllers\AuthController@passwordReset');


Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.show');
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

Route::get('courses', [App\Http\Controllers\HomeController::class, 'courses'])->name('courses');
// Route::get('become-instructor', [App\Http\Controllers\HomeController::class, 'BecomeInstructor'])->name('become_instructor');
Route::get('pricing', [App\Http\Controllers\HomeController::class, 'Pricing'])->name('pricing');

Route::get('/instructors', 'App\Http\Controllers\HomeController@searchInstructors')->name('instructors.search');
// routes/web.php

Route::get('/instructors/{instructor}', 'App\Http\Controllers\HomeController@showInstructor')->name('instructors.show');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
/**
 * Single Page
 */
//Route::get('{slug}', 'PostController@singlePage')->name('page');

Route::get('blog', 'App\Http\Controllers\PostController@blog')->name('blog');
Route::get('blog/{slug}', 'App\Http\Controllers\PostController@blog')->name('post');



Route::get('featured-courses', 'App\Http\Controllers\HomeController@courses')->name('featured_courses');
Route::get('popular-courses', 'App\Http\Controllers\HomeController@courses')->name('popular_courses');

Route::get('courses/{slug?}', 'App\Http\Controllers\CourseController@view')->name('course');

Route::group(['middleware' => ['auth', 'check_user_type']], function () {

    Route::get('courses/{slug}/lecture/{lecture_id}', 'App\Http\Controllers\CourseController@lectureView')->name('single_lecture');
    Route::get('courses/{slug}/assignment/{assignment_id}', 'App\Http\Controllers\CourseController@assignmentView')->name('single_assignment');
    Route::get('courses/{slug}/quiz/{quiz_id}', 'App\Http\Controllers\QuizController@quizView')->name('single_quiz');
    Route::get('courses/{slug?}/{id}/follow', 'App\Http\Controllers\UserController@followToggle');
    Route::post('courses/free-enroll', 'App\Http\Controllers\CourseController@freeEnroll')->name('free_enroll');
});


Route::get('profile/{id}/follow', 'App\Http\Controllers\UserController@followToggle')->name('follow');

Route::get('profile/{id}', 'App\Http\Controllers\UserController@profile')->name('profile');
Route::get('review/{id}', 'App\Http\Controllers\UserController@review')->name('review');
Route::any('logout', 'App\Http\Controllers\AuthController@logoutPost')->name('logout');


Route::get('categories', 'App\Http\Controllers\CategoriesController@home')->name('categories');
Route::get('categories/{category_slug}', 'App\Http\Controllers\CategoriesController@show')->name('category_view');
//Get Topics Dropdown for course creation category select


//Attachment Download
Route::get('attachment-download/{hash}', 'App\Http\Controllers\CourseController@attachmentDownload')->name('attachment_download');


Route::group(['middleware' => ['auth', 'check_user_type']], function () {




    Route::get('/become-instructor', 'App\Http\Controllers\UserController@showTeacherApplicationForm')->name('apply.teacher');
    Route::post('/become-instructor', 'App\Http\Controllers\UserController@submitTeacherApplication')->name('submit.teacher.application');



    Route::post('courses/{slug}/assignment/{assignment_id}', 'App\Http\Controllers\CourseController@assignmentSubmitting');
    Route::get('content_complete/{content_id}', 'App\Http\Controllers\CourseController@contentComplete')->name('content_complete');
    Route::post('courses-complete/{course_id}', 'App\Http\Controllers\CourseController@complete')->name('course_complete');

    Route::group(['prefix' => 'checkout'], function () {
        Route::get('/', 'App\Http\Controllers\CartController@checkout')->name('checkout');
        Route::post('bank-transfer', 'App\Http\Controllers\GatewayController@bankPost')->name('bank_transfer_submit');
        Route::post('paypal', 'App\Http\Controllers\GatewayController@paypalRedirect')->name('paypal_redirect');
        Route::post('offline', 'App\Http\Controllers\GatewayController@payOffline')->name('pay_offline');
    });

    Route::post('save-review/{course_id?}', 'App\Http\Controllers\CourseController@writeReview')->name('save_review');
    Route::post('update-wishlist', 'App\Http\Controllers\UserController@updateWishlist')->name('update_wish_list');


    Route::post('discussion/ask-question', 'App\Http\Controllers\DiscussionController@askQuestion')->name('ask_question');
    Route::post('discussion/reply/{id}', 'App\Http\Controllers\DiscussionController@replyPost')->name('discussion_reply_student');

    Route::group(['middleware' => 'auth'], function () {
        Route::post('quiz-start', 'App\Http\Controllers\QuizController@start')->name('start_quiz');
        Route::get('quiz/{quiz_id}', 'App\Http\Controllers\QuizController@quizAttempting')->name('quiz_attempt_url');
        Route::post('quiz/{quiz_id}', 'App\Http\Controllers\QuizController@answerSubmit');
    });

    Route::get('quiz/answer/submit', 'QuizController@answerSubmit')->name('quiz_answer_submit');
});

Route::post('/add-subscription', [CartController::class, 'addSubscription'])->name('add.subscription');

/**
 * Add and remove to Cart
 */
Route::post('/remove-from-cart', 'App\Http\Controllers\CartController@removeCart')->name('remove-from-cart');

Route::post('add-to-cart', 'App\Http\Controllers\CartController@addToCart')->name('add_to_cart');
Route::post('remove-cart', 'App\Http\Controllers\CartController@removeCart')->name('remove_cart');

/**
 * Payment Gateway Silent Notification
 * CSRF verification skipped
 */
Route::group(['prefix' => 'gateway-ipn'], function () {
    Route::post('stripe', 'App\Http\Controllers\GatewayController@stripeCharge')->name('stripe_charge');
    Route::any('paypal/{transaction_id?}', 'App\Http\Controllers\IPNController@paypalNotify')->name('paypal_notify');
});

/**
 * Users,Instructor dashboard area
 */
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'check_user_type']], function () {
    Route::get('/', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard');

    /**
     * Only instructor has access in this group
     */
    Route::group(['middleware' => ['instructor']], function () {

        Route::post('update-section/{id}', 'App\Http\Controllers\CourseController@updateSection')->name('update_section');
        Route::post('delete-section', 'App\Http\Controllers\CourseController@deleteSection')->name('delete_section');

        Route::get('create-document', 'App\Http\Controllers\CourseController@createDocument')->name('create_document');
        Route::post('create-document', 'App\Http\Controllers\CourseController@saveDocument');
        Route::get('fetch-document-content', 'App\Http\Controllers\CourseController@fetchDocumentContent')->name('fetch_document_content');

        Route::post('save-document', 'App\Http\Controllers\CourseController@saveDocument')->name('save_document');



        Route::group(['prefix' => 'setting'], function () {
            Route::get('/step/{step?}', 'UserController@setting');
            Route::get('/', 'UserController@setting');
            Route::post('/', 'UserController@update');
            Route::post('/metas', 'UserController@storeMetas');
            Route::post('metas/{meta_id}/update', 'UserController@updateMeta');
            Route::get('metas/{meta_id}/delete', 'UserController@deleteMeta');
        });
        Route::group(['prefix' => 'courses'], function () {
            Route::get('new', [App\Http\Controllers\CourseController::class, 'create'])->name('create_course');

            Route::post('new', 'App\Http\Controllers\CourseController@store');

            Route::get('{course_id}/information', 'App\Http\Controllers\CourseController@information')->name('edit_course_information');
            Route::post('{course_id}/information', 'App\Http\Controllers\CourseController@informationPost');

            Route::group(['prefix' => '{course_id}/curriculum'], function () {
                Route::get('', 'App\Http\Controllers\CourseController@curriculum')->name('edit_course_curriculum');
                Route::get('new-section', 'App\Http\Controllers\CourseController@newSection')->name('new_section');
                Route::post('new-section', 'App\Http\Controllers\CourseController@newSectionPost');

                Route::post('new-lecture', 'App\Http\Controllers\CourseController@newLecture')->name('new_lecture');
                Route::post('update-lecture/{id}', 'App\Http\Controllers\CourseController@updateLecture')->name('update_lecture');

                Route::post('new-assignment', 'App\Http\Controllers\CurriculumController@newAssignment')->name('new_assignment');
                Route::post('update-assignment/{id}', 'App\Http\Controllers\CurriculumController@updateAssignment')->name('update_assignment');

                Route::group(['prefix' => 'quiz'], function () {
                    Route::post('create', 'App\Http\Controllers\QuizController@newQuiz')->name('new_quiz');
                    Route::post('update/{id}', 'App\Http\Controllers\QuizController@updateQuiz')->name('update_quiz');

                    Route::post('{quiz_id}/create-question', 'App\Http\Controllers\QuizController@createQuestion')->name('create_question');
                });
            });

            Route::post('/course/{id}/multi-instructor-search', 'CourseController@multiInstructorSearch')->name('multi_instructor_search');
            Route::post('/course/{id}/remove-instructor', 'CourseController@removeInstructor')->name('remove_instructor');

            Route::get('/api/menu-items', [MenuController::class, 'getMenuItems']);

            Route::post('quiz/edit-question', 'App\Http\Controllers\QuizController@editQuestion')->name('edit_question_form');
            Route::post('quiz/update-question', 'App\Http\Controllers\QuizController@updateQuestion')->name('edit_question');
            Route::post('load-quiz-questions', 'App\Http\Controllers\QuizController@loadQuestions')->name('load_questions');
            Route::post('sort-questions', 'App\Http\Controllers\QuizController@sortQuestions')->name('sort_questions');
            Route::post('delete-question', 'App\Http\Controllers\QuizController@deleteQuestion')->name('delete_question');
            Route::post('delete-option', 'App\Http\Controllers\QuizController@deleteOption')->name('option_delete');

            Route::post('edit-item', 'App\Http\Controllers\CourseController@editItem')->name('edit_item_form');
            Route::post('delete-item', 'App\Http\Controllers\CourseController@deleteItem')->name('delete_item');
            Route::post('curriculum_sort', 'App\Http\Controllers\CurriculumController@sort')->name('curriculum_sort');

            Route::post('delete-attachment', 'App\Http\Controllers\CurriculumController@deleteAttachment')->name('delete_attachment_item');

            Route::post('load-section-items', 'App\Http\Controllers\CourseController@loadContents')->name('load_contents');


            Route::get('{id}/pricing', 'App\Http\Controllers\CourseController@pricing')->name('edit_course_pricing');
            Route::post('{id}/faqs', 'App\Http\Controllers\FaqController@store')->name('courses.faqs.store');

            // Route for editing FAQ (showing edit modal)
            Route::get('{id}/faqs/{faq_id}/edit', 'App\Http\Controllers\FaqController@edit')->name('courses.faqs.edit');

            // Route for updating FAQ
            Route::put('{id}/faqs/{faq_id}', 'App\Http\Controllers\FaqController@update')->name('courses.faqs.update');

            // Route for deleting FAQ
            Route::delete('{id}/faqs/{faq_id}', 'App\Http\Controllers\FaqController@destroy')->name('courses.faqs.destroy');

            Route::get('{id}/additional', 'App\Http\Controllers\CourseController@additional')->name('edit_course_additional');

            Route::post('{id}/additional', 'App\Http\Controllers\CourseController@additionalPost');
            Route::get('{id}/drip', 'App\Http\Controllers\CourseController@drip')->name('edit_course_drip');
            Route::post('{id}/drip', 'App\Http\Controllers\CourseController@dripPost');
            Route::get('{id}/publish', 'App\Http\Controllers\CourseController@publish')->name('publish_course');
            Route::post('{id}/publish', 'App\Http\Controllers\CourseController@publishPost');
        });

        Route::get('my-courses', 'App\Http\Controllers\CourseController@myCourses')->name('my_courses');
        Route::get('my-courses-reviews', 'App\Http\Controllers\CourseController@myCoursesReviews')->name('my_courses_reviews');

        Route::group(['prefix' => 'courses-has-quiz'], function () {
            Route::get('/', 'App\Http\Controllers\QuizController@quizCourses')->name('courses_has_quiz');
            Route::get('quizzes/{id}', 'App\Http\Controllers\QuizController@quizzes')->name('courses_quizzes');
            Route::get('attempts/{quiz_id}', 'App\Http\Controllers\QuizController@attempts')->name('quiz_attempts');
            Route::get('attempt/{attempt_id}', 'App\Http\Controllers\QuizController@attemptDetail')->name('attempt_detail');
            Route::post('attempt/{attempt_id}', 'App\Http\Controllers\QuizController@attemptReview');
        });

        Route::group(['prefix' => 'assignments'], function () {
            Route::get('/', 'App\Http\Controllers\AssignmentController@index')->name('courses_has_assignments');
            Route::get('course/{course_id}', 'App\Http\Controllers\AssignmentController@assignmentsByCourse')->name('courses_assignments');
            Route::get('submissions/{assignment_id}', 'App\Http\Controllers\AssignmentController@submissions')->name('assignment_submissions');
            Route::get('submission/{submission_id}', 'App\Http\Controllers\AssignmentController@submission')->name('assignment_submission');
            Route::post('submission/{submission_id}', 'App\Http\Controllers\AssignmentController@evaluation');
        });

        Route::group(['prefix' => 'earning'], function () {
            Route::get('/', 'App\Http\Controllers\EarningController@earning')->name('earning');
            Route::get('report', 'App\Http\Controllers\EarningController@earningReport')->name('earning_report');
        });
        Route::group(['prefix' => 'withdraw'], function () {
            Route::get('/', 'App\Http\Controllers\EarningController@withdraw')->name('withdraw');
            Route::post('/', 'App\Http\Controllers\EarningController@withdrawPost');

            Route::get('preference', 'App\Http\Controllers\EarningController@withdrawPreference')->name('withdraw_preference');
            Route::post('preference', 'App\Http\Controllers\EarningController@withdrawPreferencePost');
        });

        Route::group(['prefix' => 'contacts'], function () {
            Route::get('/', 'App\Http\Controllers\ContactController@contacts')->name('contacts');
            Route::post('block-unblock', ['as' => 'administratorBlockUnblock', 'uses' => 'App\Http\Controllers\UserController@administratorBlockUnblock']);

            Route::delete('{id}', ['as' => 'contacts.destroy', 'uses' => 'App\Http\Controllers\ContactController@destroy']); // New route for contact deletion
        });


        Route::group(['prefix' => 'discussions'], function () {
            Route::get('/', 'App\Http\Controllers\DiscussionController@index')->name('instructor_discussions');
            Route::get('reply/{id}', 'App\Http\Controllers\DiscussionController@reply')->name('discussion_reply');
            Route::post('reply/{id}', 'App\Http\Controllers\DiscussionController@replyPost')->name('discussion_reply_post');
        });
    });



    Route::group(['prefix' => 'media'], function () {
        Route::post('upload', [App\Http\Controllers\MediaController::class, 'store'])->name('post_media_upload');
        Route::get('load_filemanager', [App\Http\Controllers\MediaController::class, 'loadFileManager'])->name('load_filemanager');
        Route::post('delete', [App\Http\Controllers\MediaController::class, 'delete'])->name('delete_media');
    });

    Route::delete('{courseId}', 'App\Http\Controllers\CourseController@deleteCourse')->name('courses.delete');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'App\Http\Controllers\DashboardController@profileSettings')->name('profile_settings');
        Route::post('/', 'App\Http\Controllers\DashboardController@profileSettingsPost');
        Route::post('/add-education', 'App\Http\Controllers\DashboardController@addEducation')->name('add_education');
        Route::get('/get-education', 'App\Http\Controllers\DashboardController@getUserEducation')->name('get_education');
        Route::post('/edit_education', 'App\Http\Controllers\DashboardController@editEducation')->name('edit_education');
        Route::post('/delete_education', [DashboardController::class, 'deleteEducation'])->name('delete_education');

        Route::get('/delete-profile', 'App\Http\Controllers\UserController@deleteProfile')->name('delete.profile');
        Route::post('/delete-profile', 'App\Http\Controllers\UserController@destroy')->name('destroy.profile');

        Route::get('reset-password', 'App\Http\Controllers\DashboardController@resetPassword')->name('profile_reset_password');
        Route::post('reset-password', 'App\Http\Controllers\DashboardController@resetPasswordPost');
    });

    Route::get('enrolled-courses', 'App\Http\Controllers\DashboardController@enrolledCourses')->name('enrolled_courses');

    Route::get('reviews', 'App\Http\Controllers\DashboardController@myReviews')->name('reviews_i_wrote');
    Route::get('wishlist', 'App\Http\Controllers\DashboardController@wishlist')->name('wishlist');

    Route::get('my-quiz-attempts', 'App\Http\Controllers\QuizController@myQuizAttempts')->name('my_quiz_attempts');

    Route::group(['prefix' => 'purchases'], function () {
        Route::get('/', 'App\Http\Controllers\DashboardController@purchaseHistory')->name('purchase_history');
        Route::get('view/{id}', 'App\Http\Controllers\DashboardController@purchaseView')->name('purchase_view');
    });

    Route::get('my-subscriptions', 'App\Http\Controllers\SubscriptionController@mySubscription')->name('my_subscriptions');
});


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Override the logout route
Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
// Custom password reset routes
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Auth\Controllers\ResetPasswordController@reset')->name('password.update');

// Custom email verification routes
Route::get('email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

// Custom email confirmation routes
Route::get('email/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('email/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@confirm')->name('password.confirmation');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/home/{slug}', 'App\Http\Controllers\HomePageController@show');

Route::get('lessons/{course_id}/{slug}', [App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
Route::post('lesson/{slug}/test', [App\Http\Controllers\LessonController::class, 'test'])->name('lessons.test');


Route::middleware(['auth'])->group(function () {

    Route::get('/user/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::patch('/user/update-profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');

    Route::get('dashboard/tickets', [TicketController::class, 'userViewTickets'])->name('user.tickets.index');
    Route::get('dashboard/tickets/create', [TicketController::class, 'createTicketForm'])->name('user.tickets.create.form');
    Route::post('dashboard/tickets/create', [TicketController::class, 'createTicket'])->name('user.tickets.create');
    Route::get('dashboard/tickets/{ticket}', [TicketController::class, 'viewTicket'])->name('user.tickets.view');
    Route::post('dashboard/tickets/{ticket}/respond', [TicketController::class, 'respondToTicket'])->name('user.tickets.respond');
});
Route::get('payment-thank-you/{transaction_id?}', 'PaymentController@thankYou')->name('payment_thank_you_page');

Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin'], function () {
});


Route::get('/notifications', 'App\Http\Controllers\NotificationController@index')->name('notifications.index');
Route::put('/notifications/{id}/mark-as-read', 'App\Http\Controllers\NotificationController@markAsRead')->name('notifications.markAsRead');
Route::delete('/notifications/clear', 'App\Http\Controllers\NotificationController@clearNotifications')->name('notifications.clear');


Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('pages/{page:slug?}', 'App\Http\Controllers\Admin\PageController@pageProxy')->name('post_proxy');

// Admin Routes
Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {


    Route::get('/posts', [PostController::class, 'posts_admin'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{slug}', [PostController::class, 'destroy'])->name('posts.destroy');


    Route::delete('/posts/{slug}/delete-image', 'App\Http\Controllers\Admin\PostController@deleteImage')->name('posts.delete_image');


    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{slug}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{slug}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{slug}', [PageController::class, 'destroy'])->name('pages.destroy');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::delete('/pages/{slug}/delete-image', 'App\Http\Controllers\Admin\PageController@deleteImage')->name('pages.delete_image');

    // Add a new route for creating sections
    Route::get('home/settings/create', [HomeController::class, 'create'])->name('home.settings.create');

    // Add a new route for storing sections
    Route::post('home/settings/store', [HomeController::class, 'storeSection'])->name('home.settings.storeSection');

    Route::get('home/settings', [HomeController::class, 'edit'])->name('home.settings.edit');
    Route::post('home/settings/update', [HomeController::class, 'update'])->name('home.settings.update');


    Route::get('/tickets', [TicketController::class, 'adminViewTickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'adminViewTicket'])->name('tickets.view');
    Route::post('/tickets/{ticket}/respond', [TicketController::class, 'adminRespondToTicket'])->name('tickets.respond');




    Route::resource('categories', CategoryController::class);

    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/upload', 'AdminController@handleUpload')->name('upload.handle');
});




/**
 * Admin Area
 */


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'App\Http\Controllers\AdminController@index')->name('admin');

    Route::get('/dashboard', 'App\Http\Controllers\AdminController@index')->name('admin');



    Route::get('/subscription', [SubscriptionController::class, 'subscription'])->name('subscription.index');
    Route::get('/subscribed-users', [SubscriptionController::class, 'subscriptionUsers'])->name('admin.subscribed.users');

    Route::get('/subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
    Route::post('/subscription/store', [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::get('/subscription/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    Route::put('/subscription/{id}', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::delete('/subscription/{id}', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');


    Route::get('/admin/custom-css', 'App\Http\Controllers\CustomCssController@index');
    Route::post('/admin/custom-css', 'App\Http\Controllers\CustomCssController@store');



    Route::group(['prefix' => 'media_manager'], function () {
        Route::get('/', 'App\Http\Controllers\MediaController@mediaManager')->name('media_manager');
        Route::post('media-update', 'App\Http\Controllers\MediaController@mediaManagerUpdate')->name('media_update');
    });


    Route::get('/instructors/requests', 'App\Http\Controllers\UserController@viewInstructorRequests')->name('admin.instructors.requests');
    Route::get('/instructor-requests/{id}', 'App\Http\Controllers\UserController@showInstructorRequestDetails')->name('admin.instructor.request.details');
    Route::post('/instructor-requests/{id}/approve', 'App\Http\Controllers\UserController@approveInstructorRequest')->name('admin.instructor.request.approve');
    Route::post('/instructor-requests/{id}/decline', 'App\Http\Controllers\UserController@declineInstructorRequest')->name('admin.instructor.request.decline');
    Route::get('/instructor-requests', 'App\Http\Controllers\UserController@viewInstructorRequests')->name('admin.instructor.requests');



    Route::group(['prefix' => 'custom'], function () {


        Route::get('css', 'App\Http\Controllers\CustomCssController@index')->name('css');

        Route::post('css', 'App\Http\Controllers\CustomCssController@store')->name('css');
    });

    Route::get('/notifications', 'App\Http\Controllers\NotificationController@Adminindex')->name('admin.notifications');
    Route::delete('/admin/notifications/clear', 'App\Http\Controllers\NotificationController@clearAdminNotifications')->name('admin.notifications.clear');
    Route::get('/admin/notifications/markAsRead', 'App\Http\Controllers\NotificationController@markAdminNotificationsAsRead')->name('admin.notifications.markAsRead');


    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'App\Http\Controllers\CategoriesController@index')->name('category_index');
        Route::get('create', 'App\Http\Controllers\CategoriesController@create')->name('category_create');
        Route::post('create', 'App\Http\Controllers\CategoriesController@store');
        Route::get('edit/{id}', 'App\Http\Controllers\CategoriesController@edit')->name('category_edit');
        Route::post('edit/{id}', 'App\Http\Controllers\CategoriesController@update');
        Route::post('delete', 'App\Http\Controllers\CategoriesController@destroy')->name('delete_category');
    });

    Route::group(['prefix' => 'courses'], function () {
        Route::get('/', 'App\Http\Controllers\AdminController@adminCourses')->name('admin_courses');
        Route::get('popular', 'App\Http\Controllers\AdminController@popularCourses')->name('admin_popular_courses');
        Route::get('featured', 'App\Http\Controllers\AdminController@featureCourses')->name('admin_featured_courses');
    });

    // Course Importer — create a course from a pasted document
    Route::prefix('course-import')->name('admin.course-import.')->group(function () {
        Route::get('/',         [App\Http\Controllers\Admin\CourseImportController::class, 'index'])  ->name('index');
        Route::post('/preview', [App\Http\Controllers\Admin\CourseImportController::class, 'preview'])->name('preview');
        Route::post('/import',  [App\Http\Controllers\Admin\CourseImportController::class, 'import']) ->name('import');
    });



    Route::group(['prefix' => 'settings'], function () {
        Route::get('theme-settings', 'App\Http\Controllers\SettingsController@ThemeSettings')->name('theme_settings');
        Route::get('invoice-settings', 'App\Http\Controllers\SettingsController@invoiceSettings')->name('invoice_settings');
        Route::get('general', 'App\Http\Controllers\SettingsController@GeneralSettings')->name('general_settings');

        Route::get('lms-settings', 'App\Http\Controllers\SettingsController@LMSSettings')->name('lms_settings');
        Route::get('image-settings', 'App\Http\Controllers\SettingsController@ImageSettings')->name('image_settings');
        Route::get('register-settings', 'App\Http\Controllers\SettingsController@RegisterSettings')->name('register_settings');





        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

        Route::get('home-settings', 'App\Http\Controllers\SettingsController@HOMESettings')->name('home_settings');
        Route::get('social-share', 'App\Http\Controllers\SettingsController@ContactSettings')->name('contact_settings');
        Route::get('header-settings', 'App\Http\Controllers\SettingsController@HeaderSettings')->name('header_settings');
        Route::get('footer-settings', 'App\Http\Controllers\SettingsController@FooterSettings')->name('footer_settings');
        Route::get('text-settings', 'App\Http\Controllers\SettingsController@TextSettings')->name('text_settings');

        Route::get('marketing-settings', 'App\Http\Controllers\SettingsController@MarketingSettings')->name('marketing_settings');
        Route::get('course-settings', 'App\Http\Controllers\SettingsController@CourseSettings')->name('course_settings');
        Route::get('maintenance-settings', 'App\Http\Controllers\SettingsController@MaintenanceSettings')->name('maintenance_settings');

        Route::get('social', 'App\Http\Controllers\SettingsController@SocialSettings')->name('social_settings');
        //Save settings / options
        Route::post('save-settings', 'App\Http\Controllers\SettingsController@update')->name('save_settings');
        Route::get('payment', 'App\Http\Controllers\PaymentController@PaymentSettings')->name('payment_settings');
        Route::get('storage', 'App\Http\Controllers\SettingsController@StorageSettings')->name('storage_settings');
    });

    Route::post('admin/settings/save-email', 'App\Http\Controllers\SettingsController@saveEmailSettings')->name('save_email_settings');

    Route::get('gateways', 'App\Http\Controllers\PaymentController@PaymentGateways')->name('payment_gateways');
    Route::get('withdraw', 'App\Http\Controllers\SettingsController@withdraw')->name('withdraw_settings');
    Route::get('email', 'App\Http\Controllers\SettingsController@email')->name('email_settings');

    Route::group(['prefix' => 'payments'], function () {
        Route::get('/', 'App\Http\Controllers\PaymentController@index')->name('payments');
        Route::get('view/{id}', 'App\Http\Controllers\PaymentController@view')->name('payment_view');
        Route::get('delete/{id}', 'App\Http\Controllers\PaymentController@delete')->name('payment_delete');

        Route::post('update-status/{id}', 'App\Http\Controllers\PaymentController@updateStatus')->name('update_status');
    });

    Route::group(['prefix' => 'withdraws'], function () {
        Route::get('/', 'App\Http\Controllers\AdminController@withdrawsRequests')->name('withdraws');
    });

    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/', ['as' => 'contacts', 'uses' => 'App\Http\Controllers\ContactController@contacts'])->name('contacts');
        Route::delete('{id}', ['as' => 'contacts.destroy', 'uses' => 'App\Http\Controllers\ContactController@destroy']); // New route for contact deletion
        Route::post('block-unblock', ['as' => 'administratorBlockUnblock', 'uses' => 'App\Http\Controllers\UserController@administratorBlockUnblock']);

        Route::delete('{id}', ['as' => 'contacts.destroy', 'uses' => 'App\Http\Controllers\ContactController@destroy']); // New route for contact deletion

    });


    Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {

        Route::group(['prefix' => 'students-progress'], function () {
            Route::get('/{course_id?}', 'App\Http\Controllers\StudentProgressController@index')->name('student_progress');
            Route::get('{course_id}/detail/{user_id}', 'App\Http\Controllers\StudentProgressController@details')->name('progress_report_details');
        });
    });


    Route::group(['prefix' => 'plugins'], function () {
        Route::get('/', 'App\Http\Controllers\ExtendController@plugins')->name('plugins');
        Route::get('find', 'App\Http\Controllers\ExtendController@findPlugins')->name('find_plugins');
        Route::get('action', 'App\Http\Controllers\ExtendController@pluginAction')->name('plugin_action');
    });



    Route::get('students', 'App\Http\Controllers\UserController@viewStudents')->name('admin.students');
    Route::get('instructors', 'App\Http\Controllers\UserController@viewInstructors')->name('admin.instructors');
    Route::resource('homepages', 'App\Http\Controllers\HomePageController');
    Route::get('/students/search', 'App\Http\Controllers\UserController@viewStudents')->name('search.students');


    Route::group(['prefix' => 'users'], function () {
        Route::get('/', ['as' => 'users', 'uses' => 'App\Http\Controllers\UserController@users']);
        Route::get('add-administrator', ['as' => 'add_administrator_form', 'uses' => 'App\Http\Controllers\UserController@addAdministratorForm']);

        Route::post('store-administrator', ['as' => 'storeAdministrator', 'uses' => 'App\Http\Controllers\UserController@storeAdministrator']);
        Route::get('create', 'App\Http\Controllers\UserController@create')->name('users.create');
        Route::get('/users/{user}/edit', 'App\Http\Controllers\UserController@edit')->name('users.edit');
        Route::put('update/{user}', 'App\Http\Controllers\UserController@update')->name('users.update');
        Route::post('store', 'App\Http\Controllers\UserController@store')->name('users.store');



        Route::post('block-unblock', ['as' => 'administratorBlockUnblock', 'uses' => 'App\Http\Controllers\UserController@administratorBlockUnblock']);
    });




    /**
     * Change Password route
     */
    Route::group(['prefix' => 'account'], function () {
        Route::get('change-password', 'App\Http\Controllers\UserController@changePassword')->name('change_password');
        Route::post('change-password', 'App\Http\Controllers\UserController@changePasswordPost');
    });
});


// Admin Reports
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports');
    Route::post('/reports/snapshot', [\App\Http\Controllers\Admin\ReportController::class, 'snapshot'])->name('admin.reports.snapshot');
    Route::get('/reports/user-courses', [\App\Http\Controllers\Admin\ReportController::class, 'userCourses'])->name('admin.reports.user_courses');
});

// routes/web.php



// Plugins







Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {
    Route::group(['middleware' => ['instructor']], function () {

        // Routes for MultiInstructor plugin
        Route::group(['middleware' => ['check.plugin:MultiInstructor']], function () {



            Route::group(['prefix' => 'courses'], function () {
                Route::group(['prefix' => '{course_id}/instructors'], function () {
                    Route::get('/', [MultiInstructorController::class, 'instructorSettings'])->name('edit_course_instructor');
                    Route::post('/', [MultiInstructorController::class, 'instructorSettingsPost']);
                });
            });
            Route::post('/attach_instructor/{course_id}', [MultiInstructorController::class, 'attachInstructor'])->name('attach_instructor');


            Route::get('/course/{id}/multi-instructor-search', 'App\Plugins\MultiInstructor\Http\Controllers\MultiInstructorController@multiInstructorSearch')->name('multi_instructor_search');

            Route::post('/course/{id}/multi-instructor-search', 'App\Plugins\MultiInstructor\Http\Controllers\MultiInstructorController@multiInstructorSearch')->name('multi_instructor_search');
            Route::post('/course/{id}/remove-instructor', 'App\Plugins\MultiInstructor\Http\Controllers\MultiInstructorController@removeInstructor')->name('remove_instructor');
        });

        // Routes for LiveClass plugin
        Route::group(['prefix' => 'live-class', 'middleware' => ['check.plugin:LiveClass']], function () {
            // Define routes for LiveClass plugin here
        });

        // Routes for StudentsProgress plugin
        Route::group(['prefix' => 'students-progress', 'middleware' => ['check.plugin:StudentsProgress']], function () {

            Route::get('/', 'App\Http\Controllers\StudentProgressController@index')->name('my_students');

            Route::get('/{course_id?}', 'App\Http\Controllers\StudentProgressController@index')->name('student_progress');
            Route::get('{course_id}/detail/{user_id}', 'App\Http\Controllers\StudentProgressController@details')->name('progress_report_details');
        });

        // Routes for Certificate plugin
    });
    Route::group(['prefix' => 'certificate', 'middleware' => ['check.plugin:Certificate']], function () {
        Route::group(['prefix' => 'course/certificate', 'middleware' => ['auth']], function () {
            Route::get('{course_id}/download', 'App\Plugins\Certificate\Http\Controllers\CertificateController@generateCertificate')->name('download_certificate');
        });
    });
    Route::group(['prefix' => 'course/certificate', 'middleware' => ['auth']], function () {
        Route::get('{course_id}/custom/download', [App\Http\Controllers\DownloadCertificateCustomController::class, 'index'])->name('getCustomCertificate');
    });
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'check.plugin:Certificate', 'admin']], function () {

    Route::group(['prefix' => 'settings'], function () {
        Route::get('certificate', 'App\Plugins\Certificate\Http\Controllers\CertificateController@certificateSettings')->name('certificate_settings');
    });
});

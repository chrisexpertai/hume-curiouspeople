<?php

use Illuminate\Support\Facades\Route;
use App\Plugins\MultiInstructor\Http\Controllers\MultiInstructorController;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function() {
    /**
     * Only instructor has access in this group
     */
    Route::group(['middleware' => ['instructor']], function() {
        Route::group(['prefix' => 'courses'], function() {
            Route::group(['prefix' => '{course_id}/instructors'], function() {
                Route::get('/', [MultiInstructorController::class, 'instructorSettings'])->name('edit_course_instructor');
                Route::post('/', [MultiInstructorController::class, 'instructorSettingsPost']);
            });
        });
    });
    Route::post('/course/{id}/multi-instructor-search', 'MultiInstructorController@multiInstructorSearch')->name('multi_instructor_search');
Route::post('/course/{id}/remove-instructor', 'MultiInstructorController@removeInstructor')->name('remove_instructor');

});

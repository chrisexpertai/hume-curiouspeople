<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'plugin/certificate', 'middleware' => ['auth'] ], function() {
    Route::get('{course_id}/download', 'App\Http\Controllers\CertificateController@generateCertificate')->name('download_certificate');
});


Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'admin'] ], function() {

    Route::group(['prefix'=>'settings'], function() {
        Route::get('certificate', 'App\Http\Controllers\CertificateController@certificateSettings')->name('certificate_settings');
    });
});

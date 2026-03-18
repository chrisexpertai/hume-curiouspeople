@extends('layouts.full')

@section('content')



    <!-- Your content goes here -->
    <div id="content" style="display: block;">


        @include(theme('partials.lheader'), ['content' => $lecture])

        <div class="piksera-course-player-content">

            @include(theme('partials.content-navigation'), ['content' => $lecture])



            @if ($isOpen)

                <div class="piksera-course-player-content__wrapper">
                    <div class="piksera-course-player-content__header ">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="piksera-course-player-lesson">
                        <div class="piksera-course-player-lesson-video">

                            @if ($lecture->video_info())
                                @include(theme('partials.player'), ['model' => $lecture])
                            @endif


                        </div>
                        {!! clean_html($lecture->text) !!}

                    </div>



                    @if ($lecture->attachments->count())
                        <div class="piksera-course-player-lesson border p-5 rounded-3 mb-5" style="">
                            <h5 class="lecture-attachments-title mb-3">{{ __t('downloadable_materials') }}</h5>
                            @foreach ($lecture->attachments as $attachment)
                                @if ($attachment->media)
                                    <a href="{{ route('attachment_download', $attachment->hash_id) }}"
                                        class="border p-3 rounded-3 lecture-attachment mb-2 d-block">
                                        <i class="far fa-cloud-download mr-2"></i>
                                        {{ $attachment->media->slug_ext }} <small
                                            class="text-muted">({{ $attachment->media->readable_size }})</small>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="col-12">
                        <div class="piksera-course-player-content__wrapper">
                            <div class="piksera-course-player-locked">
                                <h2 class="piksera-course-player-locked__title">{{ __t('lecture_content_locked') }}</h2>
                                @if (!auth()->check())
                                    <p class="lecture-lock-desc mb-4">
                                        {!! sprintf(__t('if_enrolled_login_text'), '<a href="' . route('login') . '" class="open_login_modal">', '</a>') !!}
                                    </p>
                                @endif
                                <div class="piksera-course-player-locked__purchase">
                                    @if (auth()->check() && $lecture->drip->is_lock)
                                        <p>{!! clean_html($lecture->drip->message) !!}</p>
                                    @else
                                        <a href="{{ route('course', $course->slug) }}"
                                            class="btn btn-primary btn-lg">{{ tr('Enroll in Course to Unlock') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>



            @endif





            <span class="piksera-course-player-lesson__submit-trigger"></span>
            @include(theme('partials.bottom-navigation'), ['content' => $lecture])
        </div>



        @if (get_option('lms_options.enable_discussion'))
            @include(theme('partials.discussion'), ['content' => $lecture])
        @endif





        <!-- jquery latest version -->
        {{-- <script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script> --}}
        <script src="{{ asset('assets/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
        <!-- bootstrap js -->
        <script src="/assets/js/bootstrap.bundle.min.js"></script>

        <script src="/assets/plugins/plyr/plyr.js"></script>

        {{-- <script src="/assets/plugins/video-js/video.min.js"></script> --}}
        <script src="/assets/plugins/plyr/plyr.js"></script>

        {{-- < {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}


        </script>


        <script>
            "use strict";

            (function($) {
                $(document).ready(function() {
                    var currentUrl = window.location.href;
                    var url = new URL(currentUrl);

                    // Check for URL parameter to open curriculum sidebar
                    if (url.searchParams.has('curriculum_open') || localStorage.getItem('curriculum_open') ===
                        'yes') {
                        showCurriculumSidebar();
                    }

                    // Curriculum Toggle
                    $('[data-id="piksera-curriculum-switcher"]').click(function() {
                        toggleCurriculumSidebar();
                    });

                    // Curriculum Mobile Close
                    $('.piksera-course-player-curriculum__mobile-close').click(function() {
                        hideCurriculumSidebar();
                    });

                    // Other curriculum-related actions
                    // ...

                    // Discussions Initialization
                    if (url.searchParams.has('discussions_open') || localStorage.getItem('discussions_open') ===
                        'yes') {
                        // Code to handle discussions visibility
                    }

                    // Discussions Toggle
                    $('.piksera-course-player-header__discussions').click(function() {
                        // Code to toggle discussions visibility
                    });



                    // Function to show curriculum sidebar
                    function showCurriculumSidebar() {
                        // Code to show curriculum sidebar
                        // For example:
                        $('.piksera-course-player-curriculum').addClass('piksera-course-player-curriculum_open');
                        $('.piksera-course-player-content').addClass('piksera-course-player-content_open-sidebar');
                    }

                    // Function to hide curriculum sidebar
                    function hideCurriculumSidebar() {
                        // Code to hide curriculum sidebar
                        // For example:
                        $('.piksera-course-player-curriculum').removeClass('piksera-course-player-curriculum_open');
                        $('.piksera-course-player-content').removeClass(
                            'piksera-course-player-content_open-sidebar');
                    }

                    // Function to toggle curriculum sidebar
                    function toggleCurriculumSidebar() {
                        if ($('.piksera-course-player-curriculum').hasClass(
                                'piksera-course-player-curriculum_open')) {
                            hideCurriculumSidebar();
                        } else {
                            showCurriculumSidebar();
                        }
                    }
                });
            })(jQuery);
        </script>

        {{-- <script src="/themes/Helsinki/assets/js/dark.js"></script> --}}
        <script src="{{ asset('assets/js/dark.js') }}"></script>
        <script src="{{ asset('assets/js/dark-jr.js') }}"></script>
        {{-- <script src="/themes/Helsinki/assets/js/dark-jr.js"></script> --}}
    @endsection

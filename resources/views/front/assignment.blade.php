@extends(('layouts.full'))

@section('content')
@include(('front.partials.assignmentheader'), ['content' => $assignment])

<style>
    .piksera-course-player-content__header {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 860px;
        margin: 27px 0 -18px;
    }</style>

<div class="piksera-course-player-content">

    @include(theme('partials.content-navigation'), ['content' => $assignment])



      <div class="piksera-course-player-content__wrapper">
        <div class="piksera-course-player-content__header ">
            <span class="piksera-course-player-content__header-lesson-type"> {{$course->title}}  </span>
            <h1>{{$title}}</h1>

        <div class="piksera-course-player-assignments">

        </div>
            @php
            $upload_attachment_limit = (int) $assignment->option('upload_attachment_limit');
        @endphp

            @if($isEnrolled)

                @if($has_submission && $has_submission->status === 'submitted')
                    <div class="border rounded-4 p-3 my-4">

                        @if($has_submission->is_evaluated)
                            <div class="piksera-course-player-quiz__result">
                                <h5 class="mt-2"><i class="la la-check-circle-o"></i> {{__t('submission_evaluated')}} </h5>
                                <p class=" m-0"> {{__t('evaluated_by')}}: <strong>{{$has_submission->instructor->name}}</strong></p>
                                <p class=""> Evaluated at: {{$has_submission->evaluated_at->format(date_time_format())}}</p>
                            </div>

                            <div class="submission-result-wrap text-center my-5">
                                <h4>You got <span class="text-info">{{$has_submission->earned_numbers}}</span> numbers from {{$assignment->option('total_number')}} </h4>

                                @php
                                $earned_parcent = $has_submission->earned_numbers * 100 / $assignment->option('total_number');

                                $is_passed = $has_submission->earned_numbers >= $assignment->option('pass_number');

                                $border_color = 'warning';
                                if ($is_passed){
                                    $border_color = 'success';
                                }
                                @endphp

                                <!-- Progress bar -->
                                <div class="overflow-hidden my-4">
                                    <h6 class="mb-0">{{$earned_parcent}}%</h6>
                                    <div class="progress progress-lg bg-primary bg-opacity-10">
                                        <div class="progress-bar bg-primary aos aos-init aos-animate" role="progressbar" data-aos="slide-right" data-aos-delay="200" data-aos-duration="1000" data-aos-easing="ease-in-out" style="width: {{$earned_parcent}}%" aria-valuenow="{{$earned_parcent}}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>


                                <!-- Progress END -->

                                <h5>Result :
                                    @if($is_passed)
                                        <span class="text-success">Passed</span>
                                    @else
                                        <span class="text-danger">{{ tr('Success') }}</span>
                                    @endif
                                </h5>
                            </div>

                        @else

                            <h4 class="mb-3"><i class="la la-check-circle"></i> {{ tr('You have submitted assignment.') }}</h4>

                            <div class="alert alert-warning">
                                <i class="la la-exclamation-triangle"></i> {{__t('submission_not_valuated_text')}}
                            </div>

                        @endif

                        <div class="assignment-submitted-was-wrap">

                            @if($has_submission->text_submission)
                                <h5>{{__t('submission_text')}}</h5>

                                <div class="assignment-submission-text-wrap border p-3 my-3">
                                    {!! clean_html($has_submission->text_submission) !!}
                                </div>
                            @endif

                            @if($has_submission->attachments->count())
                                <div class="lecture-attachments border p-3 mt-4">
                                    <h5 class="lecture-attachments-title mb-3">{{__t('attachments')}}</h5>
                                    @foreach($has_submission->attachments as $attachment)
                                        @if($attachment->media)
                                            <a href="{{route('attachment_download', $attachment->hash_id)}}" class="lecture-attachment mb-2 d-block">
                                                <i class="la la-cloud-download mr-2"></i>
                                                {{$attachment->media->slug_ext}} <small class="">({{$attachment->media->readable_size}})</small>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>

                @endif






                @if($has_submission)

            </div>

                    @if($has_submission->status === 'submitting')

                        @php
                            $upload_attachment_limit = (int) $assignment->option('upload_attachment_limit');
                        @endphp




                            <form action="{{route('single_assignment', [$course->slug, $assignment->id])}}" method="post">
                                @csrf
                                     <div class="piksera-course-player-content__header ">

                                        <div class="piksera-course-player-assignments__content">
                                            @if($assignment->text)
                                             {!! clean_html($assignment->text) !!}
                                        @endif



                                        </div>
                                    </div>

                                     <div class="piksera-course-player-assignments">
                                        <div class="piksera-course-player-assignments__task">
                                            <span class="piksera-course-player-assignments__task-button piksera-course-player-assignments__task-button_rotate"> {{ tr('Submission') }} </span>
                                            <div class="piksera-course-player-assignments__task-content" style="display: block;">

                                                <textarea name="assignment_text" id="assignment_text" class="form-control" rows="10"></textarea>
                                                <small class="form-text  my-3">{{__t('text_submission_desc')}}</small>

                                            </div>
                                        </div>


                                        <div class="piksera-course-player-assignments__edit" data-editor="piksera_course_player_assignments__53573">

                                     </div>
                                     @if($assignment->attachments->count())

                        <div class="piksera-course-player-lesson border p-5 rounded-3 mb-5">
                            <h5 class="lecture-attachments-title mb-3">{{__t('downloadable_materials')}}</h5>
                            @foreach($assignment->attachments as $attachment)
                                @if($attachment->media)
                                    <a href="{{route('attachment_download', $attachment->hash_id)}}" class="lecture-attachment mb-2 d-block">
                                        <i class="la la-cloud-download mr-2"></i>
                                        {{$attachment->media->slug_ext}} <small class="">({{$attachment->media->readable_size}})</small>
                                    </a>
                                @endif
                            @endforeach
                        </div>

                    @endif


                                     @if($upload_attachment_limit)
                                     <div class="form-group">
                                         <label for="assignment_attachments">{{__t('attach_assignment_files')}}</label>

                                         <div class="row">
                                             @for($i = 1; $i<= $upload_attachment_limit; $i++)
                                             <div class="col-md-12 col-lg-12 mb-4 m-3 border rounded-4">
                                                 <div class="card assignment-attachment-wrap">
                                                     <div class="card-body">
                                                         {!! media_upload_form('assignment_attachments[]', '<i class="la la-paperclip"></i>'. __t('attach_file')) !!}
                                                     </div>
                                                 </div>
                                             </div>
                                             @endfor
                                         </div>
                                     </div>
                                     @endif
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">{{__t('submit_assignment')}}</button>
                                    </div>
                            </form>

                        </div>

                        </div>


                    @endif

                @else





                    <div class="piksera-course-player-content__wrapper">
                        <div class="piksera-course-player-content__header ">

                        </div>
                        <div class="piksera-course-player-assignments">
                           <div class="piksera-course-player-assignments__content">
                            <form action="{{route('single_assignment', [$course->slug, $assignment->id])}}" method="post">
                                @csrf

                                <button type="submit" class="btn btn-primary">{{__t('ready_submit_assignment')}}</button>
                            </form>

                           </div>

                        </div>

                @endif

            @else

                <div class="lecture-contents-locked text-center mt-5">
                    <div class="lecture-lock-icon mb-4">
                        <i class="la la-lock"></i>
                    </div>
                    <h4 class="lecture-lock-title mb-4">{{__t('content_locked')}}</h4>

                    @if( ! auth()->check())
                        <p class="lecture-lock-desc mb-4">
                            {!! sprintf(__t('if_enrolled_login_text'), '<a href="'.route('login').'" class="open_login_modal">', '</a>') !!}
                        </p>
                    @endif

                    <a href="{{route('course', $course->slug)}}" class="btn btn-primary btn-lg">{{ tr('Enroll in Course to Unlock') }}</a>
                </div>

            @endif



        </div>

        @if(get_option('lms_options.enable_discussion'))
        @endif
    </div>
        @include(theme('partials.bottom-navigation'), ['content' => $assignment])
    </div>





    < {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}



<script src="/assets/vendor/ckeditor5/ckeditor.js"></script>

<script>
    ClassicEditor
        .create( document.querySelector('#assignment_text'), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    'subscript',
                    'superscript',
                    '|',
                    'fontColor',
                    'fontBackgroundColor',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'alignment',
                    '|',
                    'link',
                    'blockQuote',
                    'insertTable',
                    '|',
                    'undo',
                    'redo',
                    '|',
                    'highlight',
                    'horizontalLine',
                    '|',
                    'imageUpload',
                    'mediaEmbed',
                    '|',
                    'removeFormat',
                    'code',
                    'codeBlock',
                    '|',
                    'indent',
                    'outdent',
                    '|',
                    'htmlEmbed',
                    'specialCharacters'
                ],
                shouldNotGroupWhenFull: true
            },
            language: 'en',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:full',
                    'imageStyle:side',
                    'linkImage'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
            licenseKey: '',
        } )
        .then(editor => {
            const toolbarContainer = document.querySelector('main .toolbar-container');
            toolbarContainer.prepend(editor.ui.view.toolbar.element);
            window.editor = editor;
        })
        .catch(err => {
            console.error(err.stack);
        });
</script>




<link rel="stylesheet" href="/themes/Helsinki/assets/css/course.css">





<!-- jquery latest version -->
<script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
<!-- bootstrap js -->
<script src="/assets/js/bootstrap.bundle.min.js"></script>

<script src="/assets/plugins/plyr/plyr.js"></script>

<script src="/assets/plugins/video-js/video.min.js"></script>
         <script src="/assets/plugins/plyr/plyr.js"></script>

       < {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}


</script>


<script> "use strict";

    (function ($) {
        $(document).ready(function () {
            var currentUrl = window.location.href;
            var url = new URL(currentUrl);

            // Check for URL parameter to open curriculum sidebar
            if (url.searchParams.has('curriculum_open') || localStorage.getItem('curriculum_open') === 'yes') {
                showCurriculumSidebar();
            }

            // Curriculum Toggle
            $('[data-id="piksera-curriculum-switcher"]').click(function () {
                toggleCurriculumSidebar();
            });

            // Curriculum Mobile Close
            $('.piksera-course-player-curriculum__mobile-close').click(function () {
                hideCurriculumSidebar();
            });

            // Other curriculum-related actions
            // ...

            // Discussions Initialization
            if (url.searchParams.has('discussions_open') || localStorage.getItem('discussions_open') === 'yes') {
                // Code to handle discussions visibility
            }

            // Discussions Toggle
            $('.piksera-course-player-header__discussions').click(function () {
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
                $('.piksera-course-player-content').removeClass('piksera-course-player-content_open-sidebar');
            }

            // Function to toggle curriculum sidebar
            function toggleCurriculumSidebar() {
                if ($('.piksera-course-player-curriculum').hasClass('piksera-course-player-curriculum_open')) {
                    hideCurriculumSidebar();
                } else {
                    showCurriculumSidebar();
                }
            }
        });
    })(jQuery);</script>

<script  src="/themes/Helsinki/assets/js/dark.js"></script>
<script src="/themes/Helsinki/assets/js/dark-jr.js"></script>
@endsection

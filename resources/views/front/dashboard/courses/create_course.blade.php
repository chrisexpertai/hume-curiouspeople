@extends('front.dashboard.courses.layout')

@section('content')
    @php
        $videoSrc = old('video_source');
    @endphp

    <style>
        .border {
            border: none !important;
        }


        .bord {
            border: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(255, 255, 255);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .piksera-loader {
            border: 2px solid rgba(0, 0, 0, .5);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            position: relative;
            animation: expandAndInvisible 3s ease-out infinite;
            margin: 20px auto;
        }

        @keyframes expandAndInvisible {

            0%,
            100% {
                transform: scale(0.8);
                opacity: 0.8;
            }

            50% {
                transform: scale(1);
                opacity: 0;
            }
        }
    </style>
    <!-- =======================
                                                                                                                                                                                                                                                                                                                                                            Page Banner START -->
    <div class="py-0 h-100px align-items-center d-flex h-200px rounded-0"
        style="background:url(/assets/images/pattern/04.png) no-repeat center center; background-size:cover;">
        <!-- Main banner background image -->
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <!-- Title -->
                    <h1 class="">{{ tr('Submit a new Course') }}</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- =======================
                                                                                                                                                                                                                                                                                                                                                            Page Banner END -->

    <!-- Step Buttons END -->
    <div class="container">
        <div class="bg-transparent bord rounded-5 mb-5">


            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    @csrf
                                    <div class="mt-3 mb-3">
                                        <h5 class="mb-3 mt-5">{{ tr('Course Title') }}</h5>

                                        <input type="text" name="title" class="form-control" id="title"
                                            placeholder="{{ __t('course_title_eg') }}" value="{{ old('title') }}"
                                            maxlength="120">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-">
                                        <label for="short_description">{{ __t('short_description') }}</label>
                                        <div class="input-group">
                                            <textarea name="short_description" id="short_description" class="form-control"
                                                placeholder="{{ __t('course_short_desc_eg') }}" data-maxlength="191"></textarea>
                                            <div class="input-group-append">
                                                <span class="input-group-text">191</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="thumbnail"
                                            class="form-label"><strong>{{ tr('Course Thumbnail') }}</strong></label>
                                        {!! image_upload_form('thumbnail_id', null, [650, 450], ['class' => 'form-control']) !!}
                                        <small class="form-text text-muted">{{ __t('course_img_guide') }}</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="level" class="form-label">{{ tr('Course Level') }}</strong></label>
                                        <select name="level" class="form-select">
                                            @foreach (course_levels() as $key => $level)
                                                <option value="{{ $key }}" {{ selected(1, $key) }}>
                                                    {{ $level }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label">{{ tr('Category') }}</strong></label>
                                        <select name="category_id" class="form-select">
                                            <option value="">{{ __t('select_category') }}</option>
                                            @foreach ($categories as $category)
                                                <optgroup label="{{ $category->category_name }}">
                                                    @foreach ($category->sub_categories as $sub_category)
                                                        <option value="{{ $sub_category->id }}">
                                                            {{ $sub_category->category_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <h5>{{ tr('Upload Intro') }}</h5>
                                        <div class="lecture-video-upload-wrap mb-5">
                                            @php
                                                $videoSrc = old('video_source');
                                            @endphp

                                            <label>{{ __t('intro_video') }}</label>

                                            <select name="video[source]"
                                                class="w-100 lecture_video_source form-control mb-2">
                                                <option value="-1">{{ tr('Select Video Source') }}</option>
                                                <option value="html5" {{ selected($videoSrc, 'html5') }}>
                                                    {{ tr('Upload Video') }}</option>
                                                <option value="external_url" {{ selected($videoSrc, 'external_url') }}>
                                                    {{ tr('URL') }}</option>
                                                <option value="youtube" {{ selected($videoSrc, 'youtube') }}>
                                                    {{ tr('YouTube') }}</option>
                                                <option value="vimeo" {{ selected($videoSrc, 'vimeo') }}>
                                                    {{ tr('Vimeo') }}</option>
                                                <option value="embedded" {{ selected($videoSrc, 'embedded') }}>
                                                    {{ tr('Embedded') }}</option>
                                            </select>

                                            <p class="video-file-type-desc">
                                                <small
                                                    class="text-muted">{{ tr('Select your preferred video type. (.mp4, YouTube, Vimeo etc.)') }}
                                                </small>
                                            </p>

                                            <div class="video-source-input-wrap mb-5"
                                                style="display: {{ $videoSrc ? 'block' : 'none' }};">

                                                <div class="video-source-item video_source_wrap_html5 bord rounded-4 bg-white p-4"
                                                    style="display: {{ $videoSrc == 'html5' ? 'block' : 'none' }};">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="video-upload-wrap text-center">
                                                                <i class="bi bi-cloud-upload text-muted display-4"></i>
                                                                <h5 class="mt-3">{{ __t('upload_video') }}</h5>
                                                                <p class="mb-2">{{ __t('file_format') }}:
                                                                    <strong>.mp4</strong>
                                                                </p>
                                                                {!! media_upload_form('video[html5_video_id]', __t('upload_video'), null) !!}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="video-poster-upload-wrap text-center">
                                                                <i class="bi bi-image text-muted display-4"></i>
                                                                <h5 class="mt-3">{{ __t('video_poster') }}</h5>
                                                                <p class="text-muted mb-3">{{ __t('poster_size') }}:
                                                                    <strong>{{ tr('600x450') }}</strong>
                                                                    {{ tr('pixels') }}. {{ __t('supports') }}:
                                                                    <strong>jpg</strong>, <strong>jpeg</strong>,
                                                                    {{ __t('or') }} <strong>png</strong>
                                                                </p>
                                                                {!! image_upload_form('video[html5_video_poster_id]') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="video-source-item video_source_wrap_external_url"
                                                style="display: {{ $videoSrc == 'external_url' ? 'block' : 'none' }};">
                                                <input type="text" name="video[source_external_url]" class="form-control"
                                                    value="" placeholder="External Video URL">
                                            </div>
                                            <div class="video-source-item video_source_wrap_youtube"
                                                style="display: {{ $videoSrc == 'youtube' ? 'block' : 'none' }};">
                                                <input type="text" name="video[source_youtube]" class="form-control"
                                                    value="" placeholder="YouTube Video URL">
                                            </div>
                                            <div class="video-source-item video_source_wrap_vimeo"
                                                style="display: {{ $videoSrc == 'vimeo' ? 'block' : 'none' }};">
                                                <input type="text" name="video[source_vimeo]" class="form-control"
                                                    value="" placeholder="Vimeo Video URL">
                                            </div>
                                            <div class="video-source-item video_source_wrap_embedded"
                                                style="display: {{ $videoSrc == 'embedded' ? 'block' : 'none' }};">
                                                <textarea name="video[source_embedded]" class="form-control" placeholder="Place your embedded code here"></textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="la la-save"></i>
                                            {{ __t('create_course') }}</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="{{ asset('assets/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/filemanager/filemanager.js') }}"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    setTimeout(function() {
                        if (document.getElementById("overlay"))
                            document.getElementById("overlay").style.display = "none";;
                        if (document.getElementById("content"))
                            document.getElementById("content").style.display = "none";
                    }, 700);
                });
                $(document).ready(function() {
                    $(".custom-dropdown-body").hide();
                    $(".custom-dropdown-toggle").click(function() {
                        $(".custom-dropdown-body").toggle();
                    });
                    $(document).on("click", function(event) {
                        if (!$(event.target).closest(".custom-dropdown").length) {
                            $(".custom-dropdown-body").hide();
                        }
                    });
                });
            </script>
        </div>
    </div>
@endsection

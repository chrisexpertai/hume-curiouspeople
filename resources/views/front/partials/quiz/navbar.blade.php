@php
    $previous = $content->previous;
    $next = $content->next;
    $is_completed = false;
    if ($auth_user && $content->is_completed) {
        $is_completed = true;
    }
@endphp




<body
    class="blog [data-bs-theme=dark] .navbar logged-in stm_lms_button theme-piksera woocommerce-js skin_custom_color marketplace piksera-theme elementor-default elementor-kit-4">

    <header class="piksera-course-player-header header">
        <div class="piksera-course-player-header__back">
            <a href="{{ route('course', $course->slug) }}" class="piksera-back-link"
                data-id="piksera-course-player-back"></a>
        </div>
        <div class="piksera-course-player-header__curriculum">
            <span class="piksera-switch-button" data-id="piksera-curriculum-switcher">
                <div class="piksera-switch-button__burger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="piksera-switch-button__title">{{ tr('Curriculum') }}</span>
            </span>
        </div>
        <div class="piksera-course-player-header__course">
            <span class="piksera-course-player-header__course-label"></span>
            <a class="piksera-course-player-header__course-title">{{ $course->title }} </a>
        </div>
        <div class="piksera-course-player-header__navigation"></div>



        @if ($quiz->option('show_time'))
            <div class="piksera-course-player-header__quiz-timer">
                <div class="piksera-course-player-quiz-timer piksera-course-player-quiz-timer_started"
                    data-text-days="Days" data-text-hours="Hours">
                    <div class="piksera-course-player-quiz-timer__title p-1">
                    </div>
                    <div class="piksera-course-player-quiz-timer__content" id="countdown-container">
                        <div id="countdown-hours" class="piksera-course-player-quiz-timer__hours"></div>
                        <span data-id="hours"
                            class="piksera-course-player-quiz-timer__separator piksera-course-player-quiz-timer__separator_show">:</span>
                        <div id="countdown-minutes" class="piksera-course-player-quiz-timer__minutes"></div>
                        <span data-id="minutes"
                            class="piksera-course-player-quiz-timer__separator piksera-course-player-quiz-timer__separator_show">:</span>
                        <div id="countdown-seconds" class="piksera-course-player-quiz-timer__seconds"></div>
                    </div>
                </div>
            </div>
        @endif



        <div class="bg-light dark-mode-switch theme-icon-active d-flex align-items-center p-1 rounded mt-2">
            <button type="button" class="btn btn-sm mb-0" data-bs-theme-value="light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-sun fa-fw mode-switch" viewBox="0 0 16 16">
                    <path
                        d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z">
                    </path>
                    <use href="#"></use>
                </svg></button>
            <button type="button" class="btn btn-sm mb-0 active" data-bs-theme-value="dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-moon-stars fa-fw mode-switch" viewBox="0 0 16 16">
                    <path
                        d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z">
                    </path>
                    <path
                        d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
                    </path>
                    <use href="#"></use>
                </svg> </button>

        </div>


        <div class="piksera-course-player-header__discussions" style="transition: all 0.3s linear 0s;">
            <span class="piksera-course-player-header__discussions-toggler">
                <span class="piksera-course-player-header__discussions-toggler__title"> Discussions </span>
            </span>
            <span class="piksera-course-player-header__discussions-close-btn"></span>
        </div>

        </div>


        {{-- <script src="{{ asset('assets/js/filemanager.js') }}"></script> --}}
        <script src="{{ asset('assets/plugins/ckeditor5d/ckeditor.js') }}"></script>
        <script>
            const textarea = document.querySelector('#assignment_text');
            var editor;

            DecoupledEditor
                .create(document.querySelector('#assignment_text'))
                .then(neweditor => {
                    const toolbarContainer = document.querySelector('.toolbar-container');
                    editor = neweditor;
                    toolbarContainer.appendChild(editor.ui.view.toolbar.element);

                })
                .catch(error => {
                    console.error(error);
                });

            // $(document).on("click", function (){

            //     textarea.value = editor.getData();
            //     document.getElementById('editform').submit();
            // })
            // document.getElementById('savedoc').onclick = () => {
            //     textarea.value = editor.getData();
            //     document.getElementById('editform').submit();
            // }
        </script>

        <script>
            window.initialDarkMode = @json(session('dark_mode', false));
        </script>
        <style>
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: var(--bs-body-bg) display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }

            .pix-loader {
                border: 3px solid rgb(255 255 255 / 50%);
                border-radius: 50%;
                width: 45px;
                height: 45px;
                position: relative;
                animation: expandAndInvisible 0s ease-out infinite;
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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Simulate loading delay (you can replace this with actual loading logic)
                setTimeout(function() {
                    // Hide loader
                            if (document.getElementById("overlay"))
                        document.getElementById("overlay").style.display = "none";;

                    // Display content
                     if (document.getElementById("content"))
                        document.getElementById("content").style.display = "block";
                }, 500); // Adjust the delay time as needed
            });
        </script>

        <!-- main js -->



        @section('page-js')
        @endsection

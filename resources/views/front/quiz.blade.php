@extends('layouts.full')

@section('content')
    @php
        $auth_user = auth()->user();

    @endphp


    <script defer src="/assets/js/quiz.js"></script>

    <link rel="stylesheet" type="text/css" href="/assets/css/deleting.css">



    <!-- Your content goes here -->
    <div id="content" style="display: block;">

        @include(theme('partials.lheader'), ['content' => $quiz])


        <div class="piksera-course-player-content">

            @include(theme('partials.content-navigation'), ['content' => $quiz])





            <div class="piksera-course-player-content__wrapper">

                @if ($isEnrolled)
                    <div class="piksera-course-player-content__header piksera-course-player-content__header_quiz">
                        <span class="piksera-course-player-content__header-lesson-type"> {{ $course->title }} </span>
                        <h1>{{ $title }}</h1>
                    </div>
                    @php
                        $attempt = $auth_user->get_attempt($quiz->id);
                    @endphp

                    @if (!$attempt || $attempt->status !== 'finished')
                        <div class="piksera-course-player-quiz ">
                            <div class="piksera-course-player-quiz__content">
                                {!! clean_html($quiz->text) !!}
                            </div>
                            <ul class="piksera-course-player-quiz__content-meta">
                                <li
                                    class="piksera-course-player-quiz__content-meta-item piksera-course-player-quiz__content-meta-item_questions">
                                    Questions count: <span class="piksera-course-player-quiz__content-meta-item-title">
                                        {{ $quiz->option('questions_limit') }} </span>
                                </li>
                                <li
                                    class="piksera-course-player-quiz__content-meta-item piksera-course-player-quiz__content-meta-item_grade">
                                    Passing grade: <span class="piksera-course-player-quiz__content-meta-item-title">
                                        {{ $quiz->option('passing_score') }}% </span>
                                </li>
                                <li
                                    class="piksera-course-player-quiz__content-meta-item piksera-course-player-quiz__content-meta-item_duration">
                                    Time limit: <span class="piksera-course-player-quiz__content-meta-item-title">
                                        {{ $quiz->option('time_limit') }} Minutes </span>
                                </li>
                            </ul>
                    @endif

                    @if ($attempt)
                        @if ($attempt->status == 'started')
                            <div class="piksera-course-player-quiz mt-4">
                                <div class="piksera-course-player-quiz__content">
                                </div>
                                <div class="alert p-3 alert-warning">
                                    Your quiz has been started
                                </div>
                                <div id="start-quiz-btn-wrapper" class="mt-2">
                                    <button id="btn-start-quiz" class="btn btn-success btn-lg"
                                        data-quiz-id="{{ $quiz->id }}">
                                        <i class="la la-play-circle"></i> {{ tr('Continue Quiz') }}
                                    </button>
                                </div>
                        @endif

                        @if ($attempt->status === 'in_review')
                            <div class="p-5 border d-flex bg-light mt-4 rounded-4 quiz-submitted-alert" style="">

                                <div>
                                    <h3>{{ tr('Quiz result in review') }}</h3>
                                    <p>{{ tr('You\'ve submitted this quiz and result is in review, and instructor will review your result soon, after that, you can see your result.') }}
                                    </p>
                                </div>
                        @endif

                        @if ($attempt->status === 'finished')
                            @php
                                $passing_percent = (int) $attempt->quiz->option('passing_score');

                                $passing_score = 0;
                                if ($passing_percent) {
                                    $passing_score =
                                        ($attempt->total_scores * $attempt->quiz->option('passing_score')) / 100;
                                }
                                $totalCorrectAnswers = $attempt->answers()->where('is_correct', true)->count();
                            @endphp

                            <div class="piksera-course-player-quiz piksera-course-player-quiz_show-answers">
                                <div class="piksera-course-player-quiz__result-container">
                                    <div class="piksera-course-player-quiz__result ">
                                        <h2 class="piksera-course-player-quiz__result-title">Result</h2>
                                        <div class="piksera-course-player-quiz__result-wrapper">
                                            @if ($attempt->earned_percent >= $passing_percent)
                                                <span class="piksera-course-player-quiz__result-progress">
                                                    {{ number_format($attempt->earned_percent, 0) }} %
                                                </span>
                                            @else
                                                <span class="piksera-course-player-quiz__result-progress" style="--bs-text-opacity: 1;color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity)) !important;">
                                                    {{ number_format($attempt->earned_percent, 0) }} %
                                                </span>
                                            @endif

                                            <div class="piksera-course-player-quiz__result-info"> <span
                                                    class="piksera-course-player-quiz__result-answers">
                                                    <strong>{{ $totalCorrectAnswers }}</strong> out of<strong>{{ $attempt->questions_limit }}</strong> questions answered correctly
                                                    @if ($attempt->earned_percent >= $passing_percent)
                                                        <p class="mb-1 text-success">
                                                            <strong> 
                                                                <i class="la la-check-circle"></i>
                                                                {{ tr('Passed') }}
                                                            </strong> 
                                                        </p>
                                                    @else
                                                        <p class="mb-1 text-danger"> 
                                                            <strong>
                                                                <i class="la la-exclamation-circle"></i>
                                                                {{ tr('Failed') }}
                                                            </strong>
                                                        </p>
                                                    @endif
                                                    <span class="piksera-course-player-quiz__result-passing-grade">
                                                        {{ tr('Passing grade:') }} {{ $passing_percent }}% </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!function_exists('getAnswerStatusClass'))
                                    @php
                                        function getAnswerStatusClass($result, $option)
                                        {
                                            $chosenAnswer = json_decode($result->answer, true);

                                            if (
                                                $result->is_correct &&
                                                is_array($chosenAnswer) &&
                                                in_array($option->id, $chosenAnswer)
                                            ) {
                                                return 'piksera-course-player-answer piksera-course-player-answer_correct piksera-course-player-answer_show-answers';
                                            } elseif (
                                                !$result->is_correct &&
                                                is_array($chosenAnswer) &&
                                                in_array($option->id, $chosenAnswer)
                                            ) {
                                                return 'piksera-course-player-answer piksera-course-player-answer_wrong piksera-course-player-answer_show-answers';
                                            } elseif ($result->is_correct && $chosenAnswer == $option->id) {
                                                return 'piksera-course-player-answer piksera-course-player-answer_correct piksera-course-player-answer_show-answers';
                                            } elseif (!$result->is_correct && $chosenAnswer == $option->id) {
                                                return 'piksera-course-player-answer piksera-course-player-answer_wrong piksera-course-player-answer_show-answers';
                                            } else {
                                                return 'piksera-course-player-answer piksera-course-player-answer_show-answers';
                                            }
                                        }
                                    @endphp
                                @endif

                                @if (!function_exists('getAnswerStatusIcon'))
                                    @php
                                        function getAnswerStatusIcon($result, $option)
                                        {
                                            $chosenAnswer = json_decode($result->answer, true);

                                            if (
                                                $result->is_correct &&
                                                is_array($chosenAnswer) &&
                                                in_array($option->id, $chosenAnswer)
                                            ) {
                                                return '<span class="piksera-correctly"></span>';
                                            } elseif (
                                                !$result->is_correct &&
                                                is_array($chosenAnswer) &&
                                                in_array($option->id, $chosenAnswer)
                                            ) {
                                                return '<span class="piksera-wrongly"></span>';
                                            } elseif ($result->is_correct && $chosenAnswer == $option->id) {
                                                return '<span class="piksera-correctly"></span>';
                                            } elseif (!$result->is_correct && $chosenAnswer == $option->id) {
                                                return '<span class="piksera-wrongly"></span>';
                                            } else {
                                                return '';
                                            }
                                        }
                                    @endphp
                                @endif


                                @php
                                    $results = $attempt->answers()->with('question')->get();

                                @endphp

                                @if ($results->count())
                                    <div class="piksera-course-player-quiz__form mt-4">
                                        @foreach ($results as $result)
                                            @php
                                                $question = $result->question;
                                                $qtype = $question->type;
                                            @endphp

                                            <div class="piksera-course-player-question"
                                                data-number-question="{{ $question->id }}">
                                                <div class="piksera-course-player-question__header">
                                                    <h3 class="piksera-course-player-question__title">
                                                        {{ $question->title }}</h3>
                                                </div>
                                                <div class="piksera-course-player-question__content">
                                                    @if ($qtype === 'radio' || $qtype === 'checkbox')
                                                        @foreach ($question->options as $option)
                                                            <div class="{{ getAnswerStatusClass($result, $option) }}">
                                                                <div class="piksera-course-player-answer__input">
                                                                    <input type="{{ $qtype }}" disabled
                                                                        @if (is_array(json_decode($result->answer, true)) && in_array($option->id, json_decode($result->answer, true))) checked @endif>

                                                                    <span
                                                                        class="piksera-course-player-answer__radio"></span>
                                                                </div>
                                                                <div class="piksera-course-player-answer__wrapper">
                                                                    <div class="piksera-course-player-answer__text">
                                                                        {{ $option->title }}</div>
                                                                    <div class="piksera-course-player-answer__status">
                                                                        {!! getAnswerStatusIcon($result, $option) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @elseif($qtype === 'text')
                                                        @if ($result->is_correct)
                                                            <div
                                                                class="piksera-course-player-answer piksera-course-player-answer_correct piksera-course-player-answer_show-answers">
                                                                <div class="piksera-course-player-answer__input">
                                                                    <input type="checkbox" disabled="" checked="">
                                                                    <span
                                                                        class="piksera-course-player-answer__radio"></span>
                                                                </div>
                                                                <div class="piksera-course-player-answer__wrapper">
                                                                    <div class="piksera-course-player-answer__text">
                                                                        {{ $result->answer }}</div>
                                                                    <div class="piksera-course-player-answer__status">
                                                                        <span class="piksera-correctly"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="piksera-course-player-answer piksera-course-player-answer_wrong piksera-course-player-answer_show-answers">
                                                                <div class="piksera-course-player-answer__input">
                                                                    <input type="radio" disabled="">
                                                                    <span
                                                                        class="piksera-course-player-answer__radio"></span>
                                                                </div>
                                                                <div class="piksera-course-player-answer__wrapper">
                                                                    <div class="piksera-course-player-answer__text">
                                                                        {{ $result->answer }}</div>
                                                                    <div class="piksera-course-player-answer__status">
                                                                        <span class="piksera-wrongly"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @elseif($qtype === 'textarea')
                                                        @if ($result->is_correct)
                                                            <div
                                                                class="piksera-course-player-question__content piksera-course-player-answer piksera-course-player-answer_correct piksera-course-player-answer_show-answers">
                                                                <div class="row w-100">
                                                                    <div class="col-11">
                                                                        <div class="piksera-course-player-fill-the-gap">
                                                                            <div
                                                                                class="piksera-course-player-fill-the-gap__answers">
                                                                                {!! clean_html($result->answer) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div
                                                                            class="piksera-course-player-answer__status text-right">
                                                                            <span class="piksera-correctly"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="piksera-course-player-question__content piksera-course-player-answer piksera-course-player-answer_wrong piksera-course-player-answer_show-answers">
                                                                <div class="row  w-100">
                                                                    <div class="col-11">
                                                                        <div class="piksera-course-player-fill-the-gap">
                                                                            <div
                                                                                class="piksera-course-player-fill-the-gap__answers">
                                                                                {!! clean_html($result->answer) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div
                                                                            class="piksera-course-player-answer__status text-right">
                                                                            <span class="piksera-wrongly"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif


                                <div class="quiz-attempt-time-wrap p-2 border rounded-4 mb-3">
                                    <p class="mb-0">
                                        <strong><i class="la la-clock"></i> Start time:</strong>
                                        {{ \Carbon\Carbon::parse($attempt->created_at)->format(date_time_format()) }},
                                        <strong><i class="la la-clock"></i> End time:</strong>
                                        {{ \Carbon\Carbon::parse($attempt->ended_at)->format(date_time_format()) }},
                                        <span class="text-info">
                                            <strong><i class="la la-clock"></i> {{ tr('Time Required:') }}</strong>
                                            <?php
                                            $hours = 0;
                                            $minutes = 0;
                                            if ($attempt->ended_at && $attempt->created_at) {
                                                $startTime = \Carbon\Carbon::parse($attempt->created_at);
                                                $endTime = \Carbon\Carbon::parse($attempt->ended_at);
                                                $timeDiff = $endTime->diff($startTime);
                                                $hours = $timeDiff->h + $timeDiff->days * 24;
                                                $minutes = $timeDiff->i;
                                            }
                                            ?>
                                            @if ($hours > 0)
                                                {{ $hours }}h
                                            @endif
                                            {{ $minutes }} {{ __t('minutes') }}
                                        </span>
                                    </p>

                        @endif
                    @else
                        <div id="start-quiz-btn-wrapper" class="piksera-course-player-quiz__start-quiz">
                            <a id="btn-start-quiz"
                                class="piksera-button piksera-button_style-primary piksera-button_size-sm piksera-button_icon-"
                                data-quiz-id="{{ $quiz->id }}" style="
                                color: white">
                                <i class="la la-play-circle" style="color:white;"></i> Start Quiz
                            </a>
                    @endif
                @else
                    <div class="lecture-contents-locked text-center mt-5">
                        <div class="lecture-lock-icon mb-4">
                            <i class="la la-lock"></i>
                        </div>
                        <h4 class="lecture-lock-title mb-4">{{ __t('content_locked') }}</h4>
                        @if (!auth()->check())
                            <p class="lecture-lock-desc mb-4">
                                {!! sprintf(__t('if_enrolled_login_text'), '<a href="' . route('login') . '" class="open_login_modal">', '</a>') !!}
                            </p>
                        @endif
                        <a href="{{ route('course', $course->slug) }}"
                            class="btn btn-primary btn-lg">{{ tr('Enroll in Course to Unlock') }}</a>
                    </div>
                @endif
            </div>

        </div>


        <span class="piksera-course-player-lesson__submit-trigger"></span>
        @include(theme('partials.bottom-navigation'), ['content' => $quiz])
    </div>


    @if (get_option('lms_options.enable_discussion'))
        @include(theme('partials.discussion'), ['content' => $quiz])
    @endif

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        /** Start Quiz **/
        $(document).on('click', '#btn-start-quiz', function() {
            var $btn = $(this);
            var quiz_id = $btn.attr('data-quiz-id');
            // console.log("quiz_id: ", {
            //     pageData: pageData.routes.start_quiz,
            //     quiz_id: quiz_id
            // });
            // return;

            $.ajax({
                url: pageData.routes.start_quiz,
                type: 'POST',
                data: {
                    quiz_id: quiz_id,
                    _token: pageData.csrf_token
                },
                beforeSend: function() {
                    $btn.addClass('loader').attr('disabled', 'disabled');
                },
                success: function(response) {
                    if (response.success) {
                        location.href = response.quiz_url
                    }
                },
                complete: function() {
                    $btn.removeClass('loader').removeAttr('disabled');
                }
            });
        });
    </script>
    {{-- <link rel="stylesheet" href="/themes/Helsinki/assets/css/course.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}">
    <link rel="stylesheet" defer="" href="{{ asset('assets/js/course.js') }}">
    {{-- <script defer="" src="/assets/js/course.js"></script> --}}

@endsection

{{-- <link rel="stylesheet" href="/themes/Helsinki/assets/css/quiz.css"> --}}

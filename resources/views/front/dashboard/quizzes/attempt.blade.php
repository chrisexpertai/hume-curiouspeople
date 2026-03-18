@extends('layouts.dashboard')


@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses_has_quiz') }}">{{ __t('courses') }}</a></li>
            <li class="breadcrumb-item"> <a
                    href="{{ route('courses_quizzes', $attempt->course_id) }}">{{ __t('quizzes') }}</a></li>
            <li class="breadcrumb-item"> <a href="{{ route('quiz_attempts', $attempt->quiz_id) }}">{{ __t('quiz_attempts') }}
                </a> </li>
            <li class="breadcrumb-item active">{{ __t('review') }}</li>
        </ol>
    </nav>



    <div class="d-sm-flex">
        <img href="{{ route('profile', $attempt->user->id) }}" class="avatar avatar-lg rounded-circle float-start me-3"
            src="{!! $attempt->user->get_photo !!}" alt="avatar">
        <div class="w-100">
            <div class="mb-3 d-sm-flex justify-content-sm-between align-items-center">
                <div>
                    <h5 class="m-0">{!! $attempt->user->name !!}</h5>
                    <span class="me-3 small">{!! $attempt->created_at !!} </span>
                </div>

            </div>
            <h5><span class="text-body fw-light mb-3">{{ tr('Course:') }} </span> <a href="{{ $attempt->course->url }}"
                    class="text-info" target="_blank">
                    {{ $attempt->course->title }}
                </a></h5>

            <h5><span class="text-body fw-light mt-3">{{ tr('Quiz:') }}</span>
                <a href="{{ $attempt->quiz->url }}">
                    {{ $attempt->quiz->title }}
                </a>
            </h5>
        </div>
    </div>

    @php
        $passing_percent = (int) $attempt->quiz->option('passing_score');

        $passing_score = 0;
        if ($passing_percent) {
            $passing_score = ($attempt->total_scores * $attempt->quiz->option('passing_score')) / 100;
        }
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        @if ($attempt->created_at)
                            <p><i class="bi bi-clock"></i> <strong>{{ tr('Start time:') }}</strong>
                                {{ \Carbon\Carbon::parse($attempt->created_at)->format(date_time_format()) }}</p>
                        @endif

                        @if ($attempt->ended_at)
                            <p><i class="bi bi-clock"></i> <strong>{{ tr('End time:') }}</strong>
                                {{ \Carbon\Carbon::parse($attempt->ended_at)->format(date_time_format()) }}</p>
                        @endif

                        @if ($attempt->ended_at)
                            <p class="text-info"><i class="bi bi-clock"></i> <strong>{{ tr('Time Required:') }}</strong>
                                {{ \Carbon\Carbon::parse($attempt->ended_at)->diffInMinutes(\Carbon\Carbon::parse($attempt->created_at)) }}
                                minutes</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>{{ tr('Total Question:') }}</strong>
                                {{ $attempt->questions_limit }}</li>
                            <li class="list-group-item"><strong>{{ tr('Total Score:') }}</strong>
                                {{ $attempt->total_scores }}</li>
                            <li class="list-group-item"><strong>{{ tr('Total Answered:') }}</strong>
                                {{ $attempt->total_answered }}</li>
                            <li class="list-group-item"><strong>{{ tr('Count on grade?') }}</strong>
                                @if ($attempt->quiz_gradable)
                                    <span class="text-success">{{ tr('Yes') }}</span>
                                @else
                                    <span class="text-muted">{{ tr('No') }}</span>
                                @endif
                            </li>
                            <li class="list-group-item"><strong>{{ tr('Passing Score:') }}</strong> {{ $passing_score }}
                                ({{ $passing_percent }}%)</li>
                            <li class="list-group-item"><strong>{{ tr('Earned Score:') }}</strong>
                                {{ $attempt->earned_scores }} ({{ $attempt->earned_percent }}%)</li>
                            <li class="list-group-item"><strong>{{ tr('Result:') }}</strong>
                                @if ($attempt->earned_percent >= $passing_percent)
                                    <span class="text-success"><i class="bi bi-check-circle"></i>
                                        {{ tr('Passed') }}</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-exclamation-circle"></i>
                                        {{ tr('Failed') }}</span>
                                @endif
                            </li>
                            <li class="list-group-item"><strong>{{ tr('Status:') }}</strong> {!! $attempt->status_html !!}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post">
        @csrf

        @php
            $answers = $attempt->answers()->with('question', 'question.options')->get();
        @endphp

        @if ($answers->count())
            <div class="card border rounded-4 my-4">
                <div class="card-header bg-light text-white">
                    <h4 class="card-title mb-0"><i class="bi bi-info-circle"></i> {{ tr('Review Attempt Information') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ tr('Question') }}</th>
                                    <th>{{ tr('Given Answer') }}</th>
                                    <th>{{ tr('Question Score') }}</th>
                                    <th>{{ tr('Review Score') }}</th>
                                    <th>{{ tr('Correct') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($answers as $answer)
                                    @php
                                        $qtype = $answer->question->type;
                                    @endphp

                                    <tr>
                                        <td>
                                            <span class="badge bg-info"><i class="bi bi-question-{{ $qtype }}"></i>
                                                {{ $qtype }}</span><br>
                                            {{ $answer->question->title }}
                                        </td>
                                        <td class="answer-cell">
                                            @if ($qtype === 'text' || $qtype === 'textarea')
                                                @php
                                                    $maxTextLength = 100;
                                                    $truncatedAnswer = clean_html(
                                                        strlen(strip_tags($answer->answer)) > $maxTextLength
                                                            ? substr(strip_tags($answer->answer), 0, $maxTextLength) .
                                                                '...'
                                                            : $answer->answer,
                                                    );
                                                @endphp
                                                <div class="truncated-text" title="{{ $answer->answer }}">
                                                    {!! nl2br(e($truncatedAnswer)) !!}</div>
                                                @if (strlen(strip_tags($answer->answer)) > $maxTextLength)
                                                    <button type="button" class="btn btn-link btn-sm read-more"
                                                        onclick="showFullAnswer(this)">Read More</button>
                                                    <span class="full-answer"
                                                        style="display: none;">{!! nl2br(clean_html($answer->answer)) !!}</span>
                                                @endif
                                            @elseif($qtype === 'radio' || $qtype === 'checkbox')
                                                @php
                                                    $options = $answer->question->options
                                                        ->pluck('title', 'id')
                                                        ->toArray();
                                                @endphp
                                                @if ($qtype === 'radio' && $answer->answer)
                                                    <p class="mb-0"><i class="bi bi-question-{{ $qtype }}"></i>
                                                        {{ array_get($options, $answer->answer) }}</p>
                                                @elseif($qtype === 'checkbox' && $answer->answer)
                                                    @foreach (json_decode($answer->answer, true) as $answeredKey)
                                                        <p class="mb-0"><i
                                                                class="bi bi-question-{{ $qtype }}"></i>
                                                            {{ array_get($options, $answeredKey) }}</p>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $answer->q_score }}</td>
                                        <td width="50">
                                            <input type="text" class="form-control"
                                                name="answers[{{ $answer->id }}][review_score]"
                                                value="{{ $answer->r_score }}">
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="answers[{{ $answer->id }}][is_correct]" value="1"
                                                    {{ checked(1, $answer->is_correct) }}>
                                                <label class="form-check-label">{{ tr('Correct') }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info" role="alert">{{ tr('No answers found to review this attempt') }}
                        </div>
        @endif

        @if ($attempt)
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                @if ($answers->count())
                    <button type="submit" class="btn btn-primary me-md-2" name="review_btn" value="review"><i
                            class="bi bi-save"></i> {{ tr('Review Quiz Attemp') }}</button>
                @endif
                <button type="submit" class="btn btn-danger" name="review_btn" value="delete"><i class="bi bi-trash"></i>
                    {{ tr('Delete Attempt') }}</button>
            </div>
        @endif
        </div>
        </div>
        </div>
    </form>


    </div>
    <script>
        function showFullAnswer(button) {
            var $button = $(button);
            var $fullAnswer = $button.siblings('.full-answer');

            $button.hide();
            $fullAnswer.show();
        }
    </script>

    <style>
        .answer-cell {
            /* Remove max-width property */
            /* max-width: 400px; */

            word-wrap: break-word;
            /* Allow long words to break and wrap onto the next line */
            vertical-align: top;
            /* Align content to the top of the cell */
            padding: 10px;
            /* Add padding to the cell content */
            border: 1px solid #ccc;
            /* Add a border around the cell */
            white-space: normal;
            /* Allow text to wrap within the cell */
        }
    </style>

@endsection

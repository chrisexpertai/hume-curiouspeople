@extends(('layouts.full'))

@section('content')

<style>
    .piksera-course-player-content__wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        height: 100%;
        background: transparent !important;
        padding: 0;
        overflow: auto;
        position: relative;
        scrollbar-width: none;
        transition: .3s;
    }</style>


<script defer src="/assets/js/quiz.js"></script>

<link rel="stylesheet" type="text/css" href="/assets/css/deleting.css">
<script defer="" src="/assets/js/course.js"></script>

<div class="piksera-course-player-content" style="max-height: 15vh; !important; min-height: 15vh; !important">
    @include(theme('partials.quiz.navbar'), ['content' => $quiz])
    <div class="piksera-course-player-content">
        @include(('front.partials.quiz.sidebar'), ['content' => $quiz])
        <div class="piksera-course-player-content__wrapper">
            <div class="piksera-course-player-quiz">
                <div class="quiz-wrap mt-4">
                    <div class="container">
                        <div class="col-md-8 offset-md-2">
                            <div class="question-wrap">
                                {{-- <form action="{{route('quiz_attempt_url', $quiz->id)}}" method="post" class="quiz-question-submit"> --}}
                                <form action="/quiz/{{ $quiz->id}}" method="post" class="quiz-question-submit">
                                    @csrf
                                    <!-- Loop through each question -->
                                    @foreach($questions as $question)
                                        <input type="hidden" name="questions[{{$question->id}}]" value="">
                                        <!-- Display question content here -->
                                        <div class="question-single-wrap">
                                            @if($question->image_id)
                                            <div class="quiz-image w-50 ml-4 mb-3">
                                                <img src="{{$question->image_url->original}}" />
                                            </div>
                                        @endif
                                      @if( $question->type === 'radio')
                                                @include('front.quiz.radio')
                                            @elseif( $question->type === 'checkbox')
                                                @include('front.quiz.checkbox')
                                            @elseif($question->type === 'text' )
                                                @include('front.quiz.text')
                                            @elseif($question->type === 'textarea')
                                                @include('front.quiz.textarea')
                                            @endif
                                        </div>
                                    @endforeach
                                    <!-- Your submit button -->
                                    <div class="piksera-course-player-quiz__finish-quiz d-flex justify-content-end">
                                        <button type="submit" name="question-submit-btn" class="btn btn-primary" data-id="start-quiz">
                                            <span class="piksera-button__title">{{ tr('Finish') }}</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('front.quiz.modal')




@php
use Carbon\Carbon;

    $attempt_time_limit = $attempt->time_limit; // Get time limit from attempt data

if ($attempt_time_limit > 0) {
    $created_at = new Carbon($attempt->created_at); // Convert to Carbon instance
    $time_elapsed = $created_at->diffInMinutes(); // Calculate time elapsed

    $remaining_time = max(0, $attempt_time_limit - $time_elapsed); // Calculate remaining time

    if ($time_elapsed >= $attempt_time_limit) {
        $attempt->status = 'finished';
        $attempt->ended_at = Carbon::now()->toDateTimeString();
        $attempt->save_and_sync();

        return redirect($quiz->url)->with('error', 'Time limit exceeded.');
    }
} else {
    $remaining_time = null; // If no time limit is set, set remaining_time to null
}

// Remaining time will be passed to the view for countdown display
$remaining_time_in_seconds = $remaining_time * 60; // Convert remaining time to seconds for JavaScript

@endphp

<!-- Include jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
   // Function to update the countdown timer
function updateCountdown(hours, minutes, seconds) {
    $('#countdown-hours').text(hours);
    $('#countdown-minutes').text(minutes);
    $('#countdown-seconds').text(seconds);
}

// Function to update the countdown timer every second
function startCountdown(remainingTimeInSeconds) {
    var countdownInterval = setInterval(function() {
        var hours = Math.floor(remainingTimeInSeconds / 3600);
        var minutes = Math.floor((remainingTimeInSeconds % 3600) / 60);
        var seconds = remainingTimeInSeconds % 60;
        updateCountdown(hours, minutes, seconds);
        remainingTimeInSeconds--;
        if (remainingTimeInSeconds < 0) {
            clearInterval(countdownInterval);
            $('#countdown-container').text('Time is up!');
            // Optionally, you can redirect the user or handle time-up scenario here
        }
    }, 1000);
}

$(document).ready(function() {
    var remainingTimeInSeconds = {{$remaining_time_in_seconds}}; // Get remaining time from PHP
    startCountdown(remainingTimeInSeconds);
});


</script>



@endsection

<div class="piksera-course-player-question" data-number-question="2">
    <div class="piksera-course-player-question__header">
        <h3 class="piksera-course-player-question__title">
            {{ $question->title }}
        </h3>
    </div>
    <div class="attempt-options-wrap mb-4">
        @php
            $shuffledOptions = $question->options->shuffle();
        @endphp

        @foreach($shuffledOptions as $option)
        <div class="piksera-course-player-answer d-flex align-items-center">
            <div class="piksera-course-player-answer__input mr-2">
                <input type="{{ $question->type }}" name="questions[{{ $question->id }}]{{ $question->type === 'checkbox' ? '[]' : '' }}" value="{{ $option->id }}">
                <span class="piksera-course-player-answer__checkbox"></span>
            </div>
            <div class="piksera-course-player-answer__wrapper">
                <div class="piksera-course-player-answer__text">
                    {{ $option->title }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

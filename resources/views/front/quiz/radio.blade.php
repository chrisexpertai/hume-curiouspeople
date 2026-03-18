<div class="piksera-course-player-question" data-number-question="3">
    <div class="piksera-course-player-question__header">
       <h3 class="piksera-course-player-question__title">
        {{$question->title}}
       </h3>
    </div>

    <div class="piksera-course-player-question__content">
        @php
            $shuffledOptions = $question->options->shuffle();
        @endphp

        @foreach($shuffledOptions as $option)
            <div class="piksera-course-player-answer">
                <div class="piksera-course-player-answer__input">
                    <input type="radio" name="questions[{{$question->id}}]{{$question->type === 'checkbox' ? '[]' : ''}}" value="{{$option->id}}">
                    <span class="piksera-course-player-answer__radio"></span>
                </div>
                <div class="piksera-course-player-answer__wrapper">
                    <div class="piksera-course-player-answer__text">
                        {{$option->title}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

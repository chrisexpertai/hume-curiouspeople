<div class="piksera-course-player-question " data-number-question="3">
    <div class="piksera-course-player-question__header">
       <h3 class="piksera-course-player-question__title">
        {{$question->title}}
       </h3>
    </div>


<div class="form-group">
    <input type="text" class="form-control" name="questions[{{$question->id}}]" placeholder="{{ tr('Write your answer') }}">
</div>

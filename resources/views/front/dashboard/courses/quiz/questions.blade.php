@php
    $questions = $quiz->questions;
@endphp

@if($questions->count())
    <div class="container">
        <div class="row">
            @foreach($questions as $question)
                <div class="col-md-12 mb-3">
                    <div id="question-{{$question->id}}" class="card quiz-question-item">
                        <div class="card-body border rounded-4 d-flex align-items-center">
                            <div class="me-3">
                                <a href="javascript:;" class="question-sort" title="Sort Question"><i class="la la-sort"></i></a>
                                <span><i class="la la-question-{{$question->type}}"></i></span>
                            </div>

                            @if($question->image_id)
                                <div class="me-3">
                                    <img src="{{$question->image_url->thumbnail}}" class="quiz-question-item-image" />
                                </div>
                            @endif

                            <p class="m-0 flex-grow-1 px-3 py-2 bg-light quiz-question-item-title">
                                <span class="question-title">{{$question->title}}</span>
                            </p>

                            <div>
                                <a href="javascript:;" class="question-edit me-2" title="Edit Question" data-question-id="{{$question->id}}"><i class="la la-pencil-square"></i></a>
                                <a href="javascript:;" class="question-trash text-danger" title="Delete Question" data-question-id="{{$question->id}}"><i class="bi bi-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif


<script>
    $(document).on('click', '#quiz-questions-tab-item', function(e) {
        sortable_questions();
    });

    function sortable_questions() {
        if (jQuery().sortable) {
            $("#quiz-questions-wrap").sortable({
                handle: ".question-sort",
                items: "div.quiz-question-item",
                start: function (e, ui) {
                    ui.placeholder.css('visibility', 'visible');
                },
                stop: function (e, ui) {
                    sorting_questions();
                },
            });
        }
    }

    function sorting_questions(){
        var questions = {};
        $('#quiz-questions-wrap .quiz-question-item').each(function(index, item){
            index++;
            questions[index] = parseInt($(this).attr('id').match(/\d+/)[0], 10);
        });
        $.post(pageData.routes.sort_questions, { questions : questions, _token : pageData.csrf_token });
    }

    function sortable_options() {
        if (jQuery().sortable) {
            $(".question-options-wrap").sortable({
                handle: ".question-option-sort",
                items: "div.question-opt",
                start: function (e, ui) {
                    ui.placeholder.css('visibility', 'visible');
                },
                stop: function (e, ui) {
                    //sorting_questions();
                },
            });
        }
    }
</script>

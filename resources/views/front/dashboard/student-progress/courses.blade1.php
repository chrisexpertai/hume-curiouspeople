@extends('layouts.dashboard')

@section('content')
<style>
    .rounded-table th:first-child {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;

    }

    .rounded-table th:last-child {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;

    }
</style>



     <div class="row justify-content-center">
             @if($auth_user->courses->count())
            <div class="table-responsive">
                <table class="table table-striped table-hover rounded-table">
                    <thead class="table-dark">
                        <tr class="border-0">
                            <th scope="border-0 rounded-start">{{ __('Thumbnail') }}</th>
                            <th scope="border-0 rounded-start">{{ __('Title') }}</th>
                             <th scope="border-0 rounded-start">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody style="border-style: none;">
                        @foreach($auth_user->courses as $course)
                        <tr>
                            <td>
                                <img src="{{ $course->thumbnail_url }}" width="80" class="img-fluid rounded" alt="{{ $course->title }}">
                            </td>
                            <td>
                                <p class="mb-1">
                                    <strong>{{ $course->title }}</strong>
                                    {!! $course->status_html() !!}
                                </p>
                                <p class="text-muted mb-1">
                                    <span class="course-list-lecture-count">{{ $course->lectures->count() }} {{ __('lectures') }}</span>
                                    @if($course->assignments->count())
                                    , <span class="course-list-assignment-count">{{ $course->assignments->count() }} {{ __('assignments') }}</span>
                                    @endif
                                    @if($course->quizzes->count())
                                    , <span class="course-list-quiz-count">{{ $course->quizzes->count() }} {{ __('quizzes') }}</span>
                                    @endif
                                </p>
                            </td>

                            <td style="width: 200px;">
                                <div class="d-grid gap-2 d-md-block">
                                    @if(route_has('student_progress'))
                                    <a href="{{ route('student_progress', $course->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-bar-chart"></i> {{ __('Students Progress Report') }}
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center my-5">
                {!! no_data(null, null, 'my-5' ) !!}
                <a href="{{ route('create_course') }}" class="btn btn-lg btn-warning">{{ __('Create Course') }}</a>
            </div>
            @endif
        </div>

@endsection

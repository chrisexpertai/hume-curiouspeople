@extends('layouts.dashboard')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses_has_quiz') }}">{{ tr('Courses') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses_quizzes', $course->id) }}">{{ tr('Quizzes') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ tr('Quiz Attempts') }}</li>
            <li class="breadcrumb-item active">{{ tr('Review') }}</li>
        </ol>
    </nav>

    @php
        $quizzes = $course->quizzes()->with('attempts')->paginate(50);
    @endphp

    @if($quizzes->total())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                @foreach($quizzes as $quiz)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="quizzes">
                                    <a href="{{ route('quiz_attempts', $quiz->id) }}">
                                        {{ $quiz->title }}
                                    </a>
                                    <p class="mb-0">
                                        <small class="text-muted">{{ tr('Quiz Attempts') }} : {{ $quiz->attempts->count() }}</small>
                                    </p>
                                </div>
                                <div class="attempts-btn-wrap">
                                    <a href="{{ route('quiz_attempts', $quiz->id) }}" class="btn btn-primary py-1">{{ tr('Attempts') }}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        {!! $quizzes->links() !!}
    @else
        <div class="alert alert-info" role="alert">
            {{ tr('No quizzes found.') }}
        </div>
    @endif

@endsection

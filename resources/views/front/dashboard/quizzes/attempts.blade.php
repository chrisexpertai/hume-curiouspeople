@extends('layouts.dashboard')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses_has_quiz') }}">{{ tr('Courses') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses_quizzes', $quiz->course_id) }}">{{ tr('Quizzes') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('quiz_attempts', $quiz->id) }}">{{ tr('Quiz Attempts') }}</a></li>
            <li class="breadcrumb-item active">{{ tr('Review') }}</li>
        </ol>
    </nav>

    @php
        $attempts = $quiz->attempts()->with('user', 'quiz', 'course')->orderBy('ended_at', 'desc')->get();
    @endphp

    @if($attempts->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ tr('Quiz Taker') }}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $index => $attempt)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <p class="mb-3">{{ $attempt->user->name }}</p>
                                <p class="mb-0 text-muted">
                                    <strong>{{ tr('Quiz') }}: </strong> <a href="{{ $attempt->quiz->url }}">{{ $attempt->quiz->title }}</a>
                                </p>
                                <p class="mb-0 text-muted">
                                    <strong>{{ tr('Course') }}: </strong> <a href="{{ $attempt->course->url }}">{{ $attempt->course->title }}</a>
                                </p>
                            </td>
                            <td>
                                <a href="{{ route('attempt_detail', $attempt->id) }}" class="btn btn-primary py-1">{{ tr('Review') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            {{ tr('No attempts found.') }}
        </div>
    @endif

@endsection

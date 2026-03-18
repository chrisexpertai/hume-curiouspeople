@extends('layouts.dashboard')


@section('content')

    @php
        $auth_user = auth()->user();

        $attempts = $auth_user->my_quiz_attempts()->with('user', 'quiz', 'course')->orderBy('ended_at', 'desc')->get();
    @endphp





    @if ($attempts->count())
        <table class="table table-bordered bg-white table-striped">

            <tr>
                <th>#</th>
                <th>{{ __t('details') }}</th>
            </tr>

            @foreach ($attempts as $attempt)
                <tr>
                    <td>#</td>
                    <td>
                        <p class="mb-3">{{ $attempt->user->name }}</p>

                        <p class="mb-0 text-muted">
                            <strong>{{ __t('quiz') }} : </strong>
                            @if ($attempt->quiz)
                                <a href="{{ $attempt->quiz->url }}">{{ $attempt->quiz->title }}</a>
                            @else
                                {{ __t('Quiz not available') }}
                            @endif
                        </p>

                        <p class="mb-0 text-muted">
                            <strong>{{ __t('course') }} : </strong>
                            @if ($attempt->course)
                                <a href="{{ $attempt->course->url }}">{{ $attempt->course->title }}</a>
                            @else
                                {{ __t('Course not available') }}
                            @endif
                        </p>
                    </td>
                </tr>
            @endforeach


        </table>
    @else
        {!! no_data() !!}
    @endif

@endsection

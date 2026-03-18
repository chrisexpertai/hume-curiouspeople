@extends('layouts.dashboard')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses_has_assignments') }}">{{ tr('Courses') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses_assignments', $course->id) }}">{{ tr('Assignments') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ tr('Assignment Submission') }}</li>
            <li class="breadcrumb-item active">{{ tr('Evaluate Submission') }}</li>
        </ol>
    </nav>

    @if($assignments->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">{{ tr('Assignment Title') }}</th>
                        <th scope="col">{{ tr('Submissions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>
                                <strong><a href="{{ route('assignment_submissions', $assignment->id) }}">{{ $assignment->title }}</a></strong>
                            </td>
                            <td>
                                <p>{{ tr('Submissions') }}: {{ $assignment->submissions->count() }}</p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination justify-content-center">
            {!! $assignments->links() !!}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            {{ tr('No assignments found.') }}
        </div>
    @endif

@endsection

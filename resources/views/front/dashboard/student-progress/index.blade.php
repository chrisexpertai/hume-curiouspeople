@extends('layouts.dashboard')

@section('content')

@php
$course = \App\Models\Course::find($course_id);
$students = $course->students()->with('photo_query')->orderBy('enrolls.enrolled_at', 'desc')->paginate(50);
@endphp

@if($students->total())




<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h5 class="mb-0">{{ tr('Course') }}: {{$course->title}}</h5>
        </div>
    </div>
    @foreach($students as $student)
    @php
    $completed_percent = $course->completed_percent($student);
    @endphp

    <div class="row mb-3 align-items-center border rounded-4 p-4">
        <div class="col-md-2">
            <div class="reviewed-user-photo">
                <a href="{{route('profile', $student->id)}}">
                    <img class="avatar-img rounded-circle border border-white border-3 shadow" src="{!! $student->get_photo !!}" alt="photo">
                </a>
            </div>
        </div>
        <div class="col-md-2">
            <div class="progress-report-loop-detail">
                <a href="{{route('profile', $student->id)}}" class="mb-2 d-block">{{$student->name}}</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{$completed_percent}}%" aria-valuenow="{{$completed_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="progress-percentage">{{$completed_percent}}%</div>
        </div>
        <div class="col-md-1">
            <a href="{{route('progress_report_details', [$course->id, $student->id])}}" class="btn btn-purple btn-sm">Details</a>
        </div>
    </div>
    @endforeach

    <div class="row">
        <div class="col">
            {!! $students->links() !!}
        </div>
    </div>

</div>

@else
<div class="container py-4">
    {!! no_data(null, null, 'my-5' ) !!}
</div>


@endif

@endsection

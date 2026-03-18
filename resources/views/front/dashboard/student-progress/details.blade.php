@extends('layouts.dashboard')

@section('content')

@php
$contents_count = $course->contents->count();
$completed_percent = $course->completed_percent($user);
$completed_content_ids = (array) $user->get_option("completed_courses.{$course->id}.content_ids");
@endphp



<div class="container py-4">
    <div class="row">
        <div class="col-md-2 mb-4">
            <div class="bg-white p-4 d-flex align-items-center justify-content-center">
                <a href="{{route('profile', $user->id)}}">
                    <img src="{!! $user->get_photo !!}" class="rounded-circle" alt="">
                </a>
            </div>
        </div>
        <div class="col-md-10 mt-4 mb-4">
            <div class="bg-white d-flex align-items-center">
                <div class="col-md-10 mb-4">
                    <a href="{{route('profile', $user->id)}}" class="text-decoration-none">
                        <h4 class="mb-2">{!! $user->name !!}</h4>
                    </a>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$completed_percent}}%" aria-valuenow="{{$completed_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mt-0 mb-0 text-muted">
                        Progress {{$completed_percent}}%, Completed {{count($completed_content_ids)}} / {{$contents_count}} items
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if($course->sections->count())
                <div class="accordion" id="accordionExample">
                    @foreach($course->sections as $section)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{$section->id}}">
                            <button class="accordion-button bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$section->id}}" aria-expanded="true" aria-controls="collapse{{$section->id}}">
                                {{$section->section_name}}
                            </button>
                        </h2>
                        <div id="collapse{{$section->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$section->id}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                @if($section->items->count())
                                    @foreach($section->items as $item)
                                        @php
                                            $is_completed = in_array($item->id, $completed_content_ids);
                                            $runTime = $item->runtime;
                                        @endphp
                                        <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="d-block text-decoration-none border-bottom py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="bi bi-{{$item->icon}} text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="m-0">{{$item->title}}</p>
                                                    @if($runTime)
                                                        <small>{{$runTime}}</small>
                                                    @endif
                                                </div>
                                                @if($is_completed)
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @else
                                                    <i class="bi bi-circle"></i>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

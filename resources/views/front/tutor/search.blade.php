@extends('layouts.app')

@section('content')

@php
            $user = auth()->user();
@endphp
<link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">

<!-- **************** MAIN CONTENT START **************** -->
<main>

    <!-- =======================
    Page Banner START -->
    <section class="py-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-dark p-4 text-center rounded-3">
                        <h1 class="text-white m-0">{{ tr('Instructors') }}</h1>
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dark breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ tr('Home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ tr('Instructors') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    Page Banner END -->


    @if (!$searchQuery)

    <!-- =======================
    Inner part START -->
    <section class="pt-4">
        <div class="container">
            <div class="row mb-4 align-items-center">
                <!-- Search bar -->
                <div class="col-sm-12 col-xl-12">
                    <form action="{{ route('instructors.search') }}" method="get" class="border rounded p-2">
                        <div class="input-group input-borderless">
                            <input type="text" name="q" id="searchQuery" class="form-control" value="{{ $searchQuery }}" placeholder="Search instructor">
                            <button type="button" class="btn btn-primary mb-0 rounded"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                {{-- <!-- Select option -->
                <div class="col-sm-6 col-xl-3 mt-3 mt-lg-0">

                </div>

                <!-- Select option -->
                <div class="col-sm-6 col-xl-3 mt-3 mt-xl-0">

                </div>

                <!-- Button -->
                <div class="col-sm-6 col-xl-2 mt-3 mt-xl-0 d-grid">

              </div> --}}
            </div>

            @endif

            <!-- Instructor list START -->
            <div class="row g-4 justify-content-center">


                @foreach($instructors as $instructor)
                @php
                    $courses_count = $instructor->courses()->publish()->count();
                    $students_count = $instructor->student_enrolls->count();
                    $instructor_rating = $instructor->get_rating;
                    $rating = $instructor->get_rating;

                @endphp

                <!-- Card item START -->
                <div href="{{route('profile', $instructor->id)}}" class="col-lg-10 col-xl-6">
                    <div class="card shadow p-2">
                        <div class="row g-0">
                            <!-- Image -->
                            <div class="col-md-4">
                                <img src="{!! $instructor->get_photo !!}" class="rounded-3"  alt='{$this->name}'>
                            </div>

                            <!-- Card body -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <!-- Title -->
                                    <div class="d-sm-flex justify-content-sm-between mb-2 mb-sm-3">
                                        <div>
                                            <h5 class="card-title mb-0"><a href="{{route('profile', $instructor->id)}}">{{$instructor->name}}</a></h5>

{{--
                                            @foreach($userOccupations as $occupationId)
                                            @php
                                            $category = \App\Models\Category::find($occupationId);
                                            $userOccupations = UserOccupation::where('user_id', $instructor->id)->pluck('category_id')->toArray();

                                        @endphp
                                            @if($category)

                                            <p class="small mb-2 mb-sm-0">{{ $category->category_name }}</p>


                                            @endif
                                        @endforeach --}}






                                        </div>
                                        <span class="h6 fw-light">{{ round($rating->rating_avg) }}<i class="fas fa-star text-warning ms-1"></i></span>
                                    </div>


                                    <!-- Content -->
                                    @if($instructor->about_me)

                                    <p class="text-truncate-2 mb-3"> {!! nl2br($instructor->about_me) !!}</p>

                                    @endif

                                    <!-- Info -->
                                    <div class="d-sm-flex justify-content-sm-between align-items-center">
                                        <!-- Title -->
                                        <h6 class="text-orange mb-0">{{$instructor->job_title}}</h6>

                                        <!-- Social button -->



                                        <ul class="list-inline mb-0 mt-3 mt-sm-0">
                                            <li class="list-inline-item">

                                                @if($instructor->get_option('social'))
                                                @foreach($instructor->get_option('social') as $socialKey => $social)
                                                    @if($social)
                                                        <a class="{{ucfirst($socialKey)}}"
                                                        href="{{$social}}" class="d-block border py-2 px-3 mb-1" target="_blank">
                                                            <i class="la la-{{$socialKey === 'website' ? 'link' : $socialKey}}"></i>

                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Card item END -->


            </div>
            <!-- Instructor list END -->

         <!-- Pagination START -->
         {{ $instructors->links() }}

         <!-- Pagination END -->

        </div>
    </section>
    <!-- =======================
    Inner part END -->

    <!-- =======================
    Action box START -->
    <section class="pt-0">
        <div class="container position-relative">
            <!-- SVG -->
            <figure class="position-absolute top-50 start-50 translate-middle ms-2">
                <svg>
                    <path d="m496 22.999c0 10.493-8.506 18.999-18.999 18.999s-19-8.506-19-18.999 8.507-18.999 19-18.999 18.999 8.506 18.999 18.999z" fill="#fff" fill-rule="evenodd" opacity=".502"/>
                    <path d="m775 102.5c0 5.799-4.701 10.5-10.5 10.5-5.798 0-10.499-4.701-10.499-10.5 0-5.798 4.701-10.499 10.499-10.499 5.799 0 10.5 4.701 10.5 10.499z" fill="#fff" fill-rule="evenodd" opacity=".102"/>
                    <path d="m192 102c0 6.626-5.373 11.999-12 11.999s-11.999-5.373-11.999-11.999c0-6.628 5.372-12 11.999-12s12 5.372 12 12z" fill="#fff" fill-rule="evenodd" opacity=".2"/>
                    <path d="m20.499 10.25c0 5.66-4.589 10.249-10.25 10.249-5.66 0-10.249-4.589-10.249-10.249-0-5.661 4.589-10.25 10.249-10.25 5.661-0 10.25 4.589 10.25 10.25z" fill="#fff" fill-rule="evenodd" opacity=".2"/>
                </svg>
            </figure>

            <div class="bg-success p-4 p-sm-5 rounded-3">
                <div class="row justify-content-center position-relative">
                    <!-- Svg -->
                    <figure class="fill-white opacity-1 position-absolute top-50 start-0 translate-middle-y">
                        <svg width="141px" height="141px">
                            <path d="M140.520,70.258 C140.520,109.064 109.062,140.519 70.258,140.519 C31.454,140.519 -0.004,109.064 -0.004,70.258 C-0.004,31.455 31.454,-0.003 70.258,-0.003 C109.062,-0.003 140.520,31.455 140.520,70.258 Z"/>
                        </svg>
                    </figure>
                    <!-- Action box -->
                    <div class="col-11 position-relative">
                        <div class="row align-items-center">
                            <!-- Title -->
                            <div class="col-lg-7">
                                <h3 class="text-white">Become an Instructor!</h3>
                                <p class="text-white mb-3 mb-lg-0">Speedily say has suitable disposal add boy. On forth doubt miles of child. Exercise joy man children rejoiced. Yet uncommonly his ten who diminution astonished.</p>
                            </div>
                            <!-- Button -->
                            <div class="col-lg-5 text-lg-end">
                                <a href="#" class="btn btn-dark mb-0">Start Teaching today</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    Action box END -->

    </main>
    <!-- **************** MAIN CONTENT END **************** -->

@endsection

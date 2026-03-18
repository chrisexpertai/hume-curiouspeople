@extends('layouts.app')

@section('content')
@php
$courses = $user->courses()->publish()->get();
$students_count = $user->student_enrolls->count();
$rating = $user->get_rating;
$followings = $user->following();
$followers = $user->followers();
$authUserIsFollower = $followers->where('follower', auth()->id())
        ->where('status',  App\Follow::$accepted)
        ->first();
$title = $user->name;
use App\Models\User;

$instructors = User::whereHas('roles', function ($query) {
        $query->where('name', 'instructor');
    })->get();


@endphp


<!-- **************** MAIN CONTENT START **************** -->
<main>

<!-- =======================
Page content START -->
<section class="pt-5 pb-0">
	<div class="container">
		<div class="row g-0 g-lg-5">

			<!-- Left sidebar START -->
			<div class="col-lg-4">
				<div class="row">
					<div class="col-md-6 col-lg-12">
						<!-- Instructor image START -->
            <div class="card shadow p-2 mb-4 text-center">
              <div class="rounded-3">
                <!-- Image -->
                <img src="<?php echo $user->get_photo; ?>" class="card-img" alt="course image">
              </div>

              <!-- Card body -->
              <div class="card-body px-3">
                <!-- Rating -->
                <ul class="list-inline">
                    {!! star_rating_generator($rating->rating_avg) !!}
                    <li class="list-inline-item ms-2 h6 fw-light mb-0">{{ round($rating->rating_avg) }}
                        /5</li>
                </ul>
                <!-- Social media button -->
				<ul class="list-inline mb-0 mt-3 mt-sm-0">
					<?php if ($user->get_option('social')): ?>
						<?php foreach ($user->get_option('social') as $socialKey => $social): ?>
							<?php if ($social): ?>
								<li class="list-inline-item">
									<a class="mb-0 me-1 text-<?php echo $socialKey; ?>" href="<?php echo $social; ?>" target="_blank"><i class="fab fa-fw fa-<?php echo $socialKey === 'website' ? 'link' : $socialKey; ?>"></i></a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
              </div>
            </div>
						<!-- Instructor image END -->
					</div>

					<div class="col-md-6 col-lg-12">
						<div class="card card-body shadow p-4 mb-4">

							<!-- Education START -->
							<!-- Title -->
							<h4 class="mb-3">{{ tr('Education') }}</h4>

							<!-- resources/views/user/education_partial.blade.php -->

							@if($educations->isEmpty())
							<p>{{ tr('No education entries found.') }}</p>
							@else
								@foreach($educations as $education)

								<!-- Education item -->
							<div class="d-flex align-items-center mb-4">
								<span class="icon-md mb-0 bg-light rounded-3"><i class="fas fa-graduation-cap"></i></span>
								<div class="ms-3">
									<h6 class="mb-0">{{ $education->school_name }}</h6>
									<p class="mb-0 small"> {{ $education->degree }}</p>
								</div>
							</div>

								@endforeach
							@endif





							<hr> <!-- Divider -->

							<!-- Skills START -->
							<h4 class="mb-3">{{ tr('Occupations') }}</h4>



							<ul>
								@foreach($userOccupations as $occupationId)
								@php
									$category = \App\Models\Category::find($occupationId);
								@endphp
								@if($category)

									<!-- Progress item -->
							<div class="overflow-hidden mb-4">
								<h6 class="uppercase">{{ $category->category_name }}</h6>
							</div>
								@endif
							@endforeach



							<!-- Skills END -->

						</div>
					</div>
				</div> <!-- Row End -->
			</div>
			<!-- Left sidebar END -->

			<!-- Main content START -->
			<div class="col-lg-8">

				<!-- Title -->
 				<h1 class="mb-0">{{$user->name}} {{$user->last_name}}</h1>

                 @if($user->job_title)
				<p>{{$user->job_title}}</p>
                @endif

                @if($user->about_me)
                <!-- Content -->
                <p>{!! nl2br($user->about_me) !!}</p>
            @endif

                <!-- Personal info -->
				<ul class="list-group list-group-borderless">
					<li class="list-group-item px-0">
						<span class="h6 fw-light"><i class="fas fa-fw fa-map-marker-alt text-primary me-1 me-sm-3"></i>{{ tr('Address:') }}</span>
						<span>{{$user->address}}</span>
					</li>
					<li class="list-group-item px-0">
						<span class="h6 fw-light"><i class="fas fa-fw fa-envelope text-primary me-1 me-sm-3"></i>{{ tr('Email:') }}</span>
						<span>{{$user->email}}</span>
					</li>
					<li class="list-group-item px-0">
						<span class="h6 fw-light"><i class="fas fa-fw fa-headphones text-primary me-1 me-sm-3"></i>{{ tr('Phone number:') }}</span>
						<span>{{$user->phone}}</span>
					</li>
					<li class="list-group-item px-0">
						<span class="h6 fw-light"><i class="fas fa-fw fa-globe text-primary me-1 me-sm-3"></i>{{ tr('Website:') }}</span>
						<span>{{$user->get_option('social.website')}}</span>
					</li>
				</ul>

				<!-- Counter START -->
				<div class="row mt-4 g-3">


					<!-- Counter item -->
					<div class="col-sm-6 col-lg-4">
						<div class="d-flex align-items-center">
							<span class="icon-lg text-success mb-0 bg-success bg-opacity-10 rounded-3"><i class="fas fa-play"></i></span>
							<div class="ms-3">
								<div class="d-flex">
									<h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0" data-purecounter-end="10"	data-purecounter-delay="{{$courses->count()}}"></h5>
									<span class="mb-0 h5"> </span>
								</div>
								<p class="mb-0 h6 fw-light">{{__t('courses')}}</p>
							</div>
						</div>
					</div>


					<!-- Counter item -->

					<div class="col-sm-6 col-lg-4">
						<div class="d-flex align-items-center">
							<span class="icon-lg text-purple bg-purple bg-opacity-10 rounded-3 mb-0"><i class="fas fa-users"></i></span>
							<div class="ms-3">
								<div class="d-flex">
									<h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0" data-purecounter-end="{{$students_count}}""></h5>
									<span class="mb-0 h5"> </span>
								</div>
								<p class="mb-0 h6 fw-light"> {{__t('students')}}</p>
							</div>
						</div>
					</div>

					<!-- Counter item -->

					<div class="col-sm-6 col-lg-4">
						<div class="d-flex align-items-center">
							<span class="icon-lg text-orange bg-orange bg-opacity-10 rounded-3 mb-0"><i class="fas fa-star"></i></span>
							<div class="ms-3">
								<div class="d-flex">
									<h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0" data-purecounter-end="{{$rating->rating_count}}" data-purecounter-delay="{{$rating->rating_count}}"></h5>
									<span class="mb-0 h5"> </span>
								</div>
								<p class="mb-0 h6 fw-light">{{ tr('Reviews') }}</p>
							</div>
						</div>
					</div>
				</div>


				<!-- Counter END -->


                @if($courses->count())
				<!-- Course START -->
				<div class="row g-4 mt-4">
					<!-- Title -->
					<h2>{{ tr('Courses List') }}</h2>

					<!-- Card item START -->
                    @foreach($courses as $course)
                    {!! course_card($course, 'col-md-4 col-sm-6') !!}
                    @endforeach

					<!-- Card item END -->

				</div>
				<!-- Course END -->
                @endif
            </div>
			<!-- Main content END -->

		</div><!-- Row END -->
	</div>
</section>
<!-- =======================
Page content END -->


@if($instructors->count())


<!-- =======================
Related instructor START -->
<section>
	<div class="container">
		<!-- Title -->
		<div class="row mb-4">
			<h2 class="mb-0">{{ tr('Related Instructors') }}</h2>
		</div>

		<!-- Slider START -->
		<div class="tiny-slider arrow-round arrow-creative arrow-blur arrow-hover">
			<div class="tiny-slider-inner" data-autoplay="false" data-arrow="true" data-dots="false" data-items="4" data-items-lg="3" data-items-md="2" data-items-xs="1">
				@foreach($instructors as $instructor)

				@php
				$instructor_rating = $instructor->get_rating;

				$instructor_students_count = $user->student_enrolls->count();

				$instructor_courses = $instructor->courses()->count();
				@endphp
				<!-- Card item START -->
				<div class="card bg-trparent">
					<div class="position-relative">
						<!-- Image -->
						<img src="{!! $instructor->get_photo !!}" class="card-img" alt="course image">
						<!-- Overlay -->
						<div class="card-img-overlay d-flex flex-column p-3">
							<div class="w-100 mt-auto text-end">
								<!-- Card category -->
								<a href="#" class="badge text-bg-info rounded-1"><i class="fas fa-user-graduate me-2"></i>{{$instructor_students_count}}</a>
								<a href="#" class="badge text-bg-orange rounded-1"><i class="fas fa-clipboard-list me-2"></i>{{$instructor_courses}}</a>
							</div>
						</div>
					</div>
					<!-- Card body -->
					<div class="card-body text-center">
						<!-- Title -->
						<h5 class="card-title"><a href="<?php echo route('profile', $instructor->id); ?>">{{ $instructor->name }}</a></h5>
							<p class="mb-2">{{ $instructor->job_title }}</p>


							<!-- Rating -->
							<ul class="list-inline hstack justify-content-center">
								<li class="list-inline-item ms-2 h6 mb-0 fw-normal">{{ round($instructor_rating->rating_avg) }}</5.0</li>
								{!! star_rating_generator($rating->rating_avg) !!}
							</ul>
					</div>
				</div>
				<!-- Card item END -->
			@endforeach
			</div>
		</div>
		<!-- Slider END -->

	</div>
</section>
<!-- =======================
Related instructor END -->

@endif



</main>
<!-- **************** MAIN CONTENT END **************** -->

@endsection



     <!-- Card START -->

    @foreach($course->instructors as $instructor)
@php
$courses_count = $instructor->courses()->publish()->count();
$students_count = $instructor->student_enrolls->count();
$instructor_rating = $instructor->get_rating;
@endphp

    <div class="card mb-0 mb-md-4">
        <div class="row g-0 align-items-center">
            <div class="col-md-5">
                <!-- Image -->
                <img src="{!! $instructor->get_photo !!}" href="{{route('profile', $instructor->id)}}" class="img-fluid rounded-3" alt="instructor-image">
            </div>
            <div class="col-md-7">
                <!-- Card body -->
                <div class="card-body">
                    <!-- Title -->
                    <h3 class="card-title mb-0">{{$instructor->name}}</h3>
                    <p class="mb-2">{{$instructor->job_title}}</p>
                    <!-- Social button -->

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

                    <!-- Info -->
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <div class="d-flex align-items-center me-3 mb-2">
                                <span class="icon-md bg-orange bg-opacity-10 text-orange rounded-circle"><i class="fas fa-user-graduate"></i></span>
                                <span class="h6 fw-light mb-0 ms-2">{{$students_count}}</span>
                            </div>
                        </li>
                        @if($instructor_rating->rating_count)


                        <li class="list-inline-item">
                            <div class="d-flex align-items-center me-3 mb-2">
                                <span class="icon-md bg-warning bg-opacity-15 text-warning rounded-circle"><i class="fas fa-star"></i></span>
                                <span class="h6 fw-light mb-0 ms-2">{{$instructor_rating->rating_avg}}</span>
                            </div>
                        </li>

                        @endif


                        <li class="list-inline-item">
                            <div class="d-flex align-items-center me-3 mb-2">
                                <span class="icon-md bg-danger bg-opacity-10 text-danger rounded-circle"><i class="fas fa-play"></i></span>
                                <span class="h6 fw-light mb-0 ms-2">{{$courses_count}} Courses</span>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Card END -->

    <!-- Instructor info -->
    <h5 class="mb-3">{{ tr('About Instructor') }}</h5>

    @if($instructor->about_me)
                                    {!! nl2br($instructor->about_me) !!}
    @endif



    @endforeach

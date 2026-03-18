@extends(('front.dashboard.courses.layout'))

@section('content')

@include(theme('dashboard.courses.course_nav'))

<script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
<script src="/assets/js/wrunner-jquery.js"></script>

<div class="card-body">
    <!-- Step content START -->
    <div class="bs-stepper-content">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div id="add-instructors-search-wrap" class="mb-4 d-flex justify-content-between align-items-center">
                    <div id="instructor-search-wrap" class="flex-grow-1 mr-3">
                        <form action="{{ route('multi_instructor_search', $course->id) }}" method="post" id="instructor-search-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" placeholder="{{ __t('search_instructors') }}" aria-label="{{ __t('search_instructors') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-outline-primary">{{ __t('search') }}</button>
                                </div>
                            </div>
                            <small class="text-muted">{{ __t('search_instructors_desc') }}</small>
                            <div id="instructor-search-results" class="mt-2"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="course-default-author-wrap mb-5 p-4">
                    @php
                    $author = $course->author;
                    $courses_count = $author->courses()->publish()->count();
                    $students_count = $author->student_enrolls->count();
                    $author_rating = $author->get_rating;
                    @endphp
                    <div class="row">
                        <div class="col-md-3 d-flex align-items-center justify-content-center">
                            <a href="{{ route('profile', $author) }}">
                                <img src="{!! $author->get_photo !!}" alt="{{ $author->name }}" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                            </a>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-1">{{ $author->name }} <span class="badge badge-info">{{ __t('author') }}</span></h4>
                                    @if($author->job_title)
                                    <h5 class="card-subtitle mb-2 text-muted">{{ $author->job_title }}</h5>
                                    @endif
                                    <div class="d-flex align-items-center">
                                        @if($author_rating->rating_count)
                                        <div class="mr-3">
                                            {!! star_rating_generator($author_rating->rating_avg) !!}
                                            <span class="text-muted">{{ $author_rating->rating_avg }}</span>
                                        </div>
                                        @endif
                                        <div class="mr-3">
                                            <i class="la la-play-circle text-primary"></i>
                                            <span class="text-muted">{{ $courses_count }} {{ __t('courses') }}</span>
                                        </div>
                                        <div class="mr-3">
                                            <i class="la la-user-circle text-primary"></i>
                                            <span class="text-muted">{{ $students_count }} {{ __t('students') }}</span>
                                        </div>
                                        <div class="mr-3">
                                            <i class="la la-comments text-primary"></i>
                                            <span class="text-muted">{{ $author_rating->rating_count }} {{ __t('reviews') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="added-instructors-wrap">
            @php
            $instructors = $course->instructors()->where('users.id', '!=', $course->user_id)->get();
            @endphp
            @if($instructors->count())
            <div class="row">
                @foreach($instructors as $instructor)
                <div class="col-md-12 border rounded-3 p-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="profile-image mr-4">
                                <a href="{{ route('profile', $instructor) }}">
                                    <img src="{!! $instructor->get_photo !!}" alt="{{ $instructor->name }}" class="img-fluid rounded-circle" style="width: 70px; height: 70px;">
                                </a>
                            </div>
                            <div class="instructor-details flex-grow-1">
                                <h4 class="card-title mb-1">{{ $instructor->name }}</h4>
                                @if($instructor->job_title)
                                <h6 class="card-subtitle mb-2 text-muted">{{ $instructor->job_title }}</h6>
                                @endif
                                <div class="d-flex align-items-center">
                                    @php
                                    $courses_count = $instructor->courses()->publish()->count();
                                    $students_count = $instructor->student_enrolls->count();
                                    $instructor_rating = $instructor->get_rating;
                                    @endphp
                                    @if($instructor_rating->rating_count)
                                    <div class="mr-3">
                                        {!! star_rating_generator($instructor_rating->rating_avg) !!}
                                        <span class="text-muted">{{ $instructor_rating->rating_avg }}</span>
                                    </div>
                                    @endif
                                    <div class="mr-3">
                                        <i class="la la-play-circle text-primary"></i>
                                        <span class="text-muted">{{ $courses_count }} {{ __t('courses') }}</span>
                                    </div>
                                    <div class="mr-3">
                                        <i class="la la-user-circle text-primary"></i>
                                        <span class="text-muted">{{ $students_count }} {{ __t('students') }}</span>
                                    </div>
                                    <div class="mr-3">
                                        <i class="la la-comments text-primary"></i>
                                        <span class="text-muted">{{ $instructor_rating->rating_count }} {{ __t('reviews') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <form action="{{ route('remove_instructor', $course->id) }}" method="post" class="remove-instructor-form">
                                    @csrf
                                    <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="la la-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

<script>
    $(document).ready(function () {
        // Function to handle instructor search form submission
        $('#instructor-search-form').submit(function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize(); // Serialize form data

            // AJAX call to search for instructors
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var instructors = response.instructors;

                        // Clear previous search results
                        $('#instructor-search-results').empty();

                        // Loop through each instructor and create a card
                        $.each(instructors, function (index, instructor) {
                            var cardHtml =
                                '<div class="card mb-3">' +
                                '<div class="card-body">' +
                                '<div class="d-flex align-items-center">' +
                                '<div class="profile-image mr-3 p-4">' +
                                '<img src="' + instructor.photo + '" alt="' + instructor.name + '" class="img-fluid rounded-circle" style="width: 70px; height: 70px;">' +
                                '</div>' +
                                '<div class="instructor-details flex-grow-1">' +
                                '<h5 class="card-title mb-1">' + instructor.name + '</h5>';
                            if (instructor.job_title) {
                                cardHtml += '<h6 class="card-subtitle mb-2 text-muted">' + instructor.job_title + '</h6>';
                            }
                            cardHtml +=
                                '<div class="d-flex align-items-center">' +
                                '<div class="mr-3">' +
                                '<i class="la la-play-circle text-primary"></i>' +
                                '<span class="text-muted">' + instructor.courses_count + ' courses</span>' +
                                '</div>' +
                                '<div class="mr-3">' +
                                '<i class="la la-user-circle text-primary"></i>' +
                                '<span class="text-muted">' + instructor.students_count + ' students</span>' +
                                '</div>' +
                                '<div class="mr-3">' +
                                '<i class="la la-comments text-primary"></i>' +
                                '<span class="text-muted">' + instructor.instructor_rating.rating_count + ' reviews</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="ml-auto">' +
                                    '<button class="btn btn-sm btn-outline-primary attach-instructor" data-instructor-id="' + instructor.id + '">' +
                                    '<i class="la la-plus"></i> Attach' +
                                    '</button>' +

                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Append the card to search results
                            $('#instructor-search-results').append(cardHtml);
                        });
                    } else {
                        console.log('Error: ', response.error); // Log any errors
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', error); // Log AJAX errors
                }
            });
        });

        // Function to handle attaching instructor
        $(document).on('click', '.attach-instructor', function () {
            var instructorId = $(this).data('instructor-id');
            var courseId = '{{ $course->id }}'; // Course ID
            var token = $('meta[name="csrf-token"]').attr('content');

            // AJAX call to attach instructor
            $.ajax({
                type: 'POST',
                url: '{{ route("attach_instructor", $course->id) }}',
                data: {
                    _token: token,
                    instructor_id: instructorId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Show success message or perform any other actions
                        console.log(response.success);
                        // Refresh the page after attaching the instructor
                        window.location.reload();
                    } else {
                        // Handle error response
                        console.log(response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', error);
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function () {
        // Function to handle deleting instructor
        $(document).on('submit', '.remove-instructor-form', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize(); // Serialize form data

            // AJAX call to remove instructor
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Show success message or perform any other actions
                        console.log(response.success);
                        // Remove the instructor card from the page
                        form.closest('.card').remove();
                    } else {
                        // Handle error response
                        console.log(response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', error);
                }
            });
        });

        // Function to handle attaching instructor
        $(document).on('click', '.attach-instructor', function () {
            var instructorId = $(this).data('instructor-id');
            var courseId = '{{ $course->id }}'; // Course ID
            var token = $('meta[name="csrf-token"]').attr('content');

            // AJAX call to attach instructor
            $.ajax({
                type: 'POST',
                url: '{{ route("attach_instructor", $course->id) }}',
                data: {
                    _token: token,
                    instructor_id: instructorId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Show success message or perform any other actions
                        console.log(response.success);
                        // Refresh the page after attaching the instructor
                        window.location.reload();
                    } else {
                        // Handle error response
                        console.log(response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', error);
                }
            });
        });
    });
</script>



@endsection

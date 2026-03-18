@extends(theme('dashboard.layout'))

@section('content')
    @include(theme('dashboard.courses.course_nav'))

    <div class="row">
        <div class="col-md-12 mt-3">
            <div id="add-instructors-search-wrap" class="mb-4">

                <div id="instructor-search-wrap">

                    <form action="{{ route('multi_instructor_search', $course->id) }}" method="post" id="instructor-search-form">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="q" value="">
                                <p class="text-muted mb-2"><small>{{ __('search_instructors_desc') }}</small></p>
                                <div id="form-response-msg"></div>
                            </div>

                            <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-theme-primary btn-block">{{ __('search_instructors') }}</button>
                            </div>
                        </div>
                    </form>

                </div>

                <div id="instructor-search-results"></div>

            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="course-default-author-wrap  mb-5 p-4 d-flex">
                <!-- Author stats and information -->
            </div>

            @php
                $instructors = $course->instructors()->where('users.id', '!=', $course->user_id)->get();
            @endphp

            @if($instructors->count())
                <div id="added-instructors-wrap">
                    @foreach($instructors as $instructor)
                        <div class="added-instructor-wrap bg-white mb-3 p-4 d-flex">
                            <!-- Instructor details -->
                            <div class="remove-instructor-btn-wrap">
                                <form action="{{ route('remove_instructor', $course->id) }}" method="post">
                                    @csrf

                                    <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">

                                    <button type="submit" class="instructor-remove-btn btn btn-outline-danger"><i class="la la-trash"></i> </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

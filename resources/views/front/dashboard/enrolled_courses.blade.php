@extends('layouts.dashboard')

@section('content')
    <div class="card bg-transparent border rounded-3">
        @if ($enrolledCourses->count())
            <div class="col-12">
                <div class="card bg-transparent border rounded-3">
                    <!-- Card header START -->
                    <div class="card-header bg-transparent border-bottom">
                        <h3 class="mb-0">{{ tr('My Courses List') }}</h3>
                    </div>
                    <!-- Card header END -->

                    <!-- Card body START -->
                    <div class="card-body">

                        <!-- Course list table START -->
                        <div class="table-responsive border-0">
                            <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">
                                <!-- Table head -->
                                <thead>
                                    <tr>
                                        <th scope="col" class="border-0 rounded-start">{{ tr('My Courses List') }}
                                        </th>
                                        <th scope="col" class="border-0">{{ tr('My Courses List') }}</th>
                                        <th scope="col" class="border-0">{{ tr('Course Category') }}</th>
                                        <th scope="col" class="border-0 rounded-end">{{ tr('Action') }}</th>
                                    </tr>
                                </thead>

                                <!-- Table body START -->
                                <tbody>
                                    @foreach ($enrolledCourses->sortByDesc('created_at') as $course)
                                        <tr>
                                            <!-- Your course card content here -->

                                            <!-- Table data -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- Image -->
                                                    <div class="w-100px">
                                                        <img src="{{ $course->thumbnail_url }}" class="rounded"
                                                            alt="{{ $course->title }}">
                                                    </div>
                                                    <div class="mb-0 ms-2">
                                                        <!-- Title -->
                                                        <h6 class="table-responsive-title">
                                                            <a
                                                                href="{{ route('course', $course->slug) }}">{{ $course->title }}</a>
                                                        </h6>
                                                        <!-- Info -->


                                                        <div class="overflow-hidden">
                                                            <h6 class="mb-0 text-end">
                                                                {{ $course->completed_percent($auth_user) }}%</h6>
                                                            <div class="progress progress-sm  progress-bar bg-opacity-10"
                                                                style="
                                                    width: {{ $course->completed_percent($auth_user) }}%; min-width:10%;
                                                ">
                                                                <div class="progress-bar aos aos-init aos-animate"
                                                                    role="progressbar" data-aos="slide-right"
                                                                    data-aos-delay="200" data-aos-duration="1000"
                                                                    data-aos-easing="ease-in-out" style="width: 50%;"
                                                                    aria-valuenow="50" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Table data -->
                                            <td>{{ $course->total_lectures }}</td>

                                            <!-- Table data -->
                                            <td>{{ $course->completed_lectures }}

                                                @if ($course->second_category_id)
                                                    @php $category = App\Models\Category::find($course->second_category_id); @endphp
                                                    {{ $category->category_name }}
                                                @endif
                                            </td>

                                            <!-- Table data -->
                                            <td>
                                                @if ($course->status == 1)
                                                    <div class="mb-1 text-center col-12">
                                                        <a href="{{ route('course', $course->slug) }}"
                                                            class="btn btn-sm btn-primary-soft me-1 mb-1 mb-md-0 col-12">
                                                            <i class="bi bi-play-circle me-1"></i>
                                                            View Course
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($course->completed_percent() >= 100)
                                                    @php
                                                        $review = has_review($auth_user->id, $course->id);
                                                        $active_plugins = json_decode(
                                                            get_option('active_plugins'),
                                                            true,
                                                        );
                                                    @endphp
                                                    {{-- <div class="d-flex align-items-center col-12 mb-1">
                                                    <a href="#"
                                                        class="btn btn-sm btn-primary-soft mb-0 col-12"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#writeReviewModal">{{ $review ? __t('update_review') : __t('write_review') }}</a>
                                                </div> --}}

                                                    @if (is_array($active_plugins) && in_array('Certificate', $active_plugins))
                                                        @if (Auth::check())
                                                            @php
                                                                $user = Auth::user();
                                                                $isCourseComplete = $user->is_completed_course(
                                                                    $course->id,
                                                                );
                                                            @endphp

                                                            @php
                                                                $certURL = route('getCustomCertificate', $course->id); # route('download_certificate', $course->id);
                                                            @endphp

                                                            <div class="text-center col-12">
                                                                <a href="{{ $certURL }}" target="_blank"
                                                                    class="btn btn-sm btn-primary-soft me-1 mb-1 mb-md-0 col-12">
                                                                    <i class="la la-certificate"></i>
                                                                    {{ tr('Download Certificate') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!-- Table body END -->
                            </table>
                        </div>
                        <!-- Course list table END -->

                        <!-- Pagination START -->
                        <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                            <!-- Content -->
                            <p class="mb-0 text-center text-sm-start">
                                Showing {{ $enrolledCourses->firstItem() }} to {{ $enrolledCourses->lastItem() }} of
                                {{ $enrolledCourses->total() }} entries
                            </p>
                            <!-- Pagination -->
                            {{ $enrolledCourses->links('pagination::bootstrap-4') }}
                        </div>
                        <!-- Pagination END -->
                    </div>
                    <!-- Card body START -->
                </div>
            </div>
        @else
            <div class="card bg-light  mt-3 mb-3 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Enrollment Status</h5>
                    <p class="card-text">
                        {{ tr('You haven\'t enrolled in a course yet.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection

{{-- @include('front.dashboard.enrolled_courses') --}}

<div class="col-xl-12">
    <!-- Counter boxes START -->
    <div class="row mb-4">
        <!-- Counter item -->
        <div class="col-sm-6 col-lg-4 mb-3 mb-lg-0">
            <div class="d-flex justify-content-center align-items-center p-4 bg-orange bg-opacity-15 rounded-3">
                <span class="display-6 lh-1 text-orange mb-0"><i class="fas fa-tv fa-fw"></i></span>
                <div class="ms-4">
                    <div class="d-flex">
                        <h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                            data-purecounter-end="{{ $enrolledCount }}" data-purecounter-delay="200"
                            data-purecounter-duration="0">{{ $enrolledCount }}</h5>
                    </div>
                    <p class="mb-0 h6 fw-light">{{ tr('Total Courses') }}</p>
                </div>
            </div>
        </div>
        <!-- Counter item -->
        {{-- <div class="col-sm-6 col-lg-4 mb-3 mb-lg-0">
            <div class="d-flex justify-content-center align-items-center p-4 bg-purple bg-opacity-15 rounded-3">
                <span class="display-6 lh-1 text-purple mb-0"><i class="fas fa-clipboard-check fa-fw"></i></span>
                <div class="ms-4">
                    <div class="d-flex">
                        <h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0" data-purecounter-end="52"
                            data-purecounter-delay="200" data-purecounter-duration="0">{{ $wishListed }}</h5>
                    </div>
                    <p class="mb-0 h6 fw-light">{{ tr('Wishlisted lessons') }}</p>
                </div>
            </div>
        </div> --}}
        <!-- Counter item -->
        <div class="col-sm-6 col-lg-4 mb-3 mb-lg-0">
            <div class="d-flex justify-content-center align-items-center p-4 bg-success bg-opacity-10 rounded-3">
                <span class="display-6 lh-1 text-success mb-0"><i class="fas fa-medal fa-fw"></i></span>
                <div class="ms-4">
                    <div class="d-flex">
                        <h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                            data-purecounter-end="{{ $myReviewsCount }}" data-purecounter-delay="300"
                            data-purecounter-duration="0">{{ $myReviewsCount }}</h5>
                    </div>
                    <p class="mb-0 h6 fw-light">{{ tr('My Reviews') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card bg-transparent border rounded-3">
        <div class="col-12">
            <div class="card bg-transparent border rounded-3">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h3 class="mb-0">{{ tr('My Courses List') }}</h3>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Course filters">
                            <a href="{{ route('dashboard', ['filter' => 'my']) }}"
                                class="btn d-flex align-items-center gap-1 {{ $filter === 'my' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <span>My Courses</span>
                                <span class="badge bg-light text-dark">{{ $filterCounts['my'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'completed']) }}"
                                class="btn d-flex align-items-center gap-1 {{ $filter === 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <span>Completed</span>
                                <span class="badge bg-light text-dark">{{ $filterCounts['completed'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'enrolled']) }}"
                                class="btn d-flex align-items-center gap-1 {{ $filter === 'enrolled' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <span>Enrolled</span>
                                <span class="badge bg-light text-dark">{{ $filterCounts['enrolled'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'available']) }}"
                                class="btn d-flex align-items-center gap-1 {{ $filter === 'available' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <span>Available</span>
                                <span class="badge bg-light text-dark">{{ $filterCounts['available'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                @if ($courses->count())
                    <div class="card-body">
                        <div class="table-responsive border-0">
                            <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="border-0 rounded-start">{{ tr('My Courses List') }}
                                        </th>
                                        <th scope="col" class="border-0">{{ tr('My Courses List') }}</th>
                                        <th scope="col" class="border-0">{{ tr('Course Category') }}</th>
                                        <th scope="col" class="border-0 rounded-end">{{ tr('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($courses as $course)
                                        @php $completedPercent = $course->completed_percent($auth_user); @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="w-100px">
                                                        <img src="{{ $course->thumbnail_url }}" class="rounded"
                                                            alt="{{ $course->title }}">
                                                    </div>
                                                    <div class="mb-0 ms-2">
                                                        <h6 class="table-responsive-title">
                                                            <a href="{{ route('course', $course->slug) }}">{{ $course->title }}
                                                            </a>
                                                        </h6>
                                                        <div class="overflow-hidden">
                                                            <h6 class="mb-0 text-end">
                                                                {{ $completedPercent }}%</h6>
                                                            <div class="progress progress-sm bg-opacity-10"
                                                                style="width: 100%; min-width:10%;">
                                                                <div class="progress-bar aos aos-init aos-animate"
                                                                    role="progressbar" data-aos="slide-right"
                                                                    data-aos-delay="200" data-aos-duration="1000"
                                                                    data-aos-easing="ease-in-out"
                                                                    style="width: {{ $completedPercent }}%;"
                                                                    aria-valuenow="{{ $completedPercent }}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $course->total_lectures }}</td>
                                            <td>{{ $course->completed_lectures }}
                                                @if ($course->second_category_id)
                                                    @php $category = App\Models\Category::find($course->second_category_id); @endphp
                                                    {{ $category->category_name }}
                                                @endif
                                            </td>
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
                            </table>
                        </div>
                        <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                            <p class="mb-0 text-center text-sm-start">
                                Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of
                                {{ $courses->total() }} entries
                            </p>
                            {{ $courses->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @else
                    <div class="card-body">
                        <div class="alert alert-light border mb-0" role="alert">
                            {{ $emptyMessage }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

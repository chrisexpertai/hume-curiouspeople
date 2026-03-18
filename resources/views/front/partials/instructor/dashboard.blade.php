<!-- Main content START -->
<div class="col-xl-12">




    <!-- Counter boxes START -->
    <div class="row g-4">
        <!-- Counter item -->
        <div class="col-sm-6 col-lg-4">
            <div class="d-flex justify-content-center align-items-center p-4 bg-warning bg-opacity-15 rounded-3">
                <span class="display-6 text-warning mb-0"><i class="fas fa-tv fa-fw"></i></span>
                <div class="ms-4">
                    <div class="d-flex">
                        <h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                            data-purecounter-end="{{ $userCoursesCount }}" data-purecounter-delay="200">0</h5>
                    </div>
                    <span class="mb-0 h6 fw-light">{{ tr('Total Courses') }}</span>
                </div>
            </div>
        </div>
        <!-- Counter item -->
        <div class="col-sm-6 col-lg-4">
            <div class="d-flex justify-content-center align-items-center p-4 bg-purple bg-opacity-10 rounded-3">
                <span class="display-6 text-purple mb-0"><i class="fas fa-user-graduate fa-fw"></i></span>
                <div class="ms-4">
                    <div class="d-flex">
                        <h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                            data-purecounter-end="{{ $enrolledCount }}" data-purecounter-delay="200">0</h5>
                        <span class="mb-0 h5"></span>
                    </div>
                    <span class="mb-0 h6 fw-light">{{ tr('Total Students') }}</span>
                </div>
            </div>
        </div>
        <!-- Counter item -->
        <div class="col-sm-6 col-lg-4">
            <div class="d-flex justify-content-center align-items-center p-4 bg-info bg-opacity-10 rounded-3">
                <span class="display-6 text-info mb-0"><i class="fas fa-gem fa-fw"></i></span>
                <div class="ms-4">
                    <div class="d-flex">
                        <h5 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                            data-purecounter-end="{{ $myReviewsCount }}" data-purecounter-delay="300">
                            {{ $myReviewsCount }}</h5>
                        <span class="mb-0 h5"></span>
                    </div>
                    <span class="mb-0 h6 fw-light">{{ tr('Total Reviews') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Counter boxes END -->


    <!-- Chart START -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card card-body bg-transparent border p-4 h-100">
                <div class="row g-4">
                    <!-- Content -->


                    <!-- Apex chart -->
                    <div id="ChartPayout"></div>
                </div>
            </div>
        </div>
        <!-- Chart END -->


        @if ($auth_user->courses->count())
            <div class="table-responsive border-0">
                <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">
                    <!-- Table head -->
                    <thead>
                        <tr>
                            <th scope="col" class="border-0 rounded-start">{{ tr('Title') }}</th>
                            <th scope="col" class="border-0">{{ tr('Enrolled') }}</th>
                            <th scope="col" class="border-0">{{ tr('Status') }}</th>
                            <th scope="col" class="border-0">{{ tr('Price') }}</th>
                            <th scope="col" class="border-0 rounded-end">{{ tr('Action') }}</th>
                        </tr>
                    </thead>
                    <!-- Table body START -->
                    <tbody>
                        @foreach ($auth_user->courses as $course)
                            <tr>
                                <!-- Course item -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Image -->
                                        <div class="w-60px">
                                            <img src="{{ $course->thumbnail_url }}" class="rounded" width="60"
                                                alt="">
                                        </div>
                                        <div class="mb-0 ms-2">
                                            <!-- Title -->
                                            <h6><a
                                                    href="{{ route('course', $course->slug) }}">{{ $course->title }}</a>
                                            </h6>
                                            <!-- Info -->
                                            <div class="d-sm-flex">
                                                <p class="h6 fw-light mb-0 small me-3"><i
                                                        class="fas fa-table text-orange me-2"></i>{{ $course->lectures->count() }}
                                                    {{ __t('lectures') }}</p>
                                                <!-- You may need to adjust the condition based on your business logic -->
                                                {!! $course->status_html() !!}

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Enrolled item -->
                                <td class="text-center text-sm-start"><?php echo number_format($course->enrolled_students); ?></td>
                                <!-- Status item -->
                                <td>
                                    <!-- You may need to adjust the condition based on your business logic -->
                                    @if ($course->status == 1)
                                        <div class="badge bg-warning bg-opacity-10 text-warning">{{ tr('Published') }}
                                        </div>
                                    @else
                                        <div class="badge bg-info bg-opacity-10 text-info">{{ tr('Preview') }}</div>
                                    @endif
                                </td>
                                <!-- Price item -->
                                <td>{!! $course->price_html() !!}</td>
                                <!-- Action item -->


                                <form action="{{ route('courses.delete', $course->id) }}" method="post">

                                    <td>
                                        <a href="{{ route('edit_course_information', $course->id) }}"
                                            class="btn btn-sm btn-success-soft btn-round me-1 mb-0"><i
                                                class="far fa-fw fa-edit"></i></a>
                                        <!-- Add the delete button here -->
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger-soft btn-round mb-0"><i
                                                class="fas fa-fw fa-times"></i></button>
                                    </td>

                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                    <!-- Table body END -->
                </table>
            </div>
        @else
            {!! no_data() !!}
            <div class="no-data-wrap text-center">
                <a href="{{ route('create_course') }}" class="btn btn-lg btn-primary">{{ __t('create_course') }}</a>
            </div>
        @endif
    </div>
    <!-- Main content END -->
</div>

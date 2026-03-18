@extends('layouts.admin') <!-- Assuming you have a main layout file -->

@section('content')
@php
$user_id = $auth_user->id;

$enrolledCount = \App\Models\Enroll::whereUserId($user_id)->whereStatus('success')->count();
$wishListed = \Illuminate\Support\Facades\DB::table('wishlists')->whereUserId($user_id)->count();

$myReviewsCount = \App\Models\Review::whereUserId($user_id)->count();
$purchases = $auth_user->purchases()->take(10)->get();
@endphp

<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-2 mb-sm-0">{{ tr('Students') }}</h1>
        </div>
    </div>

    <div class="card bg-transparent">

        <!-- Card header START -->
        <div class="card-header bg-transparent border-bottom px-0">
            <!-- Search and select START -->
            <div class="row g-3 align-items-center justify-content-between">

                <!-- Search bar -->
                <div class="col-md-8">
                    <form class="rounded position-relative" action="{{ route('search.students') }}" method="GET">
                        <input class="form-control bg-transparent" type="search" placeholder="{{ tr('Search') }}" aria-label="Search" name="query">
                        <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                            <i class="fas fa-search fs-6 "></i>
                        </button>
                    </form>
                </div>

                <!-- Tab button -->
                <div class="col-md-3">
                    <!-- Tabs START -->
                    <ul class="list-inline mb-0 nav nav-pills nav-pill-dark-soft border-0 justify-content-end" id="pills-tab" role="tablist">
                        <!-- Grid tab -->
                        <li class="nav-item" role="presentation">
                            <a href="#nav-preview-tab-1" class="nav-link mb-0 me-2 active" data-bs-toggle="tab" aria-selected="true" role="tab">
                                <i class="fas fa-fw fa-th-large"></i>
                            </a>
                        </li>
                        {{-- <!-- List tab -->
                        <li class="nav-item" role="presentation">
                            <a href="#nav-html-tab-1" class="nav-link mb-0" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                                <i class="fas fa-fw fa-list-ul"></i>
                            </a>
                        </li>
                    </ul> --}}
                    <!-- Tabs end -->
                </div>
            </div>
            <!-- Search and select END -->
        </div>
        <!-- Card header END -->

        <!-- Card body START -->
        <div class="card-body px-0">

            <!-- Tabs content START -->
            <div class="tab-content">

                <!-- Tabs content item START -->
                <div class="tab-pane fade show active" id="nav-preview-tab-1" role="tabpanel">
                    <div class="row g-4">


        @foreach ($students as $student)


                        <!-- Card item START -->
                        <div class="col-md-6 col-xxl-4">
                            <div class="card bg-transparent border h-100">
                                <!-- Card header -->
                                <div class="card-header bg-transparent border-bottom d-flex justify-content-between">
                                    <div class="d-sm-flex align-items-center">
                                        <!-- Avatar -->
                                        <div class="avatar avatar-md flex-shrink-0">
                                            <img class="avatar-img rounded-circle" src=" {{ $student->get_photo }}" alt="avatar">
                                        </div>
                                        <!-- Info -->
                                        <div class="ms-0 ms-sm-2 mt-2 mt-sm-0">
                                            <h5 class="mb-0"><a href="#">{{ $student->name }}</a></h5>
                                            <span class="text-body small"><i class="fas fa-fw fa-map-marker-alt me-1 mt-1"></i> {{ $student->city }}</span>
                                        </div>
                                    </div>

                                    <!-- Edit dropdown -->
                                    <div class="dropdown text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-round small mb-0" role="button" id="dropdownShare2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots fa-fw"></i>
                                        </a>
                                        <!-- dropdown button -->
                                        <ul class="dropdown-menu dropdown-w-sm dropdown-menu-end min-w-auto shadow rounded" aria-labelledby="dropdownShare2">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square fa-fw me-2"></i>{{ tr('Edit') }}</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-trash fa-fw me-2"></i>{{ tr('Remove') }}</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Payments -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-md bg-success bg-opacity-10 text-success rounded-circle flex-shrink-0"><i class="bi bi-currency-dollar fa-fw"></i></div>
                                            <h6 class="mb-0 ms-2 fw-light">{{ tr('Payments') }}</h6>
                                        </div>
                                        <span class="mb-0 fw-bold"> ${{ $student->totalAmountSpent }}</span>
                                    </div>

                                    <!-- Total courses -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-md bg-purple bg-opacity-10 text-purple rounded-circle flex-shrink-0"><i class="fas fa-book fa-fw"></i></div>
                                            <h6 class="mb-0 ms-2 fw-light">{{ tr('Total Course') }}</h6>
                                        </div>
                                        <span class="mb-0 fw-bold">{{ $student->enrolled_courses_count }}</span>
                                    </div>

                                    @if ($student->subscription)

                                    @if ($student->hasSubscriptionExpired())
                                      <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-md bg-purple bg-opacity-10 text-purple rounded-circle flex-shrink-0"><i class="fas fa-book fa-fw"></i></div>
                                            <h6 class="mb-0 ms-2 fw-light">{{ tr('Subscribed Plan') }}</h6>
                                        </div>
                                        <span class="mb-0 fw-bold">{{ tr('Expired') }}</span>
                                    </div>

                                    @else

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-md bg-purple bg-opacity-10 text-purple rounded-circle flex-shrink-0"><i class="fas fa-book fa-fw"></i></div>
                                            <h6 class="mb-0 ms-2 fw-light">{{ tr('Subscribed Plan') }}</h6>
                                        </div>
                                        <span class="mb-0 fw-bold">{{ $student->subscription->name }}</span>
                                    </div>


                                    @php
                                        // Calculate total duration of the subscription
                                        $totalDuration = strtotime($student->subscription_end_date) - strtotime($auth_user->subscription_start_date);

                                        // Calculate remaining duration from current date to end date
                                        $remainingDuration = strtotime($student->subscription_end_date) - time();

                                        // Calculate progress percentage
                                        $progressPercentage = ($remainingDuration / $totalDuration) * 100;
                                    @endphp



                                    <!-- Progress -->
                                    <div class="overflow-hidden">
                                        <h6 class="mb-0">{{ round($progressPercentage) }}%</h6>
                                        <div class="progress progress-sm bg-primary bg-opacity-10">
                                            <div class="progress-bar bg-primary aos aos-init aos-animate" role="progressbar" data-aos="slide-right" data-aos-delay="200" data-aos-duration="1000" data-aos-easing="ease-in-out" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                        </div>
                                    </div>

                                @endif

                            </div>

                            @else
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-md bg-purple bg-opacity-10 text-purple rounded-circle flex-shrink-0"><i class="fas fa-book fa-fw"></i></div>
                            <h6 class="mb-0 ms-2 fw-light">{{ tr('Subscribed Plan') }}</h6>
                        </div>
                        <span class="mb-0 fw-bold">{{ tr('N/A') }}</span>
             </div>

            </div>
                    @endif

                                <!-- Card footer -->
                                <div class="card-footer bg-transparent border-top">
                                    <div class="d-sm-flex justify-content-between align-items-center">
                                        <!-- Rating star -->
                                        <h6 class="mb-2 mb-sm-0">
                                            <i class="bi bi-calendar fa-fw text-orange me-2"></i> @if($student->created_at)
                                            <i class="bi bi-calendar fa-fw text-orange me-2"></i>
                                            <span class="text-body">{{ tr('Join at') }}:</span> {{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}
                                        @else
                                            <span class="text-body">{{ tr('Join date not available') }}</span>
                                        @endif


                                        </h6>
                                        {{-- <!-- Buttons -->
                                        <div class="text-end text-primary-hover">
                                            <a href="#" class="btn btn-link text-body p-0 mb-0 me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Message" aria-label="Message">
                                                <i class="bi bi-envelope-fill"></i>
                                            </a>
                                            <a href="#" class="btn btn-link text-body p-0 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Block" aria-label="Block">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card item END -->

                        @endforeach

                    </div>
                </div>
                <!-- Tabs content item END -->

            </div>
            <!-- Tabs content END -->
        </div>
        <!-- Card body END -->

        <!-- Card footer START -->
        <div class="card-footer bg-transparent pt-0 px-0">
            <!-- Pagination START -->
            <div class="d-sm-flex justify-content-sm-between align-items-sm-center">
                <!-- Content -->
                <p class="mb-0 text-center text-sm-start">{{ tr('Showing') }} {{ $students->firstItem() }} {{ tr('to') }} {{ $students->lastItem() }} {{ tr('of') }} {{ $students->total() }} {{ tr('entries') }}</p>
                <!-- Pagination -->
                {{ $students->links() }}

            </div>
            <!-- Pagination END -->
        </div>
        <!-- Card footer END -->
    </div>
</div>










@endsection

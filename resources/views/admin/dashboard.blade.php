@extends('layouts.admin')

@section('content')
    @php
        $userCount = \App\Models\User::count();
        $totalInstructors = \App\Models\User::whereUserType('instructor')->count();
        $totalStudents = \App\Models\User::whereUserType('student')->count();
        $courseCount = \App\Models\Course::publish()->count();
        $lectureCount = \App\Models\Content::whereItemType('lecture')->count();
        $quizCount = \App\Models\Content::whereItemType('quiz')->count();
        $assignmentCount = \App\Models\Content::whereItemType('assignment')->count();
        $questionCount = \App\Models\Discussion::whereDiscussionId(0)->count();
        $totalEnrol = \App\Models\Enroll::whereStatus('success')->count();
        $totalReview = \App\Models\Review::count();
        $totalAmount = \App\Models\Payment::whereStatus('success')->sum('amount');
        $withdrawsTotal = \App\Models\Withdraw::whereStatus('approved')->sum('amount');
        $payments = \App\Models\Payment::query()->orderBy('id', 'desc')->take(20)->get();

    @endphp

    <!-- Page main content START -->
    <div class="page-content-wrapper border">

        <!-- Title -->
        <div class="row">
            <div class="col-12 mb-3">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Dashboard') }}</h1>
            </div>
        </div>

        <!-- Counter boxes START -->
        <div class="row g-4 mb-4">
            <!-- Counter item -->
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-warning bg-opacity-15 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $courseCount }}" data-purecounter-delay="200">{{ $courseCount }}
                            </h2>
                            <span class="mb-0 h6 fw-light">{{ tr('Published Courses') }}</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-warning text-white mb-0"><i class="fas fa-tv fa-fw"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-warning bg-opacity-15 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $quizCount }}" data-purecounter-delay="200">{{ $quizCount }}
                            </h2>
                            <span class="mb-0 h6 fw-light">{{ tr('Quiz') }}</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-warning text-white mb-0"><i class="fas fa-tv fa-fw"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-warning bg-opacity-15 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $assignmentCount }}" data-purecounter-delay="200">
                                {{ $assignmentCount }}</h2>
                            <span class="mb-0 h6 fw-light">{{ tr('Assignments') }}</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-warning text-white mb-0"><i class="fas fa-tv fa-fw"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-warning bg-opacity-15 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $questionCount }}" data-purecounter-delay="200">
                                {{ $questionCount }}</h2>
                            <span class="mb-0 h6 fw-light">Questions</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-warning text-white mb-0"><i class="fas fa-tv fa-fw"></i></div>
                    </div>
                </div>
            </div>

            <!-- Counter item -->
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-purple bg-opacity-10 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $totalStudents }}" data-purecounter-delay="200">0</h2>
                            <span class="mb-0 h6 fw-light">{{ tr('Students') }}</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-purple text-white mb-0"><i class="fas fa-user-tie fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counter item -->
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-secondary bg-opacity-10 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $totalEnrol }}" data-purecounter-delay="200">0</h2>
                            <span class="mb-0 h6 fw-light">{{ tr('Enrolled') }}</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-secondary text-white mb-0"><i
                                class="fas fa-user-graduate fa-fw"></i></div>
                    </div>
                </div>
            </div>
            <!-- Counter item -->
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-primary bg-opacity-10 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h2 class="purecounter mb-0 fw-bold" data-purecounter-start="0"
                                data-purecounter-end="{{ $totalReview }}" data-purecounter-delay="200"></h2>
                            <span class="mb-0 h6 fw-light">{{ tr('Reviews') }}</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-primary text-white mb-0"><i
                                class="fas fa-user-graduate fa-fw"></i></div>
                    </div>
                </div>
            </div>
            <!-- Counter item -->
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-success bg-opacity-10 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex">
                                <span class="mb-0 h3 ms-1">{!! get_curr_symbol() !!}</span>
                                <h3 class="mb-0 fw-bold">{!! counter_format($totalAmount) !!}</h3>
                            </div>
                            <span class="mb-0 h6 fw-light">{{ tr('Total Income') }}</span>
                        </div>
                        <div class="icon-lg rounded-circle bg-success text-white mb-0"><i
                                class="bi bi-stopwatch-fill fa-fw"></i></div>
                    </div>
                </div>
            </div>


        </div>
        <!-- Counter boxes END -->

        <!-- Chart and Ticket START -->
        <div class="row g-4 mb-4">


            <!-- Include ApexCharts library -->
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

            <!-- Chart START -->
            <div class="col-xxl-8">
                <div class="card shadow h-100">
                    <div class="card-header p-4 border-bottom">
                        <h5 class="card-header-title">{{ tr('Earnings') }}</h5>
                    </div>

                    <div class="card-body">
                        <div id="ChartPayout"></div>
                    </div>
                </div>
            </div>
            <!-- Chart END -->

            <script>
                // JavaScript code to initialize ApexCharts for dashboardChart
                document.addEventListener('DOMContentLoaded', function() {
                    var ac = document.querySelector('#ChartPayout');
                    var cardBody = ac.closest('.card-body');
                    if (ac && cardBody) {
                        var cardHeight = cardBody.clientHeight - 60; // Adjust for padding and margins
                        var options = {
                            series: [{
                                name: 'Payout',
                                data: {!! json_encode(array_values($chartData)) !!}
                            }],
                            chart: {
                                height: cardHeight,
                                type: 'area',
                                toolbar: {
                                    show: false
                                }
                            },
                            dataLabels: {
                                enabled: true
                            },
                            stroke: {
                                curve: 'smooth'
                            },
                            colors: ['#216094'],
                            xaxis: {
                                type: 'Payout',
                                categories: {!! json_encode(array_keys($chartData)) !!},
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                }
                            },
                            yaxis: [{
                                axisTicks: {
                                    show: false
                                },
                                axisBorder: {
                                    show: false
                                }
                            }],
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return '{{ get_currency() }}' + val
                                    }

                                },
                                marker: {
                                    show: false
                                }
                            }
                        };

                        var chart = new ApexCharts(ac, options);
                        chart.render();
                    }
                });
            </script>

            <!-- Ticket START -->
            <div class="col-xxl-4">
                <div class="card shadow h-100">
                    <!-- Card header -->
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center p-4">
                        <h5 class="card-header-title">{{ tr('Support Requests') }}</h5>
                        <a href="{{ route('admin.tickets.index') }}"
                            class="btn btn-link p-0 mb-0">{{ tr('View all') }}</a>
                    </div>

                    <!-- Card body START -->
                    <div class="card-body p-4">
                        @foreach ($tickets as $ticket)
                            <!-- Ticket item START -->
                            <div class="d-flex justify-content-between position-relative">
                                <div class="d-sm-flex">
                                    <!-- Avatar -->
                                    <div class="avatar avatar-md flex-shrink-0">
                                        <!-- Assuming you have a user avatar, replace the src attribute with the user's avatar -->
                                        <img class="avatar-img rounded-circle" src="{{ $ticket->user->get_photo }}"
                                            alt="avatar">
                                    </div>
                                    <!-- Info -->
                                    <div class="ms-0 ms-sm-2 mt-2 mt-sm-0">
                                        <h6 class="mb-0"><a href="{{ route('admin.tickets.view', $ticket->id) }}"
                                                class="stretched-link">{{ $ticket->user->name }}</a></h6>
                                        <p class="mb-0">{{ $ticket->subject }}</p>
                                        <span class="small">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Ticket item END -->

                            <hr><!-- Divider -->
                        @endforeach
                    </div>
                    <!-- Card body END -->
                </div>
            </div>
            <!-- Ticket END -->

        </div>
        <!-- Chart and Ticket END -->

        <!-- Top listed Cards START -->
        <div class="row g-4">

            <!-- Top instructors START -->
            <div class="col-lg-6 col-xxl-4">
                <div class="card shadow h-100">

                    <!-- Card header -->
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center p-4">
                        <h5 class="card-header-title">{{ tr('View all') }}</h5>
                        <a href="#" class="btn btn-link p-0 mb-0">{{ tr('View all') }}</a>
                    </div>

                    <!-- Card body START -->
                    <div class="card-body p-4">

                        @foreach ($instructors as $instructor)
                            @php
                                $courses_count = $instructor->courses()->publish()->count();
                                $students_count = $instructor->student_enrolls->count();
                                $instructor_rating = $instructor->get_rating;
                                $rating = $instructor->get_rating;

                            @endphp

                            <!-- Instructor item START -->
                            <div class="d-sm-flex justify-content-between align-items-center">
                                <!-- Avatar and info -->
                                <div class="d-sm-flex align-items-center mb-1 mb-sm-0">
                                    <!-- Avatar -->
                                    <div class="avatar avatar-md flex-shrink-0">
                                        <img class="avatar-img rounded-circle" src="{!! $instructor->get_photo !!}"
                                            alt="avatar">
                                    </div>
                                    <!-- Info -->
                                    <div class="ms-0 ms-sm-2 mt-2 mt-sm-0">
                                        <h6 class="mb-1">{{ $instructor->name }}<i
                                                class="bi bi-patch-check-fill text-info small ms-1"></i></h6>
                                        <ul class="list-inline mb-0 small">
                                            <li class="list-inline-item fw-light me-2 mb-1 mb-sm-0"><i
                                                    class="fas fa-book text-purple me-1"></i>{{ $courses_count }}
                                                {{ tr('Courses') }}</li>
                                            <li class="list-inline-item fw-light me-2 mb-1 mb-sm-0"><i
                                                    class="fas fa-star text-warning me-1"></i> {{ $rating->rating_avg }}
                                                </5.00< /li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Button -->
                                <a href="{{ route('profile', $instructor->id) }}"
                                    class="btn btn-sm btn-light mb-0">{{ tr('View') }}</a>
                            </div>
                            <!-- Instructor item END -->
                            <hr><!-- Divider -->
                        @endforeach

                    </div>
                    <!-- Card body END -->
                </div>
            </div>
            <!-- Top instructors END -->

            <!-- Notice Board START -->
            <div class="col-lg-6 col-xxl-4">
                <div class="card shadow h-100">
                    <!-- Card header -->
                    <div class="card-header border-bottom p-4">
                        <h5 class="card-header-title">{{ tr('Notice board') }}</h5>
                    </div>

                    <!-- Card body START -->
                    <div class="card-body p-4">
                        <div class="custom-scrollbar h-300px">

                            @foreach ($adminNotifications->take(3) as $notification)
                                <!-- Notice Board item START -->
                                <div class="d-flex justify-content-between position-relative">
                                    <div class="d-sm-flex">
                                        <div class="icon-lg bg-purple bg-opacity-10 text-purple rounded-2 flex-shrink-0"><i
                                                class="fas fa-user-tie fs-5"></i></div>
                                        <!-- Info -->
                                        <div class="ms-0 ms-sm-3 mt-2 mt-sm-0">
                                            <h6 class="mb-0"><a href="#"
                                                    class="stretched-link">{{ $notification->type }}</a></h6>
                                            <p class="mb-0">{{ str_limit($notification->message, 33) }}</p>
                                            <span
                                                class="small">{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Notice Board item END -->

                                <hr><!-- Divider -->
                            @endforeach




                        </div>
                    </div>
                    <!-- Card body END -->

                    <!-- Card footer START -->
                    <div class="card-footer border-top">
                        <div class="alert alert-success d-flex align-items-center mb-0 py-2">
                            <div>
                                <small class="mb-0">{{ tr('More notices listed') }}</small>
                            </div>
                            <div class="ms-auto">
                                <a class="btn btn-sm btn-success-soft mb-0"
                                    href="{{ route('admin.notifications') }}">{{ tr('View all') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card footer START -->
                </div>
            </div>
            <!-- Notice Board END -->


            <!-- Traffic sources START -->
            <div class="col-lg-6 col-xxl-4">
                <div class="card shadow h-100">

                    <!-- Card header -->
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center p-4">
                        <h5 class="card-header-title">Traffic</h5>
                        <a href="#" class="btn btn-link p-0 mb-0">{{ tr('View all') }}</a>
                    </div>

                    <!-- Card body START -->
                    <div class="card-body p-4">
                        <!-- Chart -->

                        <div id="chart"></div>

                        <script>
                            console.log(
                                {!! collect($websiteViews)->map(function ($v) {
                                    return \Carbon\Carbon::parse($v->date)->format('j');
                                }) !!}
                            );
                            var options = {
                                series: [{
                                    name: 'Website Views',
                                    data: {!! json_encode(
                                        collect($websiteViews)->map(function ($v) {
                                            return $v->visits_user;
                                        }),
                                    ) !!}
                                }],
                                chart: {
                                    type: 'line',
                                    height: 350
                                },
                                xaxis: {
                                    categories: {!! collect($websiteViews)->map(function ($v) {
                                        return \Carbon\Carbon::parse($v->date)->format('M j');
                                    }) !!}
                                }
                            };

                            var chart = new ApexCharts(document.querySelector("#chart"), options);
                            chart.render();
                        </script>

                        <!-- Card body END -->
                    </div>
                    <!-- Traffic sources END -->

                </div>
                <!-- Top listed Cards END -->

            </div>
            <!-- Page main content END -->
        @endsection


        @section('page-js')
            <script src="{{ asset('assets/js/chart.min.js') }}"></script>

            <script>
                // var ctx = document.getElementById("ChartArea").getContext('2d');
                // var ChartArea = new Chart(ctx, {
                //     type: 'line',
                //     data: {
                //         labels: {!! json_encode(array_keys($chartData)) !!},
                //         datasets: [{
                //             label: 'Earning ',
                //             backgroundColor: '#216094',
                //             borderColor: '#216094',
                //             data: {!! json_encode(array_values($chartData)) !!},
                //             borderWidth: 2,
                //             fill: false,
                //             lineTension: 0,
                //         }]
                //     },
                //     options: {
                //         scales: {
                //             yAxes: [{
                //                 ticks: {
                //                     min: 0, // it is for ignoring negative step.
                //                     beginAtZero: true,
                //                     callback: function(value, index, values) {
                //                         return '{{ get_currency() }} ' + value;
                //                     }
                //                 }
                //             }]
                //         },
                //         tooltips: {
                //             callbacks: {
                //                 label: function(t, d) {
                //                     var xLabel = d.datasets[t.datasetIndex].label;
                //                     var yLabel = t.yLabel >= 1000 ? '$' + t.yLabel.toString().replace(
                //                         /\B(?=(\d{3})+(?!\d))/g, ",") : '{{ get_currency() }} ' + t.yLabel;
                //                     return xLabel + ': ' + yLabel;
                //                 }
                //             }
                //         },
                //         legend: {
                //             display: false
                //         }
                //     }
                // });
            </script>
        @endsection

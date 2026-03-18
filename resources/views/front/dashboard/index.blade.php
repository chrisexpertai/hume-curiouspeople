@extends('layouts.dashboard')

@section('content')
    @php

        $followings = $auth_user->following();
        $followers = $auth_user->followers();
        $authUserIsFollower = false;
        $authUserIsFollower = $followers
            ->where('follower', auth()->id())
            ->where('status', App\Models\Follow::$accepted)
            ->first();
        $followersCount = $followers->count();

        $user_id = $auth_user->id;
        $userCoursesCount = $auth_user->courses()->count();

        $enrolledCount = \App\Models\Enroll::whereUserId($user_id)->whereStatus('success')->count();
        $wishListed = \Illuminate\Support\Facades\DB::table('wishlists')->whereUserId($user_id)->count();

        $myReviewsCount = \App\Models\Review::whereUserId($user_id)->count();
        $purchases = $auth_user->purchases()->take(10)->get();
    @endphp

    @if ($auth_user->is_instructor)
        @include('front.partials.instructor.dashboard')
    @else
        @include('front.partials.student.dashboard')
    @endif
@endsection

@section('page-js')
    @if ($chartData)
        <script>
            function dashboardChart() {
                var ac = e.select('#ChartPayout');
                if (e.isVariableDefined(ac)) {
                    var options = {
                        series: [{
                            name: 'Earnings',
                            data: {!! json_encode(array_values($filteredChartData)) !!}
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            toolbar: {
                                show: false
                            },
                        },
                        dataLabels: {
                            enabled: true
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        colors: [ThemeColor.getCssVariableValue('--bs-primary')],
                        xaxis: {
                            type: 'Payout',
                            categories: {!! json_encode(array_keys($filteredChartData)) !!},
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            },
                        },
                        yaxis: [{
                            axisTicks: {
                                show: false
                            },
                            axisBorder: {
                                show: false
                            },
                        }],
                        tooltip: {
                            y: {
                                title: {
                                    formatter: function(e) {
                                        return "{{ get_currency() }} ";
                                    }
                                }
                            },
                            marker: {
                                show: false
                            }
                        }
                    };
                    var chart = new ApexCharts(document.querySelector("#ChartPayout"), options);
                    chart.render();
                }
            }

            // Call the dashboardChart function
            dashboardChart();
        </script>
    @endif
@endsection

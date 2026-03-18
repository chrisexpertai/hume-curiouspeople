@extends(('layouts.dashboard'))

@section('content')

<div class="mb-4 border-bottom">
    <a href="{{ route('earning') }}" class="btn btn-link">{{ __t('earnings') }}</a>
    <a href="{{ route('earning_report') }}" class="btn btn-link active">{{ __t('report_statements') }}</a>
</div>
<div class="btn-group mb-4" role="group" aria-label="Time Period Filters">
    {{-- <button type="button" class="btn btn-outline-secondary {{ request('time_period') == 'last_year' ? 'active' : '' }}" onclick="window.location.href='{{ route('earning_report', ['time_period' => 'last_year']) }}'">Last Year</button>
    <button type="button" class="btn btn-outline-secondary {{ request('time_period') == 'this_year' ? 'active' : '' }}" onclick="window.location.href='{{ route('earning_report', ['time_period' => 'this_year']) }}'">This Year</button> --}}
    <button type="button" class="btn btn-outline-secondary {{ request('time_period') == 'last_month' ? 'active' : '' }}" onclick="window.location.href='{{ route('earning_report', ['time_period' => 'last_month']) }}'">Last Month</button>
    <button type="button" class="btn btn-outline-secondary {{ request('time_period') == 'this_month' ? 'active' : '' }}" onclick="window.location.href='{{ route('earning_report', ['time_period' => 'this_month']) }}'">This Month</button>
    <button type="button" class="btn btn-outline-secondary {{ request('time_period') == 'last_week' ? 'active' : '' }}" onclick="window.location.href='{{ route('earning_report', ['time_period' => 'last_week']) }}'">Last Week</button>
    <button type="button" class="btn btn-outline-secondary {{ request('time_period') == 'this_week' ? 'active' : '' }}" onclick="window.location.href='{{ route('earning_report', ['time_period' => 'this_week']) }}'">This Week</button>
</div>

<form action="" method="get">
    <div class="input-group mb-4">
        <input type="hidden" name="time_period" value="date_range">

        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="text" class="form-control date_picker" name="date_from" placeholder="From" value="{{ request('date_from') }}">

        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="text" class="form-control date_picker" name="date_to" placeholder="To" value="{{ request('date_to') }}">

        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">{{ __t('filter_results') }}</button>
        </div>
    </div>
</form>



    <h4 class="mb-4">{!! $page_title !!}</h4>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card card-body mb-4">
                <h6 class="text-muted text-uppercase">{{ tr('Sales') }}</h6>
                <h4 class="earning-stats amount">{!! price_format($total_amount) !!}</h4>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-body mb-4">
                <h6 class="text-muted text-uppercase">{{ tr('Earnings') }}</h6>
                <h4 class="earning-stats amount">{!! price_format($total_earning) !!}</h4>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-body mb-4">
                <h6 class="text-muted text-uppercase">{{ tr('Commission Deducted') }}</h6>
                <h4 class="earning-stats amount">{!! price_format($commission) !!}</h4>
            </div>
        </div>

    </div>


    <div class="p-4 bg-white">
        <div id="chart"></div>
    </div>


    <div class="statements-report my-5">


        @if($statements->count())
            <h4 class="my-4">{{ tr('Statements showing by selected time period') }}</h4>
            <p class="text-muted my-3"> {{ tr('Showing') }} {{$statements->count()}} from {{$statements->total()}} {{ tr('results') }} </p>

            <table class="table">
                <thead>
                <tr>
                    <th>{{ tr('Course Details') }}</th>
                    <th>{{ tr('Earning') }}</th>
                    <th>{{ tr('Commission') }}</th>
                </tr>
                </thead>

                @foreach($statements as $statement)
                    <tr>
                        <td>
                            @if($statement->course)
                                <h5>
                                    <a href="{{route('course', $statement->course->slug )}}" target="_blank">
                                        {{$statement->course->title}}
                                    </a>
                                </h5>

                            @else
                                <h5 class="text-muted">{{ tr('Course not found, either deleted or removed') }}</h5>
                            @endif

                            <p class="mb-0">Price: {!! price_format($statement->amount) !!}</p>
                            <p class="text-muted mb-0">
                                <small>Payment #{{$statement->payment->id}}</small>
                                {!! $statement->payment->status_context !!}
                                <small>Date: {!! $statement->created_at->format(date_time_format()) !!}</small>
                            </p>

                            @if( ! empty($statement->payment->user))
                                <h5 class="my-3"><i class="la la-user"></i> Customer</h5>

                                <p class="mb-0">{{$statement->payment->user->name}} </p>
                                <p class="text-muted mb-0">
                                    <small>
                                        @if($statement->payment->user->address)
                                            {{$statement->payment->user->address}},
                                        @endif
                                        @if($statement->payment->user->address_2)
                                            {{$statement->payment->user->address_2}},
                                        @endif
                                        @if($statement->payment->user->city)
                                            {{$statement->payment->user->city}},
                                        @endif
                                        @if($statement->payment->user->zip_code)
                                            {{$statement->payment->user->zip_code}}
                                        @endif
                                    </small>
                                </p>
                                @if($statement->payment->user->country)
                                    <small class="text-muted">
                                        {{$statement->payment->user->country->name}}
                                    </small>
                                @endif


                            @endif
                        </td>
                        <td>

                            <p class="mb-0">
                                {!! price_format($statement->instructor_amount) !!}
                            </p>
                            <p class="text-muted mb-0"><small>{{ tr('As per') }} {{$statement->instructor_share}} {{ tr('(percent)') }}</small>
                            </p>


                        </td>
                        <td>
                            <p class="mb-0">
                                {!! price_format($statement->admin_amount) !!}
                            </p>
                            <p class="text-muted mb-0"><small>{{ tr('As per') }}{{$statement->admin_share}} {{ tr('(percent)') }}</small></p>
                        </td>
                    </tr>

                @endforeach

            </table>

        @else

            {!! no_data(__t('no_statement_found')) !!}

        @endif

        <div class="mt-5">
            {!! $statements->appends(request()->input())->links() !!}
        </div>

    </div>

@endsection


@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css')}}">
@endsection


@section('page-js')

    <script src="{{asset('assets/js/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{asset('assets/plugins/apexcharts/apexcharts.min.js')}}"></script>

    <script>
        var options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Earning',
                data: {!! json_encode(array_values($chartData)) !!}
            }],
            xaxis: {
                categories: {!! json_encode(array_keys($chartData)) !!}
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return '{{ get_currency() }}' + value;
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

    <script>
        $(function () {
            $('.date_picker').datetimepicker({format: 'YYYY-MM-DD'});
        });
    </script>

@endsection

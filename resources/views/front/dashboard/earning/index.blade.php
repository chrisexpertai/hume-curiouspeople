@extends(('layouts.dashboard'))

@section('content')
<div class="mb-4 border-bottom">
    <a href="{{ route('earning') }}" class="btn btn-link">{{ __t('earnings') }}</a>
    <a href="{{ route('earning_report') }}" class="btn btn-link active">{{ __t('report_statements') }}</a>
</div>

<div class="row g-4">
    <!-- Lifetime Sales -->
    <div class="col-sm-6 col-lg-4">
        <div class="text-center p-4 bg-light rounded-3">
            <h6 class="text-body">{{ tr('Lifetime Sales') }}</h6>
            <h2 class="mb-0 fs-1">{!! price_format($user->earning->sales_amount) !!}</h2>
        </div>
    </div>

    <!-- Lifetime Earnings -->
    <div class="col-sm-6 col-lg-4">
        <div class="text-center p-4 bg-light rounded-3">
            <h6 class="text-body">{{ tr('Lifetime Earnings') }}</h6>
            <h2 class="mb-0 fs-1">{!! price_format($user->earning->earnings) !!}</h2>
        </div>
    </div>

    <!-- Commission Deducted -->
    <div class="col-sm-6 col-lg-4">
        <div class="text-center p-4 bg-light rounded-3">
            <h6 class="text-body">{{ tr('Commission Deducted') }}</h6>
            <h2 class="mb-0 fs-1">{!! price_format($user->earning->commission) !!}</h2>
        </div>
    </div>

    <!-- Lifetime Withdrawals -->
    <div class="col-sm-6 col-lg-4">
        <div class="text-center p-4 bg-light rounded-3">
            <h6 class="text-body">{{ tr('Lifetime Withdrawals') }}</h6>
            <h2 class="mb-0 fs-1">{!! price_format($user->earning->withdrawals) !!}</h2>
        </div>
    </div>

    <!-- Balance -->
    <div class="col-sm-6 col-lg-4">
        <div class="text-center p-4 bg-light rounded-3">
            <h6 class="text-body">{{ tr('Balance') }}</h6>
            <h2 class="mb-0 fs-1">{!! price_format($user->earning->balance) !!}</h2>
            <a href="{{ route('withdraw') }}"><small><i class="bi bi-cash-register"></i> {{ tr('Withdraw') }}</small></a>
        </div>
    </div>
</div>

<div class="p-4 bg-white">
    <h4 class="mb-4">{{ tr('Earning for this month') }}</h4>
    <div id="apexChart"></div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
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

    var chart = new ApexCharts(document.querySelector("#apexChart"), options);
    chart.render();
</script>
@endsection

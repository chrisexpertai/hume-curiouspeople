@extends(('layouts.dashboard'))

@section('content')

@php
            $plans = \App\Models\SubscriptionPlan::all();

@endphp

<div class="col-xl-12">
    <div class="card card-body bg-transparent border rounded-3">
        @if ($auth_user->subscription)

        @if ($auth_user->hasSubscriptionExpired())
        <div class="container">
            <h1 class="mt-4">{{ tr('Subscription Plans') }}</h1>

            <div class="row mt-4">
                @foreach ($plans as $plan)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">{{ $plan->name }}</h3>
                                <p class="card-text"{{ tr('>Price:') }} {!! price_format($plan->price) !!}</p>
                                <p class="card-text">{{ $plan->description }}</p>
                                <a href="{{ route('subscription-plans.show', ['id' => $plan->id]) }}" class="btn btn-info btn-sm">{{ tr('Details') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @else
        <!-- Update plan START -->
        <div class="row g-4">
            <!-- Update plan item -->
            <div class="col-6 col-lg-3">
                <span>Active Plan</span>
                <h4>{{ $subscription->name }}</h4>
            </div>
            <!-- Update plan item -->
            <div class="col-6 col-lg-3">
                <span>Monthly limit</span>
                <h4>Unlimited</h4>
            </div>
            <!-- Update plan item -->
            <div class="col-6 col-lg-3">
                <span>Cost</span>
                <h4>{!! price_format($subscription->price) !!}/{{ $subscription->duration_months }} {{ tr('Month(s)') }}</h4>
            </div>

            <!-- Update plan item -->
            <div class="col-6 col-lg-3">
                <span>Expire Date</span>
                <h4> {{ \Carbon\Carbon::parse($auth_user->subscription_end_date)->format('M d, Y') }}</h4>
            </div>
        </div>
        <!-- Update plan END -->

       <!-- Progress bar -->
<div class="overflow-hidden my-4">
    @php
        // Calculate total duration of the subscription
        $totalDuration = strtotime($auth_user->subscription_end_date) - strtotime($auth_user->subscription_start_date);

        // Calculate remaining duration from current date to end date
        $remainingDuration = strtotime($auth_user->subscription_end_date) - time();

        // Calculate progress percentage
        $progressPercentage = ($remainingDuration / $totalDuration) * 100;
    @endphp

    <h6 class="mb-0">{{ round($progressPercentage) }}%</h6>
    <div class="progress progress-sm bg-primary bg-opacity-10">
        <div class="progress-bar bg-primary aos aos-init aos-animate" role="progressbar" data-aos="slide-right" data-aos-delay="200" data-aos-duration="1000" data-aos-easing="ease-in-out" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

        <!-- Button -->
        <div class="d-sm-flex justify-content-sm-between align-items-center">
            <div>
            <!-- Switch Content -->
                <p class="mb-0 small">Your plan will expire renew on: {{ \Carbon\Carbon::parse($auth_user->subscription_end_date)->format('M d, Y') }}. Payment Amount: {!! price_format($subscription->price) !!}</p>
            </div>
            <!-- Buttons -->
            <div class="mt-2 mt-sm-0">
                <form action="{{ route('cancel-subscription') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancel plan</button>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <hr>

        <!-- Plan Benefits -->
        <div class="row">
            <h6 class="mb-3">{{ tr('The plan includes') }}</h6>
            <div class="col-md-6">
                <ul class="list-unstyled">
                    @if ($subscription->includesArr)
                    @foreach ($subscription->includesArr as $include)
                        <li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>{{ $include }}</li>
                    @endforeach
                @else
                    <li>{{ tr('No includes available') }}</li>
                @endif
                       </ul>
            </div>
        </div>
    </div>

@endif

@else

{!! no_data() !!}

@endif

</div>
@endsection

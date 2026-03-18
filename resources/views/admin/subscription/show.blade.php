@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mt-4">{{ tr('Subscription Plan Details') }}</h1>

        <div class="card mt-4">
            <div class="card-body">
                <h2>{{ $plan->name }}</h2>
                <p>Price: ${{ $plan->price }}</p>

                @if($userPlan)
                    {{-- Render user subscription plan details --}}
                    <p>Your Current Plan: {{ $userPlan->name }}</p>

                    @if ($user->hasSubscriptionExpired())
                        <p class="text-danger">{{ tr('Your subscription has expired. Please renew your plan to continue accessing our services.') }}</p>
                        <form action="{{ route('renew-subscription', ['plan_id' => $userPlan->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">{{ tr('Renew Subscription') }}</button>
                        </form>
                    @else
                        <p>{{ tr('Your subscription is active.') }}</p>


                        {{-- Provide an option to switch to another plan --}}
                        <form action="{{ route('switch-subscription-plan', ['plan_id' => $userPlan->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">{{ tr('Switch to This Plan') }}</button>
                        </form>

                        {{-- Provide an option to cancel the subscription --}}
                        <form action="{{ route('cancel-subscription') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">{{ tr('Cancel Subscription') }}</button>
                        </form>

                    @endif

                @else
                    <p>{{ tr('User is not authenticated or has no subscription plan.') }}</p>
                    <form action="{{ route('assign-subscription-plan', ['plan_id' => $plan->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ tr('Choose This Plan') }}</button>
                    </form>
                @endif

                <a href="{{ route('subscription.index') }}" class="btn btn-link">{{ tr('Back to Plans') }}</a>
            </div>
        </div>
    </div>
@endsection

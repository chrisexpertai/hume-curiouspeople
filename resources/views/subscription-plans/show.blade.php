@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <div class="card border">
                    <div class="card-header bg-light text-white">
                        <h2 class="mb-0 text-dark">{{ tr('Subscription Plan Details') }}</h2>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $plan->name }}</h3>
                        <p class="card-text">Price: ${{ $plan->price }}</p>

                        @if($userPlan)
                            {{-- Render user subscription plan details --}}
                            <p class="card-text">Your Current Plan: {{ $userPlan->name }}.</p>

                            @if ($user->hasSubscriptionExpired())
                                <div class="alert alert-danger" role="alert">
                                    Your subscription has expired. Please renew your plan to continue accessing our services.
                                </div>
                                {{-- Form to add subscription plan to cart --}}
                                <form action="{{ route('add.subscription') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <button type="submit" class="btn btn-success">Add to Cart</button>
                                </form>
                            @else
                                <div class="alert alert-success" role="alert">
                                    First Cancel "{{ $userPlan->name }}," to purchase {{ $plan->name }}.
                                </div>
                                {{-- Provide an option to cancel the subscription --}}
                                <form action="{{ route('cancel-subscription') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Cancel {{ $userPlan->name }} Subscription</button>
                                </form>
                            @endif
                        @else
                            <p class="card-text">{{ tr('User is not authenticated or has no subscription plan.') }}</p>
                            {{-- Form to add subscription plan to cart --}}
                            <form action="{{ route('add.subscription') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="btn btn-success">Add to Cart</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <a href="{{ route('subscription-plans.index') }}" class="btn btn-outline-primary">{{ tr('Back to Plans') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

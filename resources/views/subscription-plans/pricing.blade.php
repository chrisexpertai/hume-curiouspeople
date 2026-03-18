@extends('layouts.app')

@section('content')
    <div class="container">
        @if($userPlan)
            <p class="mt-4">Your Current Plan: {{ $userPlan->name }}</p>
        @else
            <p class="mt-4">{{ tr('User is not authenticated or has no subscription plan.') }}</p>
        @endif

        <h1 class="mt-4">{{ tr('Subscription Plans') }}</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ tr('Name') }}</th>
                    <th>{{ tr('Price') }}</th>
                    <th>{{ tr('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                    <tr>
                        <td>{{ $plan->id }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>${{ $plan->price }}</td>
                        <td>
                            <a href="{{ route('subscription-plans.show', ['id' => $plan->id]) }}" class="btn btn-info btn-sm">{{ tr('View') }}</a>
                            <a href="{{ route('subscription-plans.edit', ['id' => $plan->id]) }}" class="btn btn-warning btn-sm">{{ tr('Edit') }}</a>
                            <form action="{{ route('subscription-plans.destroy', ['id' => $plan->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">{{ tr('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('subscription-plans.create') }}" class="btn btn-success mt-3">{{ tr('Create New Plan') }}</a>
    </div>
@endsection

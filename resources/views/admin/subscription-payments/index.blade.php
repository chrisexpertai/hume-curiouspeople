@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">

        <!-- Title -->
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Subscription Payments') }}</h1>
            </div>
        </div>

        <!-- Main card START -->
        <div class="card bg-transparent border">

            <!-- Card header START -->
            <div class="card-header bg-light border-bottom">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <!-- Search bar -->
                        <div class="col-md-6">
                            <form class="rounded position-relative" action="{{ route('admin.subscription-payments') }}" method="GET">
                                <input class="form-control bg-body" type="search" placeholder="{{ tr('Search') }}" aria-label="Search" name="search">
                                <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                                    <i class="fas fa-search fs-6 "></i>
                                </button>
                            </form>
                        </div>

                        <!-- Sort by filter -->
                        <div class="col-md-6">
                            <form action="{{ route('admin.subscription-payments') }}" method="GET" class="d-flex justify-content-end align-items-center">
                                <select class="form-select js-choice border-0 z-index-9 bg-transparent me-3" aria-label=".form-select-sm" name="sort_by">
                                    <option value="">{{ tr('Sort by') }}</option>
                                    <option value="newest">{{ tr('Newest') }}</option>
                                    <option value="oldest">{{ tr('Oldest') }}</option>
                                    <option value="success">{{ tr('Success') }}</option>
                                    <option value="failed">{{ tr('Success') }}</option>
                                </select>
                                <button type="submit" class="btn btn-primary">{{ tr('Apply') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card header END -->

            <!-- Card body START -->
            <div class="card-body">
                <!-- Subscription payments table START -->
                <div class="table-responsive border-0">
                    <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">

                        <!-- Table head -->
                        <thead>
                        <tr>
                            <th scope="col" class="border-0 rounded-start">ID</th>
                            <th scope="col" class="border-0">User</th>
                            <th scope="col" class="border-0">{{ tr('Subscription Plan') }}</th>
                            <th scope="col" class="border-0">{{ tr('Amount') }}</th>
                            <th scope="col" class="border-0">{{ tr('Status') }}</th>
                            <th scope="col" class="border-0">Subscribed At</th>
                            <th scope="col" class="border-0">Created At</th>
                            <th scope="col" class="border-0">Updated At</th>
                            <th scope="col" class="border-0 rounded-end">{{ tr('Action') }}</th>
                        </tr>
                        </thead>

                        <!-- Table body START -->
                        <tbody>
                        @foreach($subscriptionPayments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->user->name }}</td>
                                <td>{{ $payment->subscriptionPlan->name }}</td>
                                <td>{{ $payment->subscription_price }}</td>
                                <td>{{ $payment->status }}</td>
                                <td>{{ $payment->subscribed_at }}</td>
                                <td>{{ $payment->created_at }}</td>
                                <td>{{ $payment->updated_at }}</td>
                                <td>
                                    <form action="{{ route('admin.subscription-payments.update-status') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>{{ tr('Pending') }}</option>
                                            <option value="success" {{ $payment->status == 'success' ? 'selected' : '' }}>{{ tr('Success') }}</option>
                                            <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>{{ tr('Success') }}</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <!-- Table body END -->
                    </table>
                </div>
                <!-- Subscription payments table END -->
            </div>
            <!-- Card body END -->

            <!-- Card footer START -->
            <div class="card-footer bg-transparent pt-0">
                <!-- Pagination START -->
                <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                    <!-- Content -->
                    <p class="mb-0 text-center text-sm-start">
                        Showing {{ $subscriptionPayments->firstItem() }} to {{ $subscriptionPayments->lastItem() }} of {{ $subscriptionPayments->total() }} entries
                    </p>
                    <!-- Pagination -->
                    {{ $subscriptionPayments->links('pagination::bootstrap-4') }}
                </div>
                <!-- Pagination END -->
            </div>
            <!-- Card footer END -->
        </div>
        <!-- Main card END -->
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">

        <!-- Title -->
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Subscribed Users') }}</h1>
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
                            <form class="rounded position-relative" action="{{ route('admin.subscribed.users') }}" method="GET">
                                <input class="form-control bg-body" type="search" placeholder="{{ tr('Search') }}" aria-label="Search" name="search">
                                <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                                    <i class="fas fa-search fs-6 "></i>
                                </button>
                            </form>
                        </div>

                        <!-- Sort by filter -->
                        <div class="col-md-6">
                            <form action="{{ route('admin.subscribed.users') }}" method="GET" class="d-flex justify-content-end align-items-center">
                                <select class="form-select js-choice border-0 z-index-9 bg-transparent me-3" aria-label=".form-select-sm" name="sort_by">
                                    <option value="">{{ tr('Sort by') }}</option>
                                    <option value="user_id">{{ tr('User ID') }}</option>
                                    <option value="name">{{ tr('Name') }}</option>
                                    <option value="email">{{ tr('Sort by') }}</option>
                                    <option value="subscription_plan">{{ tr('Subscription Plan') }}</option>
                                    <option value="start_date">{{ tr('Start Date') }}</option>
                                    <option value="end_date">{{ tr('End Date') }}</option>
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
                <!-- Subscribed users table START -->
                <div class="table-responsive border-0">
                    <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">

                        <!-- Table head -->
                        <thead>
                        <tr>
                            <th scope="col" class="border-0">{{ tr('User ID') }}</th>
                            <th scope="col" class="border-0">{{ tr('Name') }}</th>
                            <th scope="col" class="border-0">{{ tr('Sort by') }}</th>
                            <th scope="col" class="border-0">{{ tr('Subscription Plan') }}</th>
                            <th scope="col" class="border-0">{{ tr('Start Date') }}</th>
                            <th scope="col" class="border-0">{{ tr('End Date') }}</th>
                        </tr>
                        </thead>

                        <!-- Table body START -->
                        <tbody>
                        @foreach($subscribedUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->subscriptionPlan->name }}</td>
                                <td>{{ $user->subscription_start_date }}</td>
                                <td>{{ $user->subscription_end_date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <!-- Table body END -->
                    </table>
                </div>
                <!-- Subscribed users table END -->
            </div>
            <!-- Card body END -->

            <!-- Card footer START -->
            <div class="card-footer bg-transparent pt-0">
                <!-- Pagination START -->
                <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                    <!-- Content -->
                    <p class="mb-0 text-center text-sm-start">
                        Showing {{ $subscribedUsers->firstItem() }} to {{ $subscribedUsers->lastItem() }} of {{ $subscribedUsers->total() }} entries
                    </p>
                    <!-- Pagination -->
                    {{ $subscribedUsers->links('pagination::bootstrap-4') }}
                </div>
                <!-- Pagination END -->
            </div>
            <!-- Card footer END -->
        </div>
        <!-- Main card END -->
    </div>
@endsection

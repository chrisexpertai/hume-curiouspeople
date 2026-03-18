@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">
        <!-- Title -->
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Subscription Plans') }}</h1>
            </div>
        </div>

        <!-- Main card START -->
        <div class="card bg-transparent border">

            <!-- Card body START -->
            <div class="card-body">
                <!-- Subscription plans table START -->
                <div class="table-responsive border-0">
                    <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">

                        <!-- Table head -->
                        <thead>
                        <tr>
                            <th scope="col" class="border-0">ID</th>
                            <th scope="col" class="border-0">{{ tr('Name') }}</th>
                            <th scope="col" class="border-0">{{ tr('Price') }}</th>
                            <th scope="col" class="border-0">{{ tr('Action') }}</th>
                        </tr>
                        </thead>

                        <!-- Table body START -->
                        <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->name }}</td>
                                <td>${{ $plan->price }}</td>
                                <td>
                                    <a href="{{ route('subscription-plans.show', ['id' => $plan->id]) }}" class="btn btn-info btn-sm">{{ tr('View') }}</a>
                                    <a href="{{ route('subscription.edit', ['id' => $plan->id]) }}" class="btn btn-warning btn-sm">{{ tr('Edit') }}</a>
                                    <form action="{{ route('subscription.destroy', ['id' => $plan->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">{{ tr('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <!-- Table body END -->
                    </table>
                </div>
                <!-- Subscription plans table END -->
            </div>
            <!-- Card body END -->

            <!-- Card footer START -->
            <div class="card-footer bg-transparent pt-0">
                <a href="{{ route('subscription.create') }}" class="btn btn-success mt-3">{{ tr('Create New Plan') }}</a>
            </div>
            <!-- Card footer END -->
        </div>
        <!-- Main card END -->
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">

        <!-- Title -->
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Tickets') }}</h1>
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
                            <form class="rounded position-relative" action="{{ route('admin.tickets.index') }}" method="GET">
                                <input class="form-control bg-body" type="search" placeholder="{{ tr('Search') }}" aria-label="Search" name="search">
                                <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                                    <i class="fas fa-search fs-6 "></i>
                                </button>
                            </form>
                        </div>
                  <!-- Sort by filter -->
                 <div class="col-md-6">
                    <form action="{{ route('admin.tickets.index') }}" method="GET" class="d-flex justify-content-end align-items-center">
                        <select class="form-select js-choice border-0 z-index-9 bg-transparent me-3" aria-label=".form-select-sm" name="sort_by">
                            <option value="">{{ tr('Sort by') }}</option>
                            <option value="newest">{{ tr('Newest') }}</option>
                            <option value="oldest">{{ tr('Oldest') }}</option>
                            <option value="subject_asc">{{ tr('Subject A-Z') }}</option>
                            <option value="subject_desc">{{ tr('Subject Z-A') }}</option>
                            <!-- Add more sorting options as needed -->
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
                <!-- Tickets table START -->
                <div class="table-responsive border-0">
                    <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">

                        <!-- Table head -->
                        <thead>
                        <tr>
                            <th scope="col" class="border-0 rounded-start">{{ tr('ticket_id') }}</th>
                            <th scope="col" class="border-0">{{ tr('subject') }}</th>
                            <th scope="col" class="border-0">{{ tr('user') }}</th>
                            <th scope="col" class="border-0 rounded-end">{{ tr('action') }}</th>
                        </tr>
                        </thead>

                        <!-- Table body START -->
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>
                                    <a href="{{ route('admin.tickets.view', $ticket->id) }}" class="btn btn-primary">{{ tr('view') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ tr('no_tickets_available') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <!-- Table body END -->
                    </table>
                </div>
                <!-- Tickets table END -->
            </div>
            <!-- Card body END -->

            <!-- Card footer START -->
            <div class="card-footer bg-transparent pt-0">
                <!-- Pagination -->
                <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                    <!-- Content -->
                    <p class="mb-0 text-center text-sm-start">
                        Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} entries
                    </p>
                    <!-- Pagination -->
                    {{ $tickets->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- Card footer END -->
        </div>
        <!-- Main card END -->
    </div>
@endsection

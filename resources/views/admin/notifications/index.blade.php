@extends('layouts.admin')

@section('title', 'Admin Notifications')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ tr('Admin Notifications') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ tr('Message') }}</th>
                                    <th scope="col">{{ tr('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adminNotifications as $notification)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ $notification->link }}">{{ $notification->message }}</a></td>
                                    <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">{{ tr('No notifications found.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

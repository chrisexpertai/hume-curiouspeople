@extends('layouts.dashboard')

@section('content')

<div class="container">
    @if($userTickets && $userTickets->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-3">
                <div class="card-header bg-transparent border-bottom">
                    <h3 class="mb-0">{{ tr('Your Tickets') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" class="rounded-start">{{ tr('Ticket Subject') }}</th>
                                    <th scope="col">{{ tr('Created Date') }}</th>
                                    <th scope="col" class="rounded-end">{{ tr('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userTickets as $ticket)
                                <tr>
                                    <td>
                                        <a href="{{ route('user.tickets.view', $ticket->id) }}">{{ $ticket->subject }}</a>
                                    </td>
                                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('user.tickets.view', $ticket->id) }}" class="btn btn-primary btn-sm me-1 mb-1 mb-md-0">{{ tr('View') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-3">
                <div class="card-body">
                     <a href="{{ route('user.tickets.create.form') }}" class="btn btn-primary create-ticket-btn">{{ tr('Create New Ticket') }}</a>
                     {!! no_data() !!}

                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

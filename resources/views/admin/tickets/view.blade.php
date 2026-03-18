@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="border rounded p-4 shadow-sm">
                    <h2 class="mb-4">{{ tr('Ticket Subject', ['subject' => $ticket->subject]) }}</h2>

                    <div class="mb-4">

                        {!! $ticket->content !!}

                       
                    </div>

                    <!-- Display ticket replies -->
                    <div class="mt-4">
                        <h4>{{ tr('Replies') }}</h4>
                        @if($replies->count() > 0)
                            <ul class="list-group">
                                @foreach($replies->sortBy('created_at') as $reply)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $reply->user->name }}</strong>
                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p>{{ $reply->content }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ tr('No Replies') }}</p>
                        @endif
                    </div>

                    <!-- Reply form -->
                    <div class="mt-4">
                        <h4>{{ tr('Reply') }}</h4>
                        <form method="post" action="{{ route('admin.tickets.respond', $ticket->id) }}">
                            @csrf

                            <div class="form-group">
                                <label for="reply">{{ tr('Your Reply') }}</label>
                                <textarea class="form-control" id="reply" name="reply" rows="4" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ tr('Submit Reply') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

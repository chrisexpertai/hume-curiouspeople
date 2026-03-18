<!-- resources/views/dashboard/messages/show.blade.php -->

@extends(('layouts.dashboard'))

@section('content')
    <h1>Message Details</h1>

    @if ($message)
        <p>
            <strong>{{ $message->sender->name }}</strong> to <strong>{{ $message->receiver->name }}</strong>
            <br>
            {{ $message->content }}
        </p>

        <h2>Replies</h2>

        <ul>
            @foreach ($message->replies as $reply)
                <li>
                    @if ($reply->is_reply)
                        <em>Reply from {{ $reply->sender->name }}:</em> {{ $reply->content }}
                    @else
                        {{ $reply->content }}
                    @endif
                </li>
            @endforeach
        </ul>

        @if ($message->replies->isNotEmpty())
            <form action="{{ route('messages.storeReply', $message->replies->first()->id) }}" method="post">
        @else
            <form action="{{ route('messages.storeReply', $message->id) }}" method="post">
        @endif
            @csrf
            <input type="hidden" name="sender_id" value="{{ auth()->user()->id }}">
            <textarea name="content" cols="30" rows="5" placeholder="Enter your reply"></textarea>
            <button type="submit">Reply</button>
        </form>
    @else
        <p>No messages found.</p>
    @endif
@endsection

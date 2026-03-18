@extends(('layouts.dashboard'))


@section('content')

<!-- resources/views/dashboard.blade.php -->

<!-- ... (other content) ... -->

<h2>Messages</h2>
<ul>
    @foreach ($conversations as $conversation)
        <li>
            <a href="{{ route('messages.index', ['receiver_id' => $conversation->receiver_id, 'course_id' => $conversation->course_id]) }}">
                {{ $conversation->sender->name }} - {{ $conversation->course->title }}
            </a>
        </li>
    @endforeach
</ul>

<!-- Add a form to start a new conversation -->
<form action="{{ route('messages.create') }}" method="post">
    @csrf
    <select name="receiver_id" required>
        <option value="" disabled selected>Select a recipient</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{$user->name}} {{$user->last_name}}</option>
        @endforeach
    </select>
    <select name="course_id" required>
        <option value="" disabled selected>Select a course</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}">{{ $course->title }}</option>
        @endforeach
    </select>
    <button type="submit">Start Conversation</button>
</form>


@endsection

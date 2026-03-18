<!-- resources/views/homepages/index.blade.php -->

@extends('layouts.app')  <!-- Assuming you have an app layout for user-facing views -->

@section('content')
    <h2>Choose Your Home Page</h2>

    <ul>
        @foreach($homePages as $homePage)
            <li>
                <strong>{{ $homePage->title }}</strong>
                <p>{{ $homePage->content }}</p>
                <form method="POST" action="{{ route('user.select.homepage') }}">
                    @csrf
                    <input type="hidden" name="selected_homepage" value="{{ $homePage->id }}">
                    <button type="submit" class="btn btn-primary">Select This Home Page</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection

<!-- resources/views/pages.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <h1>{{ tr('Pages') }}</h1>
        @foreach ($pages as $page)
            <div class="mb-4">
                <h2>{{ $page->title }}</h2>
                <p>{{ $page->content }}</p>
                <a href="{{ url("/pages/$page->id") }}" class="btn btn-primary">{{ tr('View Full Page') }}</a>
            </div>
        @endforeach
    </div>
@endsection

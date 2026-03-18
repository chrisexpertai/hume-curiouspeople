<!-- resources/views/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                @if($page->featured_image)
                    <img src="{{ asset($page->featured_image ?: '/assets/images/placeholder-image.png') }}" class="img-fluid mb-4" alt="Featured Image">
                @endif
                <h1 class="display-4">{{ $page->title }}</h1>
                <p class="lead">{!! cleanhtml($page->content) !!}</p>
                <div class="text-muted text-end mb-2">
                    <small>Page updated on {{ $page->updated_at->format('F d, Y \a\t h:i A') }}</small>
                </div>

            </div>
        </div>
    </div>
@endsection

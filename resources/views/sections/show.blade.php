<!-- resources/views/homepages/show.blade.php -->

@extends('layouts.app')  <!-- Assuming you have an app layout for user-facing views -->

@section('content')
    <div>
        <h2>{{ $homePage->title }}</h2>
        <p>{{ $homePage->content }}</p>
    </div>
@endsection

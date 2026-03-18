@extends('layouts.admin')  <!-- Assuming you have a main layout file -->

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="post" action="{{ route('storeAdministrator') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">{{ tr('Name:') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="email">{{ tr('Email') }}:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password">{{ tr('Password') }}:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <!-- Add more fields as needed -->

                            <button type="submit" class="btn btn-primary">{{ tr('Add Administrator') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

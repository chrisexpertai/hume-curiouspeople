@extends('layouts.admin') <!-- Assuming you have a main layout file -->

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-md-8 py-5">
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

                        <form method="post" action="{{ route('users.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">{{ tr('Name:') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>


                            <div class="form-group">
                                <label for="user_type">User Type:</label>
                                <select name="user_type" class="form-control" required>
                                     <option value="instructor">{{ tr('Instructor') }}</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>

                            <!-- Add more fields as needed -->

                            <button type="submit" class="btn btn-primary">Create User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

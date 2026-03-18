@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card  border rounded-4">
                    <div class="card-header text-white">
                        <h2 class="card-title">{{ tr('Create Menu') }}</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('menus.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ tr('Name') }}:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">{{ tr('URL') }}:</label>
                                <input type="text" class="form-control" id="url" name="url" required>
                            </div>
                            <div class="mb-3">
                                <label for="icon" class="form-label">{{ tr('Icon') }}:</label>
                                <input type="text" class="form-control" id="icon" name="icon">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ tr('Create Menu') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

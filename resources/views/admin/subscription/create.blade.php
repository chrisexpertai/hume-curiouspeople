@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mt-6">
                <div class="card-header">
                    <h1 class="mb-0">Create Subscription Plan</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('subscription.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ tr('Name:') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">{{ tr('Price:') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="duration_months" class="form-label">{{ tr('Duration (in months):') }}</label>
                            <input type="number" class="form-control" id="duration_months" name="duration_months" required>
                        </div>

                        <div class="mb-3">
                            <label for="badge" class="form-label">{{ tr('Badge:') }}</label>
                            <input type="text" class="form-control" id="badge" name="badge" required>
                        </div>

                        <div class="mb-3">
                            <label for="includes" class="form-label">{{ tr('Includes:') }}</label>
                            <textarea name="includes" id="includes" class="form-control" rows="5"> </textarea>
                            <small id="includesHelp" class="form-text text-muted">{{__a('includes_desc')}}</small>

                        </div>


                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">{{ tr('Create Plan') }}</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="{{ route('subscription.index') }}" class="btn btn-secondary">{{ tr('Back to Plans') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

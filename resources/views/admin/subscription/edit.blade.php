@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Subscription Plan</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('subscription.update', ['id' => $plan->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">{{ tr('Name:') }}</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $plan->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">{{ tr('Price:') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="price" name="price" value="{{ $plan->price }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="duration_months" class="form-label">{{ tr('Duration (in months):') }}</label>
                    <input type="number" class="form-control" id="duration_months" name="duration_months" value="{{ $plan->duration_months }}" required>
                </div>

                <div class="mb-3">
                    <label for="badge" class="form-label">{{ tr('Badge:') }}</label>
                    <input type="text" class="form-control" id="badge" name="badge" value="{{ $plan->badge }}" required>
                </div>

                <div class="mb-3">
                    <label for="includes" class="form-label">{{__a('includes')}}</label>
                    <textarea name="includes" id="includes" class="form-control" rows="5">{{ $plan->includes }}</textarea>
                    <small id="includesHelp" class="form-text text-muted">{{__a('includes_desc')}}</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ tr('Update Plan') }}</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('subscription.index') }}" class="btn btn-secondary">{{ tr('Back to Plans') }}</a>
        </div>
    </div>
</div>
@endsection

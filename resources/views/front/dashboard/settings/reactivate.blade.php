@extends(('layouts.app'))

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (Auth::user()->active)
                    <form method="POST" action="{{ route('destroy.profile') }}">
                        @csrf
                        <h2 class="mb-4">{{ tr('Deactivate Account') }}</h2>
                        <p>{{ tr('Before you go... Take a backup of your data Here') }}</p>
                        <p>{{ tr('If you deactivate your account, you can reactivate it within 30 days.') }}</p>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="confirmation" id="confirmation">
                            <label class="form-check-label" for="confirmation">{{ tr('Yes, I'd like to deactivate my account') }}</label>
                        </div>
                        <button type="submit" class="btn btn-danger mt-3">{{ tr('Deactivate Account') }}</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('reactivate.account') }}">
                        @csrf
                        <h2 class="mb-4">{{ tr('Reactivate Account') }}</h2>
                        <p>{{ tr('Your account is currently deactivated. You can reactivate it now.') }}</p>
                        <button type="submit" class="btn btn-primary mt-3">{{ tr('Reactivate Account') }}</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection



@endsection

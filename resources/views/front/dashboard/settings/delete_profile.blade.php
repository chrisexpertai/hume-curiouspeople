@extends(('layouts.dashboard'))

@section('content')<!-- delete_profile.blade.php -->



<div class="col-xl-9">
    <!-- Title and select START -->
    <div class="card border bg-transparent rounded-3 mb-0">
        <!-- Card header -->
        <div class="card-header bg-transparent border-bottom">
            <h3 class="card-header-title mb-0">{{ tr('Deactivate Account') }}</h3>
        </div>


        <!-- Card body -->
        <div class="card-body">
            <h6>{{ tr('Before you go...') }}</h6>
            <ul>
                <li>{{ tr('If you deactivate your account, you can reactivate it within 30 days.') }}<a href="{{ route('login') }}">{{ tr('Here') }}</a> </li>
                <li>{{ tr('Just login in, and decision will be made') }}</li>
            </ul>

          <!-- delete_profile.blade.php -->
<form method="POST" action="{{ route('destroy.profile') }}">
    @csrf

    <input type="checkbox" name="confirmation" id="confirmation">
    <label for="confirmation">{{ tr('Yes, I\'d like to delete my account') }}</label><br><br>
    <button type="submit" class="btn btn-danger mb-0">{{ tr('Delete Account') }}</button>

<a href="{{ route('dashboard') }}" class="btn btn-success-soft mb-2 mb-sm-0">{{ tr('Keep my account') }}</a>
</form>

        </div>
    </div>
    <!-- Title and select END -->
</div>
@endsection

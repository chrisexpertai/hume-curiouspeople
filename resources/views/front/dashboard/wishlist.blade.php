@extends(('layouts.dashboard'))

@section('content')

    @php
        $courses = $auth_user->wishlist()->publish()->get();
    @endphp



<div class="col-xl-12">

    <div class="card bg-transparent border rounded-3">
        <!-- Card header START -->
        <div class="card-header bg-transparent border-bottom">
            <h3 class="mb-0">{{ tr('WishList') }}</h3>
        </div>
        <!-- Card header END -->
        <div class="card-body p-3 p-md-4">

        @if($courses->count())
        @foreach($courses as $course)
        {!! course_card($course, 'p-3, col-lg-4') !!}

        @endforeach

        @endif

            </div>
        </div>
        <!-- Card body EMD -->
    </div>
</div>



@endsection

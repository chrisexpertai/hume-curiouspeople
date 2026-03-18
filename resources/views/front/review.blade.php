@extends('layouts.dashboard')


@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="review-view-wrap my-5">

                    <div class="card">
                        <div class="card-header">
                            {!! star_rating_generator($review->rating) !!}
                        </div>
                        <div class="card-body">

                            <div class="reviewed-user d-flex">
                                <div class="reviewed-user-photo">
                                    <a href="{{ route('profile', $review->user) }}">
                                        <img src="{!! $review->user->get_photo !!}" class="profile-photo">


                                    </a>
                                </div>
                                <div class="reviewed-user-name">
                                    <p class="mb-1">
                                    </p>
                                    <a href="{{ route('profile', $review->user) }}">{!! $review->user->name !!}</a>
                                </div>
                            </div>

                            @if ($review->review)
                                <div class="review-desc border-top pt-3 mt-3">
                                    {!! nl2br($review->review) !!}
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

            </div>
        </div>



    </div>
@endsection

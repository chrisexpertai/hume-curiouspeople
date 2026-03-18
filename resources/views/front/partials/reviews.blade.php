<div class="row mb-4">
    <h5 class="mb-4">{{ tr('Student Reviews') }}</h5>

    <!-- Rating info -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="text-center">
            <!-- Info -->
            <h2 class="mb-0">{{ $course->rating_value }}</h2>
            <!-- Star -->
            <ul class="list-inline mb-0">
                {!! star_rating_generator($course->rating_value) !!}

            </ul>
            <p class="mb-0"> {{ sprintf(__t('from_amount_reviews'), $course->rating_count) }}
            </p>
        </div>
    </div>

    <!-- Progress-bar and star -->
    <div class="col-md-8">
        <div class="row align-items-center text-center">
            <!-- Progress bar and Rating -->
            <div class="col-6 col-sm-8">
                <!-- Progress item -->
                <div class="progress progress-sm bg-warning bg-opacity-15">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <!-- Star item -->
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                </ul>
            </div>

            <!-- Progress bar and Rating -->
            <div class="col-6 col-sm-8">
                <!-- Progress item -->
                <div class="progress progress-sm bg-warning bg-opacity-15">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="80"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <!-- Star item -->
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                </ul>
            </div>

            <!-- Progress bar and Rating -->
            <div class="col-6 col-sm-8">
                <!-- Progress item -->
                <div class="progress progress-sm bg-warning bg-opacity-15">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <!-- Star item -->
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                </ul>
            </div>

            <!-- Progress bar and Rating -->
            <div class="col-6 col-sm-8">
                <!-- Progress item -->
                <div class="progress progress-sm bg-warning bg-opacity-15">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <!-- Star item -->
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                </ul>
            </div>

            <!-- Progress bar and Rating -->
            <div class="col-6 col-sm-8">
                <!-- Progress item -->
                <div class="progress progress-sm bg-warning bg-opacity-15">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="20"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <!-- Star item -->
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                    <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i></li>
                </ul>
            </div>
        </div>
    </div>
</div>




<!-- Student review START -->
<div class="row">

    <!-- Review item START -->
    @foreach ($course->reviews as $review)
        <div class="d-md-flex my-4">
            <!-- Avatar -->
            <div class="avatar avatar-xl me-4 flex-shrink-0">
                <img class="avatar-img rounded-circle" src=" {!! $review->user->get_photo !!}" alt="avatar">
            </div>
            <!-- Text -->
            <div>
                <div class="d-sm-flex mt-1 mt-md-0 align-items-center">
                    <h5 class="me-3 mb-0">{!! $review->user->name !!}</h5>
                    <!-- Review star -->
                    <ul class="list-inline mb-0">
                        @php
                            $rating = $review->rating; // Assuming rating field is present in your Review model
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $rating)
                                <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                            @else
                                <li class="list-inline-item me-0"><i class="far fa-star text-warning"></i></li>
                            @endif
                        @endfor
                    </ul>
                </div>
                <!-- Info -->
                <p class="small mb-2">{{ $review->created_at->diffForHumans() }}</p>
                @if ($review->review)
                    <p class="mb-2"> {!! nl2br($review->review) !!}</p>
                @endif

                <!-- Reply button -->
                <a href="#" class="text-body mb-0"><i class="fas fa-reply me-2"></i>Reply</a>
            </div>
        </div>
        <!-- Divider -->
        <hr>
    @endforeach

    <!-- Review item END -->

    <!-- Review item END -->
    <!-- Divider -->
    <hr>
</div>
<!-- Student review END -->

<!-- Leave Review START -->







@if ($auth_user)
    @php
        $drip_items = $course->drip_items;
        $review = has_review($auth_user->id, $course->id);
        $completed_percent = $course->completed_percent();
    @endphp
    <div class="mt-2">
        <h5 class="mb-4">{{ tr('Leave a Review') }}</h5>
        <form action="{{ route('save_review', $course->id) }}" class="row g-3" method="post">

            @csrf

            @php
                $ratingValue = 5;
                $review_text = '';
                if ($review) {
                    $ratingValue = $review->rating;
                    $review_text = $review->review;
                }
            @endphp

            <!-- Rating -->
            <div class="col-12">
                {!! star_rating_field($ratingValue) !!}

            </div>
            <!-- Message -->
            <div class="col-12">
                <textarea name="review" class="form-control" placeholder="{!! $review_text !!}" rows="3"></textarea>

            </div>
            <!-- Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary mb-0">Post Review</button>
            </div>
        </form>
    </div>
@endif
<!-- Leave Review END -->

<script>
    /**
     * Rating set
     */
    $(document).on('mouseenter', '.review-write-star-wrap i', function() {
        $(this).closest('.review-write-star-wrap').find('i').removeClass('fas fa-star').addClass('far fa-star');
        var ratingValue = $(this).attr('data-rating-value');

        for (var i = 1; i <= ratingValue; i++) {
            $(this).closest('.review-write-star-wrap').find('i[data-rating-value="' + i + '"]').removeClass(
                'far fa-star').addClass('fas fa-star');
        }

        // Set the input value to the selected rating
        $(this).closest('.review-write-star-wrap').find('input[name="rating_value"]').val(ratingValue);
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

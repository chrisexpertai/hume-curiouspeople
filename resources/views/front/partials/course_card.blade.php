<?php
$gridClass = $grid_class ? $grid_class : 'col-sm-6 col-xl-4';
?>




<!-- Card item START -->
<div class="{{ $gridClass }}">
    <div class="card shadow h-100"
        style="text-align: center;
    border-radius: 16px !important;
   transition: all .5s ease;
    ">
        <!-- Image -->


        <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="course image">
        <!-- Card body -->
        <div class="card-body  pb-0">
            <!-- Badge and favorite -->
            <div class="d-flex justify-content-between mb-2">
                <a href="#"
                    class="badge bg-purple bg-opacity-10 text-purple">{{ course_levels($course->level) }}</a>
                <button class="course-card-add-wish btn btn-link btn-sm p-0" data-course-id="{{ $course->id }}">
                    @if ($auth_user && in_array($course->id, $auth_user->get_option('wishlists', [])))
                        <i class="la la-heart"></i>
                    @else
                        <i class="la la-heart-o"></i>
                    @endif
                </button>

            </div>
            <script type="text/javascript">
                /* <![CDATA[ */
                window.pageData = @json(pageJsonData());
                /* ]]> */
            </script>

            <!-- Title -->
            <h5 class="card-title"><a href="{{ route('course', $course->slug) }}">{{ $course->title }}</a></h5>
            <p class="mb-2 text-truncate-2">{{ clean_html($course->short_description) }}</p>
            <!-- Rating star -->
            <ul class="list-inline mb-0">


                <!-- Add your PHP logic to calculate and display the rating stars -->
                <!-- Example: -->

                @if ($course->rating_count)
                    <div class="color bg-sec course-card-ratings">
                        <div class="color bg-sec star-ratings-group d-flex">
                            {!! star_rating_generator($course->rating_value) !!}
                            <span class="color bg-sec star-ratings-point mx-2"><b>{{ $course->rating_value }}</b></span>
                            <span
                                class="color bg-sec text-muted star-ratings-count">({{ $course->rating_count }})</span>
                        </div>
                    </div>
                @endif
        </div>
        <!-- Card footer -->
        <div class="card-footer pt-0 pb-3 rounded-4">
            <hr>
            <div class="d-flex justify-content-between">
                {{-- <span class="h6 fw-light mb-0"><i class="far fa-clock text-danger me-2"></i>{{ $course->price_plan }}</span> --}}
                <span class="h6 fw-light mb-0"><i class="fas fa-table text-orange me-2"></i>
                    {{ $course->total_lectures }} {{ __t('lectures') }}</span>
            </div>
        </div>
    </div>
</div>
<!-- Card item END -->

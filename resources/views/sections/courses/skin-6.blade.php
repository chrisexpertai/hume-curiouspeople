<?php

use App\Models\Category;
use App\Models\Course;

$title = __t('courses');
$courses = Course::query()->publish();

$new_courses = Course::publish()->orderBy('created_at', 'desc')->take(12)->get();

$courses = $courses->paginate(8);

?>

<section class="pt-0">
    <div class="container">
        <!-- Title -->
        <div class="row mb-4">
            <h2 class="mb-0">{{ tr('Top Listed Courses') }}</h2>
        </div>

        <div class="row">


            <!-- Slider START -->
            <div class="tiny-slider arrow-round arrow-blur arrow-hover">
                <div class="tns-outer" id="tns1-ow">
                    <div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span
                            class="current"></span></div>
                    <div id="tns1-mw" class="tns-ovh">
                        <div class="tns-inner" id="tns1-iw">
                            <div class="tiny-slider-inner  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal"
                                data-autoplay="false" data-arrow="true" data-edge="2" data-dots="false" data-items="3"
                                data-items-lg="2" data-items-sm="1" id="tns1"
                                style="transition-duration: 0s; transform: translate3d(-38.8889%, 0px, 0px);">


                                <?php if ($new_courses->count()): ?>
                                <?php foreach ($new_courses as $course): ?>
                                <div class="pb-4 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                                    <div class="card p-2 border">
                                        <div class="rounded-top overflow-hidden">
                                            <div href="<?php echo route('course', $course->slug); ?>" class="card-overlay-hover">
                                                <img src="<?php echo $course->thumbnail_url; ?>" href="<?php echo route('course', $course->slug); ?>"
                                                    class="card-img-top" alt="course image" style="max-height:215px;">
                                            </div>
                                            <!-- Hover element -->
                                            <div class="card-img-overlay">
                                                <div class="card-element-hover d-flex justify-content-end">


                                                    <form action="{{ route('add_to_cart') }}"
                                                        class="color bg link enroll-btn" method="post">
                                                        @csrf
                                                        <input type="hidden" name="course_id"
                                                            value="{{ $course->id }}">
                                                        {{-- <button type="submit" class="btn btn-warning" name="cart_btn"
                                                            value="buy_now"> <i
                                                                class="fas fa-shopping-cart text-danger"></i>
                                                        </button> --}}
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Badge and icon -->
                                            <div class="d-flex justify-content-between">
                                                <!-- Rating and info -->
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <!-- Info -->
                                                    <li
                                                        class="list-inline-item d-flex justify-content-center align-items-center">
                                                        <div
                                                            class="icon-md bg-orange bg-opacity-10 text-orange rounded-circle">
                                                            <i class="fas fa-user-graduate"></i>
                                                        </div>
                                                        <a href="<?php echo route('profile', $course->author->id); ?>"
                                                            class="h6 fw-light mb-0 ms-2"><?php echo $course->author->name . ' ' . $course->author->last_name; ?></a>
                                                    </li>
                                                    <!-- Rating -->
                                                    <li
                                                        class="list-inline-item d-flex justify-content-center align-items-center">
                                                        <div
                                                            class="icon-md bg-warning bg-opacity-15 text-warning rounded-circle">
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <span class="h6 fw-light mb-0 ms-2"><?php echo number_format($course->rating_value, 1); ?></span>

                                                    </li>
                                                </ul>
                                                <!-- Avatar -->
                                                <div class="avatar avatar-sm">
                                                    <img class="avatar-img rounded-circle"
                                                        src="{{ $course->author->get_photo }}" alt="avatar">
                                                </div>
                                            </div>
                                            <!-- Divider -->
                                            <hr>
                                            <!-- Title -->
                                            <h5 class="card-title"><a
                                                    href="<?php echo route('course', $course->slug); ?>">{{ $course->title }}</a></h5>
                                            <div class="d-flex justify-content-between align-items-center mb-0">
                                                <a href="<?php echo route('course', $course->slug); ?>"
                                                    class="badge bg-info bg-opacity-10 text-info me-2"><i
                                                        class="fas fa-circle small fw-bold"></i> <?php
                                                        if ($course->second_category_id) {
                                                            $category = App\Models\Category::find($course->second_category_id);
                                                            echo $category->category_name;
                                                        }
                                                        ?>
                                                </a>
                                                <!-- Price -->
                                                {{-- <h3 class="text-success mb-0"><?php echo $course->paid_price(false, true); ?></h3> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tns-controls" aria-label="Carousel Navigation" tabindex="0"><button type="button"
                            data-controls="prev" tabindex="-1" aria-controls="tns1"><i
                                class="fas fa-chevron-left"></i></button><button type="button" data-controls="next"
                            tabindex="-1" aria-controls="tns1"><i class="fas fa-chevron-right"></i></button></div>
                </div>
            </div>
            <!-- Slider END -->
        </div>
    </div>
</section>

@extends('layouts.app')
@section('content')

    @php
        $auth_user = auth()->user();

        if ($auth_user) {
            $followings = $auth_user->following();
            $followers = $auth_user->followers();
            $authUserIsFollower = $followers
                ->where('follower', auth()->id())
                ->where('status', App\Models\Follow::$accepted)
                ->first();
        } else {
            // Handle the case where the user is not logged in
            $followings = null;
            $followers = null;
            $authUserIsFollower = false;
        }

    @endphp

    @php
        use Illuminate\Support\Facades\URL;
    @endphp

    @php
        function enrolledUsersCount($course)
        {
            return $course->enrolls()->where('status', 'success')->count();
        }
    @endphp

    <!-- **************** MAIN CONTENT START **************** -->
    <main>

        <!-- =======================
                                                                                    Page content START -->
        <section class="pt-3 pt-xl-5">
            <div class="container" data-sticky-container>
                <div class="row g-4">
                    <!-- Main content START -->
                    <div class="col-xl-8">

                        <div class="row g-4">
                            <!-- Title START -->
                            <div class="col-12">
                                <!-- Title -->
                                <h2><?php echo $course->title; ?></h2>
                                <p><?php echo $course->short_description; ?></p>
                                <!-- Content -->
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item fw-light h6 me-3 mb-1 mb-sm-0"><i
                                            class="fas fa-star me-2"></i><?php echo number_format($course->rating_value, 1); ?> /5.0</li>
                                    <li class="list-inline-item fw-light h6 me-3 mb-1 mb-sm-0"><i
                                            class="fas fa-user-graduate me-2"></i>{{ enrolledUsersCount($course) }}
                                        {{ tr('Enrolled') }}</li>
                                    <li class="list-inline-item fw-light h6 me-3 mb-1 mb-sm-0"><i
                                            class="fas fa-signal me-2"></i><?php echo course_levels($course->level); ?></li>
                                    <li class="list-inline-item fw-light h6 me-3 mb-1 mb-sm-0"><i
                                            class="bi bi-patch-exclamation-fill me-2"></i>{{ tr('Last updated') }}
                                        <?php echo date('M d Y', strtotime($course->last_updated_at)); ?></li>
                                </ul>
                            </div>
                            <!-- Title END -->

                            <!-- Image and video -->
                            @if ($course->video_info())
                                <div class="col-12">
                                    <div class="video-player bg-body rounded-3">

                                        @include('front.partials.player', [
                                            'model' => $course,
                                            'video_caption' => ' ',
                                        ])
                                    </div>

                                </div>
                            @endif

                            <!-- About course START -->
                            <div class="col-12">
                                <div class="card border">
                                    <!-- Card header START -->
                                    <div class="card-header border-bottom">
                                        <h3 class="mb-0">{{ tr('Course description') }}</h3>
                                    </div>
                                    <!-- Card header END -->

                                    <!-- Card body START -->
                                    <div class="card-body">
                                        <p class="mb-0"><?php echo $course->description; ?></p>


                                        @if ($course->benefits_arr || $course->requirements_arr)
                                            <!-- List content -->
                                            <h5 class="mt-4">{{ tr('What you\'ll learn') }}</h5>
                                            <div class="row mb-3">
                                                @if ($course->benefits_arr)
                                                    <div class="col-md-6">
                                                        <ul class="list-group list-group-borderless">
                                                            @foreach ($course->benefits_arr as $benefit)
                                                                <li class="list-group-item h6 fw-light d-flex mb-0">
                                                                    <i
                                                                        class="fas fa-check-circle text-success me-2"></i>{{ $benefit }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if ($course->requirements_arr)
                                                    <div class="col-md-6">
                                                        <ul class="list-group list-group-borderless">
                                                            @foreach ($course->requirements_arr as $requirement)
                                                                <li class="list-group-item h6 fw-light d-flex mb-0">
                                                                    <i
                                                                        class="fas fa-check-circle text-success me-2"></i>{{ $requirement }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif





                                    </div>
                                    <!-- Card body START -->
                                </div>
                            </div>
                            <!-- About course END -->

                            <!-- Curriculum START -->
                            @include('front.partials.chapters.skin-1')
                            <!-- Curriculum END -->

                            <!-- Review START -->

                            <div class="col-12">
                                <div class="card border rounded-3">
                                    <!-- Card header START -->
                                    <div class="card-header border-bottom">
                                        @include('front.partials.reviews')
                                    </div>
                                </div>

                            </div>
                            <!-- Leave Review END -->

                            <!-- FAQs START -->
                            @include('front.partials.faq.skin-2')

                        </div>
                    </div>
                    <!-- Main content END -->

                    <!-- Right sidebar START -->
                    <div class="col-xl-4">
                        <div data-sticky data-margin-top="80" data-sticky-for="768">
                            <div class="row g-4">
                                <div class="col-md-6 col-xl-12">
                                    <!-- Course info START -->
                                    <div class="card card-body border p-4">

                                        <?php
                if ($isEnrolled) {
                ?>
                                        <!-- Card body -->
                                        <div class="card-body px-3">
                                            <!-- Info -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <!-- Price and time -->
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <h3 class="fw-bold mb-0 me-2"> </h3>
                                                    </div>
                                                </div>
                                                <!-- Share button with dropdown -->
                                                <div class="dropdown">
                                                    <!-- Share button -->
                                                    <a href="#" class="btn btn-sm btn-light rounded small"
                                                        role="button" id="dropdownShare" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="fas fa-fw fa-share-alt"></i>
                                                    </a>
                                                    <!-- dropdown button -->
                                                    <ul class="dropdown-menu dropdown-w-sm dropdown-menu-end min-w-auto shadow rounded"
                                                        aria-labelledby="dropdownShare">
                                                        <li><a class="dropdown-item"
                                                                href="https://twitter.com/intent/tweet?url={{ URL::current() }}"
                                                                target="_blank"><i
                                                                    class="fab fa-twitter-square me-2"></i>Twitter</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="https://www.facebook.com/sharer/sharer.php?u={{ URL::current() }}"
                                                                target="_blank"><i
                                                                    class="fab fa-facebook-square me-2"></i>Facebook</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="https://www.linkedin.com/shareArticle?url={{ URL::current() }}"
                                                                target="_blank"><i
                                                                    class="fab fa-linkedin me-2"></i>LinkedIn</a></li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="copyToClipboard('{{ URL::current() }}');"><i
                                                                    class="fas fa-copy me-2"></i>Copy link</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <a href="<?php echo $course->continue_url; ?>"
                                                class="color btn btn-success w-100 mb-0 btn btn-primary fav-enroll btn-lg btn-block">{{ tr('Continue Course') }}</a>

                                            <?php
    } elseif ($course->free) {
    ?>
                                            <div class="card-body px-3">
                                                <!-- Info -->
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <!-- Price and time -->
                                                    <div>
                                                        <div class="d-flex align-items-center">
                                                            <h3 class="fw-bold mb-0 me-2"> <?php # echo $course->price_html(false, true);
                                                            ?></h3>
                                                        </div>
                                                    </div>
                                                    <!-- Share button with dropdown -->
                                                    <div class="dropdown">
                                                        <!-- Share button -->
                                                        <a href="#" class="btn btn-sm btn-light rounded small"
                                                            role="button" id="dropdownShare" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fas fa-fw fa-share-alt"></i>
                                                        </a>
                                                        <!-- dropdown button -->
                                                        <ul class="dropdown-menu dropdown-w-sm dropdown-menu-end min-w-auto shadow rounded"
                                                            aria-labelledby="dropdownShare">
                                                            <li><a class="dropdown-item"
                                                                    href="https://twitter.com/intent/tweet?url={{ URL::current() }}"
                                                                    target="_blank"><i
                                                                        class="fab fa-twitter-square me-2"></i>{{ tr('Twitter') }}</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="https://www.facebook.com/sharer/sharer.php?u={{ URL::current() }}"
                                                                    target="_blank"><i
                                                                        class="fab fa-facebook-square me-2"></i>{{ tr('Facebook') }}</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="https://www.linkedin.com/shareArticle?url={{ URL::current() }}"
                                                                    target="_blank"><i
                                                                        class="fab fa-linkedin me-2"></i>{{ tr('LinkedIn') }}</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="copyToClipboard('{{ URL::current() }}');"><i
                                                                        class="fas fa-copy me-2"></i>{{ tr('Copy link') }}</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <form action="<?php echo route('free_enroll'); ?>" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="course_id" value="<?php echo $course->id; ?>">
                                                    <button type="submit"
                                                        class="color btn btn-success w-100 mb-0 btn btn-primary bg-primary fav-enroll btn-lg btn-block">{{ tr('Enroll Now') }}</button>
                                                </form>
                                            </div>

                                            <?php
    } elseif ($course->paid) {
    ?>

                                            <div class="card-body px-3">
                                                <!-- Info -->
                                                <!-- Price and time -->
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <!-- Price -->
                                                    <h5 class="fw-bold mb-0 me-2"> <?php echo $course->price_html(false, true); ?></h5>
                                                    <!-- Share button with dropdown -->
                                                    <div class="dropdown">
                                                        <!-- Share button -->
                                                        <a href="#" class="btn btn-sm btn-light rounded small"
                                                            role="button" id="dropdownShare" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fas fa-fw fa-share-alt"></i>
                                                        </a>
                                                        <!-- dropdown button -->
                                                        <ul class="dropdown-menu dropdown-w-sm dropdown-menu-end min-w-auto shadow rounded"
                                                            aria-labelledby="dropdownShare">
                                                            <li><a class="dropdown-item"
                                                                    href="https://twitter.com/intent/tweet?url={{ URL::current() }}"
                                                                    target="_blank"><i
                                                                        class="fab fa-twitter-square me-2"></i>{{ tr('Twitter') }}</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="https://www.facebook.com/sharer/sharer.php?u={{ URL::current() }}"
                                                                    target="_blank"><i
                                                                        class="fab fa-facebook-square me-2"></i>{{ tr('Facebook') }}</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="https://www.linkedin.com/shareArticle?url={{ URL::current() }}"
                                                                    target="_blank"><i
                                                                        class="fab fa-linkedin me-2"></i>{{ tr('LinkedIn') }}</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="copyToClipboard('{{ URL::current() }}');"><i
                                                                        class="fas fa-copy me-2"></i>{{ tr('Copy link') }}</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>


                                                <form action="<?php echo route('add_to_cart'); ?>" class="color add_to_cart_form"
                                                    method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="course_id" value="<?php echo $course->id; ?>">

                                                    <div class="mt-3 d-grid">

                                                        <?php
                                                        $in_cart = cart($course->id);
                                                        ?>
                                                        {{-- 
                                                        <button type="button" class="btn btn-outline-primary"
                                                            data-course-id="<?php echo $course->id; ?>" name="cart_btn"
                                                            value="add_to_cart" <?php echo $in_cart ? 'disabled="disabled"' : ''; ?>>
                                                            <?php if ($in_cart): ?>
                                                            <i class='la la-check-circle'></i> {{ tr('Added to cart') }}
                                                            <?php else: ?>
                                                            <?php endif; ?>
                                                        </button> --}}
                                                        <button type="submit" class="btn btn-success" name="cart_btn"
                                                            value="buy_now">{{ tr('Purchase Now') }}</button>

                                                    </div>





                                                </form>
                                            </div>


                                            <?php
    } elseif ($course->price_plan == 'subscription') {
    ?>
                                            <form action="{{ route('subscribe_enroll') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $course->title }}</h5>
                                                    <p class="card-text">
                                                        <strong>Subscription Required:</strong>
                                                        @if ($course->subscribtion)
                                                            <i class="fas fa-lock"></i>
                                                            {{ $course->subscription_plan->name }}
                                                        @endif
                                                    </p>
                                                    <button type="submit"
                                                        class="color btn btn-primary w-100 mb-0 btn btn-lightsky bg-red btn-lg btn-block">
                                                        {{ __t('enroll_with_subscription') }}
                                                    </button>
                                                </div>
                                            </form>

                                            <?php
    }
    ?>


                                            <!-- Divider -->
                                            <hr>

                                            <!-- Title -->
                                            <h5 class="mb-3">This course includes</h5>
                                            <ul class="list-group list-group-borderless border-0">
                                                <li class="list-group-item px-0 d-flex justify-content-between">
                                                    <span class="h6 fw-light mb-0"><i
                                                            class="fas fa-fw fa-book-open text-primary"></i>{{ tr('Lectures') }}</span>
                                                    <span><?php echo $course->total_lectures; ?></span>
                                                </li>
                                                <li class="list-group-item px-0 d-flex justify-content-between">
                                                    <span class="h6 fw-light mb-0"><i
                                                            class="fas fa-fw fa-clock text-primary"></i>{{ tr('Video Time') }}</span>
                                                    <span><?php echo seconds_to_time_format($course->total_video_time); ?></span>
                                                </li>
                                                <li class="list-group-item px-0 d-flex justify-content-between">
                                                    <span class="h6 fw-light mb-0"><i
                                                            class="fas fa-fw fa-signal text-primary"></i>{{ tr('Level') }}</span>
                                                    <span><?php echo course_levels($course->level); ?></span>
                                                </li>
                                                <li class="list-group-item px-0 d-flex justify-content-between">
                                                    <span class="h6 fw-light mb-0"><i
                                                            class="fas fa-fw fa-user-clock text-primary"></i>{{ tr('Updated') }}</span>
                                                    <span><?php echo date('M d Y', strtotime($course->last_updated_at)); ?></span>
                                                </li>

                                            </ul>
                                            <!-- Divider -->
                                            <hr>

                                            <!-- Instructor info -->
                                            <div class="d-sm-flex align-items-center">
                                                <!-- Avatar image -->
                                                <div class="avatar avatar-xl">
                                                    <img class="avatar-img rounded-circle" src="<?php echo $course->author->get_photo; ?>"
                                                        alt="avatar">
                                                </div>
                                                <div class="ms-sm-3 mt-2 mt-sm-0">
                                                    <h5 class="mb-0"><a href="<?php echo route('profile', $course->author->id); ?>">By
                                                            <?php echo $course->author->name; ?> <?php echo $course->author->last_name; ?></a></h5>
                                                    <p class="mb-0 small"><?php echo $course->author->job_title; ?></p>
                                                </div>
                                            </div>

                                            <!-- Rating and follow -->
                                            <div
                                                class="d-sm-flex justify-content-sm-between align-items-center mt-0 mt-sm-2">
                                                <!-- Rating star -->
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item me-0 small"><i
                                                            class="fas fa-star text-warning"></i></li>
                                                    <li class="list-inline-item me-0 small"><i
                                                            class="fas fa-star text-warning"></i></li>
                                                    <li class="list-inline-item me-0 small"><i
                                                            class="fas fa-star text-warning"></i></li>
                                                    <li class="list-inline-item me-0 small"><i
                                                            class="fas fa-star text-warning"></i></li>
                                                    <li class="list-inline-item me-0 small"><i
                                                            class="fas fa-star-half-alt text-warning"></i></li>
                                                    <li class="list-inline-item ms-2 h6 fw-light mb-0">
                                                    <li class="list-inline-item ms-2 h6 fw-light mb-0">
                                                        <?php
                                                        if (isset($rating)) {
                                                            if (is_object($rating)) {
                                                                echo ''; // Handle the case when $rating is an object (e.g., stdClass)
                                                            } else {
                                                                echo number_format($rating, 1); // Handle the case when $rating is a float
                                                            }
                                                        } else {
                                                            echo ''; // Handle the case when $rating is not set (i.e., null)
                                                        }
                                                        ?><?php if(isset($rating) && !is_object($rating)): ?>/5.0<?php endif; ?>
                                                    </li>


                                                    </li>
                                                </ul>

                                                <!-- button -->

                                                <button id="followToggle"
                                                    class="btn {{ $authUserIsFollower ? 'btn-danger' : 'btn-primary' }} btn-sm mb-0"
                                                    data-user-id="{{ $auth_user ? $auth_user->id : '' }}">
                                                    @if ($authUserIsFollower)
                                                        Unfollow
                                                    @else
                                                        Follow
                                                    @endif
                                                </button>

                                            </div>
                                        </div>
                                        <!-- Course info END -->
                                    </div>

                                    @if ($course->tagsArr)
                                        <div class="card card-body border m-2 p-4">
                                            <h4 class="mb-3">{{ tr('Course Tags') }}</h4>
                                            <ul class="list-inline mb-0">
                                                @foreach ($course->tagsArr as $tag)
                                                    <li class="list-inline-item">
                                                        <a class="btn btn-outline-light btn-sm"
                                                            href="#">{{ $tag }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div><!-- Row End -->
                            </div>
                        </div>
                        <!-- Right sidebar END -->


                    </div><!-- Row END -->
                    <div class="mt-6">
                        @include('sections.courses.skin-6')
                    </div>
                </div>
        </section>
        <!-- =======================
                                                                                    Page content END -->

    </main>

    <link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">



    <!-- Your JavaScript Script -->
    <script>
        $("body").on("click", "#followToggle", function(event) {
            event.preventDefault();

            var button = $(this);
            var userId = button.attr("data-user-id");
            var url = "/profile/" + userId + "/follow";

            button.addClass("loadingbar primary").prop("disabled", true);

            // Assuming you use $.get method for this as well
            $.get(url, function(response) {
                button.removeClass("loadingbar primary").prop("disabled", false);

                if (response && response.code === 200) {
                    if (response.follow) {
                        button.removeClass("btn-primary").addClass("btn-danger");
                        button.text('Unfollow');
                    } else {
                        button.removeClass("btn-danger").addClass("btn-primary");
                        button.text('Follow');
                    }
                }
            }).fail(function(error) {
                button.removeClass("loadingbar primary").prop("disabled", false);
                console.error("Error:", error);
                // Handle error if needed
            });
        });
    </script>

    <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script>
@endsection

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


@section('content')


    <!-- **************** MAIN CONTENT START **************** -->
    <main>



        <!-- =======================
    Page banner video START -->
        <section class="py-0 pb-lg-5">
            <div class="container">
                <div class="row g-3">
                    <!-- Course video START -->




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




                    <!-- Course video END -->

                    <!-- Playlist responsive toggler START -->
                    <div class="col-12 d-lg-none">
                        <button class="btn btn-primary mb-3" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                            <i class="bi bi-camera-video me-1"></i> {{ tr('Playlist') }}
                        </button>
                    </div>
                    <!-- Playlist responsive toggler END -->
                </div>
            </div>
        </section>
        <!-- =======================
    Page banner video END -->


        <!-- =======================
    Page content START -->
        <section class="pt-0">
            <div class="container">
                <div class="row g-lg-5">

                    <!-- Main content START -->
                    <div class="col-lg-8">
                        <div class="row g-4">

                            <?php
                            echo '<div class="col-12">';
                            echo '<h1>' . clean_html($course->title) . '</h1>';
                            echo '<ul class="list-inline mb-0">';
                            echo '<li class="list-inline-item h6 me-3 mb-1 mb-sm-0"><i class="fas fa-star text-warning me-2"></i>' . number_format($course->rating_value, 1) . '/5.0</li>';
                            echo '<li class="list-inline-item h6 me-3 mb-1 mb-sm-0"><i class="fas fa-signal text-success me-2"></i>' . course_levels($course->level) . '</li>';
                            echo '<li class="list-inline-item h6 me-3 mb-1 mb-sm-0"><i class="bi bi-patch-exclamation-fill text-danger me-2"></i>' . __t('last_updated') . ' ' . $course->last_updated_at->format('Y-m-d') . '</li>';
                            echo '</ul>';
                            echo '</div>';
                            ?>

                            <!-- Course title END -->

                            <!-- Instructor detail START -->
                            <div class="col-12">
                                <div class="d-sm-flex justify-content-sm-between align-items-center">
                                    <!-- Avatar detail -->
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar image -->
                                        <div class="avatar avatar-lg">
                                            <img class="avatar-img rounded-circle" src="<?php echo $course->author->get_photo; ?>" alt="avatar">
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0"><a href="<?php echo route('profile', $course->author->id); ?>">By <?php echo $course->author->name; ?></a></h6>
                                            <p class="mb-0 small"><?php echo $course->author->job_title; ?></p>
                                        </div>
                                    </div>


                                    <!-- Button -->
                                    <div class="d-flex mt-2 mt-sm-0">
                                        <button id="followToggle" class="btn btn-primary btn-sm mb-0"
                                            data-user-id="<?php echo isset($auth_user) ? $auth_user->id : ''; ?>">
                                            <?php echo $authUserIsFollower ? 'Unfollow' : 'Follow'; ?>
                                        </button>
                                        <!-- Share button with dropdown -->
                                        <div class="dropdown ms-2">
                                            <a href="#" class="btn btn-sm mb-0 btn-info-soft small" role="button"
                                                id="dropdownShare" data-bs-toggle="dropdown" aria-expanded="false">
                                                share
                                            </a>
                                            <!-- dropdown button -->
                                            <ul class="dropdown-menu dropdown-w-sm dropdown-menu-end min-w-auto shadow rounded"
                                                aria-labelledby="dropdownShare">
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="fab fa-twitter-square me-2"></i>{{ tr('Twitter') }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="fab fa-facebook-square me-2"></i>{{ tr('Facebook') }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="fab fa-linkedin me-2"></i>{{ tr('LinkedIn') }}</a></li>
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="fas fa-copy me-2"></i>{{ tr('Copy link') }}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Instructor detail END -->

                            <!-- Course detail START -->
                            <div class="col-12">
                                <!-- Tabs START -->
                                <ul class="nav nav-pills nav-pills-bg-soft px-3" id="course-pills-tab" role="tablist">
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-0 active" id="course-pills-tab-1" data-bs-toggle="pill"
                                            data-bs-target="#course-pills-1" type="button" role="tab"
                                            aria-controls="course-pills-1"
                                            aria-selected="true">{{ tr('Overview') }}</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-0" id="course-pills-tab-2" data-bs-toggle="pill"
                                            data-bs-target="#course-pills-2" type="button" role="tab"
                                            aria-controls="course-pills-2"
                                            aria-selected="false">{{ tr('Reviews') }}</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-0" id="course-pills-tab-3" data-bs-toggle="pill"
                                            data-bs-target="#course-pills-3" type="button" role="tab"
                                            aria-controls="course-pills-3"
                                            aria-selected="false">{{ tr('FAQs') }}</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-0" id="course-pills-tab-4" data-bs-toggle="pill"
                                            data-bs-target="#course-pills-4" type="button" role="tab"
                                            aria-controls="course-pills-4" aria-selected="false">Comment</button>
                                    </li>
                                </ul>
                                <!-- Tabs END -->

                                <!-- Tab contents START -->
                                <div class="tab-content pt-4 px-3" id="course-pills-tabContent">
                                    <!-- Content START -->
                                    <div class="tab-pane fade show active" id="course-pills-1" role="tabpanel"
                                        aria-labelledby="course-pills-tab-1">
                                        <!-- Course detail START -->
                                        <h5 class="mb-3">{{ tr('Course Description') }}</h5>
                                        <?php if ($course->short_description): ?>
                                        <p class="page-header-subtitle m-0"><?= clean_html($course->short_description) ?>
                                        </p>
                                        <?php endif; ?>

                                        <!-- List content -->
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







                                        <?php if ($course->description): ?>

                                        <p class="mb-0"><?= clean_html($course->description) ?></p>

                                        <?php endif; ?>



                                        <!-- Course detail END -->

                                    </div>
                                    <!-- Content END -->

                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-2" role="tabpanel"
                                        aria-labelledby="course-pills-tab-2">
                                        <!-- Review START -->
                                        @include('front.partials.reviews')

                                        <!-- Leave Review END -->

                                    </div>
                                    <!-- Content END -->

                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-3" role="tabpanel"
                                        aria-labelledby="course-pills-tab-3">
                                        @include('front.partials.faq')

                                    </div>
                                    <!-- Content END -->

                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-4" role="tabpanel"
                                        aria-labelledby="course-pills-tab-4">
                                        @include('front.partials.discussions')

                                    </div>
                                    <!-- Content END -->
                                </div>
                                <!-- Tab contents END -->
                            </div>
                            <!-- Course detail END -->
                        </div>
                    </div>
                    <!-- Main content END -->

                    <!-- Right sidebar START -->
                    <div class="col-lg-4">

                        <!-- Card body -->
                        <div class="card mb-3 bg-dark card-body px-3">
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
                                        <a href="#" class="btn btn-sm btn-light rounded small" role="button"
                                            id="dropdownShare" data-bs-toggle="dropdown" aria-expanded="false">
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
                                                        class="fab fa-linkedin me-2"></i>{{ tr('LinkedIn') }}</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="copyToClipboard('{{ URL::current() }}');"><i
                                                        class="fas fa-copy me-2"></i>{{ tr('Copy link') }}</a></li>
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
                                                <h3 class="fw-bold text-white mb-0 me-2"> <?php echo $course->price_html(false, true); ?></h3>
                                            </div>
                                        </div>
                                        <!-- Share button with dropdown -->
                                        <div class="dropdown">
                                            <!-- Share button -->
                                            <a href="#" class="btn btn-sm btn-light rounded small" role="button"
                                                id="dropdownShare" data-bs-toggle="dropdown" aria-expanded="false">
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
                                                            class="fab fa-linkedin me-2"></i>{{ tr('LinkedIn') }}</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="copyToClipboard('{{ URL::current() }}');"><i
                                                            class="fas fa-copy me-2"></i>{{ tr('Copy link') }}</a></li>
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
                                        <h5 class="fw-bold mb-0 text-white me-2"> <?php echo $course->price_html(false, true); ?></h5>
                                        <!-- Share button with dropdown -->
                                        <div class="dropdown">
                                            <!-- Share button -->
                                            <a href="#" class="btn btn-sm btn-light rounded small" role="button"
                                                id="dropdownShare" data-bs-toggle="dropdown" aria-expanded="false">
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
                                                            class="fab fa-linkedin me-2"></i>{{ tr('LinkedIn') }}</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="copyToClipboard('{{ URL::current() }}');"><i
                                                            class="fas fa-copy me-2"></i>{{ tr('Copy link') }}</a></li>
                                            </ul>
                                        </div>
                                    </div>


                                    <form action="<?php echo route('add_to_cart'); ?>" class="color add_to_cart_form" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="course_id" value="<?php echo $course->id; ?>">

                                        <div class="mt-3 d-grid">

                                            <?php
                                            $in_cart = cart($course->id);
                                            ?>

                                            {{-- <button type="button" class="btn btn-outline-primary" data-course-id="<?php echo $course->id; ?>" name="cart_btn" value="add_to_cart" <?php echo $in_cart ? 'disabled="disabled"' : ''; ?>>
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
                                        <h5 class="card-title text-white">{{ $course->title }}</h5>
                                        <p class="card-text">
                                            <strong>{{ tr('Subscription Required:') }}</strong>
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
                            </div>
                        </div>
                        <!-- Responsive offcanvas body START -->
                        <div class="offcanvas-body p-3 p-lg-0">


                            <div class="col-12">
                                <?php if ($course->sections->count()): ?>
                                <!-- Accordion START -->


                                @include('front.partials.chapters.skin-3')

                                <!-- Accordion END -->
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Responsive offcanvas body END -->

                        <?php if ($course->tags_arr): ?>
                        <!-- Tags START -->
                        <div class="mt-4">
                            <h4 class="mb-3">Tags</h4>
                            <ul class="list-inline mb-0">
                                <?php foreach ($course->tags_arr as $tags): ?>
                                <li class="list-inline-item"> <a class="btn btn-outline-light btn-sm"
                                        href="#"><?php echo $tags; ?></a> </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <!-- Tags END -->
                        <?php endif; ?>

                    </div>
                    <!-- Right sidebar END -->

                </div><!-- Row END -->
            </div>
        </section>
        <!-- =======================
    Page content END -->

    </main>
    <!-- **************** MAIN CONTENT END **************** -->





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

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
            Page intro START -->
        <section class="bg-blue py-7">
            <div class="container">
                <div class="row justify-content-lg-between">

                    <div class="col-lg-8">
                        <!-- Title -->
                        <h1 class="text-white">{{ clean_html($course->title) }}</h1>
                        @if ($course->short_description)
                            <p class="text-white">{{ clean_html($course->short_description) }}</p>
                        @endif

                        <!-- Content -->
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item text-white fw-light h6 me-3 mb-1 mb-sm-0"><i
                                    class="fas fa-star me-2"></i><?php echo number_format($course->rating_value, 1); ?> /5.0</li>
                            <li class="list-inline-item text-white fw-light h6 me-3 mb-1 mb-sm-0"><i
                                    class="fas fa-user-graduate me-2"></i>{{ enrolledUsersCount($course) }}
                                {{ tr('Enrolled') }}</li>
                            <li class="list-inline-item text-white fw-light h6 me-3 mb-1 mb-sm-0"><i
                                    class="fas fa-signal me-2"></i><?php echo course_levels($course->level); ?></li>
                            <li class="list-inline-item text-white fw-light h6 me-3 mb-1 mb-sm-0"><i
                                    class="bi bi-patch-exclamation-fill me-2"></i>{{ tr('Last updated') }}
                                <?php echo date('M d Y', strtotime($course->last_updated_at)); ?></li>
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <!-- Card body -->
                        <div class="card bg-dark card-body px-3">
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
                                                        class="fab fa-twitter-square me-2"></i>{{ tr('Twitter') }}</a></li>
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
                    </div>
        </section>
        <!-- =======================
            Page intro END -->

        <!-- =======================
            Page content START -->
        <section class="pt-0">
            <div class="container">
                <div class="row">
                    <!-- Main content START -->
                    <div class="col-12">
                        <div class="card shadow rounded-2 p-0 mt-n5">
                            <!-- Tabs START -->
                            <div class="card-header border-bottom px-4 pt-3 pb-0">
                                <ul class="nav nav-bottom-line py-0" id="course-pills-tab" role="tablist">
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-2 mb-md-0 active" id="course-pills-tab-1"
                                            data-bs-toggle="pill" data-bs-target="#course-pills-1" type="button"
                                            role="tab" aria-controls="course-pills-1" aria-selected="true">Course
                                            Materials</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-2 mb-md-0" id="course-pills-tab-2"
                                            data-bs-toggle="pill" data-bs-target="#course-pills-2" type="button"
                                            role="tab" aria-controls="course-pills-2"
                                            aria-selected="false">{{ tr('Instructor') }}</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-2 mb-md-0" id="course-pills-tab-3"
                                            data-bs-toggle="pill" data-bs-target="#course-pills-3" type="button"
                                            role="tab" aria-controls="course-pills-3"
                                            aria-selected="false">Discussion</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-2 mb-md-0" id="course-pills-tab-4"
                                            data-bs-toggle="pill" data-bs-target="#course-pills-4" type="button"
                                            role="tab" aria-controls="course-pills-4"
                                            aria-selected="false">{{ tr('Reviews') }}</button>
                                    </li>
                                    <!-- Tab item -->
                                    <li class="nav-item me-2 me-sm-4" role="presentation">
                                        <button class="nav-link mb-2 mb-md-0" id="course-pills-tab-5"
                                            data-bs-toggle="pill" data-bs-target="#course-pills-5" type="button"
                                            role="tab" aria-controls="course-pills-5"
                                            aria-selected="false">FAQ</button>
                                    </li>
                                </ul>
                            </div>
                            <!-- Tabs END -->

                            <!-- Tab contents START -->
                            <div class="card-body p-sm-4">
                                <div class="tab-content" id="course-pills-tabContent">
                                    <!-- Content START -->
                                    <div class="tab-pane fade show active" id="course-pills-1" role="tabpanel"
                                        aria-labelledby="course-pills-tab-1">




                                        <!-- Accordion START -->

                                        @include('front.partials.chapters.skin-2')

                                        <?php if ($course->description): ?>

                                        <p class="mb-0"><?= clean_html($course->description) ?></p>

                                        <?php endif; ?>


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



                                        <!-- Accordion END -->
                                    </div>
                                    <!-- Content END -->

                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-2" role="tabpanel"
                                        aria-labelledby="course-pills-tab-2">

                                        @include('front.partials.instructor')

                                    </div>
                                    <!-- Content END -->


                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-3" role="tabpanel"
                                        aria-labelledby="course-pills-tab-3">

                                        @include('front.partials.discussions')

                                    </div>
                                    <!-- Content END -->

                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-4" role="tabpanel"
                                        aria-labelledby="course-pills-tab-4">

                                        @include('front.partials.reviews')

                                    </div>
                                    <!-- Content END -->

                                    <!-- Content START -->
                                    <div class="tab-pane fade" id="course-pills-5" role="tabpanel"
                                        aria-labelledby="course-pills-tab-5">

                                        @include('front.partials.faq')

                                    </div>
                                    <!-- Content END -->

                                </div>
                            </div>
                            <!-- Tab contents END -->
                        </div>
                    </div>
                    <!-- Main content END -->
                </div><!-- Row END -->
            </div>
        </section>
        <!-- =======================
            Page content END -->

    </main>
    <!-- **************** MAIN CONTENT END **************** -->
@endsection

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ get_option('enable_rtl') ? 'rtl' : 'auto' }}">

<head>
    <title>
        @if (!empty($title))
            {{ $title }} | {{ get_option('site_name') }} - {{ get_option('site_title') }}
        @else
            {{ get_option('site_name') }} - {{ get_option('site_title') }}
        @endif
    </title>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="@if (!empty($title)) {{ $title }} | {{ get_option('site_name') }} - {{ get_option('site_title') }}  @else {{ get_option('site_name') }} - {{ get_option('site_title') }} @endif">

    <!-- Favicon -->
    @php
        $faviconUrl = media_file_uri(get_option('site_favicon'));
        $user = auth()->user();
        $user_id = $auth_user->id;

        $enrolledCount = \App\Models\Enroll::whereUserId($user_id)->whereStatus('success')->count();
        $wishListed = \Illuminate\Support\Facades\DB::table('wishlists')->whereUserId($user_id)->count();

        $myReviewsCount = \App\Models\Review::whereUserId($user_id)->count();
        $purchases = $auth_user->purchases()->take(10)->get();
    @endphp


    @if ($faviconUrl)
        <link rel="shortcut icon" href="{{ media_file_uri(get_option('site_favicon')) }}" />
    @else
        <link rel="shortcut icon" href="{{ '/assets/images/favicon.svg' }}" />
    @endif

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/apexcharts/css/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">

    @if (get_option('enable_rtl') ? 'rtl' : 'auto' == 'rtl')
        <script src="/assets/vendor/tiny-slider/tiny-slider-rtl.js"></script>
        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/css/style-rtl.css">
    @else
        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">

        <script src="/assets/vendor/tiny-slider/tiny-slider.js"></script>
    @endif

    <link id="style-switch" rel="stylesheet" type="text/css" href="/assets/css/custom.css">

    <script type="text/javascript">
        /* <![CDATA[ */
        window.pageData = @json(pageJsonData());
        /* ]]> */
    </script>

    <!-- Dark mode -->
    <script>
        const storedTheme = localStorage.getItem('theme')

        const getPreferredTheme = () => {
            if (storedTheme) {
                return storedTheme
            }
            return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light'
        }

        const setTheme = function(theme) {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'dark')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }

        setTheme(getPreferredTheme())

        window.addEventListener('DOMContentLoaded', () => {
            var el = document.querySelector('.theme-icon-active');
            if (el != 'undefined' && el != null) {
                const showActiveTheme = theme => {
                    const activeThemeIcon = document.querySelector('.theme-icon-active use')
                    const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                    const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

                    document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                        element.classList.remove('active')
                    })

                    btnToActive.classList.add('active')
                    activeThemeIcon.setAttribute('href', svgOfActiveBtn)
                }

                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (storedTheme !== 'light' || storedTheme !== 'dark') {
                        setTheme(getPreferredTheme())
                    }
                })

                showActiveTheme(getPreferredTheme())

                document.querySelectorAll('[data-bs-theme-value]')
                    .forEach(toggle => {
                        toggle.addEventListener('click', () => {
                            const theme = toggle.getAttribute('data-bs-theme-value')
                            localStorage.setItem('theme', theme)
                            setTheme(theme)
                            showActiveTheme(theme)
                        })
                    })

            }
        })
    </script>
</head>

<body>

    <!-- Header START -->

    @include('front.header')

    <!-- Header END -->

    <!-- **************** MAIN CONTENT START **************** -->

    <!-- **************** MAIN CONTENT START **************** -->
    <main>

        <!-- =======================
    Page Banner START -->
        <section class="pt-0">
            <!-- Main banner background image -->
            <div class="container-fluid px-0">
                <div class="bg-blue h-100px h-md-200px rounded-0"
                    style="background:url(/assets/images/pattern/04.png) no-repeat center center; background-size:cover;">
                </div>
            </div>

            @if ($auth_user->is_instructor)

                <div class="container mt-n4">
                    <div class="row">
                        <!-- Profile banner START -->
                        <div class="col-12">
                            <div class="card bg-transparent card-body p-0">
                                <div class="row d-flex justify-content-between">
                                    <!-- Avatar -->
                                    <div class="col-auto mt-4 mt-md-0">
                                        <div class="avatar avatar-xxl mt-n3">
                                            <img class="avatar-img rounded-circle border border-white border-3 shadow"
                                                src="{!! $auth_user->get_photo !!}" alt="">
                                        </div>
                                    </div>

                                    @php
                                        $courses_count = $auth_user->courses()->publish()->count();
                                        $students_count = $auth_user->student_enrolls->count();
                                        $instructor_rating = $auth_user->get_rating;
                                        $rating = $auth_user->get_rating;

                                    @endphp


                                    <!-- Profile info -->
                                    <div class="col d-md-flex justify-content-between align-items-center mt-4">
                                        <div>
                                            <h1 class="my-1 fs-4">{{ $auth_user->name }} <i
                                                    class="bi bi-patch-check-fill text-info small"></i></h1>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item h6 fw-light me-3 mb-1 mb-sm-0"><i
                                                        class="fas fa-star text-warning me-2"></i>{{ round($rating->rating_avg) }}
                                                </li>
                                                <li class="list-inline-item h6 fw-light me-3 mb-1 mb-sm-0"><i
                                                        class="fas fa-user-graduate text-orange me-2"></i>{{ $students_count }}
                                                    {{ tr('Enrolled Students') }}</li>
                                                <li class="list-inline-item h6 fw-light me-3 mb-1 mb-sm-0"><i
                                                        class="fas fa-book text-purple me-2"></i>{{ $courses_count }}
                                                    {{ tr('Courses') }}</li>
                                            </ul>
                                        </div>


                                        <!-- Button -->


                                        <div class="d-flex align-items-center mt-2 mt-md-0">
                                            <a href="{{ route('create_course') }}"
                                                class="btn btn-success mb-0">{{ tr('Create a course') }}</a>
                                        </div>



                                    </div>
                                </div>
                            </div>
                            <!-- Profile banner END -->

                            <!-- Advanced filter responsive toggler START -->
                            <!-- Divider -->
                            <hr class="d-xl-none">
                            <div class="col-12 col-xl-3 d-flex justify-content-between align-items-center">
                                <a class="h6 mb-0 fw-bold d-xl-none" href="#">{{ tr('Menu') }}</a>
                                <button class="btn btn-primary d-xl-none" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                                    <i class="fas fa-sliders-h"></i>
                                </button>
                            </div>
                            <!-- Advanced filter responsive toggler END -->
                        @else
                            <div class="container mt-n4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card bg-transparent card-body pb-0 px-0 mt-2 mt-sm-0">
                                            <div class="row d-sm-flex justify-sm-content-between mt-2 mt-md-0">
                                                <!-- Avatar -->
                                                <div class="col-auto">
                                                    <div class="avatar avatar-xxl position-relative mt-n3">
                                                        <img class="avatar-img rounded-circle border border-white border-3 shadow"
                                                            src="{{ $auth_user->get_photo }}" alt="">

                                                        @if ($user->hasSubscriptionExpired())
                                                        @elseif ($user->subscription)
                                                            <span
                                                                class="badge text-bg-success rounded-pill position-absolute top-50 start-100 translate-middle mt-4 mt-md-5 ms-n3 px-md-3">
                                                                {{ $user->subscription->name }}
                                                            </span>
                                                        @endif


                                                    </div>
                                                </div>
                                                <!-- Profile info -->
                                                <div class="col d-sm-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h1 class="my-1 fs-4">{{ $auth_user->name }}</h1>
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item me-3 mb-1 mb-sm-0">
                                                                <span class="h6">{{ $enrolledCount }}</span>
                                                                <span
                                                                    class="text-body fw-light">{{ tr('Enrolled Courses') }}</span>
                                                            </li>
                                                            <li class="list-inline-item me-3 mb-1 mb-sm-0">
                                                                <span class="h6">{{ $myReviewsCount }}</span>
                                                                <span
                                                                    class="text-body fw-light">{{ tr('My Reviews') }}</span>
                                                            </li>
                                                            {{-- <li class="list-inline-item me-3 mb-1 mb-sm-0">
                                                                <span class="h6">{{ $wishListed }}</span>
                                                                <span
                                                                    class="text-body fw-light">{{ tr('Wishlisted Courses') }}</span>
                                                            </li> --}}
                                                        </ul>
                                                    </div>
                                                    <!-- Button -->
                                                    <div class="mt-2 mt-sm-0">
                                                        <a href="{{ route('enrolled_courses') }}"
                                                            class="btn btn-outline-primary mb-0">{{ tr('View my courses') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Advanced filter responsive toggler START -->
                                        <!-- Divider -->
                                        <hr class="d-xl-none">
                                        <div class="col-12 col-xl-3 d-flex justify-content-between align-items-center">
                                            <a class="h6 mb-0 fw-bold d-xl-none"
                                                href="#">{{ tr('Menu') }}</a>
                                            <button class="btn btn-primary d-xl-none" type="button"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                                                aria-controls="offcanvasNavbar">
                                                <i class="fas fa-sliders-h"></i>
                                            </button>
                                        </div>
                                        <!-- Advanced filter responsive toggler END -->
                                    </div>
                                </div>
                            </div>

            @endif


            </div>
            </div>
            </div>
        </section>
        <!-- =======================
    Page Banner END -->

        <!-- =======================
    Page content START -->
        <section class="pt-0">
            <div class="container">
                <div class="row">
                    <!-- Right sidebar START -->
                    @include('front.partials.dash_menu')
                    <!-- Right sidebar END -->


                    <!-- Main content START -->
                    <div class="col-xl-9">

                        @include('inc.flash_msg')
                        @yield('content')




                    </div>
                    <!-- Main content END -->
                </div><!-- Row END -->
            </div>
        </section>
        <!-- =======================
Page content END -->

    </main>

    <!-- **************** MAIN CONTENT END **************** -->

    <!-- =======================Footer START -->
    @include('front.footer')

    <!-- =======================
Footer END -->

    <!-- Back to top -->
    <div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i>
    </div>

    <!-- Bootstrap JS -->
    {{-- <script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script> --}}
    <script src="{{ asset('assets/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>

    <!-- Vendors -->
    {{-- <script src="/assets/vendor/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="/assets/vendor/apexcharts/js/apexcharts.min.js"></script>
    <script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script> --}}


    <script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/plyr/plyr.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounterjs/dist/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/js/apexcharts.min.js') }}"></script>
    
    <!-- Template Functions -->
    <script src="/assets/js/functions.js"></script>
    <script src="/assets/js/app.js"></script>
    {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}    


    @yield('page-js')


    <script>
        $(document).ready(function() {
            // Hide the custom-dropdown by default
            $(".custom-dropdown-body").hide();

            // Toggle the custom-dropdown on click
            $(".custom-dropdown-toggle").click(function() {
                $(".custom-dropdown-body").toggle();
            });

            // Close the custom-dropdown if clicked outside of it
            $(document).on("click", function(event) {
                if (!$(event.target).closest(".custom-dropdown").length) {
                    $(".custom-dropdown-body").hide();
                }
            });
        });
    </script>
</body>

</html>

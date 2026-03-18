<!-- Top header START -->
<div class="navbar-top navbar-dark bg-light d-none d-xl-block py-2 mx-2 mx-md-4 rounded-bottom-4">
    <div class="container">
        <div class="d-lg-flex justify-content-lg-between align-items-center">
            <!-- Navbar top Left-->
            <!-- Top info -->
            <ul class="nav align-items-center justify-content-center">
                <li class="nav-item me-3" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="bottom"
                    data-bs-original-title="{{ tr('Sunday CLOSED') }}">
                    <span><i class="far fa-clock me-2"></i>{{ tr('Visit time: Mon-Sat 9:00-19:00') }}</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i
                            class="fas fa-headset me-2"></i>{{ tr('Call us now: +135-869-328') }}</a>
                </li>
            </ul>

            <!-- Navbar top Right-->
            <div class="nav d-flex align-items-center justify-content-center">


                @if (get_option('lms_options.enable_localization'))


                    <!-- Language -->
                    <div class="dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownLanguage"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-globe me-2"></i>{{ tr('Language') }}
                        </a>
                        <div class="dropdown-menu mt-2 min-w-auto shadow" aria-labelledby="dropdownLanguage">
                            @if (get_option('lms_options.enable_localization'))
                                @foreach (config('languages') as $lang => $language)
                                    @if ($lang != app()->getLocale())
                                        <a class="dropdown-item me-4" href="{{ route('lang.switch', $lang) }}">
                                            <img class="fa-fw me-2"
                                                src="/assets/images/flags/{{ $language['flag-icon'] }}.svg"
                                                alt="{{ $language['display'] }} flag">
                                            {{ $language['display'] }}
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <p class="dropdown-item disabled">{{ tr('Language localization is not enabled') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Top social -->
                <ul class="list-unstyled d-flex mb-0">
                    <li> <a class="px-2 nav-link" href="#"><i class="fab fa-facebook"></i></a> </li>
                    <li> <a class="px-2 nav-link" href="#"><i class="fab fa-instagram"></i></a> </li>
                    <li> <a class="px-2 nav-link" href="#"><i class="fab fa-twitter"></i></a> </li>
                    <li> <a class="ps-2 nav-link" href="#"><i class="fab fa-linkedin-in"></i></a> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Top header END -->

<!-- Header START -->
<header class="navbar-light header-static navbar-sticky">
    <!-- Logo Nav START -->
    <nav class="navbar navbar-expand-xl">
        <div class="container">
            <!-- Logo START -->

            <script>
                // setupImageUploadSection('logo_light');
            </script>

            <a class="navbar-brand" href="{{ route('home') }}">
                @php
                    $identifier = 'logo_light';
                    $imageSource = request()->input("{$identifier}_image_source", 'upload');
                    $imageUrl = get_option("{$identifier}_image.url");
                    $imagePath = $imageSource === 'upload' ? get_option("{$identifier}_img") : $imageUrl;
                @endphp
                @if ($imagePath)
                    <img src="{{ media_file_uri($imagePath) }}" class="light-mode-item navbar-brand-item"
                        alt="brandImg">
                @elseif ($imageUrl)
                    <img src="{{ $imageUrl }}" class="light-mode-item navbar-brand-item" alt="brandImg">
                @else
                    <img src="/assets/images/logo.svg" class="light-mode-item navbar-brand-item" alt="brandImg">
                @endif

                @php
                    $identifier = 'logo_dark';
                    $imageSource = request()->input("{$identifier}_image_source", 'upload');
                    $imageUrl = get_option("{$identifier}_image.url");
                    $imagePath = $imageSource === 'upload' ? get_option("{$identifier}_img") : $imageUrl;
                @endphp

                @if ($imagePath)
                    <img src="{{ media_file_uri($imagePath) }}" class="dark-mode-item navbar-brand-item"
                        alt="brandImg">
                @elseif ($imageUrl)
                    <img src="{{ $imageUrl }}" class="dark-mode-item navbar-brand-item" alt="brandImg">
                @else
                    <img src="/assets/images/logo-light.svg" class="dark-mode-item navbar-brand-item" alt="brandImg">
                @endif
            </a>
            <!-- Logo END -->


            <!-- Responsive navbar toggler -->
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-animation">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>

            <!-- Main navbar START -->
            <div class="navbar-collapse collapse" id="navbarCollapse">

                <!-- Nav Search END -->
                <ul class="navbar-nav navbar-nav-scroll mx-auto">
                    <!-- Nav item 1 Demos -->


                    <!-- Nav Main menu START -->
                    <ul class="navbar-nav navbar-nav-scroll me-auto">


                        @php
                            $sortedMenus = $menus->sortBy('order');
                        @endphp
                        @foreach ($sortedMenus as $menu)
                            <!-- Nav item 4 Megamenu-->
                            <li class="color nav-item  color  -fullwidth">
                                <a class="nav-link color" href="{{ $menu->url }}">{{ $menu->name }}</a>
                                <div class="color bg dropdown-menu dropdown-menu-end" data-bs-popper="none">
                                    <div class="color bg row p-4">
                                        <!-- Your Megamenu content goes here -->
                                    </div>
                                </div>
                            </li>
                        @endforeach


                        </li>


                    </ul>
                    <!-- Nav Main menu END -->
                </ul>


                <div class="d-lg-none">
                    <div class="row p-3">
                        <div class="col-6">
                            <a href="/login" class="btn w-100 btn-light btn-block">{{ tr('Login') }}</a>
                        </div>
                        <div class="col-6">
                            {{-- <a href="/register" class="btn w-100 btn-primary btn-block">{{ tr('Create account') }}</a> --}}
                        </div>
                    </div>
                </div>

            </div>
            <!-- Main navbar END -->

            <!-- Nav Search START -->
            <div class="nav nav-item dropdown nav-search px-1 px-lg-3">
                <a class="nav-link" role="button" href="#" id="navSearch" data-bs-toggle="dropdown"
                    aria-expanded="true" data-bs-auto-close="outside" data-bs-display="static">
                    <i class="bi bi-search fs-4"> </i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow rounded p-2" aria-labelledby="navSearch"
                    data-bs-popper="none">
                    <form action="/courses?q=" class="input-group" method="get">
                        <input class="form-control border-primary" type="search" name="q"
                            placeholder="Search..." aria-label="Search">
                        <button class="btn btn-primary m-0">{{ tr('Search') }}</button>
                    </form>


                </div>
            </div>
            <!-- Nav Search END -->



            <!-- Profile START -->

            @if ($auth_user ?? '')
                @include('front.partials.user_dropdown')
            @else
                <div class="mt-3 mt-lg-0 d-none d-lg-flex align-items-center">
                    <a href="/login" class="btn btn-light mx-2">{{ tr('Login') }}</a>
                    {{-- <a href="/register" class="btn btn-primary">{{ tr('Create account') }}</a> --}}
                </div>
            @endif

            <!-- Profile START -->
        </div>


    </nav>


    <!-- Logo Nav END -->
</header>
<!-- Header END -->

@php
    $categories = App\Models\Category::parent()->with('sub_categories')->get();
@endphp



<!-- Header START -->
<header class="navbar-light navbar-sticky">
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
                    <img src="{{ media_file_uri($imagePath) }}" class="light-mode-item navbar-brand-item" alt="brandImg">
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
                    <img src="{{ media_file_uri($imagePath) }}" class="dark-mode-item navbar-brand-item" alt="brandImg">
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
            <div class="navbar-collapse w-100 collapse" id="navbarCollapse">


                <!-- Nav category menu START -->
                <ul class="navbar-nav navbar-nav-scroll me-auto">
                    <!-- Nav item 1 Demos -->
                    <li class="nav-item dropdown dropdown-menu-shadow-stacked">
                        <a class="nav-link bg-primary bg-opacity-10 rounded-3 text-primary px-3 py-3 py-xl-0"
                            href="#" id="categoryMenu" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><i
                                class="bi bi-ui-radios-grid me-2"></i><span>{{ tr('Category') }}</span></a>
                        <ul class="dropdown-menu" aria-labelledby="categoryMenu">

                            <!-- Dropdown submenu -->
                            @foreach ($categories as $category)
                                @if ($category->sub_categories->count())
                                    <li class="dropdown-submenu dropend">
                                        <a class="dropdown-item dropdown-toggle"
                                            href="{{ route('category_view', $category->slug) }}">
                                            <i class="color bg la {{ $category->icon_class }}"></i>
                                            {{ $category->category_name }}
                                            <i class="color bg la la-angle-right"></i>
                                        </a>
                                        <ul class="dropdown-menu" data-bs-popper="none">
                                            @foreach ($category->sub_categories as $subCategory)
                                                <li><a class="dropdown-item"
                                                        href="{{ route('category_view', $subCategory->slug) }}">{{ $subCategory->category_name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('category_view', $category->slug) }}">
                                            <i class="color bg la {{ $category->icon_class }}"></i>
                                            {{ $category->category_name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                            <li> <a class="dropdown-item bg-primary text-primary bg-opacity-10 rounded-2 mb-0"
                                    href="{{ route('categories') }}">{{ __t('view_all_categories') }}</a></li>


                        </ul>
                    </li>
                </ul>
                <!-- Nav category menu END -->

                <!-- Nav Main menu START -->


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




                <!-- Nav Search START -->
                <div class="nav my-3 my-xl-0 px-4 flex-nowrap align-items-center">
                    <div class="nav-item w-100">
                        <form action="/courses?q=" class="position-relative">
                            <input class="form-control pe-5 bg-transparent" type="search" name="q"
                                placeholder="{{ tr('Search') }}" aria-label="{{ tr('Search') }}r">
                            <button
                                class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset"
                                type="submit">
                                <i class="fas fa-search fs-6 "></i>
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Nav Search END -->

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
            @if (get_option('lms_options.enable_localization'))
                <!-- Language -->
                <ul class="navbar-nav navbar-nav-scroll me-3 d-none d-xl-block">
                    <li class="nav-item dropdown">
                        <a class="nav-link p-0 dropdown-toggle" href="#" id="language" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-globe me-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end min-w-auto" aria-labelledby="language">
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
                        </ul>
                    </li>
                </ul>
            @endif

            <!-- Main navbar END -->
            <!-- Main navbar END -->
            @if ($auth_user ?? '')
                @include('front.partials.cart.mini')


                @include('front.partials.user_dropdown')
            @else
                <div class="mt-3 mt-lg-0 d-none d-lg-flex align-items-center">
                    <a href="/login" class="btn btn-light mx-2">{{ tr('Login') }}</a>
                    {{-- <a href="/register" class="btn btn-primary">{{ tr('Create account') }}</a> --}}
                </div>
            @endif


        </div>
    </nav>
    <!-- Logo Nav END -->
</header>
<!-- Header END -->

@php
    $categories = App\Models\Category::parent()->with('sub_categories')->get();
@endphp
<!-- Header START -->
<header class="navbar-light navbar-sticky">
    <!-- Nav START -->
    <nav class="navbar navbar-expand-xl z-index-9">
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
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <!-- Nav Search START -->
                <div class="col-xxl-6">
                    <div class="nav my-3 my-xl-0 px-4 flex-nowrap align-items-center">
                        <div class="nav-item w-100">
                            <form action="/courses?q=" class="rounded position-relative">
                                <input class="form-control pe-5 bg-secondary bg-opacity-10 border-0" type="search"
                                    name="q" placeholder="{{ tr('Search') }}" aria-label="Search">
                                <button
                                    class="btn btn-link bg-transparent px-2 py-0 position-absolute top-50 end-0 translate-middle-y"
                                    type="submit"><i class="fas fa-search fs-6 text-primary"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Nav Search END -->

                <!-- Nav Main menu START -->
                <ul class="navbar-nav navbar-nav-scroll ms-auto">
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
                </ul>
                <!-- Nav Main menu END -->

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
            @if ($auth_user ?? '')
                @include('front.partials.cart.academy_cart')
            @endif

            @if (get_option('lms_options.enable_localization'))
                @include('front.partials.language_selector')
            @endif
            <!-- Profile and notification START -->
            <ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">




                <!-- Profile dropdown START -->
                <li class="nav-item ms-3 dropdown">


                    <!-- Profile dropdown START -->
                    <!-- Profile info -->
                    @if ($auth_user ?? '')
                        @include('front.partials.user_dropdown')
                    @else
                        <div class="mt-3 mt-lg-0 d-none d-lg-flex align-items-center">
                            <a href="/login" class="btn btn-light mx-2">{{ tr('Login') }}</a>
                            {{-- <a href="/register" class="btn btn-primary">{{ tr('Create account') }}</a> --}}
                        </div>
                    @endif
            </ul>
            <!-- Profile dropdown END -->
            </li>
            </ul>
            <!-- Profile and notification END -->
        </div>
    </nav>
    <!-- Nav END -->

    <hr class="my-0">

    <!-- Category Nav link START -->
    <nav class="navbar navbar-expand-xl nav-category">
        <div class="container px-0">

            <!-- Responsive navbar toggler -->
            <button class="navbar-toggler m-auto w-100" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse2" aria-controls="navbarCollapse2" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="bi bi-grid-fill"></i> {{ tr('Category') }}
            </button>

            <!-- Main navbar START -->
            <div class="navbar-collapse w-100 collapse" id="navbarCollapse2">
                <!-- Nav Main menu START -->
                <ul class="navbar-nav navbar-nav-scroll mx-auto">
                    <!-- Nav item 1 link -->

                    @foreach ($categories as $index => $category)
                        @if ($index < 6)
                            {{-- Limit to 5 categories --}}
                            <li class="nav-item dropdown">
                                @if ($category->sub_categories->count())
                                    <a class="nav-link dropdown-toggle" href="#" id="demoMenu"
                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">{{ $category->category_name }}</a>
                                    <ul class="dropdown-menu" aria-labelledby="demoMenu">
                                        @foreach ($category->sub_categories as $subCategory)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('category_view', $subCategory->slug) }}">{{ $subCategory->category_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @else
                        @break

                        {{-- Break out of the loop after 5 categories --}}
                    @endif
                @endforeach

                <!-- Navmitem 2 link -->

            </ul>


        </div>
        <!-- Main navbar END -->
    </div>
</nav>
<!-- Category Nav link END -->

</header>
<!-- Header END -->

<script>
    // Select the footer element
    document.addEventListener('DOMContentLoaded', function() {
        var footer = document.querySelector('footer');
        footer.classList.add('pt-5', 'bg-dark');
    });
</script>

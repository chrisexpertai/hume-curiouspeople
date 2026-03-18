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
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="true"
                aria-label="Toggle navigation">
                <span class="me-2"><i class="fas fa-search fs-5"></i></span>
            </button>

            <ul class="navbar-nav navbar-nav-scroll dropdown-clickable">
                <li class="nav-item dropdown dropdown-menu-shadow-stacked">
                    <a class="nav-link" href="#" id="categoryMenu" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="true">
                        <i class="bi bi-grid-3x3-gap-fill me-3 fs-5 me-xl-1 d-xl-none"></i>
                        <i class="bi bi-grid-3x3-gap-fill me-1 d-none d-xl-inline-block"></i>
                        <span class="d-none d-xl-inline-block">{{ __t('categories') }}</span>
                    </a>

                    <ul class="dropdown-menu z-index-unset" aria-labelledby="categoryMenu" data-bs-popper="static">

                        @foreach ($categories as $category)
                            @if ($category->sub_categories->count())
                                <!-- Dropdown submenu -->
                                <li class="dropdown-submenu dropend">
                                    <a class="dropdown-item dropdown-toggle"
                                        href="#">{{ $category->category_name }}</a>
                                    <ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
                                        @foreach ($category->sub_categories as $subCategory)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('category_view', $subCategory->slug) }}">{{ $subCategory->category_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li><a class="dropdown-item"
                                        href="{{ route('category_view', $category->slug) }}">{{ $category->category_name }}</a>
                                </li>
                            @endif
                        @endforeach

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item bg-primary text-primary bg-opacity-10 rounded-2 mb-0"
                                href="{{ route('categories') }}">{{ __t('view_all_categories') }}</a></li>
                    </ul>
                </li>
            </ul>


            <!-- Main navbar START -->
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <!-- Nav Search START -->
                <div class="col-xl-8">
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
            </div>
            <!-- Main navbar END -->

            <!-- Right header content START -->
            <!-- Add to cart -->

            @if ($auth_user ?? '')
                {{-- @include('front.partials.cart.mini') --}}
            @else
                <a class="btn btn-round mb-0 bg-light d-lg-none" href="{{ route('login') }}">
                    <i class="bi bi-person fa-fw"></i>
                </a>
            @endif







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



        </div>
    </nav>
    <!-- Logo Nav END -->
</header>
<!-- Header END -->

<script>
    // Select the footer element
    document.addEventListener('DOMContentLoaded', function() {
        var footer = document.querySelector('footer');
        footer.classList.add('pt-5', 'bg-light');
    });
</script>

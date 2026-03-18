<!-- =======================
Footer START -->
<footer class="pt-0 bg-blue rounded-4 position-relative mx-2 mx-md-4 mb-3">
    <!-- SVG decoration for curve -->
    <figure class="mb-0">
        <svg class="fill-body rotate-180" width="100%" height="150" viewBox="0 0 500 150" preserveAspectRatio="none">
            <path d="M0,150 L0,40 Q250,150 500,40 L580,150 Z"></path>
        </svg>
    </figure>

    <div class="container">
        <div class="row mx-auto">
            <div class="col-lg-6 mx-auto text-center my-5">
                <!-- Logo -->
                @php
                    $identifier = 'logo_light';
                    $imageSource = request()->input("{$identifier}_image_source", 'upload');
                    $imageUrl = get_option("{$identifier}_image.url");
                    $imagePath = $imageSource === 'upload' ? get_option("{$identifier}_img") : $imageUrl;
                @endphp
                @if ($imagePath)
                    <img src="{{ media_file_uri($imagePath) }}" class="light-mode-item h-40px navbar-brand-item"
                        alt="brandImg">
                @elseif ($imageUrl)
                    <img src="{{ $imageUrl }}" class="light-mode-item h-40px navbar-brand-item" alt="brandImg">
                @else
                    <img src="/assets/images/logo.svg" class="light-mode-item h-40px navbar-brand-item" alt="brandImg">
                @endif

                @php
                    $identifier = 'logo_dark';
                    $imageSource = request()->input("{$identifier}_image_source", 'upload');
                    $imageUrl = get_option("{$identifier}_image.url");
                    $imagePath = $imageSource === 'upload' ? get_option("{$identifier}_img") : $imageUrl;
                @endphp

                {{-- @if ($imagePath)
                    <img src="{{ media_file_uri($imagePath) }}" class="dark-mode-item h-40px navbar-brand-item"
                        alt="brandImg">
                @elseif ($imageUrl)
                    <img src="{{ $imageUrl }}" class="dark-mode-item h-40px navbar-brand-item" alt="brandImg">
                @else
                    <img src="/assets/images/logo-light.svg" class="dark-mode-item h-40px navbar-brand-item"
                        alt="brandImg">
                @endif --}}

                {{-- <p class="mt-3 text-white">
                    {{ tr('Piksera education theme, built specifically for the education centers which is dedicated to teaching and involving learners.') }}
                </p> --}}
                <!-- Links -->
                {{-- <ul class="nav justify-content-center text-primary-hover mt-3 mt-md-0">
                    <li class="nav-item"><a class="nav-link text-white"
                            href="{{ route('post_proxy', get_option('about_us_page')) }}">{{ tr('About') }}</a></li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="{{ route('post_proxy', get_option('terms_of_use_page')) }}">{{ tr('Terms') }}</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="{{ route('post_proxy', get_option('privacy_policy_page')) }}">{{ tr('Privacy') }}</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="{{ route('contact.show') }}">{{ tr('Contact us') }}</a></li>
                    <li class="nav-item"><a class="nav-link text-white pe-0"
                            href="{{ route('posts.index') }}">{{ tr('News and Blogs') }}</a></li>
                </ul> --}}
                <!-- Social media button -->
                {{-- <ul class="list-inline mt-3 mb-0">
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-facebook"
                            href="{{ get_option('facebook_url') }}">
                            <i class="fab fa-fw fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-instagram"
                            href="{{ get_option('instagram_url') }}">
                            <i class="fab fa-fw fa-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-twitter"
                            href="{{ get_option('twitter_url') }}">
                            <i class="fab fa-fw fa-twitter"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-linkedin"
                            href="{{ get_option('linkedin_url') }}">
                            <i class="fab fa-fw fa-linkedin-in"></i>
                        </a>
                    </li>
                </ul> --}}
                <!-- Bottom footer link -->
                <div class="mt-3 text-white">
                    {{-- ©{{ \Carbon\Carbon::now()->format('Y') }}
                    {{ tr('Copyrights Piksera. Build by') }} <a href="{{ get_option('site_url') }}"
                        class="text-reset btn-link text-primary-hover" target="_blank">{{ tr('Piksera') }}</a> --}}
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- =======================
Footer END -->

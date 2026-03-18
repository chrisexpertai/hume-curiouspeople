<!-- =======================
Footer START -->
<footer class="pt-5">
    <div class="container">
        <!-- Row START -->
        <div class="row g-4">

            <!-- Widget 1 START -->
            <div class="col-lg-3">
                <!-- logo -->
                <a class="navbar-brand" href="{{ route('home') }}">
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
                        <img src="/assets/images/logo.svg" class="light-mode-item h-40px navbar-brand-item"
                            alt="brandImg">
                    @endif

                    @php
                        $identifier = 'logo_dark';
                        $imageSource = request()->input("{$identifier}_image_source", 'upload');
                        $imageUrl = get_option("{$identifier}_image.url");
                        $imagePath = $imageSource === 'upload' ? get_option("{$identifier}_img") : $imageUrl;
                    @endphp

                    @if ($imagePath)
                        <img src="{{ media_file_uri($imagePath) }}" class="dark-mode-item h-40px navbar-brand-item"
                            alt="brandImg">
                    @elseif ($imageUrl)
                        <img src="{{ $imageUrl }}" class="dark-mode-item h-40px navbar-brand-item" alt="brandImg">
                    @else
                        <img src="/assets/images/logo-light.svg" class="dark-mode-item h-40px navbar-brand-item"
                            alt="brandImg">
                    @endif

                </a>
                {{-- <p class="my-3">
                    {{ tr('Piksera education theme, built specifically for the education centers which is dedicated to teaching and involve learners.') }}
                </p>
                <ul class="list-inline mb-0 mt-3">
                    <li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-facebook"
                            href="{{ get_option('facebook_url') }}"><i class="fab fa-fw fa-facebook-f"></i></a> </li>
                    <li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-instagram"
                            href="{{ get_option('instagram_url') }}"><i class="fab fa-fw fa-instagram"></i></a> </li>
                    <li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-twitter"
                            href="{{ get_option('twitter_url') }}"><i class="fab fa-fw fa-twitter"></i></a> </li>
                    <li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-linkedin"
                            href="{{ get_option('linkedin_url') }}"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>
                </ul> --}}
            </div>
            <!-- Widget 1 END -->

            <!-- Widget 2 START -->
            <div class="col-lg-6">
                <div class="row g-4">
                    <!-- Link block -->
                    {{-- <div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">{{ tr('Company') }}</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link" href="{{ route('contact.show') }}">{{ tr('Contact us') }}</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">{{ tr('News and Blogs') }}</a></li>
							<li class="nav-item"><a class="nav-link" href="{{route('post_proxy', get_option('terms_of_use_page'))}}">{{ tr('Terms & Conditions') }}</a></li>
							<li class="nav-item"><a class="nav-link" href="{{route('post_proxy', get_option('privacy_policy_page'))}}">{{ tr('Privacy & Policy') }}</a></li>
 						</ul>
					</div> --}}

                    <!-- Link block -->
                    {{-- <div class="col-6 col-md-4">
                        <h5 class="mb-2 mb-md-4">{{ tr('Community') }}</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('subscription-plans.index') }}">{{ tr('Subscriptions') }}</a></li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('categories') }}">{{ tr('Categories') }}</a></li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('instructors.search') }}">{{ tr('Instructors') }}</a></li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('courses') }}">{{ tr('Courses') }}</a></li>
                        </ul>
                    </div> --}}

                    <!-- Link block -->
                    {{-- <div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">{{ tr('Teaching') }}</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link" href="/become-instructor">{{ tr('Become a teacher') }}</a></li>
							<li class="nav-item"><a class="nav-link" href="{{route('post_proxy', get_option('how_to_guide'))}}">{{ tr('How to guide') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('post_proxy', get_option('about_us_page'))}}">{{ tr('About us') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">{{ tr('Dashboard') }}</a></li>
                        </ul>
					</div>  --}}
                </div>
            </div>
            <!-- Widget 2 END -->

            <!-- Widget 3 START -->
            {{-- <div class="col-lg-3">
                <h5 class="mb-2 mb-md-4">{{ tr('Contact') }}</h5>
                <!-- Time -->
                <p class="mb-2">
                    Toll free:<span class="h6 fw-light ms-2">{{ tr('+1234 568 963') }}</span>
                    <span class="d-block small">{{ tr('(9:AM to 8:PM IST)') }}</span>
                </p>

                <p class="mb-0">{{ tr('Email:') }}<span
                        class="h6 fw-light ms-2">{{ tr('example@gmail.com') }}</span></p>

                <div class="row g-2 mt-2">
                    <!-- Google play store button -->
                    <!-- <div class="col-6 col-sm-4 col-md-3 col-lg-6">
      <a href="#"> <img src="/assets/images/client/google-play.svg" alt=""> </a>
     </div> -->
                    <!-- App store button -->
                    <!-- <div class="col-6 col-sm-4 col-md-3 col-lg-6">
      <a href="#"> <img src="/assets/images/client/app-store.svg" alt="app-store"> </a>
     </div> -->
                </div> <!-- Row END -->
            </div>
            <!-- Widget 3 END -->
        </div><!-- Row END -->

        <!-- Divider --> --}}
            <hr class="mt-4 mb-0">

            <!-- Bottom footer -->
            {{-- <div class="py-3">
                <div class="container px-0">
                    <div class="d-md-flex justify-content-between align-items-center py-3 text-center text-md-left">
                        <div class="text-primary-hover">
                        </div>
                        <div class=" mt-3 mt-md-0">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                <li class="list-inline-item">
                                </li>
                                <li class="list-inline-item">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
</footer>
<!-- =======================
Footer END -->

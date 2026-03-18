<nav class="navbar sidebar navbar-expand-xl navbar-dark bg-dark">

    <!-- Navbar brand for xl START -->
    <div class="d-flex align-items-center">
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
                <img src="/assets/images/logo-light.svg" class="light-mode-item navbar-brand-item" alt="brandImg">
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
    </div>
    <!-- Navbar brand for xl END -->

    <div class="offcanvas offcanvas-start flex-row custom-scrollbar h-100" data-bs-backdrop="true" tabindex="-1"
        id="offcanvasSidebar">
        <div class="offcanvas-body sidebar-content d-flex flex-column bg-dark">

            <!-- Sidebar menu START -->
            <ul class="navbar-nav flex-column" id="navbar-sidebar">

                <!-- Menu item 1 -->
                <li class="nav-item"><a href="{{ route('admin') }}" class="nav-link"><i
                            class="bi bi-house fa-fw me-2"></i>{{ tr('Dashboard') }}</a></li>




                <!-- Menu item 2 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapsepikserasettings" role="button"
                        aria-expanded="false" aria-controls="collapsepikserasettings">
                        <i class="bi bi-bookmark-star-fill me-2"></i>Piksera LMS
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapsepikserasettings" data-bs-parent="#navbar-sidebar">

                        @php
                            $nav_items = piksera_settings();
                        @endphp

                        @if (is_array($nav_items) && count($nav_items))
                            @foreach ($nav_items as $route => $nav_item)
                                <li class="nav-item"> <a class="nav-link"
                                        href="{!! array_get($nav_item, 'link') !!}">{{ array_get($nav_item, 'name') }}</a></li>
                            @endforeach
                        @endif

                    </ul>
                </li>


                <!-- Title -->
                <li class="nav-item ms-2 my-2">{{ tr('Pages') }}</li>

                <!-- menu item 2 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapsepage" role="button"
                        aria-expanded="false" aria-controls="collapsepage">
                        <i class="bi bi-basket fa-fw me-2"></i>{{ tr('Courses') }}
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapsepage" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('admin_courses') }}">{{ tr('All Courses') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('category_index') }}">{{ tr('Course Category') }}</a></li>
                    </ul>
                </li>


                <!-- menu item 2 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapsecms" role="button"
                        aria-expanded="false" aria-controls="collapsecms">
                        <i class="bi bi-journal-richtext me-2"></i>{{ tr('CMS') }}
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapsecms" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('admin.pages.index') }}">{{ tr('Pages') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('admin.posts.index') }}">{{ tr('Posts') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('category_index') }}">{{ tr('Categories') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('contacts') }}">{{ tr('Contacts') }}</a></li>

                    </ul>
                </li>


                <!-- Menu item 5 -->
                <li class="nav-item"> <a class="nav-link" href="{{ route('media_manager') }}"><i
                            class="bi bi-card-image fa-fw me-2"></i>{{ tr('Media') }}</a></li>


                <!-- Menu item 7 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapsesubscriptions" role="button"
                        aria-expanded="false" aria-controls="collapsesubscriptions">
                        <i class="fas fa-user-cog fa-fw me-2"></i>{{ tr('Subscriptions') }}
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapsesubscriptions" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('subscription.index') }}">{{ tr('Subscriptions') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('admin.subscribed.users') }}">{{ tr('Subscribed Users') }}</a></li>

                    </ul>
                </li>

                <!-- Menu item 4 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapseinstructors" role="button"
                        aria-expanded="false" aria-controls="collapseinstructors">
                        <i class="fas fa-user-tie fa-fw me-2"></i>{{ tr('Instructors') }}
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapseinstructors" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('admin.instructors') }}">{{ tr('Instructors') }}</a></li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('admin.instructors.requests') }}">{{ tr('Instructor requests') }}
                                <span class="badge text-bg-success rounded-circle ms-2">
                                    {{ $pendingApplicationsCount }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.tickets.index') }}"><i
                            class="bi bi-chat-square-text-fill fa-fw me-2"></i>{{ tr('Tickets') }}</a></li>

                <!-- Reports -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapsereports" role="button"
                        aria-expanded="false" aria-controls="collapsereports">
                        <i class="bi bi-bar-chart-line-fill me-2"></i>Reports
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapsereports" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.reports') }}">Overview</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.reports.user_courses') }}">User Courses</a></li>
                    </ul>
                </li>
                <!-- Menu item 6 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapseearnings" role="button"
                        aria-expanded="false" aria-controls="collapseearnings">
                        <i class="bi bi-currency-exchange me-2"></i>{{ tr('Earnings') }}
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapseearnings" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('payments') }}">{{ tr('Payments') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('admin.subscription-payments') }}">{{ tr('Subscription Payments') }}</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('withdraws') }}">{{ tr('Withdraws') }}</a></li>

                    </ul>
                </li>

                <!-- Menu item 5 -->
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.students') }}"><i
                            class="fas fa-user-graduate fa-fw me-2"></i>{{ tr('Students') }}</a></li>


                <!-- Menu item 5 -->
                <li class="nav-item"> <a class="nav-link" href="{{ route('users') }}"><i
                            class="fas fa-user fa-fw me-2"></i>{{ tr('Users') }}</a></li>




                @php
                    $active_plugins = json_decode(get_option('active_plugins'), true);
                @endphp

                @if (is_array($active_plugins) && in_array('Certificate', $active_plugins))
                    <!-- Menu item 6 -->
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#collapseplugins" role="button"
                            aria-expanded="false" aria-controls="collapseplugins">
                            <i class="bi bi-app-indicator me-2"></i>{{ tr('Plugins') }}
                        </a>
                        <!-- Submenu -->
                        <ul class="nav collapse flex-column" id="collapseplugins" data-bs-parent="#navbar-sidebar">
                            <li class="nav-item"> <a class="nav-link"
                                    href="{{ route('plugins') }}">{{ tr('Plugins') }}</a></li>


                            <li class="nav-item"> <a class="nav-link"
                                    href="{{ route('certificate_settings') }}">{{ tr('Certificate Settings') }}</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Menu item 7 -->
                    <li class="nav-item"> <a class="nav-link" href="{{ route('plugins') }}"><i
                                class="bi bi-app me-2"></i>{{ tr('Plugins') }}</a></li>
                @endif




                <!-- Menu item 7 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapsesettings" role="button"
                        aria-expanded="false" aria-controls="collapsesettings">
                        <i class="fas fa-user-cog fa-fw me-2"></i>{{ tr('Settings') }}
                    </a>
                    <!-- Submenu -->
                    <ul class="nav collapse flex-column" id="collapsesettings" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('general_settings') }}">{{ tr('General Settings') }}</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ route('payment_settings') }}">{{ tr('Payment Settings') }}</a></li>

                    </ul>
                </li>




                <!-- Title -->
                <li class="nav-item ms-2 my-2">{{ tr('Documentation') }}</li>

                <!-- Menu item 9 -->
                <li class="nav-item"> <a class="nav-link"
                        href="https://support.piksera.com/en/category/piksera-lms"><i
                            class="far fa-clipboard fa-fw me-2"></i>{{ tr('Documentation') }}</a></li>

                <!-- Menu item 10 -->
                <li class="nav-item"> <a class="nav-link" href="https://support.piksera.com/en/article/changelog"><i
                            class="fas fa-sitemap fa-fw me-2"></i>{{ tr('Changelog') }}</a></li>
            </ul>
            <!-- Sidebar menu end -->

            <!-- Sidebar footer START -->
            <div class="px-3 mt-auto pt-3">
                <div class="d-flex align-items-center justify-content-between text-primary-hover">
                    <a class="h5 mb-0 text-body" href="{{ route('general_settings') }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Settings">
                        <i class="bi bi-gear-fill"></i>
                    </a>
                    <a class="h5 mb-0 text-body" href="{{ route('home') }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Home">
                        <i class="bi bi-globe"></i>
                    </a>
                    <a class="h5 mb-0 text-body" href="{{ route('logout') }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Sign out">
                        <i class="bi bi-power"></i>
                    </a>
                </div>
            </div>
            <!-- Sidebar footer END -->

        </div>
    </div>
</nav>

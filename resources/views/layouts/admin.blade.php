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
    <meta name="author" content="piksera.com">
    <meta name="description"
        content="@if (!empty($title)) {{ $title }} | {{ get_option('site_name') }} - {{ get_option('site_title') }}  @else {{ get_option('site_name') }} - {{ get_option('site_title') }} @endif">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        var pageData = @json(pageJsonData())
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


    <script type="text/javascript">
        /* <![CDATA[ */
        window.pageData = @json(pageJsonData());
        /* ]]> */
    </script>
    <!-- Favicon -->
    @php
        $faviconUrl = media_file_uri(get_option('site_favicon'));
    @endphp


    @if ($faviconUrl)
        <link rel="shortcut icon" href="{{ media_file_uri(get_option('site_favicon')) }}" />
    @else
        <link rel="shortcut icon" href="{{ '/assets/images/favicon.svg' }}" />
    @endif
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.js">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/apexcharts/css/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">


    <link rel="stylesheet" href="/assets/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">

    @if (get_option('enable_rtl') ? 'rtl' : 'auto' == 'rtl')
        <link rel="stylesheet" type="text/css" href="/assets/css/style-rtl.css">
    @else
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    @endif
    <link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
</head>

<body>
    <style>
        /* Custom CSS for sidebar */
        #offcanvasSidebar {
            overflow-y: auto;
            /* Enable vertical scrollbar */
            max-height: 100vh;
            /* Limit maximum height to viewport height */
        }
    </style>
    <script>
        // Initialize Bootstrap offcanvas
        // var offcanvasElement = document.getElementById('offcanvasSidebar');
        // var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    </script>

    <!-- **************** MAIN CONTENT START **************** -->
    <main>

        <!-- Sidebar START -->

        @include('admin.partials.sidebar')

        <!-- Sidebar END -->

        <!-- Page content START -->
        <div class="page-content">

            <!-- Top bar START -->
            @include('admin.header')
            <!-- Top bar END -->

            @include('inc.flash_msg')
            @yield('content')
            @yield('page-js')

            <!-- Page main content END -->
        </div>
        <!-- Page content END -->

    </main>
    <!-- **************** MAIN CONTENT END **************** -->
    <!-- all js here -->
    <!-- jquery latest version -->
    {{-- <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script> --}}
    <script src="{{ asset('assets/vendor/jquery/jquery-1.12.0.min.js') }}"></script>
    <script src="/assets/vendor/jquery/jquery-ui.min.js"></script>
    <!-- bootstrap js -->
    <script src="/assets/vendor/overlay-scrollbar/js/overlayscrollbars.js"></script>
    <script src="/assets/vendor/select2/select2.min.js"></script>
    <script src="/assets/js/admin.js"></script>


    <script>
        var toastr_options = {
            closeButton: true
        };
    </script>
    <!-- Bootstrap JS -->
    <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Vendors -->
    {{-- <script src="/assets/vendor/choices/js/choices.min.js"></script> --}}
    <script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/plyr/plyr.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounterjs/dist/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>

    <!-- Template Functions -->
    <script src="/assets/js/functions.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/vendor/filemanager/filemanager.js"></script>
    <script src="/assets/vendor/toastr/toastr.min.js"></script>
    {{-- <script src="/assets/js/bootstrap-old.min.js"></script> --}}
</body>

</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ get_option('enable_rtl') ? 'rtl' : 'auto' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @if (!empty($title))
            {{ $title }} | {{ get_option('site_name') }} - {{ get_option('site_title') }}
        @else
            {{ get_option('site_name') }} - {{ get_option('site_title') }}
        @endif
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


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
    <!-- Favicon -->
    @php
        $faviconUrl = media_file_uri(get_option('site_favicon'));

    @endphp


    @if ($faviconUrl)
        <link rel="shortcut icon" href="{{ media_file_uri(get_option('site_favicon')) }}" />
    @else
        <link rel="shortcut icon" href="{{ '/assets/images/favicon.svg' }}" />
    @endif



    <!-- all css here -->
    <!-- google-font css -->
    {{-- <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet"> --}}
    <!-- bootstrap v3.3.6 css -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="/assets/css/course.css">

    @yield('page-css')

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/tiny-slider/tiny-slider.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/aos/aos.css">

    <!-- Theme CSS -->
    @if (get_option('enable_rtl') ? 'rtl' : 'auto' == 'rtl')
        <script src="/assets/vendor/tiny-slider/tiny-slider-rtl.js"></script>
        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/css/style-rtl.css">
    @else
        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">

        <script src="/assets/vendor/tiny-slider/tiny-slider.js"></script>
    @endif


    <!-- style css -->
    <link rel="stylesheet" href="{{ theme_asset('css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ theme_asset('css/css-stars.css') }}"> --}}
    <!-- modernizr css -->
    {{-- <script src="{{asset('assets/js/vendor/modernizr-2.8.3.min.js')}}"></script> --}}


    <script type="text/javascript">
        /* <![CDATA[ */
        window.pageData = @json(pageJsonData());
        /* ]]> */
    </script>

</head>

<body>
    @include('inc.flash_msg')

    @yield('content')

    @if (!auth()->check() && request()->path() != 'login')
        @include(theme('partials.login-modal-form'))
    @endif
    @yield('page-js')

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
    <script type="module" src="/assets/js/app.js"></script>



    <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script>



    <script defer="" src="/assets/js/discussions.js"></script>


    <script defer="" src="{{ asset('assets/js/dark.js') }}"></script>
    <script defer="" src="{{ asset('assets/js/dark-jr.js') }}"></script>



</body>

</html>

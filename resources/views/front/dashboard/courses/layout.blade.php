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
    @php
        $faviconUrl = media_file_uri(get_option('site_favicon'));
    @endphp


    @if ($faviconUrl)
        <link rel="shortcut icon" href="{{ media_file_uri(get_option('site_favicon')) }}" />
    @else
        <link rel="shortcut icon" href="{{ '/assets/images/favicon.svg' }}" />
    @endif

    <style>
        .bi::before,
        [class^="bi-"]::before,
        [class*=" bi-"]::before {
            display: inline-block;
            font-family: bootstrap-icons !important;
            font-style: normal;
            font-weight: normal !important;
            font-variant: normal;
            text-transform: none;
            line-height: 2.5 !important;
            vertical-align: -.125em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>

    <script type="text/javascript">
        window.pageData = @json(pageJsonData());
    </script>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/aos/aos.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/glightbox/css/glightbox.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/quill/css/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css" />
    <link rel="stylesheet"
        href="https://cdn.ckeditor.com/ckeditor5-premium-features/42.0.2/ckeditor5-premium-features.css" />

    @if (get_option('enable_rtl') ? 'rtl' : 'auto' == 'rtl')
        <link rel="stylesheet" type="text/css" href="/assets/css/style-rtl.css">
    @else
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    @endif

    <link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
</head>

<body>

    <style>
        .dropdown-item {
            display: block;
            width: 100%;
            padding: 1px 7px !important;
            clear: both;
            font-weight: 500;
            color: var(--bs-dropdown-link-color);
            text-align: inherit;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
            border-radius: var(--bs-dropdown-item-border-radius, 0);
        }

        form {
            display: block;
            margin-top: 0em;
            unicode-bidi: isolate;
            margin-block-end: 0em !important;
        }
    </style>


    <div class="main">
        @include('front.header')
        <!-- **************** MAIN CONTENT START **************** -->
        <!-- Step Buttons END -->
    </div>
    <section>
        <div class="container">
            <div class="bg-transparent border rounded-5 mb-5">
                @yield('content')
            </div>
        </div>
    </section>
    <!-- =======================Steps END -->

    <div class="main">
        <div class="navigation-holder">
            @include('front.footer')
        </div>
    </div>
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
            }
        }
    </script>

    <!-- Back to top -->
    <div class="back-top">
        <i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Vendors -->
    <script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/plyr/plyr.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounterjs/dist/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script src="{{ asset('assets/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/functions.js') }}"></script>


    @yield('page-js')
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>


    <script>
        $(document).ready(function() {
            $(".custom-dropdown-body").hide();

            $(".custom-dropdown-toggle").click(function() {
                $(".custom-dropdown-body").toggle();
            });
            $(document).on("click", function(event) {
                if (!$(event.target).closest(".custom-dropdown").length) {
                    $(".custom-dropdown-body").hide();
                }
            });
            // console.log("json: ", pageData.routes);
        });
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
</body>

</html>

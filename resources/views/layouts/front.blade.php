
@php
    $maintenance = get_option('coming.coming.enable');
@endphp

@php
    $platformLayouts = [
        'defaultlms' => 'front.default.layout',
        'educationlms' => 'front.education.layout',
        'academylms' => 'front.academy.layout',
        'courselms' => 'front.course.layout',
    //    'universitylms' => 'front.university.layout',
    //     'kindergartenlms' => 'front.kindergarten.layout',
   //      'landinglms' => 'front.landing.layout',
   //      'tutorlms' => 'front.tutor.layout',
   //      'schoollms' => 'front.school.layout',
   //      'abroadlms' => 'front.abroad.layout',
   //      'workshoplms' => 'front.workshop.layout',
    ];

    $selectedPlatformLayout = get_option("lms_settings.platform_type");
    $selectedLayout = isset($platformLayouts[$selectedPlatformLayout]) ? $platformLayouts[$selectedPlatformLayout] : $platformLayouts['defaultlms'];
@endphp


@if ($maintenance)

@include('front.maintenance')

@else


<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{get_option('enable_rtl')? 'rtl' : 'auto'}}" >
<head>
    <title>  @if( ! empty($title)) {{ $title }} | {{get_option('site_name')}} - {{get_option('site_title')}}  @else {{get_option('site_name')}} - {{get_option('site_title')}}  @endif </title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="{{route('home')}}">
	<meta name="description" content="@if( ! empty($title)) {{ $title }} | {{get_option('site_name')}} - {{get_option('site_title')}}  @else {{get_option('site_name')}} - {{get_option('site_title')}}  @endif">
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

		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}

		setTheme(getPreferredTheme())

		window.addEventListener('DOMContentLoaded', () => {
		    var el = document.querySelector('.theme-icon-active');
			if(el != 'undefined' && el != null) {
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

<script>
    function copyToClipboard(text) {
        var dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Favicon -->
        @php
        $faviconUrl = media_file_uri(get_option('site_favicon'));
        @endphp


                @if($faviconUrl)

                <link rel="shortcut icon" href="{{media_file_uri(get_option('site_favicon'))}}"/>
                @else
                <link rel="shortcut icon" href="{{('/assets/images/favicon.svg')}}"/>
                @endif
	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/tiny-slider/tiny-slider.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/aos/aos.css">
    <link rel="stylesheet" href="{{asset('assets/css/line-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/glightbox/css/glightbox.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/plyr/plyr.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
</head>


<body>



@include($selectedLayout)





<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>
@yield('page-js')

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


@if (get_option('advertising.modal.enable', false))

    <!-- Card body START -->
    <div class="card-body">


        <!-- Popup container -->
        <div id="piksera12-container" class="piksera12-container">
            <div class="piksera12-content">
                <span class="piksera12-close-btn" onclick="closePiksera12()" style="
                margin-left: 452px;
            ">×</span>
                <div class="popup-header">
                    <h2>{{ get_option('advertising.modal.title') }}</h2>
                    <img src="{{media_file_uri(get_option('modal_image'))}}" alt="Black Friday Sale Image" style="width: 250px;">
                </div>
                <p>{{ get_option('advertising.modal.desc') }}</p>
                <div class="popup-buttons">
                    <a href="{{ get_option('advertising.modal.button1link') }}" class="piksera12-btn">{{ get_option('advertising.modal.button1') }}</a>
                    <a href="{{ get_option('advertising.modal.button2link') }}" class="piksera12-btn">{{ get_option('advertising.modal.button2') }}</a>
                </div>
            </div>
        </div>


    <style>
        body {
             margin: 0;
            padding: 0;
        }

        .piksera12-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            border-radius: 16px;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.5s;
            max-width: 600px; /* Set a maximum width for the popup */
        }

        .piksera12-content {
            text-align: center;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .piksera12-close-btn {
            font-size: 20px;
            cursor: pointer;
            order: 1; /* Move the close button to the right */
        }

        .piksera12-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }

        /* Fade-in animation */
        .piksera12-container.show {
            opacity: 1;
        }



        </style>


<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Check if the user has closed the piksera12 before
        if (sessionStorage.getItem('piksera12Closed') !== 'true') {
            // Show the piksera12 immediately when the page is loaded
            showPiksera12();
        }

        // Set up a listener for the close button
        var closeBtn = document.querySelector('.piksera12-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', closePiksera12);
        }
    });

    function showPiksera12() {
        var piksera12Container = document.getElementById('piksera12-container');
        piksera12Container.style.display = 'block';

        // Trigger reflow to enable the animation
        void piksera12Container.offsetWidth;

        piksera12Container.classList.add('show');
    }

    function closePiksera12() {
        var piksera12Container = document.getElementById('piksera12-container');
        piksera12Container.classList.remove('show');

        // Hide the popup after the fade-out animation
        setTimeout(function() {
            piksera12Container.style.display = 'none';
        }, 500);

        // Set a session variable to remember the user's decision
        sessionStorage.setItem('piksera12Closed', 'true');
    }

    // Function to manually open the piksera12
    function showPiksera12Manually() {
        showPiksera12();
    }

    </script>
@endif

</body>
</html>
@endif

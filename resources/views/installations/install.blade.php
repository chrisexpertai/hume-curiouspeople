
<!DOCTYPE html>
<html lang="{{ get_option('language', app()->getLocale()) }}" dir="{{ get_option('enable_rtl') ? 'rtl' : 'auto' }}">
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

    @if(get_option('enable_rtl') ? 'rtl' : 'auto' == 'rtl')
    <script src="/assets/vendor/tiny-slider/tiny-slider-rtl.js"></script>
   <!-- Theme CSS -->
   <link rel="stylesheet" type="text/css" href="/assets/css/style-rtl.css">
   @else
       <!-- Theme CSS -->
   <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
   <script src="/assets/vendor/tiny-slider/tiny-slider.js"></script>
   @endif
 	<link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
</head>


<body>




    <div class="container">
        <div class="row justify-content-center mt-5">
          <div class="col-md-10">
            <div class="card">
              <div class="card-header"> Installing Piksera LMS | Database Connection
            </div>
            <div class="card-body">

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="">



                            @include('inc.flash_msg')

                            <form action="" id="installation-form" class="mb-5 bor p-4 rounded-4" method="post">
                                @csrf

                                <div class="mb-3">
                                    <label for="hostname" class="form-label">Server Address</label>
                                    <input type="text" class="form-control" id="hostname" name="hostname" placeholder="Enter server address" value="{{ old('hostname')? old('hostname') : '127.0.0.1' }}">
                                    <small class="form-text text-muted">Enter the server address where the database is hosted.</small>
                                    @error('hostname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="dbport" class="form-label">Database Port</label>
                                    <input type="text" class="form-control" id="dbport" name="dbport" placeholder="Enter database port" value="{{ old('dbport')? old('dbport') : '3306' }}">
                                    <small class="form-text text-muted">Specify the port for the database connection.</small>
                                    @error('dbport')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label for="database_name" class="form-label">Database Name</label>
                                    <input type="text" class="form-control" id="database_name" name="database_name" placeholder="Enter database name" value="{{ old('database_name') }}">
                                    <small class="form-text text-muted">Specify the name of the database to connect to.</small>
                                    @error('database_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="{{ old('username')? old('username') : '' }}">
                                    <small class="form-text text-muted">Provide the username for accessing the database.</small>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="{{ old('password') }}">
                                    <small class="form-text text-muted">Enter the password for the database user.</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <hr />

                                <button type="submit" class="btn btn-primary btn-lg btn-block">Start Installation</button>

                            </form>

                        </div>
                    </div>
                </div>


            </div>
            <div class="card-footer text-muted">
              © 2024 Piksera Team
            </div>
          </div>
        </div>
      </div>
    </div>

<style>
    * {
	border: 0;
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}
:root {
	--hue: 223;
	--bg: hsl(var(--hue),90%,95%);
	--fg: hsl(var(--hue),90%,5%);
	--trans-dur: 0.3s;
	font-size: calc(16px + (24 - 16) * (100vw - 320px) / (1280 - 320));
}
body {
	background-color: var(--bg);
	color: var(--fg);
	font: 1em/1.5 sans-serif;
	height: 100vh;
	display: grid;
	place-items: center;
	transition: background-color var(--trans-dur);
}
main {
	padding: 1.5em 0;
}
.ip {
	width: 16em;
	height: 8em;
}
.ip__track {
	stroke: hsl(var(--hue),90%,90%);
	transition: stroke var(--trans-dur);
}
.ip__worm1,
.ip__worm2 {
	animation: worm1 2s linear infinite;
}
.ip__worm2 {
	animation-name: worm2;
}

/* Dark theme */
@media (prefers-color-scheme: dark) {
	:root {
		--bg: hsl(var(--hue),90%,5%);
		--fg: hsl(var(--hue),90%,95%);
	}
	.ip__track {
		stroke: hsl(var(--hue),90%,15%);
	}
}

/* Animation */
@keyframes worm1 {
	from {
		stroke-dashoffset: 0;
	}
	50% {
		animation-timing-function: steps(1);
		stroke-dashoffset: -358;
	}
	50.01% {
		animation-timing-function: linear;
		stroke-dashoffset: 358;
	}
	to {
		stroke-dashoffset: 0;
	}
}
@keyframes worm2 {
	from {
		stroke-dashoffset: 358;
	}
	50% {
		stroke-dashoffset: 0;
	}
	to {
		stroke-dashoffset: -358;
	}
}
</style>

 <script>
    $(document).ready(function() {
        $('#submitBtn').click(function() {
            $(this).prop('disabled', true); // Disable button to prevent multiple submissions
            $('#loading').removeClass('d-none'); // Show loading animation
            // Perform AJAX request to submit data
            $.ajax({
                url: '/your-route', // Replace with your route
                type: 'POST',
                data: {
                    // Your data to be submitted
                },
                success: function(response) {
                    // Handle successful response, if needed
                    console.log(response);
                    // Redirect or perform any other action
                },
                error: function(xhr, status, error) {
                    // Handle error, if needed
                    console.error(xhr.responseText);
                    // Enable the button and hide loading animation
                    $('#submitBtn').prop('disabled', false);
                    $('#loading').addClass('d-none');
                }
            });
        });
    });
</script>




</body>
</html>

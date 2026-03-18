
<!-- Header START -->

@include(('front.default.header'))

<!-- Header END -->

<!-- **************** MAIN CONTENT START **************** -->

@include('inc.flash_msg')
@yield('content')


<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================Footer START -->
@include('front.footer')

<!-- =======================
Footer END -->

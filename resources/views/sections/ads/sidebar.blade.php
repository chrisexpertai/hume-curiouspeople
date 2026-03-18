
@if (get_option('adverts.ads.enable', false))

@php
    $sidebarAdCode = get_option('ads_sidebar');

@endphp
<section class="py-0 py-xl-5">
	<div class="container">
	<!-- Banner Ad Component -->
<div class="banner-ad">
    {!! $sidebarAdCode !!}
</div>

	</div>


</section>


@endif

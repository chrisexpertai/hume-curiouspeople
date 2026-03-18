



@if (get_option('adverts.ads.enable', false))

@php
    $bannerAdCode = get_option('ads_banner');

@endphp
<section class="py-0 py-xl-5">
	<div class="container">
	<!-- Banner Ad Component -->
<div class="banner-ad">
    {!! $bannerAdCode !!}
</div>

	</div>


</section>


@endif

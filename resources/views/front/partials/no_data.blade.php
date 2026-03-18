<!-- resources/views/components/NoData.blade.php -->

<div class="no-data-container">
    @php
    $identifier = 'nodata';
    $imageSource = request()->input("{$identifier}_image_source", "upload");
    $imageUrl = get_option("{$identifier}_image.url");
    $imagePath = $imageSource === "upload" ? get_option("{$identifier}_img") : $imageUrl;
@endphp
 

    @if ($imagePath)
    <img src="{{ media_file_uri($imagePath) }}" class="no-data-image" alt="brandImg">
    @elseif ($imageUrl)
    <img src="{{ $imageUrl }}" class="no-data-image" alt="brandImg">
    @else
    <img src="/images/no-data.png" class="light-mode-item h-40px navbar-brand-item" alt="brandImg">
    @endif
        <p class="no-data-message">@lang('frontend.no_data')</p>
</div>


<style>

/* public/css/styles.css */

.no-data-container {
    text-align: center;
    padding: 20px;
     border-radius: 8px;
    margin-top: 20px;
}

.no-data-image {
    max-width: 100px;
    margin-bottom: 10px;
}

.no-data-message {
    font-size: 18px;
 }

</style>

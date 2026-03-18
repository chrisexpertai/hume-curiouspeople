@php
    $video_source = $model->video_info('source');
    $src_youtube = $model->video_info('source_youtube');
    $src_vimeo = $model->video_info('source_vimeo');
    $src_external = $model->video_info('source_external_url');
    $embedded_code = $model->video_info('source_embedded');
    $text = empty($video_caption) ? null : $video_caption;
@endphp

<div class="lecture-video-wrapper bg-body video-player-wrapper  " style="border-radius: 10px; width:100%;     background: var(--bs-body-bg);">

    @if($video_source === 'embedded')
        {!! $embedded_code !!}
    @endif


    @if($video_source === 'html5')
        @php
            $video_id = $model->video_info('html5_video_id');
            $html5_video = \App\Models\Media::find($video_id);
            $media_url = media_file_uri($html5_video);


            $poster_id = (int) $model->video_info('html5_video_poster_id');
            if ( ! $poster_id && $model->thumbnail_id){
                $poster_id = $model->thumbnail_id;
            }

            $poster_src = null;
            if ($poster_id){
                $poster_src = media_image_uri($poster_id)->original;
            }
        @endphp

        @if($html5_video)

        <video                 @if($poster_src) poster="{{$poster_src}}" @endif id="player" playsinline controls>
		    <source src="{{$media_url}}" type="{{$html5_video->mime_type}}">
		    		</video>
     <script src="/assets/vendor/plyr/plyr.js"></script>
    <script>
        const player = new Plyr('#player');
    </script>
           <link rel="stylesheet" href="/assets/vendor/plyr/plyr.css">

         @endif
    @endif

    @if($video_source === 'external_url')
    <link rel="stylesheet" type="text/css" href="/assets/vendor/plyr/plyr.css" />
    <script src="/assets/vendor/plyr/plyr.js"></script>


        <video
        id="player" playsinline controls>
		    <source src="{{$src_external}}" type="video/mp4">
		    		</video>
    <script>
        const player = new Plyr('#player');
    </script>


    @endif



    @if($video_source === 'youtube')
    <link rel="stylesheet" type="text/css" href="/assets/vendor/plyr/plyr.css" />
    <script src="/assets/vendor/plyr/plyr.js"></script>
 

    <div class="plyr__video-embed" id="player">

                <iframe src="{{$src_youtube}}" frameborder="0" allowfullscreen="" allowtransparency="" allow="autoplay" style="border-radius:10px;"></iframe>


                </div>

                <script>
                  const player = new Plyr('#player');
                </script>
                    <script src="/assets/vendor/plyr/plyr.js"></script>

    @endif




    @if($video_source === 'vimeo')
    @php
        $vimeo_video_id = ''; // Extract Vimeo video ID from $src_vimeo
        preg_match('/\/(\d+)$/', $src_vimeo, $matches);
        if (!empty($matches[1])) {
            $vimeo_video_id = $matches[1];
        }
        $plyr_vimeo_url = "https://player.vimeo.com/video/{$vimeo_video_id}";
    @endphp

    <div class="plyr__video-embed" id="player">
        <iframe height="500" src="{{$plyr_vimeo_url}}" allowfullscreen allowtransparency allow="autoplay"></iframe>
    </div>
    <script src="/assets/vendor/plyr/plyr.js"></script>
    <script>
        const player = new Plyr('#player');
    </script>
@endif
@if($text)
    <p class="videoPlayerCaption m-0"><span class="captionText">{{$text}}</span></p>
@endif



</div>

@if($video_source !== 'embedded')
@section('page-css')
   <link rel="stylesheet" href="/assets/vendor/plyr/plyr.css">
   <link rel="stylesheet" href="/assets/vendor/video-js/video-js.min.css">
@endsection

@section('page-js')
    <script src="/assets/vendor/plyr/plyr.js"></script>
      <script type="text/javascript" src="http://cdn.clappr.io/latest/clappr.min.js"></script>

    <!-- Video preview start -->
    <style type="text/css">
        .plyr__progress video {
            width: 180px !important;
            height: auto !important;
            position: absolute !important;
            bottom: 30px !important;
            z-index: 1 !important;
            border-radius: 10px !important;
            border: 2px solid #fff !important;
            display: none;
            background-color: #000;
        }

        .plyr__progress video:hover {
            display: none !important;
        }

        video:not(.plyr:fullscreen video) {
            width: 100%;
            max-height: auto !important;
            max-height: 567px !important;
            border-radius: 5px;
        }
    </style>
    <script type="text/javascript">
        if ($('video#player').length) {
            const progress = document.querySelector('.plyr__progress');
            if ($('.plyr__progress video').length == 0) {
                $('.plyr__progress').append($('.plyr__video-wrapper').html());
                var previewProgressVideoObject = document.querySelector('.plyr__progress video');
            }

            // Handle hover event on the progress bar
            progress.addEventListener('mousemove', function(event) {
                if ($('.plyr__progress .plyr__poster').length > 0) {
                    $('.plyr__progress .plyr__poster').remove();
                }

                const rect = progress.getBoundingClientRect();
                const offsetX = event.clientX - rect.left;
                const percent = (offsetX / rect.width) * 100;

                // Calculate the time corresponding to the hovered position
                const duration = player.duration;
                const time = (duration * percent) / 100;

                // Update the preview position and show it
                previewProgressVideoObject.style.left = $('.plyr__tooltip').css('left').replace('px', '') - 90 + 'px'; //`${percent-10.5}%`;
                previewProgressVideoObject.style.display = 'block';

                // Seek the video to the corresponding time
                //player.currentTime = time;
                previewProgressVideoObject.currentTime = time;
            });

            // Handle mouse leave event on the progress bar
            progress.addEventListener('mouseleave', function() {
                // Hide the preview
                previewProgressVideoObject.style.display = 'none';
            });
        }
    </script>
  <script src="/assets/vendor/video-js/video.min.js"></script>
    @if($video_source === 'youtube')
         <script src="/assets/vendor/plyr/plyr.js"></script>
           <script type="text/javascript" src="http://cdn.clappr.io/latest/clappr.min.js"></script>


    @endif
    @if($video_source === 'vimeo')
         <script src="/assets/vendor/plyr/plyr.js"></script>
    @endif
@endsection
@endif



@extends('layouts.admin')

@section('content')


<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12 mb-3">
            <h1 class="h3 mb-2 mb-sm-0">{{ tr('Admin Settings') }}</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left side START -->
      @include('admin.partials.pikserasettings')
        <!-- Left side END -->

	<!-- Right side START -->
    <div class="col-xl-9">

        <!-- Tab Content START -->
        <div class="tab-content">


                <!-- Personal Information content START -->
                <div class="tab-pane show active" id="tab-1" role="tabpanel">
                    <div class="card shadow">

                        <!-- Card header -->
                        <div class="card-header border-bottom">
                            <h5 class="card-header-title">Social Share</h5>
                        </div>

                        <!-- Card body START -->
                        <div class="card-body">

    <form action="{{route('save_settings')}}" class="form-horizontal" method="post" enctype="multipart/form-data"> @csrf


           <!--   Social share    -->

        <div class="form-group row {{ $errors->has('facebook_url')? 'has-error':'' }}">
            <label for="facebook_url" class="col-sm-4 control-label">@lang('admin.facebook_url')</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="facebook_url" value="{{ old('facebook_url')? old('facebook_url') : get_option('facebook_url') }}" name="facebook_url" placeholder="@lang('admin.facebook_url')">
                {!! $errors->has('facebook_url')? '<p class="help-block">'.$errors->first('facebook_url').'</p>':'' !!}
                <p class="text-info"> @lang('admin.facebook_url_help_text')</p>
            </div>
        </div>

        <div class="form-group row {{ $errors->has('twitter_url')? 'has-error':'' }}">
            <label for="twitter_url" class="col-sm-4 control-label">@lang('admin.twitter_url')</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="twitter_url" value="{{ old('twitter_url')? old('twitter_url') : get_option('twitter_url') }}" name="twitter_url" placeholder="@lang('admin.twitter_url')">
                {!! $errors->has('twitter_url')? '<p class="help-block">'.$errors->first('twitter_url').'</p>':'' !!}
                <p class="text-info"> @lang('admin.twitter_url_help_text')</p>
            </div>
        </div>

        <div class="form-group row {{ $errors->has('linkedin_url')? 'has-error':'' }}">
            <label for="linkedin_url" class="col-sm-4 control-label">@lang('admin.linkedin_url')</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="linkedin_url" value="{{ old('linkedin_url')? old('linkedin_url') : get_option('linkedin_url') }}" name="linkedin_url" placeholder="@lang('admin.linkedin_url')">
                {!! $errors->has('linkedin_url')? '<p class="help-block">'.$errors->first('linkedin_url').'</p>':'' !!}
                <p class="text-info"> @lang('admin.linkedin_url_help_text')</p>
            </div>
        </div>

        <div class="form-group row {{ $errors->has('instagram_url')? 'has-error':'' }}">
            <label for="instagram_url" class="col-sm-4 control-label">@lang('admin.instagram_url')</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="instagram_url" value="{{ old('instagram_url')? old('instagram_url') : get_option('instagram_url') }}" name="instagram_url" placeholder="@lang('admin.instagram_url')">
                {!! $errors->has('instagram_url')? '<p class="help-block">'.$errors->first('instagram_url').'</p>':'' !!}
                <p class="text-info"> @lang('admin.instagram_url_help_text')</p>
            </div>
        </div>


        <hr />

        <div class="form-group row">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" id="settings_save_btn" class="btn btn-primary">
                    {{__a('save_settings')}}
                </button>
            </div>
        </div>

    </form>

                        <!-- Card body END -->

                    </div>
                </div>
                <!-- Personal Information content END -->

        </div>
        <!-- Tab Content END -->
    </div>
    <!-- Right side END -->
    </div> <!-- Row END -->
    <style>
        body {
            font-family: Arial, sans-serif;
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

@endsection


@section('page-js')
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
    <script>

        $(document).ready(function(){

            $('input[type="checkbox"], input[type="radio"]').click(function(){
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')){
                    input_value = $(this).val();
                }
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' }
                });
            });
        });
    </script>
@endsection

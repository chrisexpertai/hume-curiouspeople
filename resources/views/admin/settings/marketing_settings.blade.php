
@extends('layouts.admin')

@section('content')


<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12 mb-3">
            <h1 class="h3 mb-2 mb-sm-0">{{ tr('LMS Settings') }}</h1>
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
                            <h5 class="card-header-title">{{ tr('Website Settings') }}</h5>
                        </div>

                                                    <!-- Card body START -->
                                                    <div class="card-body">
                            <!-- Add a button to manually open the piksera12 -->
                            <button class="btn btn-primary" onclick="showPiksera12Manually()">{{ tr('Preview') }}</button>

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

                              <form action="{{route('save_settings')}}" class="form-horizontal" method="post" enctype="multipart/form-data"> @csrf


                            <!--   Advertising Modal    -->


                            <div class="form-group row">
                                <label class="col-md-4 control-label">{{__a('enable_advertising_modal')}} </label>
                                <div class="col-md-6">
                                    {!! switch_field('advertising[modal][enable]', '', get_option('advertising.modal.enable') ) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="modal_image" class="col-sm-4 control-label">@lang('admin.img1') </label>
                                <div class="col-sm-8">
                                    {!! image_upload_form('modal_image', get_option('modal_image')) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="modal_title" class="col-sm-4 control-label">  @lang('admin.modal_title')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="modal_title" value="{{ get_option('advertising.modal.title') }}" name="advertising[modal][title]">
                                </div>
                            </div>

                                    <div class="form-group row">
                                        <label for="alert_notice" class="col-sm-4 control-label">  @lang('admin.modal_desc')</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="modal_desc" value="{{ get_option('advertising.modal.desc') }}" name="advertising[modal][desc]">
                                        </div>
                                    </div>

                                <div class="form-group row">
                                    <label for="alert_notice" class="col-sm-4 control-label">  @lang('admin.modal_button1')</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="modal_button1" value="{{ get_option('advertising.modal.button1') }}" name="advertising[modal][button1]">
                                    </div>
                                </div>

                            <div class="form-group row">
                                <label for="alert_notice" class="col-sm-4 control-label">  @lang('admin.modal_button2')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="modal_button2" value="{{ get_option('advertising.modal.button2') }}" name="advertising[modal][button2]">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alert_notice" class="col-sm-4 control-label">@lang('admin.modal_button1link')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="modal_button1link" value="{{ get_option('advertising.modal.button1link') }}" name="advertising[modal][button1link]">
                                </div>
                            </div>

                            <div class="form-group row">
                            <label for="alert_notice" class="col-sm-4 control-label">  @lang('admin.modal_button2link')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="modal_button2link" value="{{ get_option('advertising.modal.button2link') }}" name="advertising[modal][button2link]">
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
        // Show the piksera12 only when needed, not automatically when the page is loaded
        // showPiksera12();
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



 

<script src="/assets/admin/js/custom.js"></script>

< {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}
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

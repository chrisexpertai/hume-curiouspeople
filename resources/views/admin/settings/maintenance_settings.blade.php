
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
<div id="piksera12-container" class="piksera12-container" style="display: none;">
    <div class="piksera12-content">
        <span class="piksera12-close-btn" onclick="closePiksera12()" style="
        margin-left: 452px;
    ">×</span>
        <div class="popup-header">
            @include(('front.maintenance'))

        </div>
    </div>
</div>
    <form action="{{route('save_settings')}}" class="form-horizontal" method="post" enctype="multipart/form-data"> @csrf


           <!--   Advertising Modal    -->


           <div class="form-group row">
            <label class="col-md-4 control-label">{{__a('enable_coming_coming')}} </label>
            <div class="col-md-6">
                {!! switch_field('coming[coming][enable]', '', get_option('coming.coming.enable') ) !!}
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
    top: 90%;
    left: 29%;
    border-radius: 16px;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 40px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    z-index: 999;
    opacity: 0;
    transition: opacity 0.5s;
    width: 864px;
    max-height: 527px;
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


.size1 {
    width: 100%;
    max-height: 77vh  !important;
    min-height: 77vh  !important;

}

        </style>

@endsection


@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up a listener for manually triggering the piksera12
    var manualBtn = document.querySelector('.manual-trigger-btn');
    if (manualBtn) {
        manualBtn.addEventListener('click', showPiksera12Manually);
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

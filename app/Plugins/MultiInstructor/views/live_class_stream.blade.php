@extends(theme('layout-full'))
@php
    $option = (array) array_get(json_decode($course->video_src, true), 'live_class');
    $schedule = array_get($option, 'schedule');
    $note = array_get($option, 'note_to_student');
    $force_join = (bool) request('force_join');
@endphp

@section('content')
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="shortcut icon" href="/themes/Helsinki/favicon.png"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="G84GDLSa9ZQ8U3VTNpgoiNypensXaUitZqvTioOr">

    <title>   Live Class Stream  </title>

    <!-- all css here -->
    <!-- google-font css -->

<!-- bootstrap v3.3.6 css -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/line-awesome.min.css">

    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.6.0/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.6.0/css/react-select.css"/>

    <style type="text/css">
        .navbar-brand img{
            height: 25px;
            width: auto;
        }
        #count_down{
            font-size: 25px;
            text-align: center;
            border: 1px solid #eeeeee;
            padding: 20px;
        }
    </style>

<!-- style css -->
    <link rel="stylesheet" href="/themes/Helsinki/assets/css/style.css">
     <link rel="stylesheet" href="/themes/Helsinki/assets//css/css-stars.css">
    <!-- modernizr css -->
    <script src="/assets/js/vendor/modernizr-2.8.3.min.js"></script>

</head>
<body>
  <div class="container" style="margin-top: 80px;background: white;height: 639px;width: 303px;display: flex;justify-content: center;align-items: center;">
            <div class="row">
<div class="main-content" style="background: white;height: 639px;width: 303px;display: flex;justify-content: center;align-items: center;">
         

<!-- jquery latest version -->
<script src="/assets/js/vendor/jquery-1.12.0.min.js"></script>
<!-- bootstrap js -->
<script src="/assets/js/bootstrap.bundle.min.js"></script>


    <script src="https://source.zoom.us/2.6.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/jquery.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.6.0.min.js"></script>

   <script>

        function stop_zoom() {
            if (confirm("Do you want to leave the live video class? you can join them later if the video class remains live")) {
                ZoomMtg.leaveMeeting();
            }
        }

        $( document ).ready(function() {
            start_zoom();
        });

        function start_zoom() {

            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareJssdk();

            var API_KEY        = "{{get_option('liveclass.zoom_api_key')}}";
            var API_SECRET     = "{{get_option('liveclass.secret_key')}}";
            var USER_NAME      = "{{$auth_user->name}}";
            var MEETING_NUMBER = "{{array_get($option, 'zoom_meeting_id' )}}";
            var PASSWORD       = "{{array_get($option, 'zoom_meeting_password' )}}";

            testTool = window.testTool;

            var meetConfig = {
                apiKey: API_KEY,
                apiSecret: API_SECRET,
                meetingNumber: MEETING_NUMBER,
                userName: USER_NAME,
                passWord: PASSWORD,
                leaveUrl: "{{$course->url}}",
                role: 0
            };


            var signature = ZoomMtg.generateSignature({
                meetingNumber: meetConfig.meetingNumber,
                apiKey: meetConfig.apiKey,
                apiSecret: meetConfig.apiSecret,
                role: meetConfig.role,
                success: function(res){
                    console.log(res.result);
                }
            });

            ZoomMtg.init({
                leaveUrl: "{{$course->url}}",
                isSupportAV: true,
                success: function () {
                    ZoomMtg.join(
                        {
                            meetingNumber: meetConfig.meetingNumber,
                            userName: meetConfig.userName,
                            signature: signature,
                            apiKey: meetConfig.apiKey,
                            passWord: meetConfig.passWord,
                            success: function(res){
                                console.log('join meeting success');
                            },
                            error: function(res) {
                                console.log(res);
                            }
                        }
                    );
                },
                error: function(res) {
                    console.log(res);
                }
            });
        }
    </script>

<!-- main js -->
<script src="/themes/Helsinki/assets/js/main.js"></script>
<script src="/themes/Helsinki/assets/js/profile.min.js"></script>
</div>

</div>

</body>
</html>
@endsection


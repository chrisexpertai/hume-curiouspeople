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
      @include('admin.partials.adminside')
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
    <div class="row">
        <div class="col-md-10 col-xs-12">

            <form action="{{route('save_settings')}}" method="post"> @csrf

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.company_name') </label>
                <div class="col-sm-8">

                    <input type="text" class="form-control" value="{{get_option('invoice_company_name', get_option('site_name'))}}" name="invoice_company_name" />

                </div>
            </div>
            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.phone') </label>
                <div class="col-sm-8">

                    <input type="text" class="form-control" value="{{get_option('invoice_company_phone')}}" name="invoice_company_phone" />

                </div>
            </div>
            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.email') </label>
                <div class="col-sm-8">

                    <input type="text" class="form-control" value="{{get_option('invoice_company_email', get_option('email_address'))}}" name="invoice_company_email" />

                </div>
            </div>

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.company_name') </label>
                <div class="col-sm-8">

                    <input type="text" class="form-control" value="{{get_option('invoice_company_name', get_option('site_name'))}}" name="invoice_company_name" />

                </div>
            </div>


            <div class="form-group row">
                <label for="invoice_company_address" class="col-sm-4 control-label">@lang('admin.company_address') </label>
                <div class="col-sm-8">
                    <textarea name="invoice_company_address" class="form-control">{{ get_option('invoice_company_address') }}</textarea>
                </div>
            </div>



            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.notice') </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{get_option('invoice_notice')}}" name="invoice_notice" />
                </div>
            </div>

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.footer_text') </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{get_option('invoice_footer_text')}}" name="invoice_footer_text" />
                </div>
            </div>



            <hr />
            <div class="form-group row">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" id="settings_save_btn" class="btn btn-primary">@lang('admin.save_settings')</button>
                </div>
            </div>

            </form>

        </div>
    </div>



                        <!-- Card body END -->

                    </div>
                </div>
                <!-- Personal Information content END -->

        </div>
        <!-- Tab Content END -->
    </div>
    <!-- Right side END -->
    </div> <!-- Row END -->
@endsection


@section('page-js')
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

            /**
             * show or hide stripe and paypal settings wrap
             */
            $('#enable_facebook_login').click(function(){
                if ($(this).prop('checked')){
                    $('#facebook_login_api_wrap').slideDown();
                }else{
                    $('#facebook_login_api_wrap').slideUp();
                }
            });
            $('#enable_google_login').click(function(){
                if ($(this).prop('checked')){
                    $('#google_login_api_wrap').slideDown();
                }else{
                    $('#google_login_api_wrap').slideUp();
                }
            });

        });
    </script>
@endsection

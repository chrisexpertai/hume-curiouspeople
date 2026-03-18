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



    <div class="row">
        <div class="col-md-10 col-xs-12">
            @php
            $lmsTypes = [
                'defaultlms' => [
                    'name' => 'Default LMS',
                    'img' => 'defaultlms.jpg',
                ],
                'academylms' => [
                    'name' => 'Academy LMS',
                    'img' => 'academylms.jpg',
                ],
                'educationlms' => [
                    'name' => 'Education LMS',
                    'img' => 'educationlms.jpg',
                ],
                'courselms' => [
                    'name' => 'Course LMS',
                    'img' => 'courselms.jpg',
                ],
            ];
            $selectedPlatformLayout = get_option("lms_settings.platform_type");
        @endphp

        <form action="{{ route('save_settings') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
            @csrf

            <div class="container mx-auto">
                <div class="mx-auto lg:w-1/2">
                    <h4 class="text-2xl font-bold text-black mb-4">{{ tr('Select your LMS') }}</h4>

                    <div class="flex flex-wrap -mx-2">
                        @foreach($lmsTypes as $type => $info)
                            <div class="w-full lg:w-1/2 px-2 mb-4">
                                <div class="card @if ($type === $selectedPlatformLayout) selected @endif" data-value="{{ $type }}">
                                     <div class="card-body">
                                        <p class="card-text">{{ $info['name'] }}</p>
                                        <input type="radio" name="lms_settings[platform_type]" value="{{ $type }}" class="hidden" {!! $type === $selectedPlatformLayout ? 'checked' : '' !!}>
                                    </div>
                                </div>
                             </div>
                        @endforeach
                    </div>



        <style>
            .card {
                border: 1px solid #d2d6dc;
                border-radius: 0.25rem;
                transition: box-shadow 0.2s ease-in-out;
                cursor: pointer;
            }
            .card:hover {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .card-body {
                padding: 1rem;
            }
            .selected {
                border-color: #4c51bf;
                box-shadow: 0 0 0 3px rgba(76, 81, 191, 0.5);
            }
            .hidden {
                position: absolute;
                visibility: hidden;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.card');
                cards.forEach(card => {
                    card.addEventListener('click', function() {
                        cards.forEach(c => {
                            c.classList.remove('selected');
                        });
                        card.classList.add('selected');
                        card.querySelector('input[type="radio"]').checked = true;
                    });
                });
            });
        </script>


                             <h4 class="mb-4 font-bold text-black">More Options</h4>


                             <div class="form-group row">
                                <label class="col-md-4 control-label">Instructor on Registeration</label>
                                <div class="col-md-8">
                                    {!! switch_field("lms_options[instructor_can_register]", '', get_option("lms_options.instructor_can_register")) !!}
                                    <p class="text"><small>{{ tr('Allow instructors register in registration page without applying to become an instructor.') }}</small></p>
                                </div>
                            </div>


                <div class="form-group row">
                    <label class="col-md-4 control-label"> {{__a('instructor_can_publish_course')}} </label>
                    <div class="col-md-8">
                        {!! switch_field("lms_options[instructor_can_publish_course]", '', get_option("lms_options.instructor_can_publish_course") ) !!}

                        <p class="text"><small>{{ tr('Allow instructor publish course without review process.') }}</small></p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label"> {{__a('enable_discussion')}} </label>
                    <div class="col-md-8">
                        {!! switch_field("lms_options[enable_discussion]", '', get_option("lms_options.enable_discussion") ) !!}

                        <p class="text"><small>{{ tr('Through discussion enablement, students can directly pose questions to instructors from each lecture page. A dedicated form for questions will be provided beneath every lecture.') }}</small></p>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-4 control-label"> {{__a('enable_localization')}} </label>
                    <div class="col-md-8">
                        {!! switch_field("lms_options[enable_localization]", '', get_option("lms_options.enable_localization") ) !!}
                    </div>
                </div>


                @php
                $pages = get_pages();
            @endphp

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">@lang('admin.favicon') </label>
                <div class="col-sm-8">
                    {!! image_upload_form('site_favicon', get_option('site_favicon')) !!}
                </div>
            </div>

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">{!! __a('about_us_page') !!} </label>
                <div class="col-sm-8">

                    <select name="about_us_page">
                        <option value="">{{ tr('Select page') }}</option>
                        @foreach($pages as $page)
                            <option value="{{$page->slug}}" {{selected($page->slug, get_option('about_us_page'))}} >{{$page->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">{!! __a('privacy_policy_page') !!} </label>
                <div class="col-sm-8">
                    <select name="privacy_policy_page">
                        <option value="">{{ tr('Select page') }}</option>
                        @foreach($pages as $page)
                            <option value="{{$page->slug}}" {{selected($page->slug, get_option('privacy_policy_page'))}} >{{$page->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">{!! __a('terms_of_use_page') !!} </label>
                <div class="col-sm-8">
                    <select name="terms_of_use_page">
                        <option value="">{{ tr('Select page') }}</option>
                        @foreach($pages as $page)
                            <option value="{{$page->slug}}" {{selected($page->slug, get_option('terms_of_use_page'))}} >{{$page->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="additional_css" class="col-sm-4 control-label">{!! __a('how_to_guide') !!} </label>
                <div class="col-sm-8">
                    <select name="how_to_guide">
                        <option value="">{{ tr('Select page') }}</option>
                        @foreach($pages as $page)
                            <option value="{{$page->slug}}" {{selected($page->slug, get_option('how_to_guide'))}} >{{$page->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>




            <legend class="my-4">@lang('admin.cookie_settings')</legend>

            <div class="form-group row {{ $errors->has('enable_cookie_alert')? 'has-error':'' }}">
                <label class="col-md-4 control-label">@lang('admin.enable_disable') </label>
                <div class="col-md-8">
                    <label for="enable_cookie_alert" class="checkbox-inline">
                        <input type="checkbox" value="1" id="enable_cookie_alert" name="cookie_alert[enable]" {{checked(1, get_option('cookie_alert.enable'))}} >
                        @lang('admin.enable_cookie_alert')
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <label for="cookie_message" class="col-sm-4 control-label">@lang('admin.cookie_message')</label>
                <div class="col-sm-8">
                    <textarea class="form-control" id="cookie_message" name="cookie_alert[message]" rows="6">{!! get_option('cookie_alert.message') !!}</textarea>
                    <p class="text my-3"> <small>variable <code>{privacy_policy_url}</code> will print privacy policy link</small> </p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 control-label">{{__a('enable_ads')}} </label>
                <div class="col-md-6">
                    {!! switch_field('adverts[ads][enable]', '', get_option('adverts.ads.enable') ) !!}
                </div>
            </div>





            <div class="form-group row">
                <label for="ads_banner" class="col-sm-4 control-label"> @lang('admin.ads_banner_html')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ads_banner" value="{{ get_option('ads_banner') }}" name="ads_banner">
                </div>
            </div>

            <div class="form-group row">
                <label for="ads_banner" class="col-sm-4 control-label"> @lang('admin.ads_sidebar_html')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ads_sidebar" value="{{ get_option('ads_sidebar') }}" name="ads_sidebar">
                </div>
            </div>



            {{-- Hidden Forms  - Useless --}}

            <div class="form-group d-none row">
                <label for="ads_banner" class="col-sm-4 control-label"> @lang('admin.ads_banner_html')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ads_banner" value="{{ get_option('adverts.ads.banner') }}" name="adverts[ads][banner]">
                </div>
            </div>

            <div class="form-group d-none row">
                <label for="ads_sidebar" class="col-sm-4 control-label"> @lang('admin.ads_sidebar_html')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ads_sidebar" value="{{ get_option('adverts.ads.sidebar') }}" name="adverts[ads][sidebar]">
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
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' },
                });
            });


            $('input[name="date_format"]').click(function(){
                $('#date_format_custom').val($(this).val());
            });
            $('input[name="time_format"]').click(function(){
                $('#time_format_custom').val($(this).val());
            });

        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const lmsCards = document.querySelectorAll('.lms-card');

        lmsCards.forEach(card => {
            card.addEventListener('click', function () {
                const group = this.getAttribute('data-group');
                const type = this.getAttribute('data-type');
                const radioInput = document.querySelector(`input[name="${group}_settings[${group}_type]]"][value="${type}"]`);

                // Remove selected class from all cards in the same group
                lmsCards.forEach(otherCard => {
                    const otherGroup = otherCard.getAttribute('data-group');
                    if (otherGroup === group) {
                        otherCard.classList.remove(`selected-${group}`);
                    }
                });

                // Add selected class to the clicked card
                this.classList.add(`selected-${group}`);

                if (radioInput) {
                    radioInput.checked = true;
                }
            });
        });

        // Set initial selected cards based on checked radio inputs
        const checkedRadioInputs = document.querySelectorAll('input[name="lms_settings[lms_type]"]:checked');
        setInitialSelectedCards(checkedRadioInputs, 'lms');

        function setInitialSelectedCards(checkedInputs, group) {
            checkedInputs.forEach(input => {
                const type = input.value;
                const correspondingCard = document.querySelector(`.lms-card[data-group="${group}"][data-type="${type}"]`);
                if (correspondingCard) {
                    correspondingCard.classList.add(`selected-${group}`);
                }
            });
        }
    });
</script>

<script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>

@endsection

@extends('front.dashboard.courses.layout')


@section('content')
    @include(theme('dashboard.courses.course_nav'))



    <!-- bootstrap v3.3.6 css -->


    <!-- style css -->

    <!-- modernizr css -->


    <!-- jquery latest version -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
    <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script>
    @if ($course->sections->count())
        <form action="" method="post">
            @csrf

            <div class="course-drip-wrap">
                @foreach ($course->sections as $section)
                    <div class=" border mb-4">
                        <div class="dashboard-section-header p-3 border-bottom">

                            <h3 class="mb-3">{{ $section->section_name }}</h3>

                            <div class="course-section-drip-wrap">

                                <p>{{ tr('Release by') }} <strong>{{ tr('Specific Date') }}</strong> {{ tr('or') }}
                                    <strong>{{ tr('Days After Enrollment') }}</strong>
                                </p>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __t('specific_date') }}</label>
                                        <input type="text" class="form-control date_picker"
                                            name="section[{{ $section->id }}][unlock_date]"
                                            value="{{ $section->unlock_date }}">

                                        <p class="text-muted mb-0"><small>
                                                {{ tr('When this section should be unlock') }}</small></p>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>{{ __t('days_after_enrollment') }}</label>

                                        <input type="number" class="form-control"
                                            name="section[{{ $section->id }}][unlock_days]"
                                            value="{{ $section->unlock_days }}">
                                        <p class="text-muted mb-0"><small>{{ tr('Place number of days') }}</small></p>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-purple">
                                    <i class="la la-save"></i> {{ __t('save_drip_preference') }}
                                </button>

                            </div>

                        </div>

                        <div class="p-3">

                            @foreach ($section->items as $item)
                                <div class="edit-drip-item mb-2 edit-drip-{{ $item->item_type }}">

                                    <div class="section-item-top border p-3 d-flex">
                                        <div class="form-group col-md-6"> {!! $item->icon_html !!} {{ $item->title }}</div>

                                        <div class="form-group col-md-3">
                                            <label>{{ __t('specific_date') }}</label>
                                            <input type="text" class="form-control date_picker"
                                                name="section[{{ $section->id }}][content][{{ $item->id }}][unlock_date]"
                                                value="{{ $item->unlock_date }}">

                                            <p class="text-muted mb-0">
                                                <small>{{ tr('When this section should be unlock') }}</small>
                                            </p>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>{{ __t('days_after_enrollment') }}</label>

                                            <input type="number" class="form-control"
                                                name="section[{{ $section->id }}][content][{{ $item->id }}][unlock_days]"
                                                value="{{ $item->unlock_days }}">
                                            <p class="text-muted mb-0"><small>{{ tr('Place number of days') }}</small></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-info mt-3">
                                <i class="la la-save"></i> {{ __t('save_drip_preference') }}
                            </button>
                        </div>

                        <div class="section-item-form-wrap"></div>
                    </div>
                @endforeach
            </div>
        </form>
    @else
        <div class="card">
            <div class="card-body">
                {!! no_data(null, null, 'my-5') !!}
            </div>
        </div>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>

    <script>
        $('.date_picker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>
@endsection

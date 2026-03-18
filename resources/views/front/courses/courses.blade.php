@extends('layouts.theme')


@section('content')

    @php
        $path = request()->path();
    @endphp

    <div class="color bg page-header-wrapper py-4">
        <div class="color bg container">
            <div class="color bg row">
                <div class="color bg col-md-12">

                    <nav aria-label="breadcrumb">
                        <ol class='breadcrumb mb-0'>
                            <li class='breadcrumb-item'>
                                <a href='{{route('home')}}'>
                                    <i class='la la-home'></i>  {{__t('home')}}
                                </a>
                            </li>
                            @if($path === 'courses')
                                <li class='breadcrumb-item active'> {{__t('courses')}}</li>
                            @elseif($path === 'popular-courses')
                                <li class='breadcrumb-item active'> <i class="color bg la la-bolt"></i> {{__t('popular_courses')}}</li>
                            @elseif($path === 'featured-courses')
                                <li class='breadcrumb-item active'> <i class="color bg la la-bookmark"></i> {{__t('featured_courses')}}</li>
                            @endif
                        </ol>
                    </nav>
                    <h1 class="color bg mb-3">{{$title}}</h1>
                </div>

            </div>
        </div>

    </div>


    <div class="color bg courses-container-wrap my-5">

        <form action="" id="course-filter-form" method="get">

            <div class="color bg container">

                <div class="color bg row1">

                    <div class="color bg col-md-3">


                        <div class="color bg course-filter-wrap">

                            @if(request('q'))
                                <input type="hidden" name="q" value="{{request('q')}}">
                            @endif

                            @php
                                $old_cat_id = request('category');
                                $old_topic_id = request('topic');
                                $old_level = (array) request('level');
                                $old_price = (array) request('price');
                            @endphp


                            @if($categories->count())

                                <div class="color bg course-filter-form-group p-3 mb-4">
                                    <div class="color bg form-group">
                                        <h4 class="color bg mb-3">{{__t('category')}}</h4>

                                        <select name="category" id="course_category" class="color bg form-control select2">
                                            <option value="">{{__t('select_category')}}</option>
                                            @foreach($categories as $category)
                                                <optgroup label="{{$category->category_name}}">
                                                    @if($category->sub_categories->count())
                                                        @foreach($category->sub_categories as $sub_category)
                                                            <option value="{{$sub_category->id}}" {{selected($sub_category->id, $old_cat_id)}} >{{$sub_category->category_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </optgroup>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="color bg form-group">
                                        <h4 class="color bg mb-3">{{__t('topic')}} <span class="color bg show-loader"></span> </h4>

                                        <select name="topic" id="course_topic" class="color bg form-control select2">
                                            <option value="">{{__t('select_topic')}}</option>

                                            @foreach($topics as $topic)
                                                <option value="{{$topic->id}}" {{selected($topic->id, $old_topic_id)}}>
                                                    {{$topic->category_name}}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            @endif


                            <div class="color bg course-filter-form-group p-3 mb-4">
                                <div class="color bg form-group">
                                    <h4 class="color bg mb-3">{{__t('course_level')}}</h4>
                                    @foreach(course_levels() as $key => $level)
                                        <label class="color bg custom-control custom-checkbox">
                                            <input type="checkbox" class="color bg custom-control-input" name="level[]" value="{{$key}}" {{in_array($key, $old_level) ? 'checked="checked"' : ''}} >
                                            <span class="color bg custom-control-label">{{$level}}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="color bg course-filter-form-group p-3 mb-4">
                                <div class="color bg form-group">
                                    <h4 class="color bg mb-3">{{__t('price')}}</h4>

                                    <label class="color bg custom-control custom-checkbox">
                                        <input type="checkbox" class="color bg custom-control-input" name="price[]" value="paid" {{in_array('paid', $old_price) ? 'checked="checked"' : '' }} >
                                        <span class="color bg custom-control-label">{{__t('paid')}}</span>
                                    </label>

                                    <label class="color bg custom-control custom-checkbox">
                                        <input type="checkbox" class="color bg custom-control-input" name="price[]" value="free" {{in_array('free', $old_price) ? 'checked="checked"' : '' }}>
                                        <span class="color bg custom-control-label">{{__t('free')}}</span>
                                    </label>

                                </div>
                            </div>

                            <div class="color bg course-filter-form-group p-3 mb-4">
                                <div class="color bg form-group">
                                    <h4 class="color bg mb-3">{{__t('ratings')}}</h4>
                                    <div class="color bg filter-form-by-rating-field-wrap">
                                        <label class="color bg d-flex">
                                            <input type="radio" name="rating" value="4.5" class="color bg mr-2" {{checked('4.5', request('rating'))}} >
                                            {!! star_rating_generator(4.5) !!}
                                            <span class="color bg ml-2">@lang('frontend.4_5_up')</span>
                                        </label>
                                        <label class="color bg d-flex">
                                            <input type="radio" name="rating" value="4" class="color bg mr-2" {{checked('4', request('rating'))}}>
                                            {!! star_rating_generator(4) !!}
                                            <span class="color bg ml-2">@lang('frontend.4_0_up')</span>
                                        </label>
                                        <label class="color bg d-flex">
                                            <input type="radio" name="rating" value="3" class="color bg mr-2" {{checked('3', request('rating'))}}>
                                            {!! star_rating_generator(3) !!}
                                            <span class="color bg ml-2">@lang('frontend.3_0_up')</span>
                                        </label>
                                        <label class="color bg d-flex">
                                            <input type="radio" name="rating" value="2" class="color bg mr-2" {{checked('2', request('rating'))}}>
                                            {!! star_rating_generator(2) !!}
                                            <span class="color bg ml-2">@lang('frontend.2_0_up')</span>
                                        </label>
                                        <label class="color bg d-flex">
                                            <input type="radio" name="rating" value="1" class="color bg mr-2" {{checked('1', request('rating'))}}>
                                            {!! star_rating_generator(1) !!}
                                            <span class="color bg ml-2">@lang('frontend.1_0_up')</span>
                                        </label>


                                    </div>
                                </div>
                            </div>


                            <div class="color bg course-filter-form-group p-3 mb-4">
                                <div class="color bg form-group">
                                    <h4 class="color bg mb-3">{{__t('video_duration')}}</h4>

                                    <label class="color bg custom-control custom-radio">
                                        <input type="radio" class="color bg custom-control-input" name="video_duration" value="0_2" {{checked('0_2', request('video_duration'))}} >
                                        <span class="color bg custom-control-label">{{__t('0_2_hours')}}</span>
                                    </label>
                                    <label class="color bg custom-control custom-radio">
                                        <input type="radio" class="color bg custom-control-input" name="video_duration" value="3_5" {{checked('3_5', request('video_duration'))}} >
                                        <span class="color bg custom-control-label">{{__t('3_5_hours')}}</span>
                                    </label>
                                    <label class="color bg custom-control custom-radio">
                                        <input type="radio" class="color bg custom-control-input" name="video_duration" value="6_10" {{checked('6_10', request('video_duration'))}} >
                                        <span class="color bg custom-control-label">{{__t('6_10_hours')}}</span>
                                    </label>
                                    <label class="color bg custom-control custom-radio">
                                        <input type="radio" class="color bg custom-control-input" name="video_duration" value="11_20" {{checked('11_20', request('video_duration'))}} >
                                        <span class="color bg custom-control-label">{{__t('11_20_hours')}}</span>
                                    </label>
                                    <label class="color bg custom-control custom-radio">
                                        <input type="radio" class="color bg custom-control-input" name="video_duration" value="21" {{checked('21', request('video_duration'))}} >
                                        <span class="color bg custom-control-label">{{__t('21_hours')}}</span>
                                    </label>

                                </div>
                            </div>



                        </div>



                    </div>

                    <div class="color bg col-md-9">

                        <div class="color bg course-sorting-form-wrap form-inline mb-4">

                            <div class="color bg form-group mr-2">
                                <button type="button" id="hide-course-filter-sidebar" class="color bg btn btn-outline-dark">
                                    <i class="color bg la la-filter"></i> Filter  {{count(array_except(array_filter(request()->input()), 'q'))}}
                                </button>
                            </div>

                            <div class="color bg form-group mr-2">
                                <label class="color bg filter-col mr-2">Per page:</label>
                                <select class="color bg form-control" name="perpage">
                                    @for($i = 10; $i<=100; $i = $i + 10)
                                        <option value="{{$i}}" {{selected($i, request('perpage'))}}>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="color bg form-group">
                                <select class="color bg form-control mr-2" name="sort">
                                    <option value="relevance" {{selected('relevance', request('sort'))}}>Most Relevant</option>
                                    <option value="most-reviewed" {{selected('most-reviewed', request('sort'))}}>Most Reviewed</option>
                                    <option value="highest-rated" {{selected('highest-rated', request('sort'))}}>Highest Rated</option>
                                    <option value="newest" {{selected('newest', request('sort'))}}>{{ tr('Newest') }}</option>
                                    <option value="price-low-to-high" {{selected('price-low-to-high', request('sort'))}}>Lowest Price</option>
                                    <option value="price-high-to-low" {{selected('price-high-to-low', request('sort'))}}>Highest Price</option>
                                </select>
                            </div>


                            <div class="color bg form-group ml-auto">
                                <a href="{{route('courses')}}" class="color bg btn btn-link"> <i class="color bg la la-refresh"></i> {{ tr('Clear Filter') }}</a>
                            </div>
                        </div>


                        @if($courses->count())
                            <p class="color bg text-muted mb-3"> {{ tr('Showing') }} {{$courses->count()}} from {{$courses->total()}} {{ tr('results') }} </p>

                            <div class="color bg row">
                                @foreach($courses as $course)
                                    {!! course_card($course, 'col-md-4') !!}
                                @endforeach
                            </div>
                        @else
    {!! no_data() !!}
                        @endif

                        {!! $courses->links() !!}

                    </div>

                </div>

            </div>



        </form>

    </div>


@endsection

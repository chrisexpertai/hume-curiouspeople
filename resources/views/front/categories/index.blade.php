@extends('layouts.app')

@php
    $categories = \App\Models\Category::parent()->whereStatus(1)->get();
    $categoriesWithCourses = \App\Models\Category::parent()->has('courses')->whereStatus(1)->get();
@endphp

@section('content')
    <section class="p-50 position-relative">

        <!-- Svg decoration -->
        <figure class="position-absolute bottom-0 start-0 d-none d-lg-block">
            <svg width="822.2px" height="301.9px" viewBox="0 0 822.2 301.9">
                <path class="fill-warning opacity-5"
                    d="M752.5,51.9c-4.5,3.9-8.9,7.8-13.4,11.8c-51.5,45.3-104.8,92.2-171.7,101.4c-39.9,5.5-80.2-3.4-119.2-12.1 c-32.3-7.2-65.6-14.6-98.9-13.9c-66.5,1.3-128.9,35.2-175.7,64.6c-11.9,7.5-23.9,15.3-35.5,22.8c-40.5,26.4-82.5,53.8-128.4,70.7 c-2.1,0.8-4.2,1.5-6.2,2.2L0,301.9c3.3-1.1,6.7-2.3,10.2-3.5c46.1-17,88.1-44.4,128.7-70.9c11.6-7.6,23.6-15.4,35.4-22.8 c46.7-29.3,108.9-63.1,175.1-64.4c33.1-0.6,66.4,6.8,98.6,13.9c39.1,8.7,79.6,17.7,119.7,12.1C634.8,157,688.3,110,740,64.6 c4.5-3.9,9-7.9,13.4-11.8C773.8,35,797,16.4,822.2,1l-0.7-1C796.2,15.4,773,34,752.5,51.9z">
                </path>
            </svg>
        </figure>

        <div class="container position-relative">
            <div class="row">
                <div class="col-12">
                    <div class="row align-items-center">

                        <!-- Image -->
                        <div class="col-6 col-md-3 text-center order-1">
                            {{-- @php
                    $identifier = 'categories-1';
                    $imageSource = request()->input("{$identifier}_image_source", "upload");
                    $imageUrl = get_option("{$identifier}_image.url");
                    $imagePath = $imageSource === "upload" ? get_option("{$identifier}_img") : $imageUrl;
                @endphp
                     @if (!empty($imagePath))
                        <img src="{{ media_file_uri($imagePath) }}" alt="brandImg">
                    @else
                        <img src="{{ $imageUrl }}" alt="brandImg">
                    @endif --}}

                            <img src="{{ asset('assets/images/element/category-1.svg') }}" alt="brandImg">
                            {{-- <img src="/images/category-1.svg" alt="brandImg"> --}}

                        </div>

                        <!-- Content -->
                        <div class="col-md-6 px-md-5 text-center position-relative order-md-2 mb-5 mb-md-0">

                            <!-- Svg decoration -->
                            <figure class="position-absolute top-0 start-0">
                                <svg width="22px" height="22px" viewBox="0 0 22 22">
                                    <polygon class="fill-orange"
                                        points="22,8.3 13.7,8.3 13.7,0 8.3,0 8.3,8.3 0,8.3 0,13.7 8.3,13.7 8.3,22 13.7,22 13.7,13.7 22,13.7 ">
                                    </polygon>
                                </svg>
                            </figure>

                            <!-- Svg decoration -->
                            <figure class="position-absolute top-0 start-50 translate-middle mt-n6 d-none d-md-block">
                                <svg width="27px" height="27px">
                                    <path class="fill-purple"
                                        d="M13.122,5.946 L17.679,-0.001 L17.404,7.528 L24.661,5.946 L19.683,11.533 L26.244,15.056 L18.891,16.089 L21.686,23.068 L15.400,19.062 L13.122,26.232 L10.843,19.062 L4.557,23.068 L7.352,16.089 L-0.000,15.056 L6.561,11.533 L1.582,5.946 L8.839,7.528 L8.565,-0.001 L13.122,5.946 Z">
                                    </path>
                                </svg>
                            </figure>


                            <!-- Title -->
                            <h1 class="mb-3"
                                style="
                    font-weight: 700;
                    font-size: 40px;
                ">
                                @lang('frontend.what_do_you_want_to_learn')</h1>
                            <p class="mb-3">@lang('frontend.grow_your')</p>

                            <!-- Search -->
                            <form action="/courses?q=" method="get" class="bg-body border rounded-4 p-2">
                                <div class="input-group">
                                    <input class="form-control border-0 me-1" type="search" name="q"
                                        placeholder="Search..." placeholder="@lang('frontend.search_course') ">
                                    <button type="submit" class="btn btn-dark mb-0 rounded-4">@lang('frontend.search')</button>
                                </div>
                            </form>
                        </div>

                        <!-- Image -->
                        <div class="col-6 col-md-3 text-center order-3">
                            {{-- @php
                    $identifier = 'categories-2';
                    $imageSource = request()->input("{$identifier}_image_source", "upload");
                    $imageUrl = get_option("{$identifier}_image.url");
                    $imagePath = $imageSource === "upload" ? get_option("{$identifier}_img") : $imageUrl;
                @endphp
                     @if (!empty($imagePath))
                        <img src="{{ media_file_uri($imagePath) }}" alt="brandImg">
                    @else
                        <img src="{{ $imageUrl }}" alt="brandImg">
                    @endif --}}

                            <img src="{{ asset('assets/images/element/category-2.svg') }}" alt="brandImg">
                            {{-- <img src="/images/category-2.svg" alt="brandImg"> --}}

                        </div>

                    </div> <!-- Row END -->
                </div>
            </div> <!-- Row END -->
        </div>
    </section>
    <div class="page-header-wrapper py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">

                    </nav>
                </div>
            </div>

        </div>


        <div class="categories-wrap my-5">

            <div class="container">
                <div class="row">

                    @foreach ($categories as $category)
                        <div class="col-md-4">
                            <div class="category-item-name">

                                <a href="{{ route('category_view', $category->slug) }}"
                                    style="background-color: {{ $category->bg_color }};"
                                    class="py-4 d-block text-center text-white mb-3 ">
                                    {{-- <i class="la {{ $category->icon_class }}"></i> --}}
                                    {{ $category->category_name }}
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
        {{-- @include('sections.action.skin-3') --}}
    @endsection

@extends('layouts.app')

@section('content')


 <link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">



    @php
        $path = request()->path();
    @endphp

<main>

    <!-- =======================
    Page Banner START -->
    <section class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-light p-4 text-center rounded-3">
                        <h1 class="m-0">{{ tr('Courses') }}</h1>
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ tr('Home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ tr('Courses') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    Page Banner END -->

    <!-- =======================
    Page content START -->
    <section class="pt-0">
        <div class="container">

        <!-- Filter bar START -->
        <form action="{{ route('courses') }}" method="GET" class="bg-light border p-4 rounded-3 my-4 z-index-9 position-relative">
            <div class="row g-3">
                <!-- Input -->
                <div class="col-xl-3">
                    <input class="form-control me-1" type="search" placeholder="{{ tr('Enter keyword') }}" name="q" value="{{ request('q') }}">
                </div>

                <!-- Select items -->
                <div class="col-xl-8">
                    <div class="row g-3">
                        <!-- Categories -->

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

                        <div class="col-sm-6 col-md-3 pb-2 pb-md-0">
                            <select class="form-select form-select-sm js-choice" aria-label=".form-select-sm example" name="category">
                                <option value="">{{ tr('Categories') }}</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ selected($category->id, request('category')) }}>{{ $category->category_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        @endif

                          <!-- Price level -->
                        <div class="col-sm-6 col-md-3 pb-2 pb-md-0">
                            <select class="form-select form-select-sm js-choice" aria-label=".form-select-sm example" name="price">
                                <option value="">{{ tr('Price level') }}</option>
                                <option value="free" {{ selected('free', request('price')) }}>Free</option>
                                <option value="paid" {{ selected('paid', request('price')) }}>Paid</option>
                                <option value="subscription" {{ selected('subscription', request('price')) }}>Subscription</option>
                            </select>
                        </div>






                        <div class="col-sm-6 col-md-3 pb-2 pb-md-0">
                            <select class="form-select form-select-sm js-choice" aria-label=".form-select-sm example" name="level">
                                <option value="">{{ tr('Level') }}</option>
                                @foreach(course_levels() as $key => $level)
                                    <option value="{{$key}}" {{ selected($key, request('level')) }}>{{$level}}</option>
                                @endforeach
                            </select>
                        </div>





                        <!-- Ratings -->
                        <div class="col-sm-6 col-md-3 pb-2 pb-md-0">
                            <select class="form-select form-select-sm js-choice" aria-label=".form-select-sm example" name="rating">
                                <option value="">{{ tr('Ratings') }}</option>
                                <option value="1-2" {{ selected('1-2', request('rating')) }}>1-2</option>
                                <option value="4.5 & Up" {{ selected('4.5 & Up', request('rating')) }}>4.5 & Up</option>
                                <option value="4.0 & Up" {{ selected('4.0 & Up', request('rating')) }}>4.0 & Up</option>
                                <option value="3.0 & Up" {{ selected('3.0 & Up', request('rating')) }}>3.0 & Up</option>
                                <option value="2.0 & Up" {{ selected('2.0 & Up', request('rating')) }}>2.0 & Up</option>
                                <option value="1.0 & Up" {{ selected('1.0 & Up', request('rating')) }}>1.0 & Up</option>
                            </select>
                        </div>
                    </div> <!-- Row END -->
                </div>
                <!-- Button -->
                <div class="col-xl-1">
                    <button type="submit" class="btn btn-primary mb-0 rounded z-index-1 w-100"><i class="fas fa-search"></i></button>
                </div>
            </div> <!-- Row END -->
        </form>


<!-- Filter bar END -->


            <div class="row mt-3">
                <!-- Main content START -->
                <div class="col-12">
                    @if($courses->count())
                    <!-- Course Grid START -->
                    <div class="row g-4">
                        @foreach($courses as $course)
                        {!! course_card($course, 'col-sm-6 col-lg-4 col-xl-3') !!}
                    @endforeach

                    </div>
                    <!-- Course Grid END -->
                    @else

                    @include('front.partials.no_data')

                    @endif
                    <!-- Pagination START -->
                    {!! $courses->links() !!}

                    <!-- Pagination END -->
                </div>
                <!-- Main content END -->
            </div><!-- Row END -->
        </div>
    </section>
    <!-- =======================
    Page content END -->

    </main>



<!-- Vendors -->
<script src="/assets/vendor/choices/js/choices.min.js"></script>


@endsection


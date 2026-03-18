<!-- resources/views/show.blade.php -->

@extends('layouts.app')

@section('content')




<!-- **************** MAIN CONTENT START **************** -->
<main>

    <!-- =======================
    Main Content START -->
    <section class="pb-0 pt-4 pb-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <!-- Title and Info START -->
                    <div class="row">
                        <!-- Avatar and Share -->
                        <div class="col-lg-3 align-items-center mt-4 mt-lg-5 order-2 order-lg-1">
                            <div class="text-lg-center">
                                <!-- Author info -->
                                <div class="position-relative">
                                    <!-- Avatar -->
                                    <div class="avatar avatar-xxl">
                                        <img class="avatar-img rounded-circle" src="{{ $post->user->get_photo }}" alt="avatar">
                                    </div>
                                    <a href="#" class="h5 stretched-link mt-2 mb-0 d-block">{{ $post->user->name }}</a>
                                    <p class="mb-2">{{ $post->user->job_title }}</p>
                                </div>
                                <!-- Info -->
                                <ul class="list-inline list-unstyled">
                                    <li class="list-inline-item d-lg-block my-lg-2">{{ $post->created_at->format('M d, Y') }}</li>
                                    <li class="list-inline-item d-lg-block my-lg-2">{{ estimatedReadingTime($post->content) }} min read</li>
                                    {{-- <li class="list-inline-item badge text-bg-orange"><i class="far text-white fa-heart me-1"></i>266</li> --}}
                                    <li class="list-inline-item badge text-bg-info"><i class="far fa-eye me-1"></i>{{ formatViews($post->views) }}</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col-lg-9 order-1">
                            <!-- Pre title -->
                            <span>{{ $post->created_at->format('M d, Y') }}</span><span class="mx-2">|</span><div class="badge text-bg-success"> {{ $post->topic }}
                            </div>
                            <!-- Title -->
                            <h1 class="mt-2 mb-0 display-5">{{ $post->title }}</h1>
                            <!-- Info -->
                            <p class="mt-2">{{ $post->short_description }}</p>
                            @include('sections.ads.banner')

                        </div>
                    </div>
                    <!-- Title and Info END -->



                    <!-- Quote and content START -->
                    <div class="row mt-4">
                        <!-- Content -->
                        <div class="col-12 mt-4 mt-lg-0">

                            <p class="mb-0">{!! clean_html($post->content) !!}</p>


                        </div>


                    <!-- Content END -->

                    <!-- Tags and share START -->
                    <div class="d-lg-flex justify-content-lg-between mb-4">
                        <!-- Social media button -->
                        <div class="align-items-center mb-3 mb-lg-0">
                            <h6 class="mb-2 me-4 d-inline-block">Share on:</h6>
                            <ul class="list-inline mb-0 mb-2 mb-sm-0">
                                <li class="list-inline-item"> <a class="btn px-2 btn-sm bg-facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" target="_blank"><i class="fab fa-fw fa-facebook-f"></i></a> </li>
                                <li class="list-inline-item"> <a class="btn px-2 btn-sm bg-instagram-gradient" href="https://www.instagram.com/" target="_blank"><i class="fab fa-fw fa-instagram"></i></a> </li>
                                <li class="list-inline-item"> <a class="btn px-2 btn-sm bg-twitter" href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}" target="_blank"><i class="fab fa-fw fa-twitter"></i></a> </li>
                                <li class="list-inline-item"> <a class="btn px-2 btn-sm bg-linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(Request::fullUrl()) }}" target="_blank"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>

                            </ul>
                        </div>
                        <!-- Popular tags -->
                        <div class="align-items-center">
                            <h6 class="mb-2 me-4 d-inline-block">{{ tr('Related Tags') }}:</h6>
                            <ul class="list-inline mb-0 social-media-btn">

                                @foreach ($post->tags as $tag)

                                <li class="list-inline-item"> <a class="btn btn-outline-light btn-sm mb-lg-0" href="#">{{ $tag->name }}</a> </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Tags and share END -->

                    <hr> <!-- Divider -->

                </div>
            </div> <!-- Row END -->
        </div>
    </section>
    <!-- =======================
    Main Content END -->

    <!-- =======================
    Related blog START -->
    <section class="pt-0">
        <div class="container">
        <!-- Title -->
            <div class="row mb-4">
                <div class="col-12">
                <h2 class="mb-0">{{ tr('You may also like') }}</h2>
                </div>
            </div>

            <!-- Slider START -->
            <div class="tiny-slider arrow-round arrow-hover arrow-dark">
                <div class="tiny-slider-inner" data-autoplay="false" data-arrow="true" data-edge="2" data-dots="false" data-items="3" data-items-lg="2" data-items-sm="1">

                    <!-- Slider item -->

                    @foreach ($posts as $post)

                    <div class="card bg-transparent">
                        <div class="row g-0">
                            <!-- Image -->
                            <div class="col-md-4">
                                <img src="{{ asset($post->featured_image ?: '/assets/images/placeholder-image.png') }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <!-- Card body -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <!-- Title -->
                                    <h6 class="card-title"><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h6>
                                    <span class="small">{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach


        </div>
    </section>
    <!-- =======================
    Related blog END -->

    </main>
    <!-- **************** MAIN CONTENT END **************** -->




@endsection

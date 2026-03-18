@extends('layouts.app')

@php
$auth_user = auth()->user();
$user = Auth::user();

@endphp
@section('content')
@php($cart = cart())

<script type="text/javascript">
    /* <![CDATA[ */
    window.pageData = @json(pageJsonData());
    /* ]]> */
</script>

<!-- **************** MAIN CONTENT START **************** -->
<main>
    @if(cart()->count)
    <!-- =======================
    Page Banner START -->
    <section class="py-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-light p-4 text-center rounded-3">
                        <h1 class="m-0">{{ tr('Checkout') }}</h1>
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ tr('Home') }}</a></li>
                                    @foreach($cart->courses as $cart_course)

                                    <li class="breadcrumb-item"><a href="{{ array_get($cart_course, 'course_url') }}">{{ $cart_course['title'] }}</a></li>

                                    @endforeach
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
    <section class="pt-5">
        <div class="container">

            <div class="row g-4 g-sm-5">
                <!-- Main content START -->
                <div class="col-xl-8 mb-4 mb-sm-0">


                    <!-- Personal info START -->
                    <div class="card card-body shadow p-4">
                        <!-- Title -->
                        <h5 class="mb-0">{{ tr('Personal Details') }}</h5>

                        <!-- Form START -->
                        <form class="row g-3 mt-0">
                            <!-- Name -->
                            <div class="col-md-6 bg-light-input">

                                <span class="mr-2"> {{ tr('Logged In As')}}: </span>
                                <strong class="flex-grow-1">{{$auth_user->fullName}}</strong>
                              </div>
                            <!-- Email -->
                            <div class="col-md-6 bg-light-input">

                                <span class="mr-2"> {{ tr('Email')}}: </span>
                                <strong class="flex-grow-1">{{$auth_user->email}}</strong>

                            </div>
                            <!-- Number -->
                            <div class="col-md-6 bg-light-input">


                                <span class="mr-2"> {{ tr('Phone Number')}}: </span>
                                <strong class="flex-grow-1">{{$auth_user->number}}</strong>


                            </div>

                            <!-- Address -->
                            <div class="col-md-6 bg-light-input">


                                <span class="mr-2"> {{ tr('Adress')}}: </span>
                                <strong class="flex-grow-1">{{$auth_user->adress}}</strong>

                            </div>
                            <!-- Cards -->
                            <div class="col-12">
                                 <div class="row g-2">

                                </div>
                            </div>

                        </form>
                        <!-- Form END -->

                        <!-- Payment method START -->
                        <div class="row g-3 mt-4">
                            <!-- Title -->
                            <h5 class="">{{ tr('Payment method') }}</h5>
                            <div class="col-12">
                                <div class="accordion accordion-circle" id="accordioncircle">
                                    @include(theme('partials.gateways.available-gateways'))
                                </div>
                            </div>
                        </div>
                        <!-- Payment method END -->
                    </div>
                    <!-- Personal info END -->
                </div>
                <!-- Main content END -->

                <!-- Right sidebar START -->
                <div class="col-xl-4">
                    <div class="row mb-0">
                        <div class="col-md-6 col-xl-12">
                                                <!-- Order summary START -->
                        <div class="card card-body shadow p-4 mb-4">
                            <!-- Title -->
                            <h4 class="mb-4">{{ tr('Order Summary') }}</h4>

                            <!-- PHP code to iterate through cart items -->
                            @php($cart = cart())
                            @if($cart->count)
                                @foreach($cart->courses as $cart_course)
                                    <!-- Course item START -->
                                    <div class="row g-3">
                                        <!-- Image -->
                                        <div class="col-sm-4">
                                            <img class="rounded" src="{{ array_get($cart_course, 'thumbnail') }}" alt="{{ $cart_course['title'] }}">
                                        </div>
                                        <!-- Info -->
                                        <div class="col-sm-8">
                                            <h6 class="mb-0"><a href="{{ array_get($cart_course, 'course_url') }}">{{ $cart_course['title'] }}</a></h6>
                                            <!-- Info -->
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <!-- Price -->
                                                <span class="text-success">{!! price_format(array_get($cart_course, 'price')) !!}</span>

                                                <!-- Remove and edit button -->
                                                <div class="text-primary-hover">
                                                    <!-- Add your remove and edit functionality here -->
                                                    {{-- <a href="#" class="small text-primary-hover remove-cart-btn" data-cart-id="{{ $cartKey }}">Remove --}}
                                                        {{-- <button type="button" class="btn btn-sm btn-danger remove-cart-btn" data-cart-id="{{ $cartKey }}">&times;</button> --}}
                                                    </a>
                                                     <a href="#" class="text-body me-2"><i class="bi bi-pencil-square me-1"></i>{{ tr('Edit') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Course item END -->
                                    <hr> <!-- Divider -->
                                @endforeach

                                @foreach($cart->subscriptions as $cart_subscription)
                                    <!-- subscription item START -->
                                    <div class="row g-3">
                                        <!-- Image -->
                                        <div class="col-sm-4">
                                            <img class="rounded" src="{{ array_get($cart_subscription, 'thumbnail') }}" alt="{{ $cart_subscription['title'] }}">
                                        </div>
                                        <!-- Info -->
                                        <div class="col-sm-8">
                                            <h6 class="mb-0"><a href="{{ array_get($cart_subscription, 'subscription_url') }}">{{ $cart_subscription['name'] }}</a></h6>
                                            <!-- Info -->
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <!-- Price -->
                                                <span class="text-success">{!! price_format(array_get($cart_subscription, 'price')) !!}</span>

                                                <!-- Remove and edit button -->
                                                <div class="text-primary-hover">
                                                    <!-- Add your remove and edit functionality here -->
{{--
                                                    <a href="#" class="text-body me-2"><i class="bi bi-trash me-1"></i>Remove</a>
                                                    <a href="#" class="text-body me-2"><i class="bi bi-pencil-square me-1"></i>{{ tr('Edit') }}</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- subscription item END -->
                                    <hr> <!-- Divider -->
                                @endforeach


                                <!-- Price and detail -->
                                <ul class="list-group list-group-borderless mb-2">
                                @if($cart->enable_charge_fees)

                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="h6 fw-light mb-0">{!! $cart->fees_name !!} ({!! $cart->fees_type === 'percent' ? $cart->fees_amount.'%' : '' !!})</span>
                                 </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="h6 fw-light mb-0">{{ tr('Fees Total') }}</span>
                                    <span class="text-danger"> + {!! price_format($cart->fees_total) !!}</span>
                                </li>

                            @endif

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="h5 mb-0">Total</span>
                                <span class="h5 mb-0">{!! price_format($cart->total_amount) !!}</span>
                            </li>


                                </ul>


                                <!-- Content -->
                                <p class="small mb-0 mt-2 text-center">{{ tr('By completing your purchase, you agree to these ') }}<a href="{{route('post_proxy', get_option('terms_of_use_page'))}}"><strong>{{ tr('Terms of Service') }}</strong></a></p>
                            @endif
                        </div>
                        <!-- Order summary END -->

                        </div>

                        <div class="col-md-6 col-xl-12">
                            <div class="card bg-blue p-3 position-relative overflow-hidden" style="background:url(assets/images/pattern/05.png) no-repeat center center; background-size:cover;">
                                <!-- SVG decoration -->
                                <figure class="position-absolute bottom-0 end-0 mb-n4 d-none d-md-block">
                                    <svg width="92.6px" height="135.2px">
                                        <path class="fill-white" d="M71.5,131.4c0.2,0.1,0.4,0.1,0.6-0.1c0,0,0.6-0.7,1.6-1.9c0.2-0.2,0.1-0.5-0.1-0.7c-0.2-0.2-0.5-0.1-0.7,0.1 c-1,1.2-1.6,1.8-1.6,1.8c-0.2,0.2-0.2,0.5,0,0.7C71.4,131.3,71.4,131.4,71.5,131.4z"></path>
                                        <path class="fill-white" d="M76,125.5c-0.2-0.2-0.3-0.5-0.1-0.7c1-1.4,1.9-2.8,2.8-4.2c0.1-0.2,0.4-0.3,0.7-0.2c0.2,0.1,0.3,0.4,0.2,0.7 c-0.9,1.4-1.8,2.9-2.8,4.2C76.6,125.6,76.3,125.6,76,125.5C76.1,125.5,76.1,125.5,76,125.5z M81.4,116.9 c-0.2-0.1-0.3-0.4-0.2-0.7c0.2-0.5,0.5-0.9,0.7-1.4c0.5-1.1,1-2.1,1.5-3.2c0.1-0.3,0.4-0.4,0.6-0.3c0.3,0.1,0.4,0.4,0.3,0.6 c-0.5,1.1-1,2.1-1.5,3.2c-0.2,0.5-0.5,0.9-0.7,1.4C81.9,117,81.6,117,81.4,116.9C81.4,116.9,81.4,116.9,81.4,116.9z M85.1,107.1 c0.5-1.6,1-3.2,1.3-4.8c0.1-0.3,0.3-0.4,0.6-0.4c0.3,0.1,0.4,0.3,0.4,0.6c-0.4,1.6-0.8,3.3-1.3,4.9c-0.1,0.3-0.4,0.4-0.6,0.3 c0,0,0,0-0.1,0C85.1,107.6,85,107.3,85.1,107.1z M47.3,83c-1.5-1.1-2.5-2.5-3.1-4.2c-0.1-0.3,0-0.5,0.3-0.6 c0.3-0.1,0.5,0,0.6,0.3c0.5,1.5,1.5,2.7,2.8,3.7c0.2,0.2,0.3,0.5,0.1,0.7C47.9,83.1,47.6,83.1,47.3,83C47.4,83,47.4,83,47.3,83z  M51.7,84.6c0-0.3,0.3-0.5,0.5-0.4c1.4,0.2,2.9-0.3,4.3-1.4c0.2-0.2,0.5-0.1,0.7,0.1c0.2,0.2,0.1,0.5-0.1,0.7 c-1.6,1.2-3.4,1.8-5,1.6c-0.1,0-0.1,0-0.2,0C51.8,85,51.7,84.8,51.7,84.6z M87.2,97.4c0.2-1.7,0.2-3.3,0.2-5 c0-0.3,0.2-0.5,0.5-0.5c0.3,0,0.5,0.2,0.5,0.5c0.1,1.7,0,3.4-0.2,5.1c0,0.3-0.3,0.5-0.5,0.4c-0.1,0-0.1,0-0.2,0 C87.3,97.8,87.1,97.6,87.2,97.4z M43.7,73.6c0.2-1.6,0.7-3.2,1.5-4.8l0.1-0.1c0.1-0.2,0.4-0.3,0.7-0.2c0,0,0,0,0,0 c0.2,0.1,0.3,0.4,0.2,0.7l-0.1,0.1c-0.7,1.5-1.2,3-1.4,4.5c0,0.3-0.3,0.5-0.6,0.4c-0.1,0-0.1,0-0.2,0 C43.8,74,43.7,73.8,43.7,73.6z M60,79.8c-0.2-0.1-0.3-0.5-0.1-0.7c0.4-0.6,0.8-1.3,1.1-2c0.4-0.8,0.7-1.6,1-2.4 c0.1-0.3,0.4-0.4,0.6-0.3c0.3,0.1,0.4,0.4,0.3,0.6c-0.3,0.9-0.7,1.7-1.1,2.5c-0.4,0.7-0.8,1.4-1.2,2.1C60.5,79.9,60.2,80,60,79.8 C60,79.9,60,79.8,60,79.8z M86.8,87.5c-0.3-1.6-0.7-3.2-1.2-4.8c-0.1-0.3,0.1-0.5,0.3-0.6c0.3-0.1,0.5,0.1,0.6,0.3 c0.5,1.6,1,3.3,1.2,4.9c0,0.3-0.1,0.5-0.4,0.6c-0.1,0-0.2,0-0.3,0C87,87.7,86.9,87.6,86.8,87.5z M48.2,65.1 c-0.2-0.2-0.2-0.5,0-0.7c1.2-1.3,2.5-2.4,3.9-3.4c0.2-0.1,0.5-0.1,0.7,0.1c0.1,0.2,0.1,0.5-0.1,0.7c-1.4,0.9-2.6,2-3.7,3.2 c-0.2,0.2-0.4,0.2-0.6,0.1C48.3,65.2,48.3,65.1,48.2,65.1z M63.3,70c0.3-1.6,0.5-3.3,0.5-4.9c0-0.3,0.2-0.5,0.5-0.5 c0.3,0,0.5,0.2,0.5,0.5c-0.1,1.7-0.2,3.4-0.5,5.1c0,0.3-0.3,0.4-0.6,0.4c0,0-0.1,0-0.1,0C63.3,70.4,63.2,70.2,63.3,70z M83.8,78 c-0.7-1.5-1.5-3-2.4-4.3c-0.1-0.2-0.1-0.5,0.1-0.7c0.2-0.1,0.5-0.1,0.7,0.2c0.9,1.4,1.7,2.9,2.5,4.4c0.1,0.2,0,0.5-0.2,0.7 c-0.1,0.1-0.3,0.1-0.4,0C83.9,78.2,83.8,78.1,83.8,78z M56.5,59.6c-0.1-0.3,0.1-0.5,0.4-0.6c1.7-0.4,3.4-0.5,5.2-0.3 c0.3,0,0.5,0.3,0.4,0.5c0,0.3-0.3,0.5-0.5,0.4c-1.7-0.2-3.3-0.1-4.8,0.3c-0.1,0-0.2,0-0.3,0C56.6,59.8,56.5,59.7,56.5,59.6z  M78.4,69.7c-1.1-1.3-2.2-2.5-3.4-3.6c-0.2-0.2-0.2-0.5,0-0.7c0.2-0.2,0.5-0.2,0.7,0c1.2,1.1,2.4,2.4,3.5,3.7 c0.2,0.2,0.1,0.5-0.1,0.7c-0.2,0.1-0.4,0.1-0.5,0.1C78.5,69.8,78.4,69.7,78.4,69.7z M63.6,60.1c-0.2-1.6-0.4-3.3-0.8-4.9 c-0.1-0.3,0.1-0.5,0.4-0.6c0.3-0.1,0.5,0.1,0.6,0.4c0.4,1.7,0.7,3.4,0.8,5c0,0.3-0.2,0.5-0.4,0.5c-0.1,0-0.2,0-0.3,0 C63.7,60.4,63.6,60.2,63.6,60.1z M71,63.1c-1.4-0.9-2.9-1.7-4.4-2.3c-0.3-0.1-0.4-0.4-0.3-0.6c0.1-0.3,0.4-0.4,0.6-0.3 c1.5,0.6,3.1,1.4,4.6,2.3c0.2,0.1,0.3,0.5,0.1,0.7C71.6,63.1,71.3,63.2,71,63.1C71.1,63.1,71.1,63.1,71,63.1z M61.3,50.4 c-0.6-1.5-1.3-3-2.1-4.5c-0.1-0.2-0.1-0.5,0.2-0.7c0.2-0.1,0.5-0.1,0.7,0.2c0.9,1.5,1.6,3.1,2.2,4.6c0.1,0.3,0,0.5-0.3,0.6 c-0.1,0.1-0.3,0-0.4,0C61.5,50.6,61.4,50.5,61.3,50.4z M56.5,41.8c-1-1.3-2.1-2.6-3.2-3.8c-0.2-0.2-0.2-0.5,0-0.7 c0.2-0.2,0.5-0.2,0.7,0c1.2,1.3,2.3,2.6,3.3,3.9c0.2,0.2,0.1,0.5-0.1,0.7c-0.2,0.1-0.4,0.1-0.5,0C56.6,41.9,56.5,41.8,56.5,41.8z  M49.7,34.5c-1.2-1.1-2.5-2.1-3.9-3.2c-0.2-0.2-0.3-0.5-0.1-0.7c0.2-0.2,0.5-0.3,0.7-0.1c1.4,1,2.7,2.1,3.9,3.2 c0.2,0.2,0.2,0.5,0,0.7c-0.2,0.2-0.4,0.2-0.6,0.1C49.7,34.6,49.7,34.5,49.7,34.5z M41.7,28.5c-1.4-0.9-2.8-1.8-4.3-2.6 c-0.2-0.1-0.3-0.4-0.2-0.7c0.1-0.2,0.4-0.3,0.7-0.2c1.5,0.8,2.9,1.7,4.3,2.6c0.2,0.1,0.3,0.5,0.1,0.7 C42.2,28.6,42,28.6,41.7,28.5C41.7,28.5,41.7,28.5,41.7,28.5z"></path>
                                        <path class="fill-white" d="M30.7,22.6C30.7,22.6,30.7,22.6,30.7,22.6c0,0,0.9,0.4,2.3,1c0.2,0.1,0.5,0,0.7-0.2c0.1-0.2,0-0.5-0.2-0.7 c0,0,0,0,0,0c-1.4-0.7-2.2-1-2.3-1c-0.3-0.1-0.5,0-0.6,0.3C30.3,22.2,30.4,22.5,30.7,22.6z"></path>
                                        <path class="fill-warning" d="M22.6,23.6l-1.1-4.1c0,0-11.7-7.5-11.9-7.6c-0.1-0.2-4.9-6.5-4.9-6.5l8.2,3.5l12.2,8.4L22.6,23.6z"></path>
                                        <polygon class="fill-warning opacity-6" points="31.2,12.3 4.7,5.4 25.1,17.2"></polygon>
                                        <polygon class="fill-warning opacity-6" points="21.5,19.5 15,24.8 4.7,5.4 "></polygon>
                                    </svg>
                                </figure>
                                <!-- Body -->
                                <div class="card-body">
                                    <!-- Title -->


                                    <!-- Button -->

                                </div>
                     </div><!-- Row End -->
                </div>
                <!-- Right sidebar END -->

            </div><!-- Row END -->
        </div>
    </section>
    <!-- =======================
    Page content END -->

    @else
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <!-- Image -->
                    <img src="assets/images/element/cart.svg" class="h-200px h-md-300px mb-3" alt="">
                    <!-- Subtitle -->
                    <h2>{{ tr('Your cart is currently empty') }}</h2>
                    <!-- info -->
                    <p class="mb-0">{{ tr('Please check out all the available courses and buy some courses that fulfill your needs.') }} </p>
                    <!-- Button -->
                    <a href="{{route('home')}}" class="btn btn-primary mt-4 mb-0">{{ tr('Back to Home') }}</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <style>/* Styles for Payment Received Container */
        .payment-received-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Styles for Payment Received Section */
        .payment-received {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        /* Styles for Success Icon */
        .success-icon {
            font-size: 64px;
            color: #28a745; /* Green color */
        }

        /* Styles for Success Heading */
        .success-heading {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 28px;
            color: #333;
        }

        /* Styles for Success Message */
        .success-message {
            margin-bottom: 30px;
            font-size: 18px;
            color: #555;
        }

        /* Styles for Home Button */
        .btn-primary {
            background-color: #007bff; /* Blue color */
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue color on hover */
        }
        </style>


    </main>
    <!-- **************** MAIN CONTENT END **************** -->
    <script src="/assets/js/bootstrap-old.min.js"></script>
    <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script>


@endsection

@section('page-js')
    @include(('front.partials.gateways.gateway-js'))
@endsection

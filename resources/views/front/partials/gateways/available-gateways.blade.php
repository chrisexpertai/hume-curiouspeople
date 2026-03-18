@php($hasGateways = get_option('enable_stripe') || get_option('enable_paypal') || get_option('bank_gateway.enable_bank_transfer') || get_option('enable_offline_payment'))

@if($hasGateways)
    <div class="section-payment-methods-wrap">

        <p class=" "> <i class="la la-lock"></i> Your payment information is securely encrypted for your peace of mind.</p>

        <div class="checkout-section checkout-payment-methods-wrap mt-3">

            <ul class="nav nav-pills mb-3" role="tablist">

                <?php do_action('checkout_available_gateways_nav_before', $cart); ?>


                @if(get_option('enable_stripe'))
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#payment-tab-card">
                            <i class="la la-credit-card"></i> {{ tr('Credit Card') }}
                        </a>
                    </li>
                @endif

                @if(get_option('enable_paypal'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#payment-tab-paypal">
                            <i class="la la-paypal"></i> {{ tr('PayPal') }}
                        </a>
                    </li>
                @endif

                @if(get_option('bank_gateway.enable_bank_transfer'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#payment-tab-bank">
                            <i class="la la-university"></i>  {{ tr('Bank Transfer') }}
                        </a>
                    </li>
                @endif


                @if(get_option('enable_offline_payment'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#payment-tab-offline">
                            <i class="la la-wallet"></i>  {{ tr('Offline Payment') }}
                        </a>
                    </li>
                @endif

                <?php do_action('checkout_available_gateways_nav_after', $cart); ?>

            </ul>



            <div class="tab-content">

                <?php do_action('checkout_available_gateways_tab_content_before', $cart); ?>

                @if(get_option('enable_stripe'))
                    <div class="tab-pane fade show active" id="payment-tab-card">
                        <div class="stripe-credit-card-form-wrap ml-auto mr-auto">
                            <form action="/charge" method="post" id="payment-form">
                                <div class="form-group">
                                    <label for="card-element"> {{ tr('Pay with your Credit or debit card via Stripe') }}</label>
                                    <div id="card-element" class="form-control">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                </div>

                                <!-- Used to display form errors. -->
                                <div id="card-errors" class="text-danger mb-3" role="alert"></div>

                                <button type="submit" class="btn btn-purple" id="stripe-payment-form-btn">
                                    <span class="enroll-course-btn-text mr-4 border-right pr-4">{{__t('enroll_in_course')}}</span>
                                    <span class="enroll-course-btn-price">{!! price_format($cart->total_amount) !!}</span>
                                </button>
                            </form>
                        </div>
                    </div> <!-- tab-pane.// -->
                @endif

                @if(get_option('enable_paypal'))
                    <div class="tab-pane fade" id="payment-tab-paypal">

                        <div class="paypal-payment-form-wrap py-5 text-center">

                            <form action="{{route('paypal_redirect')}}" method="post">
                                @csrf
                                <p>
                                    <button type="submit" class="btn btn-purple btn-lg" id="paypal-payment-form-btn">
                                        <span class="enroll-course-btn-text mr-4 border-right pr-4">
                                            <i class="la la-paypal"></i> {{ tr('Pay with PayPal') }}
                                        </span>
                                        <span class="enroll-course-btn-price">
                                            {!! price_format($cart->total_amount) !!}
                                        </span>
                                    </button>
                                </p>
                            </form>

                        </div>

                    </div>
                @endif

                @if(get_option('bank_gateway.enable_bank_transfer'))
                    <div class="tab-pane fade" id="payment-tab-bank">
                        @include(theme('partials.gateways.bank-transfer'))
                    </div> <!-- tab-pane.// -->
                @endif

                @if(get_option('enable_offline_payment'))

                    <div class="tab-pane fade" id="payment-tab-offline">
                        <div class="offline-payment-form-wrap pt-2 pb-5">

                            <form action="{{route('pay_offline')}}" method="post">
                                @csrf

                                <div class="form-group">

                                    <label>{{ tr('Write about your payment method') }} </label>

                                    <textarea class="form-control" name="payment_note"></textarea>
                                    <p class=" ">
                                        <small>
                                            {{ tr('Write your payment method in details, elobrate as much as you can. We will verify payment and you will get course access after payment verification.') }}
                                        </small>
                                    </p>

                                </div>


                                <p>
                                    <button type="submit" class="btn btn-purple btn-lg" id="offline-payment-form-btn">
                                        <span class="enroll-course-btn-text mr-4 border-right pr-4">
                                            <i class="la la-wallet"></i> {{ tr('Pay with Offline Payment') }}
                                        </span>
                                        <span class="enroll-course-btn-price">
                                            {!! price_format($cart->total_amount) !!}
                                        </span>
                                    </button>
                                </p>
                            </form>

                        </div>

                    </div>
                @endif

                <?php do_action('checkout_available_gateways_tab_content_after', $cart); ?>


            </div> <!-- tab-content .// -->

        </div>
    </div>

@else
    <div class="alert alert-warning">
        <i class="la la-exclamation-circle"></i> {{ tr('There is no payment gateway available to complete purchase.') }}
    </div>
@endif




@php
    $cart = cart();
@endphp
<div class="me-2 me-md-3 dropdown">
    <!-- Cart button -->
    <a class="btn btn-light btn-round mb-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-cart3 fa-fw"></i>
    </a>
    <!-- badge -->
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-dark mt-xl-2 ms-n1">
        @if($cart->count)
            {{ $cart->count }}
        @else
            0
        @endif
        <span class="visually-hidden">{{ tr('unread messages') }}</span>
    </span>
    <!-- Cart dropdown menu START -->
    <div class="dropdown-menu dropdown-animation dropdown-menu-end dropdown-menu-size-md p-0 shadow-lg border-0">
        <div class="card bg-transparent">
            <div class="card-header bg-transparent border-bottom py-4">
                <h5 class="m-0">{{ tr('Cart items') }}</h5>
            </div>
            <div class="card-body p-0">
                @if($cart->count)
                    @foreach($cart->courses as $cartKey => $cart_course)
                        <!-- Cart item START -->
                        <div class="row p-3 g-2">
                            <!-- Image -->
                            <div class="col-3">
                                <img class="rounded-2" src="{{ array_get($cart_course, 'thumbnail') }}" alt="avatar">
                            </div>

                            <div class="col-9">
                                <!-- Title -->
                                <div class="d-flex justify-content-between">
                                    <h6 class="m-0">{{ $cart_course['title'] }}</h6>
                                    <a href="#" class="small text-primary-hover remove-cart-btn" data-cart-id="{{ $cartKey }}">
                                        <button type="button" class="btn btn-sm btn-danger remove-cart-btn" data-cart-id="{{ $cartKey }}">&times;</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Cart item END -->
                    @endforeach

                    @foreach($cart->subscriptions as $cartKey => $cart_subscription)
                        <!-- Cart item START -->
                        <div class="row p-3 g-2">
                            <div class="col-12">
                                <!-- Title -->
                                <div class="d-flex justify-content-between">
                                    <h6 class="m-0">{{ $cart_subscription['name'] }}</h6>
                                    <a href="#" class="small text-primary-hover remove-cart-btn" data-cart-id="{{ $cartKey }}">
                                        <button type="button" class="btn btn-sm btn-danger remove-cart-btn" data-cart-id="{{ $cartKey }}">&times;</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Cart item END -->
                    @endforeach

                @else
                    <p class="text-center">{{ tr('No items in the cart') }}</p>
                @endif
            </div>
            <!-- Select item -->
            <p class="minicart-total-price card-body">
                Total : <strong>{!! price_format($cart->total_price) !!}</strong>
                @if($cart->total_original_price > 0)
                    <small><s>{!! price_format($cart->total_original_price) !!}</s></small>
                @endif
            </p>
        </div>
        <!-- Button -->
        @if($cart->count)
            <div class="card-footer bg-transparent p-3 border-top py-3 text-center d-flex justify-content-between position-relative">
                <a href="#" class="btn btn-sm btn-light mb-0">{{ tr('View Cart') }}</a>
                <a href="{{ route('checkout') }}" class="btn btn-sm btn-success mb-0">{{ tr('Checkout') }}</a>
            </div>
        @endif
    </div>
    <!-- Cart dropdown menu END -->
</div>

 <script>
    $(document).on('click', '.remove-cart-btn', function(e) {
        e.preventDefault();

        var $btn = $(this);
        var cart_id = $btn.attr('data-cart-id');
        $btn.closest('.row.p-3.g-2').remove();
        $.post('{{ route("remove-from-cart") }}', {cart_id: cart_id, _token: '{{ csrf_token() }}'}, function(response) {
            if (response.success) {
                $('.dropdown.mini-cart-item').html(response.cart_html);
            }
        });
    });
</script>

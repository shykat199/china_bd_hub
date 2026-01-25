@extends('frontend.layouts.front')

@section('title', 'Checkout')

@section('content')
    <style>
        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
        }

        /*.form-control,*/
        /*.form-select {*/
        /*    padding: 10px 12px;*/
        /*    border-radius: 6px;*/
        /*}*/

        .submit-btn {
            border-radius: 30px;
            font-size: 16px;
        }

    </style>

    <!-- Billing Details Start -->
    <section class="billing-details bg-light">
        <form action="{{ route('customer.payment') }}" method="post" class="ajaxform_instant_reload">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow rounded-3">
                            <div class="card-body">
                                <div class="buy-more-check">
                                    <h4 class="text-center">Order Submit OR</h4>
                                    <h5 class="text-center"><a class="text-primary" href="{{ url('/') }}">Buy More</a>
                                        <span class="animation-pulse"></span></h5>
                                </div>
                                <div class="login-form mt-4">
                                    <div class="row g-3">

                                        <!-- Level -->
                                        <div class="col-12">
                                            <label for="level" class="form-label">
                                                {{ __('নাম') }} <span class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                id="level"
                                                name="first_name"
                                                value="{{ old('first_name') }}"
                                                class="form-control"
                                                required
                                            >
                                        </div>

                                        <!-- User ID -->
                                        @if(Auth::user())
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        @endif

                                        <!-- Mobile -->
                                        <div class="col-12">
                                            <label for="mobile" class="form-label">
                                                {{ __('মোবাইল') }} <span class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="number"
                                                id="mobile"
                                                name="mobile"
                                                class="form-control"
                                                required
                                            >
                                        </div>

                                        <!-- Billing Address -->
                                        <div class="col-12">
                                            <label for="billing_address" class="form-label">
                                                {{ __('ঠিকানা') }} <span class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                id="billing_address"
                                                name="billing_address"
                                                value="{{ $billing->address_1 ?? '' }}"
                                                class="form-control"
                                                required
                                            >
                                        </div>

                                        <!-- Shipping Area -->
                                        <div class="col-12 mt-4">
                                            <select name="shipping_cost" id="shipping_cost">
                                                @foreach ($shipping_areas as $shipping_area)
                                                    <option value="{{ $shipping_area->charge }}">
                                                        {{ $shipping_area->name }} - {{ $shipping_area->charge }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12 text-center mt-4">
                                            <button type="submit" class="btn btn-primary px-5 py-2 submit-btn text-white">
                                                {{ __('অর্ডার কনফার্ম করুন') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <h4 class="text-center">{{ __('ORDER SUMMARY') }}</h4>
                                    <div>
                                        <input type="text" placeholder="Search Product" id="search-product">
                                        <ul id="show-product" class="list-group"
                                            style="position: absolute;width: 340px;right: -63px;overflow-y: auto;height: 226px;display: none">
                                        </ul>
                                    </div>
                                </div>

                                <div class="right-form mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Items') }}</th>
                                                <th>{{ __('Quantity') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($carts ?? false)
                                                @foreach ($carts as $key => $cart)
                                                    <tr id="cart-row-{{ $key }}">
                                                        <th scope="row">
                                                            <img src="{{ asset('uploads/products/galleries') }}/{{ CartItem::thumbnail($cart->id) }}"
                                                                class="b-1" alt="{{ CartItem::name($cart->id) }}">
                                                            <p>
                                                                {{ CartItem::name($cart->id) }}
                                                                @if ($cart->color)
                                                                    <span
                                                                        class="badge bg-light text-dark">({{ $cart->color }})</span>
                                                                @endif
                                                                @if ($cart->size)
                                                                    - <span
                                                                        class="badge bg-light text-dark">({{ $cart->size }})</span>
                                                                @endif
                                                            </p>
                                                        </th>
                                                        <td class="table-quantity">
                                                            <div class="quantity">
                                                                <input type="button" value="-" class="minus"
                                                                    data-key="{{ $key }}"
                                                                    data-id="{{ $cart->id }}"
                                                                    onclick="updateCart($(this))">
                                                                <input type="number" class="input-number w-25 qty"
                                                                    min="1" name="quantity"
                                                                    value="{{ $cart->quantity }}"
                                                                    onchange="updateCart($(this))"
                                                                    oninput="updateCart($(this))"
                                                                    data-key="{{ $key }}"
                                                                    data-id="{{ $cart->id }}"
                                                                    data-product-stock="{{ $cart->product_stock }}">
                                                                <input type="button" value="+" class="plus"
                                                                    data-key="{{ $key }}"
                                                                    data-id="{{ $cart->id }}"
                                                                    data-product-stock="{{ $cart->product_stock }}"
                                                                    onclick="updateCart($(this))">
                                                            </div>
                                                        </td>
                                                        <td class="total">
                                                            {{ currency(CartItem::price($cart->id, $cart->quantity), 2) }}
                                                        </td>
                                                        <td class="table-close-btn">
                                                            <button type="button"
                                                                onclick="removeFromCart(`{{ $key }}`,{{ $cart->id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 511.995 511.995">
                                                                    <path d="M437.126,74.939c-99.826-99.826-262.307-99.826-362.133,0C26.637,123.314,0,187.617,0,256.005
                                        s26.637,132.691,74.993,181.047c49.923,49.923,115.495,74.874,181.066,74.874s131.144-24.951,181.066-74.874
                                        C536.951,337.226,536.951,174.784,437.126,74.939z M409.08,409.006c-84.375,84.375-221.667,84.375-306.042,0
                                        c-40.858-40.858-63.37-95.204-63.37-153.001s22.512-112.143,63.37-153.021c84.375-84.375,221.667-84.355,306.042,0
                                        C493.435,187.359,493.435,324.651,409.08,409.006z" />
                                                                    <path
                                                                        d="M341.525,310.827l-56.151-56.071l56.151-56.071c7.735-7.735,7.735-20.29,0.02-28.046
                                        c-7.755-7.775-20.31-7.755-28.065-0.02l-56.19,56.111l-56.19-56.111c-7.755-7.735-20.31-7.755-28.065,0.02
                                        c-7.735,7.755-7.735,20.31,0.02,28.046l56.151,56.071l-56.151,56.071c-7.755,7.735-7.755,20.29-0.02,28.046
                                        c3.868,3.887,8.965,5.811,14.043,5.811s10.155-1.944,14.023-5.792l56.19-56.111l56.19,56.111
                                        c3.868,3.868,8.945,5.792,14.023,5.792c5.078,0,10.175-1.944,14.043-5.811C349.28,331.117,349.28,318.562,341.525,310.827z" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7">
                                                        <p class="text-center">{{ __('No available item in cart') }}</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                    <div class="order-cart mt-4">
{{--                                        <ul id="order-details">--}}
{{--                                            <li>{{ __('Subtotal') }}<span class="sub-total"--}}
{{--                                                    data-sub-total="{{ Cookie::get('subTotal') }}">{{ currency(Cookie::get('subTotal'), 2) }}</span>--}}
{{--                                            </li>--}}
{{--                                            <li>{{ __('Shipping Charge') }}--}}
{{--                                                @if (Cookie::get('totalShipping') == 0)--}}
{{--                                                    <span class="total-shipping">{{ __('Free') }}</span>--}}
{{--                                                @else--}}
{{--                                                    <span--}}
{{--                                                        class="total-shipping">{{ currency(Cookie::get('totalShipping'), 2) }}</span>--}}
{{--                                                @endif--}}
{{--                                            </li>--}}
{{--                                            @if (Cookie::get('coupon_discount'))--}}
{{--                                                <li>{{ __('Coupon') }}<span>{{ currency(Cookie::get('coupon_discount'), 2) }}</span>--}}
{{--                                                </li>--}}
{{--                                            @endif--}}
{{--                                            <li>{{ __('Total') }}<span--}}
{{--                                                    class="grand-total">{{ currency(Cookie::get('total') - Cookie::get('coupon_discount'), 2) }}</span>--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}

                                        <ul id="order-details">
                                            <li>
                                                {{ __('Subtotal') }}
                                                <span class="sub-total" data-sub-total="{{ session('subTotal', 0) }}">
                                                    {{ currency(session('subTotal', 0), 2) }}
                                                </span>
                                            </li>

                                            <li>
                                                {{ __('Shipping Charge') }}
                                                @if (session('totalShipping', 0) == 0)
                                                    <span class="total-shipping">{{ __('Free') }}</span>
                                                @else
                                                <span class="total-shipping">
                                                    {{ currency(session('totalShipping', 0), 2) }}
                                                </span>
                                                @endif
                                            </li>

                                            @if (session()->has('coupon_discount'))
                                                <li>
                                                    {{ __('Coupon') }}
                                                    <span>{{ currency(session('coupon_discount'), 2) }}</span>
                                                </li>
                                            @endif

                                            <li>
                                                {{ __('Total') }}
                                                <span class="grand-total">
                                                    {{ currency(
                                                        session('total', 0) - session('coupon_discount', 0),
                                                        2
                                                    ) }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>

                                    <h5 class="mb-2">{{ __('Promotional Code') }} ({{ __('Have a coupon?') }})</h5>
                                    @if (Cookie::get('coupon_infos'))
                                        @php
                                            $coupon_infos = json_decode(Cookie::get('coupon_infos'));
                                        @endphp
                                        <div class="right-search input-group mb-0">
                                            <input type="text" name="code" id="code"
                                                placeholder="Enter your coupon code" value="{{ $coupon_infos->code }}">
                                            <button type="button" class="btn-anime"
                                                id="apply-coupon">{{ __('Apply Coupon') }}</button>
                                        </div>
                                        <div class="row mb-2 coupon-infos">
                                            <div class="col-11">
                                                <h5 class="text-warning">{{ $coupon_infos->code }}</h5>
                                            </div>
                                            <div class="col-1">
                                                <h5><a href="javascript:void(0)" onclick="removeCoupon()"><i
                                                            class="fa-solid fa-xmark text-danger"></i></a></h5>
                                            </div>
                                        </div>
                                    @else
                                        <div class="right-search input-group mb-0">
                                            <input type="text" name="code" id="code"
                                                placeholder="Enter your coupon code">
                                            <button type="button" class="btn-anime"
                                                id="apply-coupon">{{ __('Apply Coupon') }}</button>
                                        </div>
                                        <div class="row mb-2 coupon-infos">
                                            {{-- AJAX --}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @push('modal') --}}
            <div class="modal fade" id="pay-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Pay amount <span class="pay-amount"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="bank" class="col-form-label">Select Bank</label>
                                <select name="bank" id="bank" class="form-control">
                                    <option value="bKash">bKash</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Nagad">Nagad</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paid_amount" class="col-form-label">Paid Amount</label>
                                <input type="number" step="any" class="form-control" name="paid_amount"
                                    id="paid_amount">
                            </div>
                            <div class="mb-3">
                                <label for="transaction_id" class="col-form-label">Transaction Id</label>
                                <input type="text" id="transaction_id" step="any" class="form-control"
                                    name="transaction_id">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn border-danger text-danger"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn theme-btn completed">Complete</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endpush --}}
        </form>
    </section>
    <!-- Billing Details End -->

@stop

@push('script')
    <script>
        $("#apply-coupon").click(function() {
            var code = $("#code").val();
            var csrf = "{{ @csrf_token() }}"
            $.ajax({
                url: "{{ route('customer.coupon') }}",
                data: {
                    _token: csrf,
                    code: code
                },
                type: 'post'
            }).done(function(res) {
                if (res.status !== 'error') {
                    $('.coupon-infos').html(res.coupon_infos)
                    $("#order-details").html(res.after_coupon);
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end', // bottom-right
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end', // bottom-right
                        icon: 'error',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });
        })
        $('#shipping_cost').on('change', function() {
            var shipping_cost = $(this).val();
            var sub_total = $('.sub-total').data('sub-total');
            var grand_total = parseFloat(sub_total) + parseFloat(shipping_cost);

            // Format shipping_cost and grand_total to have 2 digits after the decimal point
            shipping_cost = parseFloat(shipping_cost).toFixed(2);
            grand_total = parseFloat(grand_total).toFixed(2);

            $('.total-shipping').text('৳' + shipping_cost);
            $('.grand-total').text('৳' + grand_total);

            var csrf = "{{ @csrf_token() }}"

            $.ajax({
                url: `{{route('customer.updateShipping')}}`,
                type: 'POST',
                data: {
                    shipping_cost: shipping_cost,
                    _token: csrf
                },
                success: function (response) {
                    console.log('Shipping updated:', response);
                }
            });

        });

        function removeCoupon() {
            var csrf = "{{ @csrf_token() }}"
            $.ajax({
                url: "{{ route('customer.coupon.remove') }}",
                data: {
                    _token: csrf
                },
                type: 'post'
            }).done(function(res) {
                $("#code").val('');
                $('.coupon-infos').html('');
                $("#order-details").html(res.data);
                Swal.fire({
                    toast: true,
                    position: 'bottom-end', // bottom-right
                    icon: 'success',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        }

        {{--function removeFromCart(key, id) {--}}
        {{--    swal({--}}
        {{--        title: "{{ __('Really!?') }}",--}}
        {{--        text: "{{ __('Are you sure you want remove this form cart?') }}",--}}
        {{--        icon: "warning",--}}
        {{--        buttons: true,--}}
        {{--        dangerMode: true,--}}
        {{--    }).then((whileDelete) => {--}}
        {{--        if (whileDelete) {--}}
        {{--            var csrf = "{{ csrf_token() }}";--}}
        {{--            $.ajax({--}}
        {{--                url: "{{ route('customer.removeFromCart') }}",--}}
        {{--                data: {--}}
        {{--                    _token: csrf,--}}
        {{--                    key: key,--}}
        {{--                    id: id--}}
        {{--                },--}}
        {{--                type: "POST"--}}
        {{--            }).done(function(e) {--}}
        {{--                swal("{{ __('Poof! Your item has been removed!') }}", {--}}
        {{--                    icon: "success",--}}
        {{--                }).then(value => {--}}
        {{--                    $("#cart-count").text(e.count);--}}
        {{--                    $(".sub-total").text(e.sub_total);--}}
        {{--                    $(".grand-total").text(e.grand_total);--}}
        {{--                    $(".total-shipping").text(e.totalShipping);--}}
        {{--                    $("#cart-row-" + key).remove();--}}
        {{--                });--}}
        {{--            })--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

        function removeFromCart(key, id) {
            Swal.fire({
                title: "Really!?",
                text: "Are you sure you want to remove this from cart?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('customer.removeFromCart') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            key: key,
                            id: id
                        },
                        success: function (e) {
                            Swal.fire(
                                "Removed!",
                                "Your item has been removed.",
                                "success"
                            );

                            $("#cart-count").text(e.count);
                            $(".sub-total").text(e.sub_total);
                            $(".grand-total").text(e.grand_total);
                            $(".total-shipping").text(e.totalShipping);
                            $("#cart-row-" + key).remove();
                        }
                    });
                }
            });
        }

        function updateCart(elem) {

            var key = elem.data('key');
            var id = elem.data('id');
            var product_stock = elem.data('product-stock');
            var action = elem.val();
            var qty = elem.closest('tr').find('.qty');

            if (isNaN(action)) {
                if (elem.val() === '+') {
                    qty.val(parseInt(qty.val()) + 1);
                } else {
                    qty.val(parseInt(qty.val()) - 1);
                }
            }
            if (product_stock == qty || product_stock <= qty) {
                qty = product_stock;
            }
            if(qty.val() == 1){
                elem.val('-').prop('disabled', true);
            }
            var csrf = "{{ csrf_token() }}";
            if (qty.val() > 0) {
                $.ajax({
                    url: "{{ route('customer.updateCart') }}",
                    type: "post",
                    data: {
                        _token: csrf,
                        key: key,
                        id: id,
                        qty: qty.val()
                    },
                }).done(function(e) {
                    if (e.status == 'success') {
                        $(".sub-total").text(e.sub_total);
                        $(".grand-total").text(e.grand_total);
                        // elem.closest('tr').find('.qty').val(qty);
                        elem.closest('tr').find('.total').text(e.productTotal);
                    } else {
                        qty.val(parseInt(qty.val()) - 1);
                        swal("{{ __('Sorry!') }}", e, "error");
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error:", textStatus, errorThrown);
                });
            }
        }

        $('.mobile-banking').on('click', function() {
            $('#pay-modal').modal('show');
            const amount_text = $('.grand-total').text();
            const amount = amount_text.replace('৳', '');
            $('.pay-amount').text(parseFloat(amount));
        })

        $('.completed').on('click', function() {
            let bank = $('#bank').val();
            let paid_amount = $('#paid_amount').val();
            let transaction_id = $('#transaction_id').val();

            if (bank == '' || paid_amount == '' || transaction_id == '') {
                Notify('error', null, 'All fields are required.');
            } else {
                $('#pay-modal').modal('hide');
            }
        })
    </script>



    <script>
        $(document).click(function() {
            $('#show-product').css('display', 'none');
        })
        $(document).ready(function() {
            let typingTimer;
            const typingDelay = 1000; // 1 second delay

            $('#search-product').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    var keyword = $('#search-product').val();
                    $.ajax({
                        url: `{{ url('/get-product') }}`,
                        data: {
                            name: keyword
                        },
                        dataType: "JSON",
                        success: function(res) {
                            $('#show-product').html('');
                            $('#show-product').css('display', 'block');
                            res.forEach(element => {
                                $('#show-product').append(`
                                <a href="javascript:addToCart(${element.id})" onclick="relode()">
                                    <li class="list-group-item">
                                            <p>${element.name}</p>
                                    </li>
                                </a>
                            `)
                            });

                            console.log(res);
                        }
                    });
                }, typingDelay);
            });

            // Clear the timeout if the user starts typing again before the delay is over
            $('#search-product').on('keydown', function() {
                clearTimeout(typingTimer);
            });
        });
    </script>
@endpush

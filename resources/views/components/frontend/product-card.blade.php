@php
    $bn_cart_button = Illuminate\Support\Facades\DB::table('bn_cart_button')->find(1);
@endphp
<div class="product-card">
    <div class="product-img">
        <a href="{{ route('product', $product->slug) }}">
            @if ($product->images->first()->image ?? false)
                <img src="{{ asset('uploads/products/galleries/' . $product->images->first()->image ?? '') }}" class="b-1" alt="{{ $product->name }}">
            @endif

            @if ($product->quantity <= 0 && $product->is_manage_stock)
                <small class="sold-out">Stock out</small>
            @endif
        </a>
        @isset($product->details->flash_deal_title)
            @if ($product->details->flash_deal_title == '')
                <span></span>
            @else
                <span class="tag">{{ $product->details->flash_deal_title }}</span>
            @endif
        @endisset
        <ul class="product-cart">
            <li><a href="javascript:addToWishlist({{ $product->id }})"><span class="icon"><i class="fa-regular fa-heart"></i></span></a></li>
            <li><a href="javascript:buyNow({{ $product->id }})"><span class="text">{{ __('BUY NOW') }}</span></a>
            </li>
            <li><a href="javascript:addToCart({{ $product->id }})"><span class="icon"><i class="fas fa-{{ $product->quantity ? 'cart-shopping' : 'circle-xmark text-danger' }}"></i></span></a>
            </li>
        </ul>
    </div>
    <div class="product-card-details w-100">

        <h5 class="title"><a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a></h5>
        <div class="d-flex align-items-center gap-2">
            @if (hasPromotion($product->id))
                <span class="price">{{ currency(promotionPrice($product->id)) }}</span>
                <span class=""><del class="text-secondary">{{ currency($product->unit_price) }}</del> <small class="text-secondary">{{ __('- ') }} {{ round((($product->unit_price - promotionPrice($product->id)) / $product->unit_price) * 100) }}{{ __('%') }}</small></span>
            @else
                @if ($product->discount > 0)
                    <span class="price" style="font-size: 20px">{{ currency($product->sale_price) }} </span>
                    <span style="padding-top: 2px">
                        <del class="text-secondary">{{ currency($product->unit_price) }}</del>
                        <small class="text-secondary"> {{ __('- ') }}@if ($product->discount_type == 'percentage')
                                {{ $product->discount }}
                            @elseif($product->discount_type == 'fixed')
                                {{ round(($product->discount / $product->unit_price) * 100) }}
                            @endif{{ __('%') }}
                        </small>
                    </span>
                @else
                    <span class="price" style="font-size: 20px">{{ currency($product->unit_price) }}</span>
                @endif
            @endif
        </div>
        <!-- <div class="d-flex justify-content-between">
            <div class="star-rating">
                <div class="rateit" data-rateit-value="{{ productRating($product->reviews) }}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
            </div>
        </div> -->


        <div class="d-flex gap-2 mt-2">
            <a class="flex-fill"
               href="{{ $product->quantity <= 0 && $product->is_manage_stock ? 'javascript:void(0)' : 'javascript:addToCart(' . $product->id . ')' }}">
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm w-100"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Add to Cart"
                    {{ $product->quantity <= 0 && $product->is_manage_stock ? 'disabled' : null }}>
                    <i class="fas fa-shopping-cart"></i>
                </button>
            </a>

            <a class="flex-fill"
               href="{{ $product->quantity <= 0 && $product->is_manage_stock ? 'javascript:void(0)' : 'javascript:buyNow(' . $product->id . ')' }}">
                <button
                    type="button"
                    class="btn btn-danger btn-sm w-100 text-white"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Buy Now"
                    {{ $product->quantity <= 0 && $product->is_manage_stock ? 'disabled' : null }}>
                    <i class="fas fa-bolt"></i>
                </button>
            </a>
        </div>

    @if ($bn_cart_button->status)
            <a href="{{ $product->quantity <= 0 && $product->is_manage_stock ? 'javascript:void(0)' : 'javascript:addToCart(' . $product->id . ')' }}">
                <button class="btn btn-danger w-100 mt-2" style="border-radius: 0; color:white;" {{ $product->quantity <= 0 && $product->is_manage_stock ? 'disabled' : null }}>
                    <i class="fas fa-shopping-cart"></i> অর্ডার করুন
                </button>
            </a>
        @endif
    </div>

    <!-- <div> -->

    <!-- </div> -->
</div>

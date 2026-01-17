<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $campaign_data->name }}</title>
    {{--    <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" type="image/x-icon" />--}}
    <!-- fot awesome -->
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css/all.css') }}"/>
    <!-- core css -->
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/animate.css"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/owl.theme.default.css"/>
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/owl.carousel.min.css"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/select2.min.css"/>
    <!-- common css -->
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/style.css"/>
    <link rel="stylesheet" href="{{ asset('frontend/campaign/css') }}/responsive.css"/>
    @php
        $pixelCount = isset($pixels) ? count($pixels) : 0;
        $pixelsExists = isset($pixels);
    @endphp
    <script>
        console.log('[Facebook Pixel] ==========================================');
        console.log('[Facebook Pixel] Debug Info:');
        console.log('[Facebook Pixel] $pixels variable exists:', {{ $pixelsExists ? 'true' : 'false' }});
        console.log('[Facebook Pixel] Pixel count: {{ $pixelCount }}');
        @if(isset($pixels) && count($pixels) > 0)
        console.log('[Facebook Pixel] Pixels found! Initializing...');
        @else
        console.error('[Facebook Pixel] ✗ No pixels found!');
        console.error('[Facebook Pixel] $pixels is:', '{{ isset($pixels) ? "defined but empty" : "not defined" }}');
        @endif
    </script>

    @if(isset($pixels) && count($pixels) > 0)
        <!-- Facebook Pixel Code -->
        <script>
            console.log('[Facebook Pixel] ==========================================');
            console.log('[Facebook Pixel] Initializing Facebook Pixel...');
            console.log('[Facebook Pixel] Pixel count: {{ count($pixels) }}');
            @foreach($pixels as $pixel)
            console.log('[Facebook Pixel] Pixel ID: {{ $pixel->code }}');
            @endforeach

                !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');

            console.log('[Facebook Pixel] Pixel script loading...');

            // Function to initialize pixels
            function initializePixels() {
                if (typeof fbq !== 'undefined') {
                    console.log('[Facebook Pixel] ✓ Pixel script loaded successfully');
                    console.log('[Facebook Pixel] fbq function type:', typeof fbq);

                    @foreach($pixels as $pixel)
                    console.log('[Facebook Pixel] Initializing pixel: {{ $pixel->code }}');
                    try {
                        fbq('init', '{{ $pixel->code }}');
                        console.log('[Facebook Pixel] ✓ Pixel {{ $pixel->code }} initialized');
                    } catch (e) {
                        console.error('[Facebook Pixel] ✗ Error initializing pixel {{ $pixel->code }}:', e);
                    }
                    @endforeach

                    console.log('[Facebook Pixel] Tracking PageView event...');
                    try {
                        fbq('track', 'PageView');
                        console.log('[Facebook Pixel] ✓ PageView event fired successfully');
                    } catch (e) {
                        console.error('[Facebook Pixel] ✗ Error firing PageView:', e);
                    }
                } else {
                    console.error('[Facebook Pixel] ✗ ERROR: fbq is not defined!');
                    console.error('[Facebook Pixel] Possible causes:');
                    console.error('  1. Ad blocker is enabled (uBlock, AdBlock Plus, etc.)');
                    console.error('  2. Privacy extension is blocking (Privacy Badger, Ghostery, etc.)');
                    console.error('  3. Browser privacy settings blocking third-party scripts');
                    console.error('  4. Network/firewall blocking connect.facebook.net');
                    console.error('  5. Script loading error');
                }
            }

            // Try multiple times to ensure pixel loads
            var attempts = 0;
            var maxAttempts = 10;
            var checkInterval = setInterval(function () {
                attempts++;
                if (typeof fbq !== 'undefined') {
                    clearInterval(checkInterval);
                    initializePixels();
                } else if (attempts >= maxAttempts) {
                    clearInterval(checkInterval);
                    console.error('[Facebook Pixel] ✗ Pixel failed to load after ' + maxAttempts + ' attempts');
                    console.error('[Facebook Pixel] Check browser console for blocked requests');
                }
            }, 200);

            // Also try after page load
            window.addEventListener('load', function () {
                setTimeout(function () {
                    if (typeof fbq === 'undefined') {
                        console.error('[Facebook Pixel] ✗ Pixel still not loaded after page load');
                    }
                }, 1000);
            });
        </script>
        <noscript>
            @foreach($pixels as $pixel)
                <img height="1" width="1" style="display:none"
                     src="https://www.facebook.com/tr?id={{ $pixel->code }}&ev=PageView&noscript=1"/>
            @endforeach
        </noscript>
        <!-- End Facebook Pixel Code -->
    @else
        <script>
            console.warn('[Facebook Pixel] No pixels found. Check if $pixels variable is available.');
        </script>
    @endif

    <meta name="app-url" content="{{route('campaign',$campaign_data->slug)}}"/>
    <meta name="robots" content="index, follow"/>
    <meta name="description" content="{{$campaign_data->description}}"/>
    <meta name="keywords" content="{{ $campaign_data->slug }}"/>

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product"/>
    <meta name="twitter:site" content="{{$campaign_data->name}}"/>
    <meta name="twitter:title" content="{{$campaign_data->name}}"/>
    <meta name="twitter:description" content="{{ $campaign_data->description}}"/>
    <meta name="twitter:creator" content="hellodinajpur.com"/>
    <meta property="og:url" content="{{route('campaign',$campaign_data->slug)}}"/>
    <meta name="twitter:image" content="{{asset($campaign_data->image_one)}}"/>

    <!-- Open Graph data -->
    <meta property="og:title" content="{{$campaign_data->name}}"/>
    <meta property="og:type" content="product"/>
    <meta property="og:url" content="{{route('campaign',$campaign_data->slug)}}"/>
    <meta property="og:image" content="{{asset($campaign_data->image_one)}}"/>
    <meta property="og:description" content="{{ $campaign_data->description}}"/>
    <meta property="og:site_name" content="{{$campaign_data->name}}"/>

    @if(!empty($gtm_code) && count($gtm_code) > 0)
        <!-- Google Tag Manager -->
        <script>
            window.dataLayer = window.dataLayer || [];
        </script>
        @foreach($gtm_code as $gtm)
            @php
                // Handle GTM code format - remove GTM- prefix if already present
                $gtmId = str_replace('GTM-', '', $gtm->code);
            @endphp
            <script>
                (function (w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({
                        'gtm.start':
                            new Date().getTime(), event: 'gtm.js'
                    });
                    var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', 'GTM-{{ $gtmId }}');
            </script>
        @endforeach
        <!-- End Google Tag Manager -->
    @endif
    <style>
        /* Style for selected product card */
        .selected {
            border: 2px solid green; /* Change border color to green */
        }

        .countdown-container {
            text-align: center;
        }

        .counter-card {
            border: 2px dotted white; /* Dotted border */
            border-radius: 15px; /* Rounded corners */
            padding: 5px; /* Padding for the card */
            background-color: transparent; /* Slightly transparent white background */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            text-align: center; /* Center the text within each card */

        }

        .counter-card div {
            font-size: 1.2em;
            font-weight: bolder;
            color: white;
        }

        .counter-card span {
            display: block; /* Make the span block-level for better spacing */
            font-size: 0.8em; /* Font size for labels */
            color: orange;
        }

        @keyframes colorAnimation {
            0% {
                color: pink; /* Start with pink */
            }
            33% {
                color: green; /* Transition to green */
            }
            66% {
                color: red; /* Transition to red */
            }
            100% {
                color: pink; /* Return to pink */
            }
        }

        .animated-heading {
            font-size: 2em; /* Adjust font size as needed */
            font-weight: bold; /* Make the heading bold */
            animation: colorAnimation 3s linear infinite; /* Apply the animation */


        }

        .form_inn {
            padding: 10px;
        }

        @media (max-width: 992px) {
            .campro_inn, .cont_inner, .cont_num, .discount_inn {
                padding: 10px !important; /* Add 10px padding for tablet and smaller devices */
                width: 100%;
            }

            .discount_inn {
                margin: 10px 0 0 0;
            }

            .campro_inn h2 {
                font-size: 20px;
            }
        }

    </style>
    <style>
        .button-3d {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .button-3d:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }


    </style>
    <style>
        .button-animated-border {
            position: relative;
            overflow: hidden;
            border: 3px solid white; /* Initial border */
            border-radius: 10px; /* Optional: for rounded corners */
            transition: color 0.3s ease; /* Transition for text color */
            animation: border-animation 3s linear infinite; /* Animation */
        }

        @keyframes border-animation {
            0% {
                border-color: white; /* Transparent at start */
                transform: scale(0.95); /* Initial scale */
            }
            25% {
                border-color: yellow; /* Fill with white */
                transform: scale(1); /* Slightly grow */
            }
            50% {
                border-color: white; /* Transparent in middle */
                transform: scale(0.95); /* Back to original scale */
            }
            75% {
                border-color: yellow; /* Fill with white again */
                transform: scale(1); /* Slightly grow again */
            }
            100% {
                border-color: white; /* Transparent at end */
                transform: scale(0.95); /* Back to original scale */
            }
        }

        .button-animated-border:hover {
            color: #fff; /* Change text color on hover */
        }
    </style>

    {{--    {!! $generalsetting->header_code !!}--}}
</head>

<body>
@php
    //    $subtotal = Cart::instance('shopping')->subtotal();
    //    $subtotal=str_replace(',','',$subtotal);
    //    $subtotal=str_replace('.00', '',$subtotal);
    //    $shipping = Session::get('shipping')?Session::get('shipping'):0;
@endphp
<section style="background-image: radial-gradient(at center center, #139525 28%, #0E320F 79%)">
    <div class="container py-2 py-md-4">
        <div class="row gy-2">
            <div class="col-md-7">
                <h4 class="text-light text-center py-2 py-md-4 fw-bolder">{!! $campaign_data->top_title_1  !!} <span
                        class="text-warning"> {!! $campaign_data->top_title_2  !!}</span></h4>
            </div>
            <div class="col-md-5">
                <div class="countdown-container">
                    <div class="countdown" id="countdown">
                        <div class="row g-1">
                            <div class="col-3">
                                <div class="counter-card">
                                    <div id="days"></div>
                                    <span>Days</span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="counter-card">
                                    <div id="hours"></div>
                                    <span>Hours</span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="counter-card">
                                    <div id="minutes"></div>
                                    <span>Minutes</span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="counter-card">
                                    <div id="seconds"></div>
                                    <span>Seconds</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container py-2 py-md-4">
        <div class="py-2 py-md-4  rounded" style="border:2px dashed green">
            <h2 class="animated-heading text-center">{!! $campaign_data->heading_1 !!}</h2>
        </div>
    </div>
</section>

<div class="col-sm-12">
    <div class="ord_btn">
        <a href="#order_form" class="cam_order_now" id="cam_order_now"> অর্ডার করতে ক্লিক করুন <i
                class="fa-solid fa-hand-point-right"></i> </a>
    </div>
</div>
<section>
    <div class="container py-2 py-md-4">
        <div class="row gy-2">
            @if($campaign_data->image_one)
                <div class="col-sm-6">
                    <img class="img-fluid shadow" src="{{asset('uploads/'.$campaign_data->image_one)}}">
                </div>
            @endif
            @if($campaign_data->image_two)
                <div class="col-sm-6">
                    <img class="img-fluid shadow" src="{{asset('uploads/'.$campaign_data->image_two)}}">
                </div>
            @endif
        </div>
    </div>
</section>
<section>
    <div class="container py-2 py-md-4">
        <div class="row gy-2">
            @if($campaign_data->feature_1)
                <div class="col-sm-6">
                    <div class="py-2 py-md-4  rounded" style="border:1px dashed green">
                        <h2 class="text-center">{!! $campaign_data->feature_1 !!}</h2>
                    </div>
                </div>
            @endif
            @if($campaign_data->feature_1)
                <div class="col-sm-6">
                    <div class="py-2 py-md-4  rounded" style="border:1px dashed green">
                        <h2 class="text-center">{!! $campaign_data->feature_2 !!}</h2>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
<section>
    <div class="container py-2">
        <div class="py-2 py-md-4  rounded" style="border:2px dashed green">
            <h2 class="animated-heading text-center">{!! $campaign_data->heading_2 !!}</h2>
        </div>
    </div>
</section>
<section>
    <div class="container py-2 ">
        <div class="py-2 py-md-4  rounded" style="border:2px dashed green">
            <h2 class="animated-heading text-center">{!! $campaign_data->heading_3 !!}</h2>
        </div>
    </div>
</section>

@if($campaign_data->video!=null)
    <section class="camp_video_sec">
        <div class="container">

            <div class="row justify-content-center gy-2 gy-md-4">
                <div class="col-md-8">
                    <h2 class="p-2 py-md-3 rounded text-center"
                        style="background-color:black;border:green 2px solid;color:white;font-weight:bolder">প্রডাক্টের
                        "ভিডিও দেখুন"</h2>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="camp_vid rounded" style="border:5px solid red">
                        <iframe width="100%" height="480"
                                src="https://www.youtube.com/embed/{{$campaign_data->video}}"
                                title="{{$campaign_data->banner_title}}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen="">
                        </iframe>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="ord_btn">
                        <a href="#order_form" class="cam_order_now" id="cam_order_now"> অর্ডার করতে ক্লিক করুন <i
                                class="fa-solid fa-hand-point-right"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<section class="py-2 py-md-4" style="background: linear-gradient(to bottom, #FAF4B3, #ECC7CF);">
    <div class="container my-2 my-md-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center p-2 p-md-4 rounded" style="background-color:#FBEFF7;border:2px dashed #F1ACE7">
                    আমাদের থেকে বিস্তারিত জানতে এই নাম্বারে কল করুন {{maanAppearance('hotline_number') ?? 'N/A'}}</h2>
                <div class="row justify-content-center my-2 my-md-4 gy-2">
                    <div class="col-md-6 custom_btn">
                        <div class="shadow-lg">
                            <a href="tel:{{ maanAppearance('hotline_number') ?? 'N/A'}}"
                               class="btn btn-danger btn-lg d-block py-md-3 fs-2 fw-bolder button-3d button-animated-border">
                                <i class="fa-solid fa-phone"></i> আমাদের কল করুন </a>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="shadow-lg">
                            <a href="https://wa.me/{{$contact->whatsapp ?? 'N/A'}}"
                               class="btn btn-success btn-lg d-block py-md-3 fs-2 text-light fw-bolder button-3d button-animated-border">
                                <i class="fa-brands fa-whatsapp"></i> হোয়াটসঅ্যাপ
                            </a>
                        </div>

                    </div>
                </div>

                <h2 class="text-center p-2 p-md-4 rounded"
                    style="background-color:#FBEFF7;border:2px dashed #F1ACE7">{!! $campaign_data->heading_4 !!}</h2>

            </div>
        </div>
    </div>
</section>

@if(optional($campaign_data)->short_description && strlen($campaign_data->short_description) > 15 ||
optional($campaign_data)->description && strlen($campaign_data->description) > 15)
    <section class="rules_sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h2>বিস্তারিত</h2>
                            {!! $campaign_data->short_description !!}
                            <br>
                            <br>
                            {!!$campaign_data->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="campro_inn">
                    <div class="campro_head">
                        <h2>{{$campaign_data->name}}</h2>
                    </div>

                    <div class="campro_img_slider owl-carousel">
                        @if($campaign_data->image_one)
                            <div class="campro_img_item">
                                <img src="{{asset('uploads/'.$campaign_data->image_one)}}" alt="">
                            </div>
                        @endif
                        @if($campaign_data->image_two)
                            <div class="campro_img_item">
                                <img src="{{asset('uploads/'.$campaign_data->image_two)}}" alt="">
                            </div>
                        @endif
                        @if($campaign_data->image_three)
                            <div class="campro_img_item">
                                <img src="{{asset('uploads/'.$campaign_data->image_three)}}" alt="">
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-12">
                        <div class="ord_btn">
                            <a href="#order_form" class="cam_order_now" id="cam_order_now"> অর্ডার করতে ক্লিক করুন <i
                                    class="fa-solid fa-hand-point-right"></i> </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="rev_inn">

                    <h2 class="campaign_offer">{{$campaign_data->review}}</h2>

                    <div class="review_slider owl-carousel">
                        @foreach($campaign_data->images as $key=>$value)
                            <div class="review_item">
                                <img src="{{asset($value->image)}}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="col-sm-12">
                        <div class="ord_btn">
                            <a href="#order_form" class="cam_order_now" id="cam_order_now"> অর্ডার করতে ক্লিক করুন <i
                                    class="fa-solid fa-hand-point-right"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="form_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="form_inn">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 class="campaign_offer">অফারটি সীমিত সময়ের জন্য, তাই অফার শেষ হওয়ার আগেই অর্ডার
                                    করুন</h2>
                                @if($campaign_data->note)
                                    <p class="my-1 text-center">
                                        {!! $campaign_data->note !!}
                                    </p>
                                @endif
                            </div>

                        </div>
                        <div class="row order_by">
                            <div class="col-lg-7 cust-order-1">
                                <div class="cart_details">
                                    @if($products->count()>1)
                                        <div class="card mb-2 ">
                                            <div class="card-header">
                                                <h5 class="potro_font">একটি পণ্য সিলেক্ট করুনণ </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    @php
                                                        $firstProduct = null;
                                                        $shipping_areas = \App\Models\ShippingArea::where('status', 1)->orderBy('id', 'asc')->get();
                                                    @endphp
                                                    @foreach($products as $product)
                                                        @php
                                                            $firstProduct = $products[0]
                                                        @endphp
                                                        <div class="col-md-3 col-6">
                                                            <!-- Adjusted column width for smaller cards -->
                                                            <div class="border shadow">
                                                                <!-- Wrap the card with form-check for better usability -->
                                                                <input type="radio"
                                                                       name="product"
                                                                       id="product_{{ $product->id }}"
                                                                       value="{{ $product->id }}"
                                                                       {{ $loop->first ? 'checked' : '' }}
                                                                       style="display:none"

                                                                       data-pId="{{ $product->id }}"
                                                                       data-name="{{ $product->name }}"
                                                                       data-slug="{{ $product->slug }}"
                                                                       data-price="{{ $product->sale_price }}"
                                                                       data-image="{{ asset('uploads/products/galleries/'.$product->image->image) }}"

                                                                       onchange="updateCart(this)"
                                                                >
                                                                <label for="product_{{ $product->id }}"
                                                                       class="card shadow-sm product-card {{ $loop->first ? 'selected' : '' }}"
                                                                       style="cursor:pointer">

                                                                    <img src="{{ asset('uploads/products/galleries/'.$product->image->image) }}"
                                                                         class="card-img-top"
                                                                         style="height:100px;object-fit:cover">

                                                                    <div class="card-body p-1 text-center">
                                                                        <div class="card-title">{{ Str::limit($product->name, 20) }}</div>
                                                                        <div class="card-text mb-1">
                                                                            ৳{{ $product->sale_price }}
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="potro_font">পণ্যের বিবরণ </h5>
                                        </div>
                                        <div class="card-body cartlist  table-responsive">
                                            <table
                                                class="cart_table table table-bordered table-striped text-center mb-0">
                                                <thead>
                                                <tr>

                                                    <th style="width: 40%;">প্রোডাক্ট</th>
                                                    <th style="width: 20%;">পরিমাণ</th>
                                                    <th style="width: 20%;">মূল্য</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @if(!empty($firstProduct))
                                                    <tr>
                                                        <td class="text-left">
                                                            <a id="cart_product_link" style="font-size: 14px;" href="{{route('product',$firstProduct->slug)}}">
                                                                <img id="cart_product_img" src="{{ asset('uploads/products/galleries/'.$firstProduct->image->image) }}" height="30" width="30"> {{Str::limit($value->name,20)}}
                                                            </a>

{{--                                                            @if($firstProduct && ($firstProduct->productstock->color->isNotEmpty() || $firstProduct->productstock->size->isNotEmpty()))--}}
{{--                                                                <div class="row g-1 mt-2">--}}
{{--                                                                    <!-- Size Selector -->--}}
{{--                                                                    @if($product->productstock->size->isNotEmpty())--}}
{{--                                                                        <div class="col-6">--}}

{{--                                                                            <select id="size-selector-{{ $firstProduct->id }}" class="form-select form-select-sm cart-size-selector" data-id="{{ $firstProduct->id }}">--}}
{{--                                                                                <option>Select an option</option>--}}
{{--                                                                                @foreach($firstProduct->productstock as $pSize)--}}
{{--                                                                                    <option value="{{ $pSize->size->name }}">--}}
{{--                                                                                        {{ $pSize->size->name }}--}}
{{--                                                                                    </option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
{{--                                                                            <label--}}
{{--                                                                                for="size-selector-{{ $firstProduct->id }}"--}}
{{--                                                                                class="form-label text-muted text-start"--}}
{{--                                                                                style="font-size: 0.875rem;">--}}
{{--                                                                                Size:--}}
{{--                                                                                @if($value->options->product_size)--}}
{{--                                                                                    {{$value->options->product_size}}--}}
{{--                                                                                @endif--}}
{{--                                                                            </label>--}}
{{--                                                                        </div>--}}
{{--                                                                    @endif--}}

{{--                                                                    <!-- Color Selector -->--}}
{{--                                                                    @if($firstProduct->productstock->color->isNotEmpty())--}}
{{--                                                                        <div class="col-6">--}}
{{--                                                                            <select--}}
{{--                                                                                id="color-selector-{{ $firstProduct->rowId }}"--}}
{{--                                                                                class="form-select form-select-sm cart-color-selector"--}}
{{--                                                                                data-id="{{ $firstProduct->rowId }}">--}}
{{--                                                                                <option>Select an option</option>--}}
{{--                                                                                @foreach($firstProduct->productstock as $pColor)--}}
{{--                                                                                    <option value="{{ $pColor->color->name }}">--}}
{{--                                                                                        {{ $pColor->color->name }}--}}
{{--                                                                                    </option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
{{--                                                                            <label--}}
{{--                                                                                for="color-selector-{{ $firstProduct->id }}"--}}
{{--                                                                                class="form-label text-muted text-start"--}}
{{--                                                                                style="font-size: 0.875rem;">Color:--}}
{{--                                                                                @if($value->options->product_color)--}}
{{--                                                                                    {{ $value->options->product_color }}--}}
{{--                                                                                @endif--}}
{{--                                                                            </label>--}}
{{--                                                                        </div>--}}
{{--                                                                    @endif--}}
{{--                                                                </div>--}}
{{--                                                            @endif--}}
                                                        </td>
                                                        <td width="15%" class="cart_qty">
                                                            <div class="qty-cart vcart-qty">
                                                                <div class="quantity">
                                                                    <button class="minus cart_decrement">-</button>

                                                                    <input type="text"
                                                                           id="cart_qty"
                                                                           value="1"
                                                                           readonly
                                                                           data-price="{{ $firstProduct->sale_price }}" />

                                                                    <button class="plus cart_increment">+</button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>৳ <span id="unit_price">{{$firstProduct->sale_price}}</span></td>

                                                    </tr>
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="text-end px-4">মোট</th>
                                                        <td>
                                                            ৳ <strong id="net_total">{{ $firstProduct->sale_price ?? 0 }}</strong>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $getFirstCharge = !empty( $shipping_areas[0]) ?  $shipping_areas[0]->charge : 70
                                                    @endphp
                                                    <tr>
                                                        <th colspan="2" class="text-end px-4">ডেলিভারি চার্জ</th>
                                                        <td>
                                                            ৳ <strong id="shipping_cost">{{ $getFirstCharge }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" class="text-end px-4">সর্বমোট</th>
                                                        <td>
                                                            ৳ <strong id="grand_total">
                                                                {{ ($firstProduct->sale_price ?? 0) + ($getFirstCharge ?? 0) }}
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 cus-order-2">
                                <div class="checkout-shipping" id="order_form">
                                    <form action="{{route('customer.ordersave')}}" method="POST" data-parsley-validate="">
                                        @csrf
                                        <input type="hidden" id="productId" name="pId" value="{{$firstProduct->id}}">
                                        <input type="hidden" id="productQuantity" name="quantity" value="1">
                                        <input type="hidden" id="cartSubTotal" name="subtotal" value="{{$firstProduct->sale_price ?? 0}}">
                                        <input type="hidden" id="deliveryCharge" name="shipping_cost" value="{{$getFirstCharge}}">
                                        <input type="hidden" id="totalAmount" name="totalAmount" value="{{ ($firstProduct->sale_price ?? 0) + ($getFirstCharge ?? 0) }}">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="potro_font">আপনার ইনফরমেশন দিন </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for="name">আপনার নাম লিখুন * </label>
                                                            <input type="text" id="name"
                                                                   class="form-control @error('name') is-invalid @enderror"
                                                                   name="name" value="{{old('name')}}" placeholder="নাম"
                                                                   required>
                                                            @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- col-end -->
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for="phone">আপনার মোবাইল লিখুন *</label>
                                                            <input type="number" minlength="11"
                                                                   maxlength="11" pattern="0[0-9]+"
                                                                   title="please enter number only and 0 must first character"
                                                                   title="Please enter an 11-digit number." id="phone"
                                                                   class="form-control @error('phone') is-invalid @enderror"
                                                                   name="phone" value="{{old('phone')}}"
                                                                   placeholder="+৮৮ বাদে ১১ সংখ্যা " required>
                                                            @error('phone')
                                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- col-end -->
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for="address">আপনার ঠিকানা লিখুন *</label>
                                                            <input type="text" id="address"
                                                                   class="form-control @error('address') is-invalid @enderror"
                                                                   placeholder="জেলা, থানা, গ্রাম " name="address"
                                                                   value="{{old('address')}}" required>
                                                            @error('address')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for="area">আপনার এরিয়া সিলেক্ট করুন *</label>
                                                            <select type="area" id="area"
                                                                    class="form-control @error('area') is-invalid @enderror"
                                                                    name="area" required>
                                                                @if(!empty($shipping_areas))
                                                                    @foreach($shipping_areas as $key=>$shipping_area)
                                                                        <option
                                                                            value="{{$shipping_area->charge}}">{{ $shipping_area->name }}
                                                                            - {{ $shipping_area->charge }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @error('area')
                                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- col-end -->
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <button class="order_place order_place" type="submit">অর্ডার
                                                                কন্ফার্ম করুন
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- card end -->
                                    </form>
                                </div>
                                @if($campaign_data->billing_details)
                                    <p class="my-1 text-center">
                                        {!! $campaign_data->billing_details !!}
                                    </p>
                                @endif
                            </div>
                            <!-- col end -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="{{ asset('frontend/campaign/js') }}/jquery-2.1.4.min.js"></script>
<script src="{{ asset('frontend/campaign/js') }}/all.js"></script>
<script src="{{ asset('frontend/campaign/js') }}/bootstrap.min.js"></script>
<script src="{{ asset('frontend/campaign/js') }}/owl.carousel.min.js"></script>
<script src="{{ asset('frontend/campaign/js') }}/select2.min.js"></script>
<script src="{{ asset('frontend/campaign/js') }}/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('message'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: "{{ session('alert-type') }}",
            title: "{{ session('message') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
@endif

<!-- bootstrap js -->
<script>
    $(document).ready(function () {
        $(".owl-carousel").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            items: 1,
        });
        $('.owl-nav').remove();
    });
</script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
<script>
    $("#area").on("change", function () {
        let shippingCharge = parseFloat($(this).val());
        let qty = parseInt($("#cart_qty").val());
        let price = parseFloat($("#cart_qty").data("price"));

        let netTotal = price * qty;
        let grandTotal = netTotal + shippingCharge;

        // Update UI
        $("#shipping_cost").text(shippingCharge.toFixed(2));
        $("#net_total").text(netTotal.toFixed(2));
        $("#grand_total").text(grandTotal.toFixed(2));
    });
</script>
<script>
    {{--$(".cart_remove").on("click", function () {--}}
    {{--    var id = $(this).data("id");--}}
    {{--    $("#loading").show();--}}
    {{--    if (id) {--}}
    {{--        $.ajax({--}}
    {{--            type: "GET",--}}
    {{--            data: {id: id},--}}
    {{--            url: "{{route('cart.remove')}}",--}}
    {{--            success: function (data) {--}}
    {{--                if (data) {--}}
    {{--                    $(".cartlist").html(data);--}}
    {{--                    $("#loading").hide();--}}
    {{--                    return cart_count() + mobile_cart() + cart_summary();--}}
    {{--                }--}}
    {{--            },--}}
    {{--        });--}}
    {{--    }--}}
    {{--});--}}

    function updateTotals(qty) {
        let price = parseFloat($("#cart_qty").data("price"));
        let shipping = parseFloat($("#shipping_cost").text());

        let netTotal = price * qty;
        let grandTotal = netTotal + shipping;

        $("#net_total").text(netTotal.toFixed(2));
        $("#grand_total").text(grandTotal.toFixed(2));

        $("#cartSubTotal").val(netTotal.toFixed(2));
        $("#totalAmount").val(grandTotal.toFixed(2));
        $("#productQuantity").val(qty);
    }

    $(".cart_increment").on("click", function () {
        let qtyInput = $("#cart_qty");
        let qty = parseInt(qtyInput.val()) + 1;

        qtyInput.val(qty);
        updateTotals(qty);
    });


    $(".cart_decrement").on("click", function () {
        let qtyInput = $("#cart_qty");
        let qty = parseInt(qtyInput.val());

        if (qty > 1) {
            qty--;
            qtyInput.val(qty);
            updateTotals(qty);
        }
    });

</script>
<script>
    $('.review_slider').owlCarousel({
        dots: false,
        arrow: false,
        autoplay: true,
        loop: true,
        margin: 10,
        smartSpeed: 1000,
        mouseDrag: true,
        touchDrag: true,
        items: 6,
        responsiveClass: true,
        responsive: {
            300: {
                items: 1,
            },
            480: {
                items: 2,
            },
            768: {
                items: 5,
            },
            1170: {
                items: 5,
            },
        }
    });
</script>

<script>
    $('.campro_img_slider').owlCarousel({
        dots: false,
        arrow: false,
        autoplay: true,
        loop: true,
        margin: 10,
        smartSpeed: 1000,
        mouseDrag: true,
        touchDrag: true,
        items: 3,
        responsiveClass: true,
        responsive: {
            300: {
                items: 1,
            },
            480: {
                items: 2,
            },
            768: {
                items: 3,
            },
            1170: {
                items: 3,
            },
        }
    });
</script>
<script>
    // Set the deadline from the campaign data
    const deadline = new Date("{{ $campaign_data->deadline }}").getTime();

    // Update the countdown every 1 second
    const x = setInterval(function () {
        // Get current date and time
        const now = new Date().getTime();

        // Calculate the distance between now and the deadline
        const distance = deadline - now;

        // Time calculations for days, hours, minutes and seconds
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the respective elements
        document.getElementById("days").innerHTML = days;
        document.getElementById("hours").innerHTML = hours;
        document.getElementById("minutes").innerHTML = minutes;
        document.getElementById("seconds").innerHTML = seconds;

        // If the countdown is over, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "EXPIRED";
        }
    }, 1000);
    // Event listener for size selector change
    $('.cart-size-selector').on('change', function () {
        var rowId = $(this).data('id'); // Get the row ID
        var selectedSize = $(this).val(); // Get the selected size

        if (rowId) {
            $.ajax({
                type: "GET", // Change to GET if your route accepts GET requests
                data: {
                    'id': rowId,
                    'product_size': selectedSize // New size to update
                },
                url: "{{ route('cart.update') }}", // Use the same route for updating size
                success: function (data) {
                    if (data) {
                        $(".cartlist").html(data); // Update the cart list UI with new data
                        return cart_count(); // Update the cart count
                    }
                },
                error: function () {
                    alert('An error occurred while updating the size. Please try again.');
                }
            });
        }
    });


    // Event listener for color selector change
    $('.cart-color-selector').on('change', function () {
        var rowId = $(this).data('id'); // Get the row ID
        var selectedColor = $(this).val(); // Get the selected color

        if (rowId) {
            $.ajax({
                type: "GET", // Change to GET if your route accepts GET requests
                data: {
                    'id': rowId,
                    'product_color': selectedColor // New size to update
                },
                url: "{{ route('cart.update') }}", // Use the same route for updating size
                success: function (data) {
                    if (data) {
                        $(".cartlist").html(data); // Update the cart list UI with new data
                        return cart_count(); // Update the cart count
                    }
                },
                error: function () {
                    alert('An error occurred while updating the size. Please try again.');
                }
            });
        }
    });
</script>
<!--Data Layer Start - GTM & Facebook Pixel-->
<script type="text/javascript">
    // Initialize dataLayer for GTM
    window.dataLayer = window.dataLayer || [];
    console.log('[GTM] dataLayer initialized');

    // Clear previous ecommerce data
    dataLayer.push({
        ecommerce: null
    });

    // GTM: PageView Event
    console.log('[GTM] Tracking page_view event');
    dataLayer.push({
        'event': 'page_view',
        'page_title': '{{ $campaign_data->name }}',
        'page_location': '{{ request()->fullUrl() }}',
        'page_path': '{{ request()->path() }}'
    });
    console.log('[GTM] page_view event fired');

    // Check if Facebook Pixel is loaded
    setTimeout(function () {
        if (typeof fbq !== 'undefined') {
            console.log('[Facebook Pixel] Status: LOADED ✓');
            console.log('[Facebook Pixel] fbq function available:', typeof fbq);
            console.log('[Facebook Pixel] You can test pixel by running: fbq("track", "PageView")');
        } else {
            console.error('[Facebook Pixel] Status: NOT LOADED ✗');
            console.error('[Facebook Pixel] Possible reasons:');
            console.error('  1. Ad blocker is enabled');
            console.error('  2. Privacy extension is blocking the pixel');
            console.error('  3. Network issue preventing pixel from loading');
            console.error('  4. Pixel code not properly initialized');
            console.error('[Facebook Pixel] Check Network tab for blocked requests to connect.facebook.net');
        }
    }, 500);

    // Helper function to check pixel status (can be called from console)
    window.checkPixelStatus = function () {
        console.log('[Facebook Pixel] ========== PIXEL STATUS CHECK ==========');
        if (typeof fbq !== 'undefined') {
            console.log('✓ fbq is defined');
            console.log('✓ Pixel is loaded');
            try {
                fbq('track', 'PageView');
                console.log('✓ Test PageView event fired successfully');
            } catch (e) {
                console.error('✗ Error firing test event:', e);
            }
        } else {
            console.error('✗ fbq is NOT defined');
            console.error('✗ Pixel is NOT loaded');
            console.error('Check if ad blockers or privacy extensions are enabled');
        }
        console.log('[Facebook Pixel] ==========================================');
    };

    console.log('[Facebook Pixel] Type checkPixelStatus() in console to verify pixel');
</script>

<script type="text/javascript">
    @php
        $cartAvailable = class_exists(\Gloudemans\Shoppingcart\Facades\Cart::class);
    @endphp
    // View Cart Event - Fire when cart section is visible
    @if($cartAvailable && Cart::instance('shopping')->count() > 0)
    $(document).ready(function () {
        // Clear previous ecommerce object
        dataLayer.push({ecommerce: null});

        // Calculate cart total
        var cartTotal = 0;
        var cartItems = [];
        @foreach(Cart::instance('shopping')->content() as $cartInfo)
        var itemTotal = {{ $cartInfo->price }} * {{ $cartInfo->qty ?? 1 }};
        cartTotal += itemTotal;
        cartItems.push({
            item_name: "{{ addslashes($cartInfo->name) }}",
            item_id: "{{ $cartInfo->id }}",
            price: {{ $cartInfo->price }},
            item_brand: "{{ $cartInfo->options->brand ?? '' }}",
            item_category: "{{ $cartInfo->options->category ?? '' }}",
            item_size: "{{ $cartInfo->options->product_size ?? '' }}",
            item_color: "{{ $cartInfo->options->product_color ?? '' }}",
            currency: "BDT",
            quantity: {{ $cartInfo->qty ?? 1 }}
        });
        @endforeach

        // GTM: View Cart Event
        console.log('[GTM] Tracking view_cart event');
        console.log('[GTM] View Cart data:', {
            currency: "BDT",
            value: cartTotal,
            items: cartItems
        });
        dataLayer.push({
            event: "view_cart",
            ecommerce: {
                currency: "BDT",
                value: cartTotal,
                items: cartItems
            }
        });
        console.log('[GTM] view_cart event fired');

        // Facebook Pixel: ViewCart Event
        @if(isset($pixels) && count($pixels) > 0)
        if (typeof fbq !== 'undefined') {
            console.log('[Facebook Pixel] Tracking ViewCart event');
            console.log('[Facebook Pixel] ViewCart data:', {
                content_type: 'product',
                value: cartTotal,
                currency: 'BDT',
                num_items: {{ Cart::instance('shopping')->count() }}
            });
            fbq('track', 'ViewCart', {
                content_type: 'product',
                value: cartTotal,
                currency: 'BDT',
                num_items: {{ Cart::instance('shopping')->count() }},
                contents: [
                        @foreach(Cart::instance('shopping')->content() as $cartInfo)
                    {
                        id: "{{ $cartInfo->id }}",
                        quantity: {{ $cartInfo->qty ?? 1 }},
                        item_price: {{ $cartInfo->price }}
                    },
                    @endforeach
                ]
            });
            console.log('[Facebook Pixel] ViewCart event fired');
        } else {
            console.error('[Facebook Pixel] ERROR: fbq is not defined. Cannot fire ViewCart event.');
        }
        @else
        console.warn('[Facebook Pixel] No pixels configured. ViewCart event skipped.');
        @endif
    });
    @endif
</script>

<script type="text/javascript">
    // Begin Checkout Event - Fire when order form is visible
    @php
        $cartAvailable = class_exists(\Gloudemans\Shoppingcart\Facades\Cart::class);
    @endphp
    @if($cartAvailable && Cart::instance('shopping')->count() > 0)
    $(document).ready(function () {
        // Check if order form is in viewport
        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        // Fire begin_checkout when order form comes into view
        var orderForm = document.getElementById('order_form');
        var checkoutFired = false;

        function checkCheckout() {
            if (!checkoutFired && orderForm && isElementInViewport(orderForm)) {
                checkoutFired = true;

                // Clear previous ecommerce object
                dataLayer.push({ecommerce: null});

                // Calculate checkout total
                var checkoutTotal = 0;
                var checkoutItems = [];
                @foreach(Cart::instance('shopping')->content() as $cartInfo)
                var itemTotal = {{ $cartInfo->price }} * {{ $cartInfo->qty ?? 1 }};
                checkoutTotal += itemTotal;
                checkoutItems.push({
                    item_name: "{{ addslashes($cartInfo->name) }}",
                    item_id: "{{ $cartInfo->id }}",
                    price: {{ $cartInfo->price }},
                    item_brand: "{{ $cartInfo->options->brand ?? '' }}",
                    item_category: "{{ $cartInfo->options->category ?? '' }}",
                    item_size: "{{ $cartInfo->options->product_size ?? '' }}",
                    item_color: "{{ $cartInfo->options->product_color ?? '' }}",
                    currency: "BDT",
                    quantity: {{ $cartInfo->qty ?? 1 }}
                });
                @endforeach

                // GTM: Begin Checkout Event
                console.log('[GTM] Tracking begin_checkout event');
                console.log('[GTM] Begin Checkout data:', {
                    currency: "BDT",
                    value: checkoutTotal,
                    items: checkoutItems
                });
                dataLayer.push({
                    event: "begin_checkout",
                    ecommerce: {
                        currency: "BDT",
                        value: checkoutTotal,
                        items: checkoutItems
                    }
                });
                console.log('[GTM] begin_checkout event fired');

                // Facebook Pixel: InitiateCheckout Event
                @if(isset($pixels) && count($pixels) > 0)
                if (typeof fbq !== 'undefined') {
                    console.log('[Facebook Pixel] Tracking InitiateCheckout event');
                    console.log('[Facebook Pixel] InitiateCheckout data:', {
                        content_type: 'product',
                        value: checkoutTotal,
                        currency: 'BDT',
                        num_items: {{ Cart::instance('shopping')->count() }}
                    });
                    fbq('track', 'InitiateCheckout', {
                        content_type: 'product',
                        value: checkoutTotal,
                        currency: 'BDT',
                        num_items: {{ Cart::instance('shopping')->count() }},
                        contents: [
                                @foreach(Cart::instance('shopping')->content() as $cartInfo)
                            {
                                id: "{{ $cartInfo->id }}",
                                quantity: {{ $cartInfo->qty ?? 1 }},
                                item_price: {{ $cartInfo->price }}
                            },
                            @endforeach
                        ]
                    });
                    console.log('[Facebook Pixel] InitiateCheckout event fired');
                } else {
                    console.error('[Facebook Pixel] ERROR: fbq is not defined. Cannot fire InitiateCheckout event.');
                }
                @else
                console.warn('[Facebook Pixel] No pixels configured. InitiateCheckout event skipped.');
                @endif
            }
        }

        // Check on scroll and on load
        window.addEventListener('scroll', checkCheckout);
        window.addEventListener('load', checkCheckout);
        setTimeout(checkCheckout, 1000); // Also check after 1 second
    });
    @endif
</script>

<script>
    // Update the cart and highlight the selected card
    function recalculateCart() {
        let qty = parseInt($("#cart_qty").val());
        let price = parseFloat($("#cart_qty").data("price"));
        let shipping = parseFloat($("#shipping_cost").text());

        let netTotal = price * qty;
        let grandTotal = netTotal + shipping;

        $("#net_total").text(netTotal.toFixed(2));
        $("#grand_total").text(grandTotal.toFixed(2));

        $("#cartSubTotal").val(netTotal.toFixed(2));
        $("#deliveryCharge").val(shipping.toFixed(2));
        $("#totalAmount").val(grandTotal.toFixed(2));
    }

    // Product change
    function updateCart(el) {
        let price = $(el).data("price");
        let name = $(el).data("name");
        let slug = $(el).data("slug");
        let image = $(el).data("image");
        let productId = $(el).val();

        // Reset qty
        $("#cart_qty").val(1).data("price", price);

        // Update product info
        $("#cart_product_name").text(name.substring(0, 20));
        $("#cart_product_img").attr("src", image);
        $("#cart_product_link").attr("href", "/product/" + slug);

        $("#productId").val(productId);

        // Update price
        $("#unit_price").text(price);

        recalculateCart();

        // UI highlight
        $(".card.product-card").removeClass("selected");
        $(el).next("label").addClass("selected");
    }

    // Automatically highlight the first card on page load
    document.addEventListener('DOMContentLoaded', function () {
        const firstCard = document.querySelector('.card.product-card');
        if (firstCard) {
            firstCard.classList.add('selected');
        }
    });
</script>
@if(!empty($gtm_code) && count($gtm_code) > 0)
    <!-- Google Tag Manager (noscript) -->
    @foreach($gtm_code as $gtm)
        @php
            $gtmId = str_replace('GTM-', '', $gtm->code);
        @endphp
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-{{ $gtmId }}" height="0" width="0"
                    style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endforeach
    <!-- End Google Tag Manager (noscript) -->
@endif
</body>
</html>

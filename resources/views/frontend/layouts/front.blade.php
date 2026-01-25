<!DOCTYPE html>
<html dir="{{ lang('direction') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="keywords" content="{{ maanAppearance('keywords') }}" />
    <meta name="description" content="{{ maanAppearance('meta_desc') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Twitter Card Data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@yield('meta_url')">
    <meta name="twitter:title" content="@yield('meta_title','CHINA HUB')">
    <meta name="twitter:description" content="@yield('meta_description',maanAppearance('meta_desc'))">
    <meta name="twitter:creator" content="Maan Theme">
    <meta name="twitter:image" content="{{ asset('uploads/products/meta_image') }}/@yield('meta_image')">
    <meta name="twitter:data1" content="@yield('meta_price')">
    <meta name="twitter:label1" content="Price">
    <meta name="twitter:data2" content="@yield('meta_color')">
    <meta name="twitter:label2" content="Color">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph Data -->
    <meta property="og:title" content="@yield('meta_title','CHINA HUB')"/>
    <meta property="og:type" content="eCommerce"/>
    <meta property="og:image" content="{{ asset('uploads/products/meta_image') }}/@yield('meta_image')"/>
    <meta property="og:site_name" content="{{ config('app.name') }}"/>
    <meta property="og:url" content="@yield('meta_url')"/>
    <meta property="og:description" content="@yield('meta_description',maanAppearance('meta_desc'))"/>

    <title>@yield('title')</title>

    <!-- Apple Favicon -->
    <link rel="apple-touch-icon" href="{{ asset('uploads') }}/{{ maanAppearance('favicon') }}">
    <!-- All Device Favicon -->
    <link rel="icon" href="{{ asset('uploads') }}/{{ maanAppearance('favicon') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.rtl.min.css" integrity="sha384-dc2NSrAXbAkjrdm9IYrX10fQq9SDG6Vjz7nQVKdKcJl3pC+k37e7qJR5MVSCS+wR" crossorigin="anonymous">
    <!-- Swiper -->
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper.min.css') }}">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <!-- Nice Select -->
    <link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}">
    <!-- RateIt -->
    <link rel="stylesheet" href="{{ asset('frontend/rateit/rateit.css') }}">
    <!-- Normalize -->
    <link rel="stylesheet" href="{{ asset('frontend/css/normalize.css') }}">
    <!-- Default -->
    <link rel="stylesheet" href="{{ asset('frontend/css/default.css') }}">
    <!-- uniBox -->
    <link rel="stylesheet" href="{{ asset('frontend/uniBox/css/unibox.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="{{ asset('frontend/jquery-ui/jquery-ui.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('frontend/fontawesome-free-6.1.1-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- notifications css -->
    <link rel="stylesheet" href="{{asset('backend/assets/notifications/css/lobibox.min.css')}}"/>
    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('frontend/css/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.rtl.css') }}">
    <!-- Responsive -->
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <!-- My Custom CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/my-custom.css') }}">

    <!-- product zoom css -->
    <link rel="stylesheet" href="{{ asset('frontend/zoom-images-carousel/zoom-custom.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* FORCE SweetAlert toast width */
        .swal2-container.swal2-top-end > .swal2-popup {
            width: auto !important;
            padding: 0.75rem 1rem !important;
            border-radius: 6px !important;
        }
    </style>
    <style>
        .my-toast {
            padding: 12px !important;
            font-size: 14px !important;
            border-radius: 8px !important;
        }

        .swal2-title{
            line-height: 25px !important;
        }

    </style>
    @stack('custom-css')
</head>

<body>

<div id="main-wrapper">
    <!-- Facebook share button SDK -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0" nonce="iH4fRLBO"></script>
    <!-- Facebook share button SDK -->
    <header>
    @include('frontend.includes.top-bar')
    <!-- Main Header Start -->
        <div class="main-header">
            @include('frontend.includes.mid-bar')
            @include('frontend.includes.menu-bar')
        </div>
        <!-- Main Header End -->
    </header>
    <main>
        @yield('content')
        @stack('modal')
    </main>
    <footer>
        @include('frontend.includes.info-footer')
        @include('frontend.includes.main-footer')
    </footer>
</div>

<!-- jQuery -->
<script src="{{ asset('frontend/js/vendor/jquery-3.6.0.min.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('frontend/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Touch Punch -->
<script src="{{ asset('frontend/jquery-ui/jquery.ui.touch-punch.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('frontend/js/vendor/bootstrap.min.js') }}"></script>
<!-- Popper -->
<script src="{{ asset('frontend/js/vendor/popper.min.js') }}"></script>
<!-- Swiper -->
<script src="{{ asset('frontend/js/vendor/swiper.min.js') }}"></script>
<!-- Slick -->
<script src="{{ asset('frontend/js/vendor/slick.min.js') }}"></script>
<!-- Counter Up -->
<script src="{{ asset('frontend/js/vendor/countdown.js') }}"></script>
<!-- uniBox -->
<script src="{{ asset('frontend/uniBox/js/unibox.min.js') }}"></script>
<!-- SweetAlert -->
<script src="{{ asset('frontend/js/vendor/sweetalert.min.js') }}"></script>
<script src="{{asset('backend/assets/notifications/js/lobibox.min.js')}}"></script>
<script src="{{asset('backend/assets/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
{{-- Custom --}}
<script src="{{ asset('plugins/validation-setup/validation-setup.js') }}"></script>
<script src="{{ asset('plugins/custom/notification.js') }}"></script>
<script src="{{ asset('plugins/custom/form.js') }}"></script>
<!-- Nice Select -->
<script src="{{ asset('frontend/js/vendor/nice-select.min.js') }}"></script>
<!-- RateIt -->
<script src="{{ asset('frontend/rateit/jquery.rateit.min.js') }}"></script>
<!-- Index -->

<!-- product zoom js -->
<script src="{{ asset('frontend/zoom-images-carousel/zoom-image.js') }}"></script>
<script src="{{ asset('frontend/zoom-images-carousel/zoom-custom.js') }}"></script>
<!-- product zoom js -->
<script src="{{ asset('frontend/js/index.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            if ("{{ session('success') }}") {

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",

                    showClass: {
                        popup: 'swal2-toast-show'
                    },
                    hideClass: {
                        popup: 'swal2-toast-hide'
                    },

                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    width: 'auto'
                });

            }

        });
    </script>
@endif

<script>
    "use strict";

    var xhr = new XMLHttpRequest();

    function buyNow(id){
        var csrf = "{{ csrf_token() }}";
        var courier = $('#courier').val();
        var qty = $('.input-number').val();
        var total_price = $('#total_price').text();
        var size = $('input[name="size"]:checked').val();
        var color = $('input[name="color"]:checked').val();
        var size_id = $('input[name="size"]:checked').data('size_id');
        var color_id = $('input[name="color"]:checked').data('color_id');
        var shipping_area = $('input[name="delivery_charge"]:checked').val();

        $.ajax({
            url: "{{ route('customer.addToCart') }}",
            data: { _token:csrf, id:id, qty:qty, total_price:total_price, color:color, size:size, courier:courier, size_id:size_id, color_id:color_id, shipping_area:shipping_area, buynow: true },
            method: "POST"
        }).done(function(e){
            $("#cart-count").text(e.count);
            {{--swal("{{ __('Good Choice!') }}", e.name+" {{ __('is added to cart') }}", "success");--}}
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "{{ __('Good Choice!') }}",
                text: e.name + " {{ __('is added to cart') }}",
                showConfirmButton: false,
                timer: 3000,      // 👈 stays 5 seconds (5000 ms)
                timerProgressBar: true,
                width: '500px',
                height:'2000px',
                customClass: {
                    popup: 'my-toast'  // 👈 custom style class
                }
            });

            $(location).attr("href","{{ url('checkout') }}");
        })
    }

    function addToCart(id) {
        var csrf = "{{ csrf_token() }}";
        var courier = $('#courier').val();
        var qty = $('.input-number').val();
        var total_price = $('#total_price').text();
        var size = $('input[name="size"]:checked').val();
        var color = $('input[name="color"]:checked').val();
        var size_id = $('input[name="size"]:checked').data('size_id');
        var color_id = $('input[name="color"]:checked').data('color_id');
        var shipping_area = $('input[name="delivery_charge"]:checked').val();

        $.ajax({
            url: "{{ route('customer.addToCart') }}",
            data: {_token:csrf, id:id, qty:qty, total_price:total_price, color:color, size:size, courier:courier, size_id:size_id, color_id:color_id, shipping_area:shipping_area},
            method: "POST"
        }).done(function(e) {
            if (e.status == 'success') {
                $("#cart-count").text(e.count);
                {{--swal("{{ __('Good Choice!') }}", e.name+" {{ __('is added to cart') }}", "success");--}}
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'success',
                    title: "{{ __('Good Choice!') }}",
                    text: e.name + " {{ __('is added to cart') }}",
                    showConfirmButton: false,
                    timer: 3000,      // 👈 stays 5 seconds (5000 ms)
                    timerProgressBar: true,
                    width: '500px',
                    height:'2000px',
                    customClass: {
                        popup: 'my-toast'  // 👈 custom style class
                    }
                });

            } else {
                swal("{{ __('Sorry!') }}", e, "error");
            }
        })
    }

    /* wholesale */
    $('.input-number,#whole_minus,#whole_plus').on('keyup click', function () {
        hollCal();

    })

    function hollCal() {
        $('.input-number').data('id')
        let quty = parseInt($('input[name="quantity"]').val());
        let p_price = $('#current_price').text();
        //console.log(quty);

        let product_min_qty = $('.product-min-qty').text().trim().split(' ');
        let product_max_qty = $('.product-max-qty').text().trim().split(' ');
        let product_price_all = $('.product-price-all').text().trim().split(' ');
        let product_stoke_quantity = $('.multivendor-price').data('product-quantity');
        if(product_stoke_quantity==quty ||product_stoke_quantity<=quty){
            $('#whole_plus').attr("disabled", true);
            parseInt($('input[name="quantity"]').val(product_stoke_quantity));
            quty= product_stoke_quantity
        }else {
            $('#whole_plus').attr("disabled", false);
        }

        if (product_min_qty!=''){
            let lenth= product_min_qty.length;
            $(product_max_qty).each(function( index, value ) {

                if(index==0 && quty<parseInt(product_min_qty[index])){
                    $('#total_price').text((quty*p_price).toFixed(2));
                }else{
                        if (parseInt(quty)>=parseInt(product_min_qty[index]) && parseInt(quty)<=parseInt(value)){
                            $('#total_price').text((quty*product_price_all[index]).toFixed(2));
                    return;
                        }else{
                            if (index===lenth-1 && quty>parseInt(value)){
                                $('#total_price').text((quty*product_price_all[index]).toFixed(2));
                            }

                        }
                }


            });
        }else {
            $('#total_price').text(quty*p_price);
        }


    }

    function addToWishlist(id){
        if("{{auth()->guard('customer')->check()}}"){
            var csrf = "{{ csrf_token() }}"
            $.ajax({
                url: "{{ route('customer.addToWishlist') }}",
                data: {_token:csrf,id:id},
                method: "POST"
            }).done(function(e){
                if(e.status === 'exists'){
                    $("#wishlist-count").text(e.count);
                    swal("{{ __('Hey!') }}", e.name+" {{ __('is already in your wishlist') }}","warning");
                }else{
                    $("#wishlist-count").text(e.count);
                    swal("{{ __('Great!') }}", e.name+" {{ __('added to your wishlist') }}",e.status);
                }
            });
        }else{
            swal("{{ __('Please!') }}","{{ __('Login to add product to your wishlist') }}","warning");
        }
    }

    function wishToCart(id){
        var csrf = "{{ @csrf_token() }}";
        $.ajax({
            url: "{{ route('customer.wishToCart') }}",
            data: {_token:csrf,id:id},
            type: "POST",
        }).done(function(e){
            $("#cart-count").text(e.count);
            $("#wishlist-count").text(e.wishCount);
            $("#wish-"+id).remove();
            swal("{{ __('Good Choice!') }}", e.name+" {{ __('is added to cart') }}", "success");
        })
    }

    function removeFromWishlist(id){
        var csrf = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('customer.removeFromWishlist') }}",
            data: {_token:csrf,id:id},
            type: "POST"
        }).done(function(e){
            console.log($(this));
            $("#wish-"+id).remove();
            $("#wishlist-count").text(e.count);
            swal("{{ __('Hash!') }}",e.name+"{{ __(' is removed from your wishlist!') }}","warning");
        });
    }

    function changeCurrency(id){
        var csrf = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('frontend.change-currency') }}",
            data: {_token:csrf,id:id},
            type: "POST",
        }).done(function(e){
            $("#cur-symbol").text(e.symbol);
            $("#cur-title").text(e.name);
            location.reload();
        })
    }

    function changeLanguage(id){
        var csrf = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('frontend.change-language') }}",
            data: {_token:csrf,id:id},
            type: "POST",
        }).done(function(e){
            console.log(e);
            location.reload();
        })
    }

    function ajaxFilter(page,id){
        var csrf = "{{ csrf_token() }}";
        var category = $('input[name="category"]:checked').val();
        var slug = "{{request('q')}}";
        console.log(slug)
        var brand = [];
        var seller = [];
        var color = [];
        var size = [];
        var sorting = $("#sorting").val();
        var min = $("#price-check-b").val();
        var max = $("#price-check-c").val();

        $('.brand-check:checked').each(function(){
            brand.push($(this).val())
        })
        $('.color-check:checked').each(function(){
            color.push($(this).val())
        })
        $('.size-check:checked').each(function(){
            size.push($(this).val())
        })
        $('.seller-check:checked').each(function(){
            seller.push($(this).val())
        })

        if(xhr !== 'undefined'){
            xhr.abort(); //stop existing ajax request if new request has been sent to server
        }

        xhr = $.ajax({
            url: "{{ route('frontend.ajax-filter') }}",
            data: {_token:csrf,category:category,slug:slug,color:color,size:size,brand:brand,seller:seller,min:min,max:max,sorting:sorting,page:page},
            type: 'post',
            beforeSend: function(){
                $("#product-loader").show();
            },
        }).done(function(e){
            $(".breadcrumb-manu h3").text($('input[name="category"]:checked').data('name'));
            $("#product-area").html(e);
            if(slug!=null){
                var url = "{{url()->current()}}"+"?q="+slug+"&page="+page;
            }else {
                var url = "{{url()->current()}}"+"?page="+page;
            }

            window.history.pushState("", "", url);
            $("#product-loader").hide()
            $("#sorting").change(function(){
                ajaxFilter();
            })
        })
    }

    $(document).on('click','#pagination a',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var page = url.split('page=')[1];
        window.history.pushState("", "", url);
        ajaxFilter(page);
    })

    function dealOfTheWeek(tab){
        $.ajax({
            url: "{{ route('deal-of-the-week') }}",
            data: {_token:"{{csrf_token()}}",tab:tab},
            type: "POST"
        }).done(function(e){
            $("#"+tab).html(e);
        })
    }

    $(".brand-check").change(function(){
        ajaxFilter();
    })

    $(".seller-check").change(function(){
        ajaxFilter();
    });

    $(".category-check").change(function(){
        ajaxFilter();
    });

    $(".color-check").change(function(){
        ajaxFilter();
    });

    $(".size-check").change(function(){
        ajaxFilter();
    });

    $(".price-check").change(function(){
        ajaxFilter();
    });

    $("#sorting").change(function(){
        ajaxFilter();
    })

</script>
<script>
    "use strict";

    var boxes = [];

    sxQuery(document).ready(function() {

        var settings = {
            // REQUIRED
            suggestUrl: '{{ route('frontend.suggest',['query'=>'']) }}', // the URL that provides the data for the suggest
            ivfImagePath: 'https://yourserver.com/images/ivf/', // the base path for instant visual feedback images

            // OPTIONAL
            instantVisualFeedback: 'all', // where the instant visual feedback should be shown, 'top', 'bottom', 'all', or 'none', default: 'all'
            throttleTime: 100, // the number of milliseconds before the suggest is triggered after finished input, default: 300ms
            extraHtml: undefined, // extra HTML code that is shown in each search suggest
            highlight: true, // whether matched words should be highlighted, default: true
            queryVisualizationHeadline: '', // A headline for the image visualization, default: empty
            animationSpeed: 200, // speed of the animations, default: 300ms
            callbacks: {
                enter: function(text,link){console.log('enter callback: '+text);}, // callback on what should happen when enter is pressed, default: undefined, meaning the link will be followed
                enterResult: function(text,link){window.location.replace(link);}, // callback on what should happen when enter is pressed on a result or a suggest is clicked
            },
            placeholder: 'Search for something',
            minChars: 3, // minimum number of characters before the suggests shows, default: 3
            suggestOrder: [], // the order of the suggests
            suggestSelectionOrder: [], // the order of how they should be selected
            noSuggests: '<b>{{ __('We haven\'t found anything for you') }}, <u>{{ __('sooorrryyy') }}</u></b>',
            emptyQuerySuggests: {
                "suggests":{
                    "Products":[
                            @foreach(uniBoxSuggestions() as $suggestion)
                        {"name":"{{$suggestion->name}}","image":"{{ asset('uploads/products/galleries').'/'.$suggestion->images->first()->image }}","id":"{{ $suggestion->id }}}","link":"{{ route('product',$suggestion->slug) }}"},
                        @endforeach
                    ]
                }
            },
            //maxWidth: 400 // the maximum width of the suggest box, default: as wide as the input box
        };

        // apply the settings to an input that should get the unibox
        // apply to multiple boxes
        boxes = sxQuery(".s1").unibox(settings);
    });
</script>

<script>
    // $("#editable-div").on('input',function(){
    //     $("#chat-box").val($(this).html())
    // })
    function sendToSeller(seller,customer)
    {
        var msg = $("#editable-div").html();
        var csrf = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('frontend.send-to-seller') }}",
            data: {_token:csrf,seller_id:seller,user_id:customer,message:msg},
            type: "post"
        }).done(function(e){
            console.log(e);
            loadMessage(seller,customer);
        })
    }
    function loadMessage(seller,customer)
    {
        var csrf = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('frontend.message.load') }}",
            data: {_token:csrf,seller_id:seller,user_id:customer},
            type: "post"
        }).done(function(e){
            $("#messages").html(e);
            $("#editable-div").text('');
        })
    }
</script>
<script>
    $(document).ready(function(){
        $(".s").bind("keyup", function (event){
            event.preventDefault();
            var search = $(".s").val();
            var url= "{{ route('frontend.suggest-new')}}";
            var symbol =  "{{bdtSymble()}}";
            //alert(search);
            $.ajax({
                type: "GET",
                url: url,
                dataType:"json",
                data:{'search':search},
                success: function(data){
                    $('.suggestion-wrap ul').empty();

                    const productUrl = "{{ url('/p') }}";
                    $.each(data,function (index,element){
                        if (index<=4){
                            $('.suggestion-wrap ul').append('<li class="unibox-selectable row">' +
                                '<a href="'+productUrl+'/'+element.slug+'"><img src="{{asset('uploads/products/galleries')}}/'+element.images[0].image+'" alt="" height="50" width="50" style="padding-right:2px;padding-bottom:2px;"/><div class="search-items-content"><p>'+element.name+'</p><b>'+symbol+element.sale_price+'</b><div class="sess-discount" id="sess-discount-'+index+'" >'+'</div></div></a></li>');
                            if(element.promotions_active[index]){
                                $.each(element.promotions_active,function (index1,element1){
                                    if(element1.promotion_price){
                                        $('#sess-discount-'+index).append('<del>'+symbol+element.unit_price+'</del><b>'+' -'+Math.round(((element.unit_price-element1.promotion_price)/element.unit_price)*100)+'%'+'</b>');
                                    }
                                })
                            }else {
                                if(element.discount>0 && element.discount_type =='fixed'){
                                    $('#sess-discount-'+index).append('<del>'+symbol+element.unit_price+'</del><b>'+' -'+Math.round((element.discount/element.unit_price)*100)+'%'+'</b>');
                                }else{
                                    $('#sess-discount-'+index).append('<del>'+symbol+element.unit_price+'</del><b>'+' -'+Math.round(element.discount)+'%'+'</b>');
                                }
                            }


                        }else{
                            if (index==5){
                                $('.suggestion-wrap ul').append('<li class="unibox-selectable row">' +
                                    '<form action="{{ route('frontend.shop') }}" method="get"><div class="input-group"><input t class="s" type="hidden" name="q" value="'+search+'"><button type="submit" class="product-serarch-btn">Sea all results</button></div></form></li>');
                            }

                        }

                    })
                },
                error: function (data){
                    //alert("Error");
                }
            });
        });
    });
</script>

<script>
    const slider = document.querySelector('.category-scroll');

    let targetScroll = slider.scrollLeft;
    let isAnimating = false;

    function smoothScroll() {
        if (!isAnimating) return;

        slider.scrollLeft += (targetScroll - slider.scrollLeft) * 0.15;

        if (Math.abs(targetScroll - slider.scrollLeft) < 0.5) {
            slider.scrollLeft = targetScroll;
            isAnimating = false;
            return;
        }

        requestAnimationFrame(smoothScroll);
    }

    /* Mouse wheel → smooth horizontal slide */
    slider.addEventListener('wheel', (e) => {
        e.preventDefault();

        targetScroll += e.deltaY * 1.5;
        targetScroll = Math.max(
            0,
            Math.min(targetScroll, slider.scrollWidth - slider.clientWidth)
        );

        if (!isAnimating) {
            isAnimating = true;
            smoothScroll();
        }
    }, { passive: false });

    /* Drag slider */
    let isDown = false;
    let startX;
    let scrollStart;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.style.cursor = 'grabbing';
        startX = e.pageX;
        scrollStart = slider.scrollLeft;
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.style.cursor = 'grab';
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.style.cursor = 'grab';
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        const walk = (e.pageX - startX) * 1.3;
        slider.scrollLeft = scrollStart - walk;
        targetScroll = slider.scrollLeft;
    });
</script>


@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: "{{ __('Subscribed!') }}",
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if ($errors->has('email'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ $errors->first('email') }}",
            confirmButtonText: 'OK'
        });
    </script>
@endif

@stack('script')
@yield('script')

</body>

</html>

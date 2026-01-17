@extends('frontend.layouts.front')

@section('title',$product->name)

@section('meta_title',$product->meta_title ?? $product->name)

@section('meta_description',$product->meta_description ?? '')

@section('meta_image',$product->meta_image)

@section('meta_url',url()->full())

@section('meta_price',currency($product->unit_price,2))

@section('meta_color','Black')

@section('content')
    <section class="review-pages-section">
        <div class="container">
            <h2>Write Review</h2>
            <div class="review-section-wrapper">
                <div class="left-side">
                    <div class="delivery-date">
                        <p>Delivered on 22 Jan 2023</p>
                        <small>Rate and review purchased product:</small>
                    </div>
                    <div class="product-info">
                        <div class="product-info-img">
                            <img src="https://static-01.daraz.com.bd/p/347eec8efd23b3c436d44d9e5b2e0a1c.jpg" alt="img">
                        </div>
                        <div class="product-info-dis">
                            <h6>Mini DP Thunderbolt DisplayPort To 1080P HDMI Video Converter Adapter Cable</h6>
                            <small>Color Family:...</small>
                        </div>
                    </div>
                    <div class="pl-30">
                        <div class="review-rating" id="review-seller">
                            <!-- Rating Stars Box -->
                            <div class='rating-stars text-center'>
                                <ul id='stars'>
                                <li class='star' title='Very Poor' data-value='1'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Poor' data-value='2'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Neutral' data-value='3'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Satisfactory' data-value='4'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Delightful!!!' data-value='5'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                </ul>
                            </div>
                            <div class='success-box'>
                                <div class='text-message'>
                                    <span>Very Poor</span>
                                </div>
                            </div>
  
  
                        </div>
                        <div class="review-details">
                            <p>Review detail</p>
                            <a href="#">How to write a good review</a>
                        </div>
                        <div class="review-form-wrp">
                                <textarea class="form-control"></textarea>
                                <input type="file" class="d-none" id="review-img">
                                <label for="review-img" class="review-img">
                                    <i class="fa fa-camera"></i>
                                    <span>Upload Photo</span>
                                </label>
                        </div>
                    </div>
                </div>
                
                <div class="right-side">
                    <div class="sold-by">
                        <p>Sold by <span>Planetx</span></p>
                        <strong>Rate and review your seller:</strong>
                    </div>
                    <div class="seller-review my-2">
                        <ul>
                            <li class="negative"><i class="fa fa-star" ></i></li>
                            <li class="neutral"><i class="fa fa-star" ></i></li>
                            <li class="positive"><i class="fa fa-star" ></i></li>
                        </ul>
                        <p>Positive</p>
                    </div>
                    <div class="review-details">
                        <p>Review detail</p>
                    </div>
                    <textarea class="form-control"></textarea>
                    <div class="rider-rateing mt-2">
                        <strong>Rate your Rider: </strong>
                        <div class="review-rating">
                            <!-- Rating Stars Box -->
                            <div class='rating-stars text-center'>
                                <ul id='stars'>
                                <li class='star' title='Very Poor' data-value='1'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Poor' data-value='2'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Neutral' data-value='3'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Satisfactory' data-value='4'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                <li class='star' title='Delightful!!!' data-value='5'>
                                    <i class='fa fa-star fa-fw'></i>
                                </li>
                                </ul>
                            </div>
                            <div class='success-box'>
                                <div class='text-message'>
                                    <span>Very Poor</span>
                                </div>
                            </div>
                        </div>
                        <textarea class="form-control"></textarea>
                        <button class="btn theme-btn mt-4">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@push('script')
    <script>
       $(document).ready(function(){
            /* 1. Visualizing things on Hover - See next part for action on click */
            $('#stars li').on('mouseover', function(){
                var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
            
                // Now highlight all the stars that's not after the current hovered star
                $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
                });
                
            }).on('mouseout', function(){
                $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
                });
            });
            
            
            /* 2. Action to perform on click */
            $('#stars li').on('click', function(){
                var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                var stars = $(this).parent().children('li.star');
                
                for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
                }
                
                for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
                }
                
                // JUST RESPONSE (Not needed)
                var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                var msg = "";
                if (ratingValue == 1) {
                    msg = "Very Poor";
                } else if (ratingValue == 2){
                    msg = "Poor"
                }
                else if (ratingValue == 3){
                    msg = "Neutral"
                }
                else if (ratingValue == 4){
                    msg = "Satisfactory"
                }
                else if (ratingValue == 5){
                    msg = "Delightful"
                }
                else {
                    msg = "Very Poor";
                }
                responseMessage(msg);
                
            });
            
            
            });


            function responseMessage(msg) {
            $('.success-box').fadeIn(200);  
            $('.success-box div.text-message').html("<span>" + msg + "</span>");
            }
    </script>
@endpush

<!-- Banner Start -->
<section class="banner">
    <div class="container">
        <div class="banner-wrapper">
            <div class="row">
                <div class="col-lg-2">
                    <div class="side-mega-manu">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="swiper banner-slider">
                        <div class="swiper-wrapper">
                            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="swiper-slide row align-items-center" data-background="<?php echo e(asset('uploads/banners')); ?>/<?php echo e($banner->image); ?>">
                                    <div class="col-lg-7">
                                        <div class="banner-content">



                                                <a href="<?php echo e(route('category',$banner->category->slug)); ?>"><?php echo e(__('SHOP NOW >')); ?></a>


                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="swiper-pagination d-none"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="banner-add">
                        <ul>
                            <?php $__currentLoopData = $bannerAds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <div class="banner-add-wrapper">
                                        <a href="<?php echo e(route('product',$promotion->product->slug)); ?>" class="banner-add-thumb">
                                            <img src="<?php echo e(asset('uploads/products/galleries')); ?>/<?php echo e($promotion->product->images->first()->image); ?>" alt="<?php echo e($promotion->product->name); ?>">
                                        </a>
                                        <div class="banner-add-content">
                                            <a href="<?php echo e(route('product',$promotion->product->slug)); ?>"><span><?php echo e($promotion->product->name); ?></span></a>
                                            <h5><a href="<?php echo e(route('product',$promotion->product->slug)); ?>"><?php echo e($promotion->title); ?></a></h5>
                                            <div class="star-rating">
                                                <div class="rateit" data-rateit-value="<?php echo e(productRating($promotion->product->reviews)); ?>" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner End -->
<?php $__env->startPush('script'); ?>
    <script>

        bannerSlider()

        /*======================
            Banner Slider
        ======================*/
        function bannerSlider() {
            "use strict";

            let menu = [
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    "<?php echo e($banner->title); ?>",
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
            let mySwiper = new Swiper(".banner-slider", {
                slidesPerView: 'auto',
                centeredSlides: true,
                spaceBetween: 0,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function(index, className) {
                        return '<div class="' + className + '">' + (menu[index]) + '</div>';
                    },
                },
            });

            $('.swiper-pagination-bullet').on('mouseover',function() {
                $(this).trigger("click");
            });
        }
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/china_hub/resources/views/frontend/_banner.blade.php ENDPATH**/ ?>
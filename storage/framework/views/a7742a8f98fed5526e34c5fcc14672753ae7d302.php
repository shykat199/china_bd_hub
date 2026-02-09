<?php $__env->startSection('title','Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="maan-main-content">
        <div class="maan-state-overview maan-layout-style-one">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="maan-counter-wpr grid-4">
                            <a href="javascript:orderList(0)" class="maan-counter-box">
                                <div class="maan-icon maan-radius maan-icon-clr-lightdanger">
                                    <i> <img src="<?php echo e(asset('customer/img/icons/1.svg')); ?>" alt="Icon"></i>
                                </div>
                                <div class="maan-desc">
                                    <div class="maan-counter">
                                        <span class="maan-counter-title counter"><?php echo e(orderCount(0)); ?></span>
                                    </div>
                                    <p class="maan-counter-content"><?php echo e(__('Total Order')); ?></p>
                                </div>
                            </a>
                            <a href="javascript:orderList(5)" class="maan-counter-box">
                                <div class="maan-icon maan-radius maan-icon-clr-lightblue">
                                    <i> <img src="<?php echo e(asset('customer/img/icons/track-blue.svg')); ?>" alt="Icon"></i>
                                </div>
                                <div class="maan-desc">
                                    <div class="maan-counter">
                                        <div class="maan-counter">
                                            <span class="maan-counter-title counter"><?php echo e(orderCount(5)); ?></span>
                                        </div>
                                        <p class="maan-counter-content"><?php echo e(__('Order Shipped')); ?></p>
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:orderList(6)" class="maan-counter-box">
                                <div class="maan-icon maan-radius maan-icon-clr-lightgreen">
                                    <i> <img src="<?php echo e(asset('customer/img/icons/track-green.svg')); ?>" alt="Icon"></i>
                                </div>
                                <div class="maan-desc">
                                    <div class="maan-counter">
                                        <span class="maan-counter-title counter"><?php echo e(orderCount(6)); ?></span>
                                    </div>
                                    <p class="maan-counter-content"><?php echo e(__('Order Delivered')); ?></p>
                                </div>
                            </a>
                            <a href="javascript:orderList(7)" class="maan-counter-box">
                                <div class="maan-icon maan-radius maan-icon-clr-lightred">
                                    <i> <img src="<?php echo e(asset('customer/img/icons/order-cancel.svg')); ?>" alt="Icon"></i>
                                </div>
                                <div class="maan-desc">
                                    <div class="maan-counter">
                                        <span class="maan-counter-title counter"><?php echo e(orderCount(7)); ?></span>
                                    </div>
                                    <p class="maan-counter-content"><?php echo e(__('Order Canceled')); ?></p>
                                </div>
                            </a>

                            <?php

                            ?>
                            <a href="javascript:orderList(7)" class="maan-counter-box">
                                <div class="maan-icon maan-radius maan-icon-clr-lightred">
                                    <i> <img src="<?php echo e(asset('customer/img/icons/money-com.svg')); ?>" alt="Icon"></i>
                                </div>
                                <div class="maan-desc">
                                    <div class="maan-counter">
                                        <span class="maan-counter-title counter"><?php echo e(number_format(sumOfOrder(),2)); ?></span> TK
                                    </div>
                                    <p class="maan-counter-content"><?php echo e(__('Total Order Amount')); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="maan-content-wpr">
                    <!-- Order list will appeared here -->
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        "use strict";
        var xhr = new XMLHttpRequest();
        function orderList(stat,page){
            var csrf = "<?php echo e(csrf_token()); ?>"
            if(xhr !== 'undefined'){
                xhr.abort(); //stop existing ajax request if new request has been sent to server
            }
            console.log('Stat: '+stat);
            console.log('Page: '+page);
            $.ajax({
                url : "<?php echo e(route('customer.order.list')); ?>",
                data : {_token:csrf,stat:stat,page:page},
                type : "post"
            }).done(function(e){
                $(".maan-content-wpr").html(e);
            })
        }

        $(document).ready(function(){
            orderList(0);
        })

        $(document).on('click','.pagination-bar a',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.split('page=')[1];
            var stat = $(this).data('stat');
            orderList(stat,page);
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/customer/pages/order.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Success'); ?>

<?php $__env->startSection('content'); ?>
    <section class="shop-list">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-lg-6 text-center">
                    <h3><?php echo e(__('Thank you for your purchase')); ?></h3>
                    <p class="h4"><?php echo e(__('Your order no is')); ?> <b><?php echo e($order->order_no); ?></b></p>
                    <h3><?php echo e(currency(($order->total_price), 2)); ?></h3>
                    <p class="mt-5"></p>
                    <table class="table table-bordered">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <img src="<?php echo e(asset('uploads/products/galleries')); ?>/<?php echo e(CartItem::thumbnail($item->product_id)); ?>" height="75" alt="<?php echo e(CartItem::thumbnail($item->product_id)); ?>">
                                    <br>
                                    <?php echo e(CartItem::name($item->product_id)); ?>

                                </td>
                                <td><?php echo e(__('Estimated shipping ')); ?> <?php echo e(CartItem::estimatedShippingDays($item->product_id)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                    <div class="mybazar-payment-delivery-date-link">
                        <p><?php echo e(__('For More Details, Track Your Delivery Status Under')); ?> <span><?php echo e(__('My Account')); ?> > <?php echo e(__('Order')); ?></span> <a href="<?php echo e(route('gust-customer.order',session('customer_mobile'))); ?>"><?php echo e(__('View Order')); ?><?php echo e(session('customer_mobile')); ?></a></p>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/customer/checkout/payment-success.blade.php ENDPATH**/ ?>
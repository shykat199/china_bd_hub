<!-- invoice start  -->
<div class="maan-mybazar-invoice">
    <div class="my-bazar-invoice-header">
        <a href="" class="logo">
            <img src="<?php if($website->logo): ?><?php echo e(URL::to('uploads').'/'.$website->logo); ?> <?php else: ?> 'uploads/logo.png' <?php endif; ?>"
                 width="150" alt="logo">
        </a>
        
        <button class="maan-print-btn d-print-none" id="print_button">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                 x="0px" y="0px" width="45px" height="45px" viewBox="0 0 45 45" style="enable-background:new 0 0 45 45;"
                 xml:space="preserve">
        <path d="M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5   c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z    M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5   V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25   S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25   S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25   S33.189,18.792,32.5,18.792z"></path>
        </svg>
        </button>
        <div class="customer-detail">
            <p><b><?php echo e(ucfirst($website->website_name)); ?></b></p>
            <p><?php echo e($website->get_in_touch??''); ?></p>
            <p><?php echo e(ucfirst($website->city).'-'.$website->post_code??''); ?></p>
            <p><?php echo e($website->country->name??''); ?></p>
            <p><?php echo e($website->email??''); ?></p>
        </div>
    </div>
    <div class="mybazar-billing-info">
        <div class="row">
            <div class="col-6">
                <div class="billing-info">
                    <h4><?php echo e(__('Billing Address')); ?></h4>
                    <ul>
                        <li><span><?php echo e(__('Name')); ?>:</span><?php echo e($order->full_name()); ?></li>
                        <?php if($order->user_mobile): ?>
                            <li><span><?php echo e(__('Phone')); ?> :</span><?php echo e($order->user_mobile??''); ?></li>
                        <?php endif; ?>
                        <li><span><?php echo e(__('Address')); ?>:</span> <?php echo e($order->shipping_address_2); ?> </li>

                    </ul>
                </div>
            </div>
            <div class="col-6">
                <div class="billing-info">
                    <h4><?php echo e(__('Shipping Address')); ?></h4>
                    <ul>
                        <li> <?php echo e($order->shipping_address_1); ?>

                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <h5><span><?php echo e(__('Invoice No')); ?>: </span>
            <?php echo e($order->order_no ?? ''); ?>

        </h5>
        <h5><span><?php echo e(__('Invoice Date')); ?>: </span><?php echo e(date("d M Y ",strtotime($order->created_at))); ?></h5>
        <h5><span><?php echo e(__('Sold By')); ?>: </span>
            <?php echo e(ucfirst($website->website_name)); ?>

        </h5>
    </div>
    <div class="mybazar-product-info-billing">
        <table class="table">
            <thead>
            <tr>
                <th scope="col"><?php echo e(__('Item')); ?></th>
                
                <th scope="col"><?php echo e(__('Color')); ?></th>
                <th scope="col"><?php echo e(__('Size')); ?></th>
                <th scope="col"><?php echo e(__('HRS')); ?>/<?php echo e(__('QTY')); ?></th>
                <th scope="col"><?php echo e(__('Rate')); ?></th>
                <th scope="col"><?php echo e(__('Subtotal')); ?></th>
            </tr>
            </thead>
            <tbody id="seller-invoice-table1">
            <?php
            $sellerId =  "<script>document.writeln(seller_id);</script>";
            ?>
            <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($detail->order_stat!=7): ?>
                <tr data-product="<?php echo e($detail->product_id); ?>">
                    <td scope="row">
                        <?php echo e($detail->product->name??''); ?>

                        
                    </td>
                    
                    <td><?php echo e($detail->color??''); ?></td>
                    <td><?php echo e($detail->size??''); ?></td>
                    <td><?php echo e($detail->qty??''); ?></td>
                    <td><?php echo e($detail->sale_price??''); ?></td>
                    <td><?php echo e($detail->total_price??''); ?></td>
                </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mybazar-total-info">
        <ul>
            <li><?php echo e(__('Item(s) Subtotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
            <li><?php echo e(__('Shipping Charge')); ?>:<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
            <li>-------------------------------------------</li>
            <li><?php echo e(__('SubTotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
            <li><?php echo e(__('Coupon')); ?>:<span><?php echo e(number_format($order->discount,2)); ?> ৳</span></li>
            
            <li>-------------------------------------------</li>
            <li><?php echo e(__('Total')); ?>:<span><?php echo e(number_format($order->total_price,2)); ?> ৳</span></li>
        </ul>
    </div>
    <div class="signature">
        <p>signature</p>
    </div>
</div>
<!-- invoice end  -->
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/OrderManagement/Resources/views/orders/invoice.blade.php ENDPATH**/ ?>
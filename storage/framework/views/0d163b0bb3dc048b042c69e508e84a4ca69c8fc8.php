<div class="customer-dashboard-table">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo e(__('Image')); ?></th>
            <th><?php echo e(__('Product')); ?></th>
            <th><?php echo e(__('Delivery Time')); ?></th>
            <th><?php echo e(__('Quantity')); ?></th>
            <th><?php echo e(__('Payment')); ?></th>
            <th><?php echo e(__('Price')); ?></th>
            <th><?php echo e(__('Status')); ?></th>
            <th><?php echo e(__('Action')); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php if($order->product): ?>
                        <img src="<?php echo e(asset('uploads/products/galleries')); ?>/<?php echo e($order->product->images->first()->image); ?>" alt="<?php echo e($order->product->name); ?>">
                    <?php endif; ?>
                </td>
                <td><b><?php echo e($order->order->order_no ?? ''); ?></b> <small><?php echo e($order->product->name ?? ''); ?> </small></td>
                <td><?php echo e($order->product->details->inside_shipping_days ?? '7-30 days'); ?></td>
                <td><?php echo e($order->qty); ?></td>
                <td><?php echo e($order->order->payment_by ?? ''); ?></td>
                <td><?php echo e($order->grand_total); ?></td>
                <td>
                    <a href="" class="<?php echo e(orderButtonClass($order->order_stat)); ?>"><?php echo e(orderStatus($order->order_stat)); ?></a>
                </td>
                <td><a href="<?php echo e(route('order.details',$order->id)); ?>" class="manage-btn"><?php echo e(__('Manage Order')); ?></a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.customer.page-navigation','data' => ['paginator' => $orders,'stat' => $stat]]); ?>
<?php $component->withName('customer.page-navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($orders),'stat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat)]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/china_hub/resources/views/customer/pages/_order_list.blade.php ENDPATH**/ ?>
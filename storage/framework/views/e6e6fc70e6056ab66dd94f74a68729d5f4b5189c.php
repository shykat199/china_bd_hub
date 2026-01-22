<div class="sidebar-widget">
    <h6><?php echo e(__('Brand')); ?></h6>
    <div class="widget-valu brand-widget">
        <ul>
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <input type="checkbox" id="<?php echo e($brand->slug); ?>" class="brand-check" value="<?php echo e($brand->id); ?>">
                    <label for="<?php echo e($brand->slug); ?>"><?php echo e($brand->name); ?> (<?php echo e($brand->products->count()); ?>)</label>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/brand-widget.blade.php ENDPATH**/ ?>
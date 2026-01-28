<div class="sidebar-widget">
    <h6><?php echo e(__('Size')); ?></h6>
    <div class="product-size-wrap">
        <ul>
            <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <label class="product-size">
                    <input type="checkbox" name="size[]" class="size-check" value="<?php echo e($size->id); ?>">
                    <span class="checkmark"><?php echo e($size->name); ?></span>
                </label>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/size-widget.blade.php ENDPATH**/ ?>
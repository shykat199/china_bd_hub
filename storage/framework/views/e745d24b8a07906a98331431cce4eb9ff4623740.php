<div class="sidebar-widget">
    <h6><?php echo e(__('Color')); ?></h6>
    <div class="product-color-wraper">
        <ul>
            <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <label class="porduct-color">
                    <input type="checkbox" class="color-check" name="colors[]" value="<?php echo e($color->id); ?>">
                    <span class="checkmark" style="background-color: <?php echo e($color->hex); ?>"></span>
                </label>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/color-widget.blade.php ENDPATH**/ ?>
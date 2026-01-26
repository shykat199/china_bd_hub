<div class="sidebar-widget">
    <h6><?php echo e(__('Browse Categories')); ?></h6>
    <ul>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <input type="radio" name="category" data-name="<?php echo e($category->name); ?>" class="category-check" id="<?php echo e($category->slug); ?>" value="<?php echo e($category->id); ?>" <?php echo e($category->slug == Request::segment(2) ? 'checked' : ''); ?>>
                <label for="<?php echo e($category->slug); ?>"><?php echo e($category->name); ?> (<?php echo e($category->productCount()); ?>)</label>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/category-widget.blade.php ENDPATH**/ ?>
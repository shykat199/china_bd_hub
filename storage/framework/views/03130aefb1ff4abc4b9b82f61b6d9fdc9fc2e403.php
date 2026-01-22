<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($cat->id); ?>" <?php if(isset($product) && $cat->id == $product->category_id || $cat->id == old('category_id')): ?> selected <?php endif; ?>>
        <?php for($i = 0; $i <= $child; $i++): ?>
            <?php echo e('>'); ?>

        <?php endfor; ?>

        <?php echo e($cat->name); ?>

    </option>
    <?php if(isset($cat->children)): ?>
        <?php echo $__env->make('productmanagement::includes.category_option', [
            'child' => $child + $child,
            'categories' => $cat->children,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/includes/category_option.blade.php ENDPATH**/ ?>
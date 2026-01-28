<?php $__env->startSection('title', config('app.name', '') ); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('frontend._banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('frontend._notice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    

    

    

    

    <?php echo $__env->make('frontend._offer-count', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('frontend._products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>





    <?php if(isset($pop_up->is_active) && $pop_up->is_active): ?>

        <?php echo $__env->make('frontend._popup', ['pop_up' => $pop_up], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/frontend/index.blade.php ENDPATH**/ ?>
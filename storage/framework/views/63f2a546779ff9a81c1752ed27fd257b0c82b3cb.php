<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title'); ?><?php echo e(config('app.name', '')); ?></title>
    <link rel="icon" href="<?php if(config('app.favicon')): ?><?php echo e(asset(config('app.favicon'))); ?><?php endif; ?>" type="image/x-icon">
    <script type="text/javascript">
        'use strict';
        var public_path = "<?php echo url('/'); ?>";
    </script>

    <?php echo $__env->make('backend.includes.layout_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('css'); ?>
</head>
<body>

<div id="main-wrapper">
    <header>
        <!-- Side Bar Start -->
        <?php if(auth()->guard('seller')->check()): ?>
            <?php echo $__env->make('backend.includes.seller_side_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('backend.includes.side_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!-- Side Bar End -->
    </header>
    <main>
        <!-- Content Header Start -->
        <?php echo $__env->make('backend.includes.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Content Header End -->

        <!-- Content Body Start -->
        <?php echo $__env->yieldContent('content'); ?>
        <!-- Content Body end -->
    </main>
    <!-- /.content-wrapper -->

    <?php echo $__env->yieldPushContent('modal'); ?>
</div>
<!-- ./wrapper -->
<?php echo $__env->make('backend.includes.layout_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('script'); ?>
<?php echo $__env->yieldPushContent('custom-script'); ?>
</body>
</html>
<?php /**PATH /var/www/html/china_hub/resources/views/backend/layouts/app.blade.php ENDPATH**/ ?>
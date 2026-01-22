<!DOCTYPE html>
<html dir="<?php echo e(lang('direction')); ?>" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="keywords" content="<?php echo e(maanAppearance('keywords')); ?>" />
    <meta name="description" content="<?php echo e(maanAppearance('meta_desc')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo $__env->yieldContent('title'); ?></title>

    <!-- Apple Favicon -->
    <link rel="apple-touch-icon" href="<?php echo e(asset('uploads')); ?>/<?php echo e(maanAppearance('favicon')); ?>">

    <!-- All Device Favicon -->
    <link rel="icon" href="<?php echo e(asset('uploads')); ?>/<?php echo e(maanAppearance('favicon')); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/bootstrap.min.css')); ?>">

    <!-- Normalize -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/normalize.css')); ?>">

    <!-- Nice Select -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/nice-select.css')); ?>">

    <!-- SweetAlert -->
    <script src="<?php echo e(asset('frontend/js/vendor/sweetalert.min.js')); ?>"></script>

    <!-- Default -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/default.css')); ?>">

    <!-- Style -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/style.css')); ?>">

    <!-- Responsive -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/responsive.css')); ?>">

    <!-- Timeline CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/timeline.css')); ?>">

    <!-- Invoice CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/invoice.css')); ?>">

    <!-- Cancel CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/cancel.css')); ?>">

    <!-- My Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/my-custom.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/style.rtl.css')); ?>">

    <!-- Print CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('customer/css/print.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/style.css')); ?>">
    <!-- Responsive -->
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/responsive.css')); ?>">
</head>

<body>

<?php if(session()->has('locale')): ?> <?php echo e(app()->setLocale(Session::get('locale'))); ?> <?php endif; ?>

<div id="main-wrapper">
    <!-- Main Header Start -->
    <div class="main-header">
        <?php echo $__env->make('customer.includes.mid-bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('customer.includes.menu-bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- Main Header End -->
    <div class="mybazar-customer-dashboard">
        <div class="container">
            <div class="row user-dashbord pt-0">
                <div class="col-lg-9 order-lg-last">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
                <div class="col-lg-3">
                    <?php echo $__env->make('customer.includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php echo $__env->make('customer.includes.info-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('customer.includes.main-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </footer>
</div>

<!-- jQuery -->
<script src="<?php echo e(asset('customer/js/vendor/jquery-3.6.0.min.js')); ?>"></script>

<!-- Bootstrap -->
<script src="<?php echo e(asset('customer/js/vendor/bootstrap.min.js')); ?>"></script>

<!-- Popper -->
<script src="<?php echo e(asset('customer/js/vendor/popper.min.js')); ?>"></script>

<!-- Nice Select -->
<script src="<?php echo e(asset('customer/js/vendor/jquery.nice-select.min.js')); ?>"></script>

<!-- Waypoints -->
<script src="<?php echo e(asset('customer/js/vendor/waypoints.min.js')); ?>"></script>

<!-- Counter Up -->
<script src="<?php echo e(asset('customer/js/vendor/counterup.min.js')); ?>"></script>

<!-- Count Down -->
<script src="<?php echo e(asset('customer/js/vendor/countdown.js')); ?>"></script>
<script src="<?php echo e(asset('customer/js/vendor/chart.min.js')); ?>"></script>

<!-- Index -->
<script src="<?php echo e(asset('customer/js/index.js')); ?>"></script>

<!-- Form Pass -->
<script src="<?php echo e(asset('customer/js/form-pass.js')); ?>"></script>

<?php echo $__env->yieldContent('script'); ?>

</body>

</html>
<?php /**PATH /var/www/html/china_hub/resources/views/customer/layouts/master.blade.php ENDPATH**/ ?>
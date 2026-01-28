<?php $__env->startSection('title','Email Password Link'); ?>

<?php $__env->startSection('content'); ?>
    <h2><?php echo e(__('Reset Your Password')); ?></h2>
    <form action="<?php echo e(route('customer.password.send')); ?>" method="post" >
        <?php echo csrf_field(); ?>
        <div class="input-group">
            <span><img src="<?php echo e(asset('customer/img/icons/mail.svg')); ?>" alt=""></span>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" placeholder="example@domain.com">
            <?php if($errors->has('email')): ?> <p><?php echo e($errors->first('email')); ?></p> <?php endif; ?>
        </div>
        <button type="submit" class="btn login-btn"><?php echo e(__('Send Verification Code')); ?></button>
    </form>
    <div class="login-footer">
        <a href="<?php echo e(route('customer.register')); ?>"><span><img src="<?php echo e(asset('customer/img/icons/user.svg')); ?>" alt=""></span><?php echo e(__('Create New Account')); ?></a>
        <a href="<?php echo e(route('customer.login')); ?>"><span><img src="<?php echo e(asset('customer/img/icons/lock1.svg')); ?>" alt=""></span><?php echo e(__('Login Here')); ?></a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/frontend/auth/passwords/email.blade.php ENDPATH**/ ?>
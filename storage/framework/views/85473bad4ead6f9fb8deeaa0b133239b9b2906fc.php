<?php $__env->startSection('title','Create New Account'); ?>

<?php $__env->startSection('content'); ?>
    <h2><?php echo e(__('Create Your Account')); ?></h2>
    <form action="<?php echo e(route('customer.register')); ?>" method="post" >
        <?php echo csrf_field(); ?>
        <div class="input-group">
            <span><img src="<?php echo e(asset('customer/img/icons/User.svg')); ?>" alt=""></span>
            <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" class="form-control" placeholder="Full Name">
            <?php if($errors->has('first_name')): ?> <p><?php echo e($errors->first('first_name')); ?></p> <?php endif; ?>
        </div>










        <div class="input-group">
            <span><img src="<?php echo e(asset('customer/img/icons/mail.svg')); ?>" alt=""></span>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" placeholder="example@domain.com">
            <?php if($errors->has('email')): ?> <p><?php echo e($errors->first('email')); ?></p> <?php endif; ?>
        </div>
        <div class="input-group">
            <span><img src="<?php echo e(asset('customer/img/icons/User.svg')); ?>" alt=""></span>
            <input type="text" name="username" value="<?php echo e(old('username')); ?>" class="form-control" placeholder="Username">
            <?php if($errors->has('username')): ?> <p><?php echo e($errors->first('username')); ?></p> <?php endif; ?>
        </div>
        <div class="input-group">
            <span><img src="<?php echo e(asset('customer/img/icons/Lock.svg')); ?>" alt=""></span>
            <span class="hide-pass">
                            <img src="<?php echo e(asset('customer/img/icons/Hide.svg')); ?>" alt="">
                            <img src="<?php echo e(asset('customer/img/icons/show.svg')); ?>" alt="">
                        </span>
            <input type="password" id="myPass" name="password" class="form-control" placeholder="Password">
            <?php if($errors->has('password')): ?> <p><?php echo e($errors->first('password')); ?></p> <?php endif; ?>
        </div>
        <div class="input-group">
            <span><img src="<?php echo e(asset('customer/img/icons/Lock.svg')); ?>" alt=""></span>
            <span class="hide-pas">
                            <img src="<?php echo e(asset('customer/img/icons/Hide.svg')); ?>" alt="">
                            <img src="<?php echo e(asset('customer/img/icons/show.svg')); ?>" alt="">
                        </span>
            <input type="password" id="myPas" name="password_confirmation" class="form-control" placeholder="Re-type Password">
        </div>
        <button type="submit" class="btn login-btn"><?php echo e(__('Create Account')); ?></button>
    </form>
    <div class="login-footer">
        <a href="<?php echo e(route('customer.login')); ?>"><span><img src="<?php echo e(asset('customer/img/icons/user.svg')); ?>" alt=""></span><?php echo e(__('Go to login')); ?></a>
        <a href="<?php echo e(route('customer.password.email')); ?>"><span><img src="<?php echo e(asset('customer/img/icons/lock1.svg')); ?>" alt=""></span><?php echo e(__('Forgot Password?')); ?></a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        let showPass1 = document.querySelector('.hide-pas');
        showPass1.addEventListener('click', function() {
            showPass1.classList.toggle("show-pass");
            var y = document.getElementById("myPas");
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        })

        let showPass = document.querySelector('.hide-pass');
        showPass.addEventListener('click', function() {
            showPass.classList.toggle("show-pass");
            var x = document.getElementById("myPass");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/frontend/auth/register.blade.php ENDPATH**/ ?>
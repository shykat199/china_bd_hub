<div class="row">
    <div class="col-lg-3">
        <p><?php echo e(__('First Name')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input name="first_name" type="text" required class="form-control <?php echo e($errors->has('first_name') ? ' is-invalid' : ''); ?>" value="<?php if($customer->first_name): ?><?php echo e($customer->first_name); ?><?php else: ?><?php echo e(old('first_name')); ?><?php endif; ?>" placeholder="Full Name">
            <?php if($errors->has('first_name')): ?>
                <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('first_name')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Mobile')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input name="mobile" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                   class="form-control <?php echo e($errors->has('mobile') ? ' is-invalid' : ''); ?>"
                   value="<?php if($customer->mobile): ?><?php echo e($customer->mobile); ?><?php else: ?><?php echo e(old('mobile')); ?><?php endif; ?>"
                   placeholder="Mobile" required>
            <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <label id="mobile-error" class="invalid-feedback error" for="mobile"><?php echo e($message); ?></label>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Email')); ?><span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input name="email" type="email" class="form-control <?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" value="<?php if($customer->email): ?><?php echo e($customer->email); ?><?php else: ?><?php echo e(old('email')); ?><?php endif; ?>" placeholder="Email" required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <label id="email-error" class="invalid-feedback error" for="email"><?php echo e($message); ?></label>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Password')); ?><span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input name="password" type="password" minlength="8" class="form-control <?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" value="" placeholder="Password" <?php if(Request::is('admin/customers/create')): ?> required <?php endif; ?>>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <label id="password-error" class="invalid-feedback error" for="password"><?php echo e($message); ?></label>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Confirm Password')); ?> <?php if(Request::is('admin/customers/create')): ?> <span class="text-red">*</span> <?php endif; ?> </p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input type="password" minlength="8" name="password_confirmation"
                   class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Confirm Password" <?php if(Request::is('admin/customers/create')): ?> required <?php endif; ?>>
            <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Address')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <textarea name="address" placeholder="Address" required class="form-control"><?php if($customer->address): ?><?php echo e($customer->address); ?><?php else: ?><?php echo e(old('address')); ?><?php endif; ?></textarea>
            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

</div>
<?php $__env->startPush('js'); ?>
    <script>
        $(document).ready(function () {
            "use strict";
            $("#customersForm").validate();
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/CustomerManagement/Resources/views/customers/form.blade.php ENDPATH**/ ?>
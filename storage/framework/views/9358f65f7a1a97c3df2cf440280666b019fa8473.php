<div class="row">
    <div class="col-lg-12">
        <?php if($errors->any()): ?>
            <ul class="alert alert-danger" role="alert">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Coupon Type')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <select name="type" class="form-select category form-control<?php echo e($errors->has('faq_category_id') ? ' is-invalid' : ''); ?>" required id="type">
                <option value=""><?php echo e(__('Select Category')); ?></option>
                <option value="product"><?php echo e(__('For Product')); ?></option>
                <option value="cart"><?php echo e(__('For Total Order')); ?></option>
            </select>

            <?php if($errors->has('type')): ?>
                <span class="invalid-feedback" role="alert">
                <strong><?php echo e($errors->first('type')); ?></strong>
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row" id="coupon-form">

</div>

<?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/coupon/form.blade.php ENDPATH**/ ?>
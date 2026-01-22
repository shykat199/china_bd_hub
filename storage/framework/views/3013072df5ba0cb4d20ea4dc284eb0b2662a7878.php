<div class="row">

    <div class="col-lg-3">
        <p><?php echo e(__('Brand Name')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input id="name" type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   name="name"
                   value="<?php if($brand->name): ?><?php echo e($brand->name); ?><?php else: ?><?php echo e(old('name')); ?><?php endif; ?>"
                   placeholder="Name"
                   required
                   autofocus>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Logo (120x80)')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group file-upload overflow-visible">
            <label class="file-title">Browse</label>
            <input id="logo" type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   name="image" accept="image/*"
                   value="<?php if($brand->image): ?><?php echo e($brand->image); ?><?php else: ?><?php echo e(old('image')); ?><?php endif; ?>"
                   <?php if(Request::is('brands/create')): ?>required <?php endif; ?>>
            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Slug')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input id="slug" type="text" class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="slug" value="<?php if($brand->slug): ?><?php echo e($brand->slug); ?><?php else: ?><?php echo e(old('slug')); ?><?php endif; ?>" required placeholder="Slug" autofocus>

            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Meta Title')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input id="meta_title" type="text" class="form-control <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   name="meta_title"
                   value="<?php if($brand->meta_title): ?><?php echo e($brand->meta_title); ?><?php else: ?><?php echo e(old('meta_title')); ?><?php endif; ?>"
                   required placeholder="Meta Title"
                   autofocus>

            <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Meta description')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <textarea class="form-control <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      name="meta_description"><?php if($brand->meta_description): ?><?php echo e($brand->meta_description); ?><?php else: ?><?php echo e(old('meta_description')); ?><?php endif; ?></textarea>

            <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Brand Order')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input id="brand_order" type="number" min="0" class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="order" value="<?php if($brand->order): ?><?php echo e($brand->order); ?><?php else: ?><?php echo e(old('order')); ?><?php endif; ?>" placeholder="Brand Order" autofocus>

            <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</div>

<?php $__env->startPush('js'); ?>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                // validate form on keyup and submit
                $("#brandForm").validate();

            });
        })(jQuery);

    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/brands/form.blade.php ENDPATH**/ ?>
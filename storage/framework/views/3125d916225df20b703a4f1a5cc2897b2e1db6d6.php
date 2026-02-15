<div class="row">

    <div class="col-lg-3">
        <p><?php echo e(__('Name of Area')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input name="name" type="text" required
                   class="form-control <?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>"
                   value="<?php if($shipping_area->name): ?><?php echo e($shipping_area->name); ?><?php else: ?><?php echo e(old('name')); ?><?php endif; ?>"
                   placeholder="Color Name">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error invalid-feedback" id="name-error" for="name"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Shipping Charge')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input type="number" name="charge" class="form-control" value="<?php if($shipping_area->charge): ?><?php echo e($shipping_area->charge); ?><?php else: ?><?php echo e(old('charge')); ?><?php endif; ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Status')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <select name="status"
                    class="form-select form-control<?php echo e($errors->has('status') ? ' is-invalid' : ''); ?>">
                <option value="1" <?php if($shipping_area->status==1): ?> selected <?php endif; ?>><?php echo e(__('Active')); ?></option>
                <option value="0" <?php if($shipping_area->status==0): ?> selected <?php endif; ?> ><?php echo e(__('Inactive')); ?></option>
            </select>
    </div>
</div>

<?php $__env->startPush('js'); ?>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {

                // validate form on keyup and submit
                $("#pageForm").validate({
                    ignore: ".note-editor *"
                });

                $('#editor').summernote({
                    tabsize: 2,
                    height: 120,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']]
                    ]
                })
            });
        })(jQuery);

    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/shipping_area/pages/form.blade.php ENDPATH**/ ?>
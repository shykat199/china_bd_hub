<div class="row">
    <div class="col-lg-3">
        <p><?php echo e(__('Name')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7 mb-2">
        <input id="name" type="text" class="form-control" name="name" value="<?php if($category->name): ?><?php echo e($category->name); ?><?php else: ?><?php echo e(old('name')); ?><?php endif; ?>" required placeholder="Name" autofocus>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Parent Category')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="overflow-visible">
            <select name="category_id" class="parent form-select form-control">
                <option value=""><?php echo e(__('Select Category')); ?></option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>">
                        <?php echo e($cat->name); ?>

                    </option>
                    <?php if(isset($cat->children)): ?>
                        <?php echo $__env->make('productmanagement::includes.category_option', [
                            'child' => 1,
                            'categories' => $cat->children,
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <?php if($category->category_id == null): ?>
        <div class="col-lg-3">
            <p><?php echo e(__('Ordering Number')); ?></p>
        </div>

        <?php
            $orderCount = \App\Models\Backend\Category::whereNull('category_id')->count() + 1;
        ?>
        <div class="col-lg-7">
            <div class="overflow-visible">
                <select name="cat_order" class="parent form-select form-control">
                    <option value="">Select Order</option>
                    <?php for($i = 1; $i <= $orderCount; $i++): ?>
                        <option value="<?php echo e($i); ?>"<?php echo e($category->cat_order == $i ? 'selected' : ''); ?>>
                            <?php echo e($i); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    <?php else: ?>

        <div class="col-lg-3">
            <p><?php echo e(__('Ordering Number')); ?></p>
        </div>

        <?php
            $orderCount = \App\Models\Backend\Category::whereNull('category_id')->count() + 1;
        ?>
        <div class="col-lg-7">
            <div class="overflow-visible">
                <select name="cat_order" class="parent form-select form-control">
                    <option value="">Select Order</option>
                    <?php for($i = 1; $i <= $orderCount; $i++): ?>
                        <option value="<?php echo e($i); ?>">
                            <?php echo e($i); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

    <?php endif; ?>
    <div class="col-lg-3">
        <p><?php echo e(__('Slug')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input id="slug" type="text" class="form-control" name="slug" value="<?php echo e($category->slug); ?>" required="" placeholder="Slug" autofocus="">
        </div>
    </div>

    <div class="col-lg-3 has-parent">
        <p><?php echo e(__('Banner(200x200)')); ?></p>
    </div>
    <div class="col-lg-7 mb-2 has-parent">
        <div class="input-group file-upload">
            <label class="file-title">Browse</label>
            <input id="banner" type="file" class="form-control" name="banner" accept="image/*">
        </div>
    </div>
    <div class="col-lg-3 has-parent">
        <p><?php echo e(__('Logo(32x32)')); ?></p>
    </div>
    <div class="col-lg-7 mb-3 has-parent">
        <div class="input-group file-upload">
            <label class="file-title">Browse</label>
            <input id="icon" type="file" class="form-control" name="icon" accept="image/*">
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Meta Title')); ?></p>
    </div>
    <div class="col-lg-7">
        <input name="meta_title" type="text" required class="form-control" value="<?php if($category->meta_title): ?><?php echo e($category->meta_title); ?><?php else: ?><?php echo e(old('meta_title')); ?><?php endif; ?>" placeholder="Meta Title">
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Meta description')); ?></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <textarea name="meta_description" class="form-control"><?php if($category->meta_description): ?><?php echo e($category->meta_description); ?><?php else: ?><?php echo e(old('meta_description')); ?><?php endif; ?></textarea>
        </div>
    </div>
    <div class="col-lg-3">
        <p><?php echo e(__('Commission Rate')); ?> <span class="text-red">*</span></p>
    </div>
    <div class="col-lg-7">
        <div class="input-group commission-group overflow-visible">
            <input type="number" min="0" step="0.1" max="100" name="commission_rate" class="commission-input" placeholder="Commission Rate" value="<?php if($category->commission_rate): ?><?php echo e($category->commission_rate); ?><?php else: ?><?php echo e(old('commission_rate')??0); ?><?php endif; ?>" min="1" required>
            <span class="commission-persent">%</span>
        </div>
    </div>
    <div class="col-lg-3">
    </div>
    <div class="col-lg-7">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="for_menu" name="for_menu" <?php if($category->for_menu): ?> checked <?php endif; ?>>
            <label class="form-check-label" for="for_menu">
                <?php echo e(__("Would you like to add this to the top menu?")); ?>

            </label>
        </div>
    </div>

</div>

<?php $__env->startPush('js'); ?>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $(".parent").select2();

                $('#name').keyup(function(event) {
                    $("input[name='slug']").val(clean($(this).val()));
                    $("input[name='meta_title']").val(clean($(this).val()));
                });

                checkCateId();
                $('.parent').on('change', function() {
                    checkCateId();
                })

                function checkCateId() {
                    if (!$('.parent').val()) {
                        $('.has-parent').removeClass('d-none');
                    } else {
                        $('.has-parent').addClass('d-none');
                    }
                }
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/categories/form.blade.php ENDPATH**/ ?>
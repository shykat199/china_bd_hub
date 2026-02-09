<?php $__env->startSection('title','Category - '); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
    <?php echo $__env->make('productmanagement::includes.product_management', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" Area-labelledby="add-category-tab">
                <div class="container content-title">
                    <h4><?php echo e(__('Category Information')); ?></h4>
                </div>
                <div class="container">
                    <form id="categoryForm" method="post" action="<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.categories.store')); ?><?php elseif(auth()->guard('seller')->check()): ?><?php echo e(route('seller.categories.store')); ?><?php endif; ?>" enctype="multipart/form-data" class="add-brand-form ajaxform_instant_reload">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('productmanagement::categories.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="col-lg-7 offset-3">
                            <div class="from-submit-btn">
                                <button class="submit-btn" type="submit"><?php echo e(__('Save')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/categories/create.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Pages - '); ?>
<?php $__env->startPush('css'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <div class="container">
            <div class="main-content default-manu">
                <?php echo $__env->make('backend.pages.shipping_area.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <!-- Tab Manu End  -->
                <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" Area-labelledby="add-category-tab">
                <div class="container content-title">
                    <h4><?php echo e(__('Add Shipping Area')); ?></h4>
                </div>
                    <form id="pageForm" method="post" action="<?php echo e(route('backend.shipping_area.store')); ?>" enctype="multipart/form-data" class="add-brand-form">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('backend.pages.shipping_area.pages.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="col-lg-7 offset-3">
                            <div class="from-submit-btn">
                                <button class="submit-btn" type="submit"><?php echo e(__('Save')); ?></button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
    </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/shipping_area/pages/create.blade.php ENDPATH**/ ?>
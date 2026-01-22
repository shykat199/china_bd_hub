<?php $__env->startSection('title', 'Product Update - '); ?>
<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('plugins/image-uploader/image-uploader.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <?php echo $__env->make('productmanagement::includes.product_management', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-product" Area-labelledby="add-product-tab">
                <form id="productForm" class="add-brand-form ajaxform_instant_reload"
                    action="<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.products.update', $product->id)); ?><?php elseif(auth()->guard('seller')->check()): ?><?php echo e(route('seller.products.update', $product->id)); ?><?php endif; ?>"
                    method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <?php echo $__env->make('productmanagement::products.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </form>
            </div>
        </div>
    <?php $__env->stopSection(); ?>
    <?php $__env->startPush('js'); ?>
        <script src="<?php echo e(asset('plugins/image-uploader/image-uploader.min.js')); ?>"></script>
        <script>
            $(function() {
                "use strict";
                $(document).ready(function() {
                    let preloaded = [];

                    var product_images = <?php echo json_encode($product->images); ?>;
                    product_images.forEach(image => {
                        preloaded.push({
                            id: image.id,
                            src: public_path + '/uploads/products/galleries/' + image.image
                        });
                    });

                    $('.input-images').imageUploader({
                        preloaded: preloaded,
                        imagesInputName: 'images',
                        preloadedInputName: 'old_images',
                        maxSize: 1024 * 10240,
                        maxFiles: 4,
                        mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
                        extensions: undefined
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/products/edit.blade.php ENDPATH**/ ?>
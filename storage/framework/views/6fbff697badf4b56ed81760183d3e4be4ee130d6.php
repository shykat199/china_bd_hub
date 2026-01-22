<div class="">
    <div class="content-tab-title px-0">
        <h4><?php echo e(__('Wholesale Management')); ?></h4>
    </div>
    <!-- Tab Manu Start -->
    <div class="nav nav-tabs p-tab-manu px-0" id="nav-tab" role="tablist">
        <?php
            $product_index_route =  route('backend.products.wholesale');
            $product_create_route =  route('backend.products.wholesale.form');
        ?>
        <?php if(auth()->user()->can('browse_products') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/products/wholesale')): ?>active <?php endif; ?>"
                    id="all-product-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#all-product"
                    type="button" role="tab" Area-controls="all-product" Area-selected="true"
                    <?php if(url()->full()!=$product_index_route): ?> onclick="location.href='<?php echo e($product_index_route); ?>'" <?php endif; ?>>
                <?php echo e(__('WholeSale Product')); ?>

            </button>
        <?php endif; ?>
        <?php if(auth()->user()->can('create_products') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/products/wholesale/form')): ?>active <?php endif; ?>"
                    id="add-product-tab"
                    data-bs-toggle="tab" data-bs-target="#add-product"
                    type="button" role="tab" Area-controls="add-product" Area-selected="true"
                    <?php if(url()->full()!=$product_create_route): ?> onclick="location.href='<?php echo e($product_create_route); ?>'" <?php endif; ?>><?php echo e(__('Add WholeSale')); ?>

            </button>
        <?php endif; ?>



    </div>
    <!-- Tab Manu End -->
</div>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/wholesales/wholesale_management.blade.php ENDPATH**/ ?>
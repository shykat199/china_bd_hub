<div class="container">
    <div class="content-tab-title">
        <h4><?php echo e(__('Product Management')); ?></h4>
    </div>
    <!-- Tab Manu Start -->
    <div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">
        <?php
            $product_index_route = auth('seller')->user() ? route('seller.products.index') : route('backend.products.index');
            $product_create_route = auth('seller')->user() ? route('seller.products.create') : route('backend.products.create');
            $category_index_route = auth('seller')->user() ? route('seller.categories.index') : route('backend.categories.index');
            $category_create_route = auth('seller')->user() ? route('seller.categories.create') : route('backend.categories.create');
            $brand_index_route = auth('seller')->user() ? route('seller.brands.index') : route('backend.brands.index');
            $brand_create_route = auth('seller')->user() ? route('seller.brands.create') : route('backend.brands.create');
        ?>
        <?php if(auth()->user()->can('browse_products') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/products','seller/products')): ?>active <?php endif; ?>"
                    id="all-product-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#all-product"
                    type="button" role="tab" Area-controls="all-product" Area-selected="true"
                    <?php if(url()->full()!=$product_index_route): ?> onclick="location.href='<?php echo e($product_index_route); ?>'" <?php endif; ?>>
                <?php echo e(__('All Product')); ?>

            </button>
        <?php endif; ?>
        <?php if(auth()->user()->can('create_products') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/products/create','seller/products/create')): ?>active <?php endif; ?>"
                    id="add-product-tab"
                    data-bs-toggle="tab" data-bs-target="#add-product"
                    type="button" role="tab" Area-controls="add-product" Area-selected="true"
                    <?php if(url()->full()!=$product_create_route): ?> onclick="location.href='<?php echo e($product_create_route); ?>'" <?php endif; ?>><?php echo e(__('Add Product')); ?>

            </button>
        <?php endif; ?>
        <?php if(auth()->user()->can('browse_categories') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/categories','seller/categories')): ?>active <?php endif; ?>"
                    id="category-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#category" type="button"
                    role="tab" Area-controls="category" Area-selected="false"
                    <?php if(url()->full()!=$category_index_route): ?> onclick="location.href='<?php echo e($category_index_route); ?>'" <?php endif; ?>><?php echo e(__('Category')); ?>

            </button>
        <?php endif; ?>
        <?php if(auth()->user()->can('create_categories') || auth()->user()->hasRole('super-admin')): ?>
            <button
                class="nav-link <?php if(Request::is('admin/categories/create','seller/categories/create')): ?>active <?php endif; ?>"
                id="add-category-tab"
                data-bs-toggle="tab" data-bs-target="#add-category"
                type="button" role="tab" Area-controls="add-category" Area-selected="false"
                <?php if(url()->full()!=$category_create_route): ?> onclick="location.href='<?php echo e($category_create_route); ?>'" <?php endif; ?>><?php echo e(__('Add Category')); ?>

            </button>
        <?php endif; ?>
        <?php if(auth()->user()->can('browse_brands') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/brands','seller/brands')): ?>active <?php endif; ?>" id="brand-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#brand" type="button"
                    role="tab" Area-controls="brand" Area-selected="false"
                    <?php if(url()->full()!=$brand_index_route): ?> onclick="location.href='<?php echo e($brand_index_route); ?>'" <?php endif; ?>><?php echo e(__('Brand')); ?>

            </button>
        <?php endif; ?>
        <?php if(auth()->user()->can('create_brands') || auth()->user()->hasRole('super-admin')): ?>
            <button class="nav-link <?php if(Request::is('admin/brands/create','seller/brands/create')): ?>active <?php endif; ?>"
                    id="add-brand-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#add-brand"
                    type="button" role="tab" Area-controls="add-brand" Area-selected="false"
                    <?php if(url()->full()!=$brand_create_route): ?> onclick="location.href='<?php echo e($brand_create_route); ?>'" <?php endif; ?>><?php echo e(__('Add Brand')); ?>

            </button>
        <?php endif; ?>
    </div>
    <!-- Tab Manu End -->
</div>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/includes/product_management.blade.php ENDPATH**/ ?>
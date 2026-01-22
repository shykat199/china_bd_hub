<?php $__env->startSection('title', 'Product - '); ?>
<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('plugins/image-uploader/image-uploader.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <?php echo $__env->make('productmanagement::includes.product_management', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-product" Area-labelledby="add-product-tab">
                <form id="productForm" class="add-brand-form ajaxform_instant_reload" action="<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.products.store')); ?><?php elseif(auth()->guard('seller')->check()): ?><?php echo e(route('seller.products.store')); ?><?php endif; ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="container content-title">
                        <h4><?php echo e(__('Product Information')); ?></h4>
                    </div>
                    <div class="container">
                        <div class="add-product-form">
                            <div class="row">
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Product Name')); ?>

                                        <span class="text-red">*</span>
                                    </p>
                                </div>
                                <div class="col-lg-4">
                                    <input id="name" type="text" class="form-control" name="name" placeholder="Product Name" autofocus required>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Minimum Qty')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-4">
                                    <input type="number" id="minimum_qty" name="minimum_qty" class="form-control" min="1" required>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Category')); ?> <span class="text-red">*</span> <a target="_blank" class="rounded-circle w-50 h-50" href="<?php echo e(route('backend.categories.create')); ?>"><i class="fas fa-plus-circle text-primary"></i></a></p>
                                </div>
                                <div class="col-lg-4">
                                    <select name="category_id" class="category form-select form-control">
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
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Brand')); ?> <a target="_blank" class="rounded-circle w-50 h-50" href="<?php echo e(route('backend.brands.create')); ?>"><i class="fas fa-plus-circle text-primary"></i></a></p>
                                </div>
                                <div class="col-lg-4">
                                    <select name="brand_id" class="brand form-select form-control">
                                        <option value=""><?php echo e(__('Select Brand')); ?></option>
                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($brand->id); ?>"> <?php echo e($brand->name); ?> </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Tags')); ?> </p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="sm-title-group">
                                        <div class="input-group overflow-visible">
                                            <select class="form-control tags" multiple="multiple" name="tags[]">
                                                <option value="">Select Tags</option>
                                            </select>
                                        </div>
                                        <small class="sm-text"><?php echo e(__('This is used for search. Input those words by which customer can find this product.')); ?></small>
                                    </div>
                                </div>
                                
                                
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Barcode')); ?> </p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="barcode" class="form-control" placeholder="Barcode">
                                    </div>
                                </div>
                                <?php
                                    $unites = DB::table('units')->get();
                                ?>

                                <div class="col-lg-2">
                                    <p><?php echo e(__('Unit')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <select name="unit" class="form-select form-control" required>
                                        <option value=""><?php echo e(__('Select Unit')); ?></option>
                                        <?php $__currentLoopData = $unites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e(ucfirst($unit->name)); ?>"><?php echo e(__($unit->name)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Refundable')); ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="form-check form-switch btn-one-off">
                                            <label>Disable</label>
                                            <input type="hidden" value="0" name="is_refundable">
                                            <input name="is_refundable" class="form-check-input" value="1" type="checkbox">
                                            <label>Enable</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Slug')); ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="slug" class="form-control" placeholder="Slug">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('SKU')); ?> </p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="sku" class="form-control" value="<?php echo e($sku); ?>" placeholder="sku">
                                    </div>
                                </div>
                                <?php if(auth()->user()->getRoleNames()->first() != 'Seller'): ?>
                                    <div class="col-lg-2">
                                        <p><?php echo e(__('Seller')); ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <select name="seller_id" class="seller form-select form-control">
                                            <option value=""><?php echo e(__('Select Seller')); ?></option>
                                            <?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($seller->id); ?>"><?php echo e($seller->company_name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Product Warranty')); ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="warranty" class="form-control" placeholder="Product Warranty">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <p><?php echo e(__('Return Policy')); ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="return_policy" class="form-control" placeholder="Product Return in Days">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container bg-tr">
                        <div class="row">
                            <div class="col-lg-8 center-content">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Product Images')); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Images')); ?> <span class="text-red">*</span></p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="sm-title-group">
                                                    <div class="input-images"></div>
                                                    <span class="sm-text product_image"><?php echo e(__('Use 330x430 size image for Best Fit.Minimum 1 and maximum 4 image.These images are visible in product details page gallery.')); ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Product Videos')); ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Video Provider')); ?></p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group file-upload">
                                                    <input type="text" name="video_provider" class="form-control" placeholder="Youtube">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Video Link')); ?></p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="sm-title-group">
                                                    <div class="input-group">
                                                        <input type="url" name="video_link" class="form-control" placeholder="Video Link">
                                                    </div>
                                                    <span class="sm-text product_image"><?php echo e(__('Use proper link without extra parameter. Don’t use short share link.')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Product variation')); ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion mb-4" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button text-center font-weight-bold d-inline-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" Area-expanded="true" Area-controls="collapseOne">
                                                        <?php echo e(__('Do you want to add variation for this product?')); ?>

                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse" Area-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="d-flex justify-content-between mb-3">
                                                            <h5><?php echo e(__('Variation wise stock')); ?></h5>
                                                            <button type="button" class="btn btn-warning btn-sm base-bg text-light another-vAreation"><i class="fa fa-plus-circle d-inline-block mt-1" Area-hidden="true"></i></button>
                                                        </div>
                                                        <div class="vAreants">
                                                            <div class="input-group mb-4 d-flex align-items-center">
                                                                <button type="button" class="btn btn-danger input-group-text btn-sm text-light remove-row"><i class="fas fa-trash d-inline-block mt-1"></i></button>
                                                                <select name="colors_new[]" class="form-control color-select">
                                                                    <option value="">-<?php echo e(__('Select Color')); ?>-</option>
                                                                    <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($color->id); ?>"><?php echo e($color->name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                                <select name="sizes_new[]" class="form-control">
                                                                    <option value="">-<?php echo e(__('Select Size')); ?>-</option>
                                                                    <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $sz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($sz->id); ?>"><?php echo e($sz->name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                                <input type="number" class="form-control variant-qty" placeholder="Enter quantity" name="quantities_new[]">
                                                                <div>
                                                                    <label for="variantImage">
                                                                        <img src="<?php echo e(asset('dummy-image-square.jpg')); ?>" alt="Choose Image" width="80" height="160" style="border-radius: 4px; margin: 3px">
                                                                    </label>
                                                                    <input id="variantImage" type="file" class="form-control d-none" name="variant_image_new[]" onchange="showImage(event)">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Product price + stock')); ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Unit price')); ?> <span class="text-red">*</span></p>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="overflow-visible">
                                                    <input name="unit_price" type="number" class="form-control" placeholder="0" min="0" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Purchase price')); ?></p>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="overflow-visible">
                                                    <input name="purchase_price" min="0" type="number" class="form-control" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Discount Type')); ?></p>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="overflow-visible">
                                                    <select name="discount_type" class="form-control">
                                                        <option value="">-<?php echo e(__('Select')); ?>-</option>
                                                        <option value="fixed"><?php echo e(__('Fixed')); ?></option>
                                                        <option value="percentage"><?php echo e(__('Percentage')); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Discount')); ?></p>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="overflow-visible">
                                                    <input name="discount" min="0" type="number" class="form-control" placeholder="0">
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Available Quantity')); ?> <span class="text-red">*</span></p>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="overflow-visible">
                                                    <input name="quantity" id="available_qty" min="1" type="number" class="form-control" placeholder="0" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Attributes')); ?></p>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="sm-title-group">
                                                    <div class="input-group">
                                                        <select name="attributes[]" multiple="multiple" class="attributes form-select" Area-label="Select Attribute">
                                                            <option value="">Select Attribute</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Product Description')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-lg-12">
                                                <textarea name="description" class="editor" id="textEditor"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('PDF Specification')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('PDF Specification')); ?></p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group file-upload">
                                                    <label class="file-title">Browse</label>
                                                    <input name="pdf_specification" type="file" class="form-control" accept="application/pdf">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('SEO Meta Tags')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Meta Title')); ?> </p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <input name="meta_title" type="text" class="form-control" placeholder="Meta Title">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Description')); ?></p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <textarea name="meta_description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <p><?php echo e(__('Meta Image')); ?></p>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group file-upload">
                                                    <label class="file-title">Browse</label>
                                                    <input name="meta_image" type="file" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 sidebar-items">
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Shipping Configuration')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e(__('Free Shipping')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_free_shipping">
                                                    <input name="is_free_shipping" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <p><?php echo e(__('Flat Rate')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_flat_rate">
                                                    <input name="is_flat_rate" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <p><?php echo e(__('Product Wise Shipping')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_product_wise_shipping">
                                                    <input name="is_product_wise_shipping" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <p><?php echo e(__('Is Product Quantity Multiply')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_quantity_multiply">
                                                    <input name="is_quantity_multiply" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Low Stock Quantity Warning')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-9">
                                                <p><?php echo e(__('Want to manage stock')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_manage_stock">
                                                    <input name="is_manage_stock" value="1" class="form-check-input" type="checkbox" checked>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <p><?php echo e(__('Qty')); ?></p>
                                            </div>
                                            <div class="col-lg-9">
                                                <input name="warning_quantity" type="number" class="form-control" min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Stock Visibility State')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e(__('Show Stock Quantity')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_show_stock_quantity">
                                                    <input name="is_show_stock_quantity" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <p><?php echo e(__('Show Stock with Text Only')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_show_stock_with_text_only">
                                                    <input name="is_show_stock_with_text_only" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <p><?php echo e(__('Hide Stock')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_hide_stock">
                                                    <input name="is_hide_stock" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Cash on Delivery')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e(__('Status')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_cash_on_delivery">
                                                    <input name="is_cash_on_delivery" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Featured')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e(__('Status')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_featured">
                                                    <input name="is_featured" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Best Selling')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e(__('Status')); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_best_sell">
                                                    <input name="is_best_sell" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e('Todays Deal'); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e('Status'); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_todays_deal">
                                                    <input name="is_todays_deal" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Flash Deal')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <p><?php echo e('Status'); ?></p>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-switch">
                                                    <input type="hidden" value="0" name="is_flesh_deal">
                                                    <input name="is_flash_deal" value="1" class="form-check-input" type="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Estimate Shipping Time')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0"><?php echo e(__('Inside Dhaka')); ?> <span class="text-red">*</span></p>
                                        <div class="mb-3">
                                            <select name="inside_shipping_days" class="form-control" required>
                                                <option value=""><?php echo e(__('Select Shipping(Inside Dhaka)')); ?></option>
                                                <option selected value="1-3 days"> 1-3 days </option>
                                                <option value="3-5 days"> 3-5 days </option>
                                                <option value="3-7 days"> 3-7 days </option>
                                                <option value="5-10 days"> 5-10 days </option>
                                                <option value="5-15 days"> 5-15 days </option>
                                                <option value="15-30 days"> 15-30 days </option>
                                            </select>
                                        </div>
                                        <p class="mb-0"><?php echo e(__('Outside Dhaka')); ?> <span class="text-red">*</span></p>
                                        <div class="mb-3">
                                            <select name="outside_shipping_days" class="form-control" required>
                                                <option value=""><?php echo e(__('Select Shipping(Outside Dhaka)')); ?></option>
                                                <option selected value="1-3 days"> 1-3 days </option>
                                                <option value="3-5 days"> 3-5 days </option>
                                                <option value="3-7 days"> 3-7 days </option>
                                                <option value="5-10 days"> 5-10 days </option>
                                                <option value="5-15 days"> 5-15 days </option>
                                                <option value="15-30 days"> 15-30 days </option>
                                            </select>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Vat & TAX')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-lg-12 mb-2">
                                                <div class="form-group">
                                                    <label for=""><?php echo e(__('Vat')); ?></label>
                                                    <input name="vat" min="0" type="number" class="form-control" placeholder="Enter vat amount">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="publish_stat"><?php echo e(__('Publish status')); ?> <span class="text-red">*</span></label>
                                            <select name="publish_stat" id="publish_stat" class="form-control" required>
                                                <option value="1"><?php echo e(__('Save As Draft')); ?></option>
                                                <option value="0"><?php echo e(__('Save & Unpublish')); ?></option>
                                                <option selected value="2"><?php echo e(__('Save & Publish')); ?></option>
                                            </select>
                                        </div>
                                        <button class="btn btn-warning submit-btn mb-4 mt-3 d-block w-100"><i class="fa-solid fa-floppy-disk"></i> <?php echo e(__('Save')); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php $__env->stopSection(); ?>
    <?php $__env->startPush('js'); ?>
        <?php echo $__env->make('productmanagement::products.product-js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <script src="<?php echo e(asset('plugins/image-uploader/image-uploader.min.js')); ?>"></script>
        <script>
                $(document).on('input', '.variant-qty', function () {
                    let totalQty = 0;

                    // Sum all variant quantities
                    $('.variant-qty').each(function () {
                        let qty = parseInt($(this).val());
                        if (!isNaN(qty) && qty > 0) {
                            totalQty += qty;
                        }
                    });

                    // If total quantity is 0, fallback to 1
                    totalQty = totalQty > 0 ? totalQty : 1;

                    // Update minimum quantity input
                    $('#available_qty')
                        .val(totalQty)
                        .attr('max', totalQty); // optional safety
                });
            </script>
        <script>
            $(document).ready(function() {
                "use strict";

                $('#name').keyup(function(event) {
                    $("input[name='slug']").val(clean($(this).val()));
                    $("input[name='meta_title']").val(clean($(this).val()));
                });

                $("input[name='unit_price']").change(function() {
                    var max = parseInt($(this).val());
                    if (max) {
                        $("input[name='discount']").attr('max', max);
                    }
                });

                $(".tags").select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    minimumResultsForSearch: Infinity,
                    placeholder: "Type and hit space to add a tag"
                });

                $(".courieres").select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    minimumResultsForSearch: Infinity,
                    placeholder: "Type and hit space to add a tag"
                });

                $(".attributes").select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    minimumResultsForSearch: Infinity,
                    placeholder: "Type and hit space to add a attribute"
                });
                $(".category").select2();
                $(".brand").select2();
                $(".seller").select2();
                $(".colors").select2({
                    placeholder: 'Select a Color'
                });
                $(".size").select2({
                    placeholder: 'Select a Size'
                });

                /*rich text editor set*/
                $('.editor').summernote({
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

                $('.input-images').imageUploader({
                    imagesInputName: 'images',
                    preloadedInputName: 'old'
                });



            });
            // variant image
            function showImage(event) {
                var input = $(event.target);
                var img = input.prev('label').find('img');
                var reader = new FileReader();
                reader.onload = function(e) {
                    img.attr('src', e.target.result);
                };
                reader.readAsDataURL(input[0].files[0]);
            }
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/products/create.blade.php ENDPATH**/ ?>
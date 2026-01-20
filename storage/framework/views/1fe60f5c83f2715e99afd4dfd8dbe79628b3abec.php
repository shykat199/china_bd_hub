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
                <input id="name" type="text" class="form-control" name="name" placeholder="Product Name" value="<?php echo e($product->name ?? ''); ?>" required>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Minimum Qty')); ?> <span class="text-red">*</span></p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="number" id="minimum_qty" name="minimum_qty" class="form-control" min="1" value="<?php echo e($product->minimum_qty ?? ''); ?>" required>
                </div>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Category')); ?> <span class="text-red">*</span> <a target="_blank" class="rounded-circle w-50 h-50" href="<?php echo e(route('backend.categories.create')); ?>"><i class="fas fa-plus-circle text-primary"></i></a></p>
            </div>
            <div class="col-lg-4">
                <select name="category_id" class="category form-select form-control<?php echo e($errors->has('category_id') ? ' is-invalid' : ''); ?>" required>
                    <option value=""><?php echo e(__('Select Category')); ?></option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php if($cat->id == $product->category_id || $cat->id == old('category_id')): ?> selected <?php endif; ?>>
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
                <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <label class="error " id="category_id-error" for="category_id"><?php echo e($message); ?></label>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Brand')); ?> <a target="_blank" class="rounded-circle w-50 h-50" href="<?php echo e(route('backend.brands.create')); ?>"><i class="fas fa-plus-circle text-primary"></i></a></p>
            </div>
            <div class="col-lg-4">
                <select name="brand_id" class="brand form-select form-control<?php echo e($errors->has('brand_id') ? ' is-invalid' : ''); ?>">
                    <option value=""><?php echo e(__('Select Brand')); ?></option>
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($brand->id); ?>" <?php if($brand->id == $product->brand_id || $brand->id == old('brand_id')): ?> selected <?php endif; ?>>
                            <?php echo e($brand->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <label class="error " id="brand_id-error" for="brand_id"><?php echo e($message); ?></label>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Tags')); ?> </p>
            </div>
            <div class="col-lg-4">
                <div class="sm-title-group">
                    <div class="input-group overflow-visible">
                        <select name="tags[]" multiple="multiple" class="tags form-select" Area-label="Select Tags">
                            <option value="">Select Attribute</option>
                            <?php if($product->tags != '' && $product->tags != 'null'): ?>
                                <?php $__currentLoopData = $product->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tag); ?>" selected><?php echo e($tag); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <span class="sm-text"><?php echo e(__('This is used for search. Input those words by which customer can find this product.')); ?></span>
                </div>
            </div>

            <div class="col-lg-2">
                <p><?php echo e(__('Barcode')); ?> </p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="text" name="barcode" class="form-control<?php echo e($errors->has('barcode') ? ' is-invalid' : ''); ?>" value="<?php if($product->barcode): ?> <?php echo e($product->barcode); ?><?php else: ?><?php echo e(old('barcode')); ?> <?php endif; ?>" placeholder="Barcode">
                    <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <label class="error " id="barcode-error" for="barcode"><?php echo e($message); ?></label>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-lg-2">
                <p><?php echo e(__('Unit')); ?> <span class="text-red">*</span></p>
            </div>
            <div class="col-lg-4">
                <select name="unit" required class="form-select form-control<?php echo e($errors->has('unit') ? ' is-invalid' : ''); ?>">
                    <option value=""><?php echo e(__('Select Unit')); ?></option>
                    <option value="Kg" <?php if($product->unit == 'Kg' || old('unit') == 'Kg'): ?> selected <?php endif; ?>><?php echo e(__('Kg')); ?>

                    </option>
                    <option value="Piece" <?php if($product->unit == 'Piece' || old('unit') == 'Piece'): ?> selected <?php endif; ?>><?php echo e(__('Piece')); ?>

                    </option>
                    <option value="Meter" <?php if($product->unit == 'Meter' || old('unit') == 'Meter'): ?> selected <?php endif; ?>><?php echo e(__('Meter')); ?>

                    </option>
                    <option value="Litre" <?php if($product->unit == 'Litre' || old('unit') == 'Litre'): ?> selected <?php endif; ?>><?php echo e(__('Litre')); ?>

                    </option>
                    <option value="Pound" <?php if($product->unit == 'Pound' || old('unit') == 'Pound'): ?> selected <?php endif; ?>><?php echo e(__('Pound')); ?>

                    </option>
                    <option value="Pair" <?php if($product->unit == 'Pair' || old('unit') == 'Pair'): ?> selected <?php endif; ?>><?php echo e(__('Pair')); ?>

                    </option>
                    <option value="Set" <?php if($product->unit == 'Set' || old('unit') == 'Set'): ?> selected <?php endif; ?>><?php echo e(__('Set')); ?>

                    </option>
                </select>
                <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <label class="error " id="unit-error" for="unit"><?php echo e($errors->first('unit')); ?></label>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Refundable')); ?></p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <div class="form-check form-switch btn-one-off">
                        <label>Disable</label>
                        <input type="hidden" value="0" name="is_refundable">
                        <input name="is_refundable" <?php if($product->is_refundable || old('is_refundable')): ?> checked <?php endif; ?> class="form-check-input" value="1" type="checkbox">
                        <label>Enable</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Slug')); ?></p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="text" name="slug" class="form-control<?php echo e($errors->has('slug') ? ' is-invalid' : ''); ?>" value="<?php if($product->slug): ?> <?php echo e($product->slug); ?><?php else: ?><?php echo e(old('slug')); ?> <?php endif; ?>" placeholder="Slug">
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <label class="error " id="slug-error" for="slug"><?php echo e($message); ?></label>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('SKU')); ?> </p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="text" name="sku" class="form-control<?php echo e($errors->has('sku') ? ' is-invalid' : ''); ?>" value="<?php if($product->sku): ?> <?php echo e($product->sku); ?><?php else: ?><?php echo e(old('sku')); ?> <?php endif; ?>" placeholder="sku">
                    <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <label class="error " id="sku-error" for="sku"><?php echo e($message); ?></label>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <?php if(auth()->user()->getRoleNames()->first() == 'Seller'): ?>
                <input type="hidden" name="seller_id" value="<?php echo e(auth()->id()); ?>">
            <?php else: ?>
                <div class="col-lg-2">
                    <p><?php echo e(__('Seller')); ?> <span class="text-red">*</span></p>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <select name="seller_id" class="seller form-select form-control<?php echo e($errors->has('seller_id') ? ' is-invalid' : ''); ?>" required>
                            <option value=""><?php echo e(__('Select Seller')); ?></option>
                            <?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($seller->id); ?>" <?php if($seller->id == $product->seller_id): ?> selected <?php endif; ?>><?php echo e($seller->company_name ?? ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['seller_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <label class="error" id="seller_id-error" for="seller_id"><?php echo e($message); ?></label>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-lg-2">
                <p><?php echo e(__('Product Warranty')); ?></p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="text" name="warranty" class="form-control" value="<?php if($product->warranty): ?> <?php echo e($product->warranty); ?><?php else: ?><?php echo e(old('warranty')); ?> <?php endif; ?>" placeholder="Product Warranty">
                </div>
            </div>
            <div class="col-lg-2">
                <p><?php echo e(__('Return Policy')); ?></p>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="text" name="return_policy" class="form-control" value="<?php if($product->return_policy): ?> <?php echo e($product->return_policy); ?><?php else: ?><?php echo e(old('return_policy')); ?> <?php endif; ?>" placeholder="Product Return in Days">
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
                    <h5><?php echo e(__('Product Images')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <p><?php echo e(__('Images')); ?> <?php if(Request::is('admin/products/create')): ?>
                                    <span class="text-red">*</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-lg-8">
                            <div class="sm-title-group">
                                <div class="input-images"></div>
                                <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <label class="error " id="images-error" for="images">
                                        <?php echo e($message); ?>

                                    </label>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                <input type="text" name="video_provider" class="form-control" placeholder="Youtube" value="<?php if($product->video && $product->video->video_provider != "''"): ?> <?php echo e($product->video->video_provider); ?><?php else: ?><?php echo e(old('video_provider')); ?> <?php endif; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p><?php echo e(__('Video Link')); ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="sm-title-group">
                                <div class="input-group">
                                    <input type="url" name="video_link" class="form-control" placeholder="Video Link" value="<?php if($product->video && $product->video->video_link != "''"): ?> <?php echo e($product->video->video_link); ?><?php else: ?><?php echo e(old('video_link')); ?> <?php endif; ?>">
                                </div>
                                <span class="sm-text product_image"><?php echo e(__('Use proper link without extra parameter. Don’t use short share link.')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Product VAreation')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="accordion mb-4" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-center font-weight-bold d-inline-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" Area-expanded="true" Area-controls="collapseOne">
                                    <?php echo e(__('Do you want to add Variation for this product?')); ?>

                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse <?php echo e($product->productstock ? 'show' : ''); ?>" Area-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5><?php echo e(__('Variation wise stock')); ?></h5>
                                        <button type="button" class="btn btn-warning btn-sm base-bg text-light another-vAreation"><i class="fa fa-plus-circle d-inline-block mt-1" Area-hidden="true"></i></button>
                                    </div>
                                    <div class="vAreants">
                                        <?php
                                            $count = 0;
                                        ?>
                                        <?php $__currentLoopData = $product->productstock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $productstock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input type="hidden" name="product_stock_id[]" value="<?php echo e($productstock->id); ?>">
                                            <div class="input-group mb-4 d-flex align-items-center">
                                                <button type="button" data-product_stock_id="<?php echo e($productstock->id); ?>" class="btn btn-danger input-group-text btn-sm text-light remove-row"><i class="fas fa-trash d-inline-block mt-1"></i></button>
                                                <select name="colors[]" class="form-control color-select">
                                                    <option value=""> <?php echo e(__('Select Color')); ?>-</option>
                                                    <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php echo e($productstock->color_id == $color->id ? 'selected' : ''); ?> value="<?php echo e($color->id); ?>"><?php echo e($color->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <select name="sizes[]" class="form-control">
                                                    <option value=""> <?php echo e(__('Select Size')); ?>-</option>
                                                    <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $sz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php echo e($productstock->size_id == $sz->id ? 'selected' : ''); ?> value="<?php echo e($sz->id); ?>"><?php echo e($sz->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <input type="number" class="form-control variant-qty" value="<?php echo e($productstock->quantities); ?>" placeholder="Enter quantity" name="quantities[]">
                                                <div>
                                                    <label for="variantImage<?php echo e($count); ?>">
                                                        <img src="<?php echo e(isset($productstock->variant_image) ? asset('uploads/products/galleries/' . $productstock->variant_image) : asset('dummy-image-square.jpg')); ?>" alt="Choose Image" width="80" height="160" style="border-radius: 4px; margin: 3px">
                                                    </label>
                                                    <input id="variantImage<?php echo e($count); ?>" type="file" class="form-control d-none" name="variant_image[]" data-variant_id="<?php echo e($productstock->id ?? ''); ?>" data-product_id="<?php echo e($productstock->product_id); ?>" onchange="showImage(event)">
                                                </div>
                                            </div>
                                            <?php
                                                $count++;
                                            ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="col-lg-8">
                            <div class="input-group overflow-visible">
                                <input name="unit_price" type="number" value="<?php echo e($product->unit_price); ?>" class="form-control" placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p><?php echo e(__('Purchase price')); ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group overflow-visible">
                                <input name="purchase_price" min="0" type="number" value="<?php echo e($product->purchase_price); ?>" class="form-control" placeholder="0">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p><?php echo e(__('Discount Type')); ?></p>
                        </div>
                        <div class="col-lg-8 mb-2">
                            <div class="overflow-visible">
                                <select name="discount_type" class="form-control">
                                    <option value="">-<?php echo e(__('Select')); ?>-</option>
                                    <option <?php echo e($product->discount_type == 'fixed' ? 'selected' : ''); ?> value="fixed"><?php echo e(__('Fixed')); ?></option>
                                    <option <?php echo e($product->discount_type == 'percentage' ? 'selected' : ''); ?> value="percentage"><?php echo e(__('Percentage')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p><?php echo e(__('Discount')); ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group overflow-visible">
                                <input name="discount" min="0" type="number" class="form-control" value="<?php echo e($product->discount); ?>" placeholder="0">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <p><?php echo e(__('Available Quantity')); ?> <span class="text-red">*</span></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group overflow-visible">
                                <input name="quantity" id="available_qty" min="1" type="number" class="form-control" placeholder="0" value="<?php echo e($product->quantity); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <p><?php echo e(__('Attributes')); ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="sm-title-group">
                                <div class="input-group">
                                    <select name="attributes[]" multiple="multiple" class="attributes form-select" Area-label="Select Attribute">
                                        <option value="">Select Attribute</option>
                                        <?php if($product->attributes != '' && $product->attributes != 'null'): ?>
                                            <?php $__currentLoopData = json_decode($product->attributes); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($attr); ?>" selected><?php echo e($attr); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Product Description')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <textarea name="description" class="editor" id="textEditor">
                                <?php if($product->description != "''"): ?>
<?php echo e($product->description); ?><?php else: ?><?php echo e(old('description')); ?>

<?php endif; ?>
                            </textarea>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('PDF Specification')); ?></h5>
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
                    <?php if($product->pdf_specification): ?>
                        <div class="row">
                            <div class="col-12">
                                <embed src="<?php echo e(URL::to('uploads/products/pdf') . '/' . $product->pdf_specification ?? ''); ?>" type="application/pdf" width="100%" height="350">
                            </div>
                        </div>
                    <?php endif; ?>
                    <br>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('SEO Meta Tags')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <p><?php echo e(__('Meta Title')); ?> </p>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <input name="meta_title" type="text" class="form-control" value="<?php echo e($product->meta_title); ?>" placeholder="Meta Title">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p><?php echo e(__('Description')); ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <textarea name="meta_description" class="form-control"> <?php if($product->meta_description): ?>
<?php echo e($product->meta_description); ?><?php else: ?><?php echo e(old('meta_description')); ?>

<?php endif; ?>
</textarea>
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
                            <?php $__errorArgs = ['meta_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label id="meta_image-error" class="error " for="meta_image">
                                    <?php echo e($message); ?>

                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                <input name="is_free_shipping" value="1" class="form-check-input" <?php if(($product->details && $product->details->is_free_shipping) || old('is_free_shipping') == 1): ?> checked <?php endif; ?> type="checkbox">
                            </div>
                        </div>
                        <div class="col-9">
                            <p><?php echo e(__('Flat Rate')); ?></p>
                        </div>
                        <div class="col-3">
                            <div class="form-switch">
                                <input type="hidden" value="0" name="is_flat_rate">
                                <input name="is_flat_rate" value="1" class="form-check-input" <?php if(($product->details && $product->details->is_flat_rate) || old('is_flat_rate') == 1): ?> checked <?php endif; ?> type="checkbox">
                            </div>
                        </div>
                        <div class="col-9">
                            <p><?php echo e(__('Product Wise Shipping')); ?></p>
                        </div>
                        <div class="col-3">
                            <div class="form-switch">
                                <input type="hidden" value="0" name="is_product_wise_shipping">
                                <input name="is_product_wise_shipping" value="1" class="form-check-input" <?php if($product->details && $product->details->is_product_wise_shipping): ?> checked <?php endif; ?> type="checkbox">
                            </div>
                        </div>
                        <div class="col-9">
                            <p><?php echo e(__('Is Product Quantity Multiply')); ?></p>
                        </div>
                        <div class="col-3">
                            <div class="form-switch">
                                <input type="hidden" value="0" name="is_quantity_multiply">
                                <input name="is_quantity_multiply" value="1" class="form-check-input" <?php if($product->details && $product->details->is_quantity_multiply): ?> checked <?php endif; ?> type="checkbox">
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
                                <input name="is_manage_stock" value="1" class="form-check-input" type="checkbox" <?php echo e($product->is_manage_stock ? 'checked' : ''); ?>>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <p><?php echo e(__('Qty')); ?></p>
                        </div>
                        <div class="col-lg-9">
                            <input name="warning_quantity" type="number" class="form-control" value="<?php echo e(optional($product->details)->warning_quantity); ?>">
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
                                <input name="is_show_stock_quantity" value="1" class="form-check-input" <?php if($product->details && $product->details->is_show_stock_quantity): ?> checked <?php endif; ?> <?php if(Request::is('admin/products/create')): ?> checked <?php endif; ?> type="checkbox">
                            </div>
                        </div>
                        <div class="col-9">
                            <p><?php echo e(__('Show Stock with Text Only')); ?></p>
                        </div>
                        <div class="col-3">
                            <div class="form-switch">
                                <input type="hidden" value="0" name="is_show_stock_with_text_only">
                                <input name="is_show_stock_with_text_only" value="1" class="form-check-input" <?php if($product->details && $product->details->is_show_stock_with_text_only): ?> checked <?php endif; ?> type="checkbox">
                            </div>
                        </div>
                        <div class="col-9">
                            <p><?php echo e(__('Hide Stock')); ?></p>
                        </div>
                        <div class="col-3">
                            <div class="form-switch">
                                <input type="hidden" value="0" name="is_hide_stock">
                                <input name="is_hide_stock" value="1" class="form-check-input" <?php if($product->details && $product->details->is_hide_stock): ?> checked <?php endif; ?> type="checkbox">
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
                                <input name="is_cash_on_delivery" value="1" class="form-check-input" <?php if($product->details && $product->details->is_cash_on_delivery): ?> checked <?php endif; ?> type="checkbox">
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
                                <input name="is_featured" value="1" class="form-check-input" <?php if($product->details && $product->details->is_featured): ?> checked <?php endif; ?> type="checkbox">
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
                                <input name="is_best_sell" value="1" class="form-check-input" <?php if($product->details && $product->details->is_best_sell): ?> checked <?php endif; ?> type="checkbox">
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
                                <input name="is_todays_deal" value="1" class="form-check-input" <?php if($product->details && $product->details->is_todays_deal): ?> checked <?php endif; ?> type="checkbox">
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
                                <input name="is_flash_deal" value="1" class="form-check-input" <?php if(optional($product->details)->is_flash_deal): ?> checked <?php endif; ?> type="checkbox">
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
                    <div class="input-group month overflow-visible mb-3">
                        <select name="inside_shipping_days" class="form-select form-control" required>
                            <option value=""><?php echo e(__('Select Shipping(Inside Dhaka)')); ?></option>
                            <option value="1-3 days" <?php if(optional($product->details)->inside_shipping_days == '1-3 days'): ?> selected <?php endif; ?>>
                                1-3 days
                            </option>
                            <option value="3-5 days" <?php if(optional($product->details)->inside_shipping_days == '3-5 days'): ?> selected <?php endif; ?>>
                                3-5 days
                            </option>
                            <option value="3-7 days" <?php if(optional($product->details)->inside_shipping_days == '3-7 days'): ?> selected <?php endif; ?>>
                                3-7 days
                            </option>
                            <option value="5-10 days" <?php if(optional($product->details)->inside_shipping_days == '5-10 days'): ?> selected <?php endif; ?>>
                                5-10 days
                            </option>
                            <option value="5-15 days" <?php if(optional($product->details)->inside_shipping_days == '5-15 days'): ?> selected <?php endif; ?>>
                                5-15 days
                            </option>
                            <option value="15-30 days" <?php if(optional($product->details)->inside_shipping_days == '15-30 days'): ?> selected <?php endif; ?>>
                                15-30 days
                            </option>
                        </select>
                    </div>

                    <p class="mb-0"><?php echo e(__('Outside Dhaka')); ?> <span class="text-red">*</span></p>
                    <div class="input-group month overflow-visible">
                        <select name="outside_shipping_days" class="form-select form-control" required>
                            <option value=""><?php echo e(__('Select Shipping(Outside Dhaka)')); ?></option>
                            <option value="1-3 days" <?php if(optional($product->details)->outside_shipping_days == '1-3 days'): ?> selected <?php endif; ?>>
                                1-3 days
                            </option>
                            <option value="3-5 days" <?php if(optional($product->details)->outside_shipping_days == '3-5 days'): ?> selected <?php endif; ?>>
                                3-5 days
                            </option>
                            <option value="3-7 days" <?php if(optional($product->details)->outside_shipping_days == '3-7 days'): ?> selected <?php endif; ?>>
                                3-7 days
                            </option>
                            <option value="5-10 days" <?php if(optional($product->details)->outside_shipping_days == '5-10 days'): ?> selected <?php endif; ?>>
                                5-10 days
                            </option>
                            <option value="5-15 days" <?php if(optional($product->details)->outside_shipping_days == '5-15 days'): ?> selected <?php endif; ?>>
                                5-15 days
                            </option>
                            <option value="15-30 days" <?php if(optional($product->details)->outside_shipping_days == '15-30 days'): ?> selected <?php endif; ?>>
                                15-30 days
                            </option>
                        </select>
                    </div>
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
                                <input name="vat" min="0" type="number" class="form-control" placeholder="Enter vat amount" value="<?php echo e(optional($product->details)->vat); ?>" step="any">
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

<?php $__env->startPush('js'); ?>
    <?php echo $__env->make('productmanagement::products.product-js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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

            // $(".courieres").select2({
            //     tags: true,
            //     tokenSeparators: [',', ' '],
            //     minimumResultsForSearch: Infinity,
            //     placeholder: "Type and hit space to add a tag"
            // });

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
        });

        // variant image
        function showImage(event) {
            var input = $(event.target);
            var file = input[0].files[0];
            var img = input.prev('label').find('img');

            var variantId = $(event.target).data('variant_id');
            var productId = $(event.target).data('product_id');

            if (variantId || productId) {
                var url = "<?php echo e(route('backend.variant.update.image')); ?>"
                var formData = new FormData();
                formData.append('variant_id', variantId);
                formData.append('product_id', productId);
                formData.append('image', file);
                formData.append('_token', "<?php echo e(csrf_token()); ?>");

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        notification('success', data.message);
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            img.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                var reader = new FileReader();
                reader.onload = function(e) {
                    img.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }


        }
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/products/form.blade.php ENDPATH**/ ?>
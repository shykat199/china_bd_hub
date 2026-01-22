<?php $__env->startSection('title','Edit Coupon | '); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <div class="container">
            <div class="content-tab-title">
                <h4><?php echo e(__('Edit Coupon')); ?></h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" Area-labelledby="add-category-tab">
                <div class="container">
                    <form id="faqForm" method="post" action="<?php echo e(route('backend.coupon.update',$coupon->id)); ?>" class="add-brand-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>

                        <div class="row">
                            <div class="col-lg-3">
                                <p><?php echo e(__('Coupon Type')); ?> <span class="text-red">*</span></p>
                            </div>
                            <div class="col-lg-7">
                                <div class="input-group">
                                    <select name="type" class="form-select category form-control<?php echo e($errors->has('faq_category_id') ? ' is-invalid' : ''); ?>" required id="type" disabled>
                                        <option value=""><?php echo e(__('Select Category')); ?></option>
                                        <option value="product" <?php echo e($coupon->type == 'product' ? 'selected' : ''); ?>><?php echo e(__('For Product')); ?></option>
                                        <option value="cart" <?php echo e($coupon->type == 'cart' ? 'selected' : ''); ?>><?php echo e(__('For Total Order')); ?></option>
                                    </select>

                                    <?php if($errors->has('type')): ?>
                                        <span class="invalid-feedback" role="alert">
                <strong><?php echo e($errors->first('type')); ?></strong>
            </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="coupon-form">
                            <?php if($coupon->type == 'product'): ?>
                                <div class="col-lg-3">
                                    <p><?php echo e(__('Coupon Code')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input name="code" type="text" required class="form-control" value="<?php echo e($coupon->code); ?>" placeholder="Alphabet & Number">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <p><?php echo e(__('Product')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <select name="products[]" class="form-select select2 category form-control<?php echo e($errors->has('faq_category_id') ? ' is-invalid' : ''); ?>" required multiple>
                                            <option value=""><?php echo e(__('Select Product')); ?></option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $__currentLoopData = $subCategory->subSubCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php $__currentLoopData = $subSubCategory->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($product->id); ?>" <?php echo e(in_array($product->id,$details->product_id) ? 'selected' : ''); ?>><?php echo e($category->name.' >>> '.$subCategory->name .' >>> '.$subSubCategory->name.' >>> '.$product->name); ?></span></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Number of Coupon')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input name="qty" type="number" required value="<?php echo e($coupon->qty); ?>" class="form-control" placeholder="<?php echo e(__('Total coupon can be used')); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Start')); ?></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group month overflow-visible">
                                        <input name="start" type="date" class="form-control" value="<?php echo e(date('Y-m-d',strtotime($coupon->start))??null); ?>">
                                        <?php $__errorArgs = ['start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <p><?php echo e(__('End')); ?></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group month overflow-visible">
                                        <input name="end" type="date" class="form-control" value="<?php echo e(date('Y-m-d',strtotime($coupon->end))??null); ?>">
                                        <?php $__errorArgs = ['end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <p><?php echo e(__('Discount')); ?></p>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group month overflow-visible">
                                        <input name="discount" type="number" class="form-control" value="<?php echo e($coupon->discount); ?>">
                                        <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group month overflow-visible">
                                        <select name="discount_type" class="form-select category form-control" required>
                                            <option value=""><?php echo e(__('Select Product')); ?></option>
                                            <option value="currency" <?php echo e($coupon->discount_type == 'currency' ? 'selected' : ''); ?>><?php echo e(__('$')); ?></option>
                                            <option value="percent" <?php echo e($coupon->discount_type == 'percent' ? 'selected' : ''); ?>><?php echo e(__('%')); ?></option>
                                        </select>
                                    </div>
                                </div>

                            <?php elseif($coupon->type == 'cart'): ?>
                                <div class="col-lg-3">
                                    <p><?php echo e(__('Coupon Code')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input name="code" type="text" required class="form-control" value="<?php echo e($coupon->code); ?>" placeholder="Alphabet & Number">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Minimum Shopping')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input name="min_buy" type="number" required class="form-control" value="<?php echo e($details->min_buy); ?>" placeholder="<?php echo e(__('Minimum shopping amount to eligible for this coupon')); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Maximum Discount')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input name="max_discount" type="number" value="<?php echo e($details->max_discount); ?>" required class="form-control" placeholder="<?php echo e(__('Maximum amount to be discount')); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Number of Coupon')); ?> <span class="text-red">*</span></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input name="qty" type="number" value="<?php echo e($coupon->qty); ?>" required class="form-control" placeholder="<?php echo e(__('Maximum amount to be discount')); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Discount')); ?></p>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group month overflow-visible">
                                        <input name="discount" type="number" value="<?php echo e($coupon->discount); ?>" class="form-control">
                                        <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group month overflow-visible">
                                        <select name="discount_type" class="form-select category form-control" required>
                                            <option value=""><?php echo e(__('Select Product')); ?></option>
                                            <option value="currency" <?php echo e($coupon->discount_type == 'currency' ? 'selected' : ''); ?>><?php echo e(__('$')); ?></option>
                                            <option value="percent" <?php echo e($coupon->discount_type == 'percent' ? 'selected' : ''); ?>><?php echo e(__('%')); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <p><?php echo e(__('Start')); ?></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group month overflow-visible">
                                        <input name="start" type="date" class="form-control" value="<?php echo e(date('Y-m-d',strtotime($coupon->start))??null); ?>">
                                        <?php $__errorArgs = ['start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <p><?php echo e(__('End')); ?></p>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group month overflow-visible">
                                        <input name="end" type="date" class="form-control" value="<?php echo e(date('Y-m-d',strtotime($coupon->end))??null); ?>">
                                        <?php $__errorArgs = ['end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="col-lg-7 offset-3">
                            <div class="from-submit-btn">
                                <button class="submit-btn" type="submit"><?php echo e(__('Update')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        $(".select2").select2(); // initialize select2

        $("#type").change(function(){
            var type = $(this).val();
            var csrf = "<?php echo e(@csrf_token()); ?>"
            $.ajax({
                url: "<?php echo e(route('backend.coupon.product')); ?>",
                data: {_token:csrf,type:type},
                type: "post",
                beforeSuccess: function(){
                    console.log('loading...')
                }
            }).done(function(e){
                $("#coupon-form").html(e);

                $(".select2").select2(); // initialize select2
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/coupon/edit.blade.php ENDPATH**/ ?>
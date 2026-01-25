<?php
    $bn_cart_button = Illuminate\Support\Facades\DB::table('bn_cart_button')->find(1);
?>
<div class="product-card">
    <div class="product-img">
        <a href="<?php echo e(route('product', $product->slug)); ?>">
            <?php if($product->images->first()->image ?? false): ?>
                <img src="<?php echo e(asset('uploads/products/galleries/' . $product->images->first()->image ?? '')); ?>" class="b-1" alt="<?php echo e($product->name); ?>">
            <?php endif; ?>

            <?php if($product->quantity <= 0 && $product->is_manage_stock): ?>
                <small class="sold-out">Stock out</small>
            <?php endif; ?>
        </a>
        <?php if(isset($product->details->flash_deal_title)): ?>
            <?php if($product->details->flash_deal_title == ''): ?>
                <span></span>
            <?php else: ?>
                <span class="tag"><?php echo e($product->details->flash_deal_title); ?></span>
            <?php endif; ?>
        <?php endif; ?>
        <ul class="product-cart">
            <li><a href="javascript:addToWishlist(<?php echo e($product->id); ?>)"><span class="icon"><i class="fa-regular fa-heart"></i></span></a></li>
            <li><a href="javascript:buyNow(<?php echo e($product->id); ?>)"><span class="text"><?php echo e(__('BUY NOW')); ?></span></a>
            </li>
            <li><a href="javascript:addToCart(<?php echo e($product->id); ?>)"><span class="icon"><i class="fas fa-<?php echo e($product->quantity ? 'cart-shopping' : 'circle-xmark text-danger'); ?>"></i></span></a>
            </li>
        </ul>
    </div>
    <div class="product-card-details w-100">

        <h5 class="title"><a href="<?php echo e(route('product', $product->slug)); ?>"><?php echo e($product->name); ?></a></h5>
        <div class="d-flex align-items-center gap-2">
            <?php if(hasPromotion($product->id)): ?>
                <span class="price"><?php echo e(currency(promotionPrice($product->id))); ?></span>
                <span class=""><del class="text-secondary"><?php echo e(currency($product->unit_price)); ?></del> <small class="text-secondary"><?php echo e(__('- ')); ?> <?php echo e(round((($product->unit_price - promotionPrice($product->id)) / $product->unit_price) * 100)); ?><?php echo e(__('%')); ?></small></span>
            <?php else: ?>
                <?php if($product->discount > 0): ?>
                    <span class="price" style="font-size: 20px"><?php echo e(currency($product->sale_price)); ?> </span>
                    <span style="padding-top: 2px">
                        <del class="text-secondary"><?php echo e(currency($product->unit_price)); ?></del>
                        <small class="text-secondary"> <?php echo e(__('- ')); ?><?php if($product->discount_type == 'percentage'): ?>
                                <?php echo e($product->discount); ?>

                            <?php elseif($product->discount_type == 'fixed'): ?>
                                <?php echo e(round(($product->discount / $product->unit_price) * 100)); ?>

                            <?php endif; ?><?php echo e(__('%')); ?>

                        </small>
                    </span>
                <?php else: ?>
                    <span class="price" style="font-size: 20px"><?php echo e(currency($product->unit_price)); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <!-- <div class="d-flex justify-content-between">
            <div class="star-rating">
                <div class="rateit" data-rateit-value="<?php echo e(productRating($product->reviews)); ?>" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
            </div>
        </div> -->


        <div class="d-flex gap-2 mt-2">
            <a class="flex-fill"
               href="<?php echo e($product->quantity <= 0 && $product->is_manage_stock ? 'javascript:void(0)' : 'javascript:addToCart(' . $product->id . ')'); ?>">
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm w-100"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Add to Cart"
                    <?php echo e($product->quantity <= 0 && $product->is_manage_stock ? 'disabled' : null); ?>>
                    <i class="fas fa-shopping-cart"></i>
                </button>
            </a>

            <a class="flex-fill"
               href="<?php echo e($product->quantity <= 0 && $product->is_manage_stock ? 'javascript:void(0)' : 'javascript:buyNow(' . $product->id . ')'); ?>">
                <button
                    type="button"
                    class="btn btn-danger btn-sm w-100 text-white"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Buy Now"
                    <?php echo e($product->quantity <= 0 && $product->is_manage_stock ? 'disabled' : null); ?>>
                    <i class="fas fa-bolt"></i>
                </button>
            </a>
        </div>

    <?php if($bn_cart_button->status): ?>
            <a href="<?php echo e($product->quantity <= 0 && $product->is_manage_stock ? 'javascript:void(0)' : 'javascript:addToCart(' . $product->id . ')'); ?>">
                <button class="btn btn-danger w-100 mt-2" style="border-radius: 0; color:white;" <?php echo e($product->quantity <= 0 && $product->is_manage_stock ? 'disabled' : null); ?>>
                    <i class="fas fa-shopping-cart"></i> অর্ডার করুন
                </button>
            </a>
        <?php endif; ?>
    </div>

    <!-- <div> -->

    <!-- </div> -->
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/product-card.blade.php ENDPATH**/ ?>
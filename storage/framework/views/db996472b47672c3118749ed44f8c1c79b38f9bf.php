<h6><?php echo e($title); ?></h6>
<div class="widget-popular">
    <ul>
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <?php if($product->images->first()->image ?? false): ?>
                <div class="pro-img">
                    <img src="<?php echo e(asset('uploads/products/galleries/'.$product->images->first()->image ?? '')); ?>" alt="<?php echo e($product->name); ?>" >
                </div>
                <?php endif; ?>
                <div class="pro-text">
                    <h6><a href="<?php echo e(route('product',$product->slug)); ?>"><?php echo e($product->name); ?></a></h6>
                    <div class="star-rating">
                        <div class="rateit" data-rateit-value="<?php echo e(productRating($product->reviews)); ?>" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                    </div>
                    <?php if(hasPromotion($product->id)): ?>
                        <p><?php echo e(currency(promotionPrice($product->id),2)); ?> <br><del class="text-secondary"><?php echo e(currency($product->unit_price,2)); ?></del></p>
                    <?php else: ?>
                        <?php if($product->discount > 0): ?>
                            <p><?php echo e(currency(($product->unit_price - $product->discount),2)); ?> <br><del class="text-secondary"><?php echo e(currency($product->unit_price,2)); ?></del></p>
                        <?php else: ?>
                            <p><?php echo e(currency($product->unit_price,2)); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/product-widget.blade.php ENDPATH**/ ?>
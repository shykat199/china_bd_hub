
<div class="sidebar-widget dd">
    <h6><?php echo e(__('Price')); ?></h6>
    <div class="price-range-wraper">
        <div class="price-wrap">
            <div class="price-input-wrapper1">
                <div class="bordered-price">
                    <span class="first"><?php echo e(userCurrency('symbol')); ?></span>
                    <input class="b price-check" id="price-check-b" type="number" placeholder="min" >
                </div>
                <span class="middle"><?php echo e(__(' - ')); ?></span>
                <div class="bordered-price">
                    <span class="last"><?php echo e(userCurrency('symbol')); ?> </span>
                    <input class="c price-check" id="price-check-c" type="number" placeholder="max" >
                </div>
                <button class="price-range-btnn"><i class="fa fa-play"></i></button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/china_hub/resources/views/components/frontend/price-widget.blade.php ENDPATH**/ ?>
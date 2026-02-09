<div class="content-tab-title">
    <h4><?php echo e(__('Shepping Area')); ?></h4>
</div>
<!-- Tab Manu Start  -->
<div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">

    <button class="nav-link <?php if(Request::is('admin/shipping_area')): ?>active <?php endif; ?>" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance"
            type="button" role="tab" Area-controls="appearance" Area-selected="false"
            <?php if(url()->full()!=route('backend.shipping_area.index')): ?> onclick="location.href='<?php echo e(route('backend.shipping_area.index')); ?>'" <?php endif; ?>
    ><?php echo e(__('Shipping Area')); ?>

    </button>
    <button class="nav-link <?php if(Request::is('admin/shipping_area/create')): ?>active <?php endif; ?>" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance"
            type="button" role="tab" Area-controls="appearance" Area-selected="false"
            <?php if(url()->full()!=route('backend.shipping_area.create')): ?> onclick="location.href='<?php echo e(route('backend.shipping_area.create')); ?>'" <?php endif; ?>
    ><?php echo e(__('Add Shipping Area')); ?>

    </button>

</div>
<?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/shipping_area/nav.blade.php ENDPATH**/ ?>
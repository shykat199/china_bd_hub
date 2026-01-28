<div class="container">
    <div class="content-tab-title">
        <h4><?php echo e(__('Customer Management')); ?></h4>
    </div>
    <!-- Tab Manu Start -->
    <div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">
        <button class="nav-link <?php if(Request::is('admin/customers')): ?>active <?php endif; ?>" id="all-customers-tab"
                data-bs-toggle="tab"
                data-bs-target="#all-customers" type="button" role="tab" Area-controls="all-customers"
                Area-selected="false"
                <?php if(url()->full()!=route('backend.customers.index')): ?> onclick="location.href='<?php echo e(route('backend.customers.index')); ?>'" <?php endif; ?>
        ><?php echo e(__('All Customers')); ?>

        </button>
        
        <button class="nav-link <?php if(Request::is('admin/suspended_customers')): ?>active <?php endif; ?>" id="suspended-customers-tab"
                data-bs-toggle="tab"
                data-bs-target="#suspended-customers" type="button" role="tab"
                Area-controls="suspended-customers" Area-selected="false"
                <?php if(url()->full()!=route('backend.customers.suspended')): ?> onclick="location.href='<?php echo e(route('backend.customers.suspended')); ?>'" <?php endif; ?>
        ><?php echo e(__('Suspended Customers')); ?>

        </button>
        <button class="nav-link <?php if(Request::is('admin/email_subscriber')): ?>active <?php endif; ?>" id="email_subscriber-tab"
                data-bs-toggle="tab"
                data-bs-target="#email_subscriber" type="button" role="tab"
                Area-controls="email_subscriber" Area-selected="false"
                <?php if(url()->full()!=route('backend.email_subscriber')): ?> onclick="location.href='<?php echo e(route('backend.email_subscriber')); ?>'" <?php endif; ?>
        ><?php echo e(__('Email Subscriber')); ?>

        </button>
    </div>
    <!-- Tab Manu End -->
</div>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/CustomerManagement/Resources/views/nav.blade.php ENDPATH**/ ?>
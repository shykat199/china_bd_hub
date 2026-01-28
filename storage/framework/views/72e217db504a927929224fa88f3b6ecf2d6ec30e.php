<div class="container">
    <div class="content-tab-title">
        <h4><?php echo e(__('User Permission')); ?> </h4>
    </div>
    <!-- Tab Manu Start -->
    <div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">
        <button class="nav-link <?php if(Request::is('admin/users','admin/users/*')): ?>active <?php endif; ?>" id="users-tab" data-bs-toggle="tab"
                data-bs-target="#users" type="button" role="tab" Area-controls="users" Area-selected="false"
                <?php if(url()->full()!=route('backend.users.index')): ?> onclick="location.href='<?php echo e(route('backend.users.index')); ?>'" <?php endif; ?>
        ><?php echo e(__('Users')); ?>

        </button>
        <button class="nav-link <?php if(Request::is('admin/roles','admin/roles/*')): ?>active <?php endif; ?>" id="roles-tab" data-bs-toggle="tab"
                data-bs-target="#roles" type="button" role="tab" Area-controls="roles" Area-selected="false"
                <?php if(url()->full()!=route('backend.roles.index')): ?> onclick="location.href='<?php echo e(route('backend.roles.index')); ?>'" <?php endif; ?>
        ><?php echo e(__('Roles')); ?>

        </button>
        <button class="nav-link <?php if(Request::is('admin/permissions','admin/permissions/*')): ?>active <?php endif; ?>" id="permission-tab"
                data-bs-toggle="tab" data-bs-target="#permission" type="button" role="tab" Area-controls="permission"
                Area-selected="false"
                <?php if(url()->full()!=route('backend.permissions.index')): ?> onclick="location.href='<?php echo e(route('backend.permissions.index')); ?>'" <?php endif; ?>
        ><?php echo e(__('Permission')); ?>

        </button>
    </div>
    <!-- Tab Manu End -->
</div><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/user_permission/nav.blade.php ENDPATH**/ ?>
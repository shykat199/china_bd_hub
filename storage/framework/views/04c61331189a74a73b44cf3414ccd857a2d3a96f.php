<?php if($paginator->hasPages()): ?>
    <div class="pagination-bar justify-content-center d-flex">
        <ul class="pagination">
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item active">
                    <a class="page-link" Area-label="Previous">
                        <span Area-hidden="true">«</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" Area-label="Previous" data-stat="<?php echo e($stat); ?>">
                        <span Area-hidden="true">«</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php $__currentLoopData = $paginator->getUrlRange(1,$paginator->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($paginator->currentPage() == $key): ?>
                    <li class="page-item active"><a class="page-link" ><?php echo e($key); ?></a></li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>" data-stat="<?php echo e($stat); ?>"><?php echo e($key); ?></a></li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" Area-label="Next" data-stat="<?php echo e($stat); ?>">
                        <span Area-hidden="true">»</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link active" Area-label="Next">
                        <span Area-hidden="true">»</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/china_hub/resources/views/components/customer/page-navigation.blade.php ENDPATH**/ ?>
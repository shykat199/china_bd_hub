<?php $__env->startSection('title','Commissions List - '); ?>
<?php $__env->startSection('content'); ?>
<div class="content-body">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="brand" role="tabpanel" Area-labelledby="brand-tab">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col">
                        <h5 class="py-2"><?php echo app('translator')->get('Commissions List'); ?></h5>
                    </div>
                    <div class="col-md-4 text-end align-self-center mt-2">
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search..." Area-describedby="basic-addon2">
                                <button class="input-group-text btn btn-warning" id="basic-addon2"><i class="fa fa-search" Area-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="responsibe-table">
                    <table class="table p-table">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('#'); ?></th>
                                <th><?php echo app('translator')->get('Category'); ?></th>
                                <th><?php echo app('translator')->get('Commission Rate'); ?></th>
                                <th><?php echo app('translator')->get('Created At'); ?></th>
                                <th><?php echo app('translator')->get('Updated At'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->index+1); ?></td>
                                <td><?php echo e($commission->name); ?></td>
                                <td><?php echo e($commission->commission_rate); ?>%</td>
                                <td><?php echo e(date('d-m-Y H:i A', strtotime($commission->created_at))); ?></td>
                                <td><?php echo e(date('d-m-Y H:i A', strtotime($commission->updated_at))); ?></td>
                                <td>
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0)" data-url="<?php echo e(route('backend.commissions.update', $commission->id)); ?>" data-rate="<?php echo e($commission->commission_rate); ?>" data-category="<?php echo e($commission->name); ?>" class="action edit-commission"><button><i class="fas fa-edit"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <?php echo e($commissions->links('vendor.pagination.bootstrap-4')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tab Content End -->
</div>

<div class="modal fade" id="commission-edit-modal" tabindex="-1" Area-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="commission-edit-form ajaxform_instant_reload" action="" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('put'); ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update commission rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" Area-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Category Name</label>
                        <input type="text" class="form-control category" id="recipient-name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for=""><?php echo e(__('Commission Rate')); ?></label>
                        <div class="input-group mb-3">
                            <input type="number" step="0.1" class="form-control rate" name="rate" placeholder="Commission Rate" Area-describedby="basic-addon2">
                            <span class="input-group-text btn btn-warning" id="basic-addon2">%</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning submit-btn"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        $('.edit-commission').on('click', function() {
            let url = $(this).data('url');
            let rate = $(this).data('rate');
            let category = $(this).data('category');
            $('.rate').val(rate);
            $('.category').val(category);
            $('.commission-edit-form').attr('action', url);
            $('#commission-edit-modal').modal('show');
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/commissions/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Stock List'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5><?php echo e(__('Manage Stock')); ?></h5>
                    <table class="table p-0 p-table table-bordered table-striped table-hover">
                        <thead class="bg-secondary text-light">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="selectAll">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th><?php echo e(__('ID')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Price')); ?></th>
                                <th><?php echo e(__('Stock')); ?></th>
                                <th><?php echo e(__('Sold')); ?></th>
                                <th><?php echo e(__('Viewed')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="borderd">
                                <td><input type="checkbox"
                                           class="rowCheckbox"
                                           value="<?php echo e($product->id); ?>"></td>
                                <td><?php echo e($product->id); ?></td>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e(currency($product->unit_price, 2)); ?></td>
                                <td>
                                    <div class="badge bg-success">
                                        <?php echo e($product->quantity); ?>

                                    </div>
                                </td>
                                <td><?php echo e($product->orders_sum_qty); ?></td>
                                <td><?php echo e($product->total_viewed); ?></td>
                                <td>
                                    <a href="<?php echo e(route('backend.stocks.show', $product->id)); ?>" class="text-warning">
                                        <i class="fa fa-eye" Area-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="row float-end pt-3">
                        <div class="col-12 text-center">
                            <?php echo e($products->links('vendor.pagination.bootstrap-4')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        $(document).on('change', '#selectAll', function () {
            $('.rowCheckbox').prop('checked', this.checked);
        });

        $(document).on('change', '.rowCheckbox', function () {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            }
        });

        $('#bulkDeleteBtn').on('click', function () {
            let ids = [];

            $('.rowCheckbox:checked').each(function () {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                alert('Please select at least one stock.');
                return;
            }

            if (!confirm('Are you sure you want to delete selected stock?')) {
                return;
            }

            $.ajax({
                url: "<?php echo e(route('backend.stockBulkDelete')); ?>",
                type: "POST",
                data: {
                    ids: ids,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function () {
                    $('#selectAll').prop('checked', false);
                    window.location.reload()
                },
                error: function () {
                    alert('Something went wrong.');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/stocks/index.blade.php ENDPATH**/ ?>
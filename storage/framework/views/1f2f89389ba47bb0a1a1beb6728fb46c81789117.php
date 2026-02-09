<?php $__env->startSection('title','Brands - '); ?>
<?php $__env->startPush('css'); ?>
    <?php echo $__env->make('backend.includes.datatable_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
    <?php echo $__env->make('productmanagement::includes.product_management', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="brand" role="tabpanel" Area-labelledby="brand-tab">
                <div class="container">
                    <div class="content-table mt-0">
                        <table id="mDataTable" class="table p-table">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="selectAll">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th scope="col"><?php echo e(__('ID')); ?></th>
                                <th scope="col"><?php echo e(__('Name')); ?></th>
                                <th scope="col"><?php echo e(__('Logo')); ?></th>
                                <th scope="col"><?php echo e(__('Slug')); ?></th>
                                <th scope="col"><?php echo e(__('Order')); ?></th>
                                <th scope="col"><?php echo e(__('Status')); ?></th>
                                <th scope="col"><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <?php echo $__env->make('backend.includes.datatable_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        $(function() {
            "use strict";

            $(document).ready(function(){
                // DataTable
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.brand.list')); ?><?php elseif(auth()->guard('seller')->check()): ?><?php echo e(route('seller.brand.list')); ?><?php endif; ?>",

                    // 🔥 never sort by checkbox column
                    order: [[1, 'desc']],

                    columns: [
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return `<input type="checkbox" class="rowCheckbox" value="${data}">`;
                            }
                        },
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'logo', orderable: false, searchable: false },
                        { data: 'slug' },
                        { data: 'order' },
                        { data: 'is_active', orderable: false },
                        { data: 'action', orderable: false, searchable: false }
                    ]
                });

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
                        alert('Please select at least one category.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected categories?')) {
                        return;
                    }

                    $.ajax({
                        url: "<?php echo e(route('backend.brands.bulk-delete')); ?>",
                        type: "POST",
                        data: {
                            ids: ids,
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function () {
                            $('#selectAll').prop('checked', false);
                            table.ajax.reload(null, false); // reload without page reset
                        },
                        error: function () {
                            alert('Something went wrong.');
                        }
                    });
                });

            });

            $(document).on('click','#mDataTable .status', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var brand_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +<?php if(auth()->guard('admin')->check()): ?>'/admin/brand/changeStatus'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/brand/changeStatus'<?php endif; ?>,
                    data: {'status': status, 'brand_id': brand_id,'field': 'is_active'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/brands/index.blade.php ENDPATH**/ ?>
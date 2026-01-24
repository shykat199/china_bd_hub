<?php $__env->startSection('title','Category - '); ?>
<?php $__env->startPush('css'); ?>
    <?php echo $__env->make('backend.includes.datatable_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
    <?php echo $__env->make('productmanagement::includes.product_management', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="category" role="tabpanel" Area-labelledby="category-tab">
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
                                <th scope="col"><?php echo e(__('Id')); ?></th>
                                <th scope="col"><?php echo e(__('Name')); ?></th>
                                <th scope="col"><?php echo e(__('Image')); ?></th>
                                <th scope="col"><?php echo e(__('Parent')); ?></th>
                                <th scope="col"><?php echo e(__('Display')); ?></th>
                                <th scope="col"><?php echo e(__('Sort')); ?></th>
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
            $(document).on('click','#mDataTable .status', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var cat_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +<?php if(auth()->guard('admin')->check()): ?>'/admin/category/changeStatus'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/category/changeStatus'<?php endif; ?>,
                    data: {'status': status, 'cat_id': cat_id,'field': 'is_active'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
            $(document).on('click','#mDataTable .display_out_website', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var cat_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +<?php if(auth()->guard('admin')->check()): ?>'/admin/category/changeStatus'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/category/changeStatus'<?php endif; ?>,
                    data: {'status': status, 'cat_id': cat_id,'field': 'show_in_home'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });

            $(document).ready(function(){
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "<?php echo e(route('backend.category.list')); ?>",

                    order: [[0, 'desc']], // sort by id

                    columns: [
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `
                                <input type="checkbox"
                                       class="rowCheckbox"
                                       value="${data}">
                            `;
                                        }
                        },
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'image', orderable: false, searchable: false },
                        { data: 'category_id' },
                        { data: 'show_in_home', orderable: false },
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
                        url: "<?php echo e(route('backend.categories.bulk-delete')); ?>",
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
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/categories/index.blade.php ENDPATH**/ ?>
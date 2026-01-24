<?php $__env->startSection('title','Email Subscriber - '); ?>
<?php $__env->startPush('css'); ?>
    <?php echo $__env->make('backend.includes.datatable_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
    <?php echo $__env->make('customermanagement::nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="all-customers" Area-labelledby="all-customers-tab">
                <div class="container">

                    <div class="content-table mt-0">
                        <table id="mDataTable" class="table p-table">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th scope="col"><?php echo e(__('Email')); ?></th>
                                <th scope="col"><?php echo e(__('Status')); ?></th>
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
        "use strict";

        $(function () {

            $(document).ready(function(){
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.email_subscriber.list')); ?><?php elseif(auth()->guard('seller')->check()): ?><?php echo e(route('seller.email_subscriber.list')); ?><?php endif; ?>",

                    order: [[0, 'desc']],

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
                        { data: 'email'},
                        { data: 'opt_out' },
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
                        alert('Please select at least one Email Subscriber.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected Email Subscriber?')) {
                        return;
                    }

                    $.ajax({
                        url: "<?php echo e(route('backend.subscriberBulkDelete')); ?>",
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
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +<?php if(auth()->guard('admin')->check()): ?>'/admin/email_subscriber/changeStatus'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/email_subscriber/changeStatus'<?php endif; ?>,
                    data: {'status': status, 'id': id,'field': 'opt_out'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/CustomerManagement/Resources/views/email_subscriber/index.blade.php ENDPATH**/ ?>
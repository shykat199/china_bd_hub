<?php $__env->startSection('title','Customer - '); ?>
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
                                <th scope="col"><?php echo e(__('Name')); ?></th>
                                <th scope="col"><?php echo e(__('Email')); ?></th>
                                <th scope="col"><?php echo e(__('Phone Number')); ?></th>
                                <th scope="col"><?php echo e(__('Gender')); ?></th>
                                <th scope="col"><?php echo e(__('Status')); ?></th>
                                <th scope="col"><?php echo e(__('Suspended')); ?></th>
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
        <!-- modal content -->
        <div class="modal fade" id="exampleModal" tabindex="-1" Area-labelledby="exampleModalLabel" Area-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('New message')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" Area-label="Close"></button>
                    </div>
                    <form id="messageForm">
                        <div class="modal-body">
                            <input type="hidden" name="email" id="email" value="">
                            <input type="hidden" name="name"  id="name" value="">
                            <div class="mb-3 form-group ">
                                <label for="message-text" class="col-form-label"><?php echo e(__('Subject')); ?>:</label>
                                <input name="subject" class="form-control" type="text" required>
                            </div>
                            <div class="mb-3 form-group ">
                                <label for="message-text" class="col-form-label"><?php echo e(__('Message')); ?>:</label>
                                <textarea name="message" rows="3" class="form-control" required id="message-text"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                            <button type="submit" id="submitMessageForm" class="btn btn-primary"><?php echo e(__('Send message')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                    ajax: "<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.customer.list')); ?><?php elseif(auth()->guard('seller')->check()): ?><?php echo e(route('seller.customer.list')); ?><?php endif; ?>",

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
                        { data: 'last_name' },
                        { data: 'email'},
                        { data: 'mobile' },
                        { data: 'gender' },
                        { data: 'is_active' },
                        { data: 'is_suspended' },
                        { data: 'action',searchable:false,sortable:false },
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
                        alert('Please select at least one user.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected user?')) {
                        return;
                    }

                    $.ajax({
                        url: "<?php echo e(route('backend.suspendUserBulkDelete')); ?>",
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
                    url: public_path +<?php if(auth()->guard('admin')->check()): ?>'/admin/customer/changeStatus'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/customer/changeStatus'<?php endif; ?>,
                    data: {'status': status, 'id': id,'field': 'is_active'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
            $(document).on('click','#mDataTable .suspend', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +<?php if(auth()->guard('admin')->check()): ?>'/admin/customer/changeStatus'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/customer/changeStatus'<?php endif; ?>,
                    data: {'status': status, 'id': id,'field': 'is_suspended'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
            $(document).on("click", ".message-btn", function () {
                var recipient = $(this).data('recipient');
                var recipient_name = $(this).data('recipient_name');
                $(".modal-body").find('#email').val(recipient);
                $(".modal-body").find('#name').val(recipient_name);
            });

            $(document).on("submit", "#messageForm", function (e) {
                e.preventDefault();
                var form = document.getElementById('messageForm');
                var message = form.querySelector('.modal-body textarea').value;
                var subject = form.querySelector('.modal-body input[name="subject"]').value;
                var email = form.querySelector('.modal-body input[name="email"]').value;
                var name = form.querySelector('.modal-body input[name="name"]').value;

                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: public_path + <?php if(auth()->guard('admin')->check()): ?>'/admin/customer/sendMail'<?php elseif(auth()->guard('seller')->check()): ?>'/seller/customer/sendMail'<?php endif; ?>,
                    data: {'subject':subject,'name': name, 'email': email, 'message': message},
                    success: function (data) {
                        $('#exampleModal').modal('hide');
                        notification('success', data.message);
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/CustomerManagement/Resources/views/customers/index.blade.php ENDPATH**/ ?>
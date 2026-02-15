<?php $__env->startSection('title', 'Sizes - '); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <?php echo $__env->make('productmanagement::includes.product_management', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-brand" role="tabpanel" Area-labelledby="add-brand-tab">
                <div class="container">
                    <div class="row bg-white d-flex justify-content-center gap-5">

                        <div class="col-lg-8 col-sm-12">
                            <div class="mb-2">
                                <h4 class="text-center">Size</h4>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-6 mb-2 ms-auto text-end">
                                <form action="<?php echo e(route('backend.variant.size')); ?>" method="GET" id="limitForm">

                                    <select name="limit"
                                            class="form-select"
                                            onchange="this.form.submit()">
                                        <?php $__currentLoopData = [10, 25, 50, 100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $limit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($limit); ?>"
                                                <?php echo e(request('limit', 10) == $limit ? 'selected' : ''); ?>>
                                                <?php echo e($limit); ?> per page
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </form>
                            </div>
                            <form action="<?php echo e(route('backend.variant.store')); ?>" class="d-flex gap-1" style="width: 100%" method="POST">
                                <?php echo csrf_field(); ?>
                                <div style="width: 100%">
                                    <input type="text" class="form-control" name="size" placeholder="Size Name" value="<?php echo e(old('color')); ?>">
                                    <span class="text-danger"><?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success text-white">Add</button>
                                </div>
                            </form>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                            <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </th>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="ids[]" value="<?php echo e($size->id); ?>"
                                                       data-bulk="true"
                                                       class="wholesaleCheckbox rowCheckbox">
                                            </td>
                                            <th scope="row"><?php echo e($size->id); ?></th>
                                            <td><?php echo e($size->name); ?></td>
                                            <td>
                                                <!-- EDIT -->
                                                <button
                                                    class="btn btn-sm btn-primary edit-btn"
                                                    data-id="<?php echo e($size->id); ?>"
                                                    data-name="<?php echo e($size->name); ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editColorModal">
                                                    Edit
                                                </button>

                                                <!-- DELETE -->
                                                <form action="<?php echo e(route('variant.size.delete', $size->id)); ?>"
                                                      method="POST"
                                                      class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button class="btn btn-sm btn-danger text-white"
                                                            onclick="return confirm('Are you sure?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($sizes->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editColorModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" id="editColorForm">
                    <?php echo csrf_field(); ?>

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Size</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name"
                                       class="form-control"
                                       id="editName" required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary text-white"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button class="btn btn-success text-white">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('custom-script'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function () {

                    const id   = this.dataset.id;
                    const name = this.dataset.name;

                    document.getElementById('editName').value = name;

                    // 🔥 THIS IS THE FIX
                    document.getElementById('editColorForm').action =
                        "<?php echo e(url('variants-size/update')); ?>/" + id;
                });
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectAll = document.getElementById('selectAll');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            /* =====================================================
               1. LOCK STATUS CHECKBOXES (IMMUTABLE FOR BULK)
            ===================================================== */
            function lockStatusCheckboxes() {
                document.querySelectorAll('input[data-bulk="false"]').forEach(cb => {

                    // store original state once
                    if (!cb.dataset.locked) {
                        cb.dataset.locked = cb.checked ? '1' : '0';
                    }

                    // stop all bubbling / row triggers
                    ['click', 'change', 'mousedown'].forEach(evt => {
                        cb.addEventListener(evt, e => e.stopPropagation());
                    });

                    // force state back if anything toggles it
                    cb.checked = cb.dataset.locked === '1';
                });
            }

            lockStatusCheckboxes();

            /* =====================================================
               2. SELECT ALL (ONLY ROW CHECKBOXES)
            ===================================================== */
            if (selectAll) {
                selectAll.addEventListener('change', function () {

                    document.querySelectorAll('input[data-bulk="true"]').forEach(cb => {
                        cb.checked = this.checked;
                    });

                    // re-assert status states
                    lockStatusCheckboxes();
                });
            }

            /* =====================================================
               3. ROW CHECKBOX CHANGE → SYNC SELECT ALL
            ===================================================== */
            function bindRowCheckboxEvents() {
                document.querySelectorAll('input[data-bulk="true"]').forEach(cb => {
                    if (cb.dataset.bound) return;

                    cb.dataset.bound = '1';

                    cb.addEventListener('change', function () {

                        const total = document.querySelectorAll('input[data-bulk="true"]').length;
                        const checked = document.querySelectorAll('input[data-bulk="true"]:checked').length;

                        if (selectAll) {
                            selectAll.checked = total === checked;
                        }

                        lockStatusCheckboxes();
                    });
                });
            }

            bindRowCheckboxEvents();

            /* =====================================================
               4. BULK DELETE AJAX
            ===================================================== */
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function () {

                    const checked = document.querySelectorAll('input[data-bulk="true"]:checked');

                    if (!checked.length) {
                        alert('Please select at least one item');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected items?')) {
                        return;
                    }

                    const ids = Array.from(checked).map(cb => cb.value);

                    fetch("<?php echo e(route('size.bulkDelete')); ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                        },
                        body: JSON.stringify({ ids })
                    })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                location.reload();
                            } else {
                                alert('Delete failed');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Something went wrong');
                        });
                });
            }

        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/variants/sizes.blade.php ENDPATH**/ ?>
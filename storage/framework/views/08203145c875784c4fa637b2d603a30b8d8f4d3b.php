<script>
    let count = <?php echo json_encode($count ?? 0, 15, 512) ?>;

    $('.another-vAreation').on('click', function() {
        count++
        var inputs = `<div class="input-group mb-4 d-flex align-items-center">
                            <button type="button" class="btn btn-danger input-group-text btn-sm text-light remove-row"><i class="fas fa-trash d-inline-block mt-1"></i></button>
                            <select name="colors_new[]" class="form-control color-select">
                                <option value="">-<?php echo e(__('Select Color')); ?>-</option>
                                <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($color->id); ?>"><?php echo e($color->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select name="sizes_new[]" class="form-control">
                                <option value="">-<?php echo e(__('Select Size')); ?>-</option>
                                <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $sz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sz->id); ?>"><?php echo e($sz->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <input type="number" class="form-control variant-qty" placeholder="Enter quantity" name="quantities_new[]">
                            <div>
                                <label for="variantImage${count}">
                                    <img src="<?php echo e(asset('dummy-image-square.jpg')); ?>" alt="Choose Image" width="80" height="160" style="border-radius: 4px; margin: 3px">
                                </label>
                                <input id="variantImage${count}" type="file" class="form-control d-none" name="variant_image_new[]" onchange="showImage(event)">
                            </div>
                        </div>`;

        $('.vAreants').append(inputs);
        initColorSelect2()
    })

    function initColorSelect2() {
        $('.color-select').select2({
            tags: true,
            placeholder: 'Select or add color',
            createTag: function (params) {
                const term = $.trim(params.term);
                if (term === '') return null;

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });
    }
    initColorSelect2();

    $(document).on('click', '.remove-row', function() {
        var productStockId = $(this).data("product_stock_id");
        var button = $(this)
        if (productStockId) {
            var url = `/admin/variants/delete-variant/${productStockId}`
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                    url: url,
                    type: "delete",
                    dataType: "json",
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    success: function(res) {
                        if (res.status) {
                            notification('success', res.message);
                            button.parent('.input-group').remove();
                        } else {
                            notification('error', res.message);
                        }
                    }
                });
                }
            })

        } else {
            button.parent('.input-group').remove();
        }
    })
</script>
<?php /**PATH /var/www/html/china_hub/app/Modules/Backend/ProductManagement/Resources/views/products/product-js.blade.php ENDPATH**/ ?>
<?php $__env->startPush('css'); ?>
    <?php echo $__env->make('backend.includes.datatable_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <style>
        .card{
            background: #ffff;
        }
        .custom-btn-list button {
            background: transparent;
            border: 0;
        }

        .custom-btn-list button i, .custom-btn-list a i {
            color: #444;
            font-size: 16px;
        }

        .button-list.custom-btn-list a, .button-list.custom-btn-list button {
            margin: 3px 5px;
            padding: 0;
        }

        .action2-btn {
            margin: 0;
            padding: 0;
            margin-bottom: 20px;
        }

        .action2-btn li {
            display: inline-block;
            list-style: none;
            margin: 2px 0;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

<div class="row mt-2">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Order <?php echo e($order->order_no); ?></h4>
        </div>
    </div>
</div>

<div class="row order_page">

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('backend.update-custom-order',$order->order_no)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="container mt-4">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <!-- Products Select -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Products *</label>
                                <select name="product_id" id="productSelect" class="form-select">
                                    <option value="">Select Product..</option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"><?php echo e($value->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                         <strong><?php echo e($message); ?></strong>
                                        </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Products Table -->
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Sell Price</th>
                                        <th>Discount</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="cartTable">
                                    <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <tr id="row-<?php echo e($details->id); ?>">
                                            <td>
                                                <?php $__currentLoopData = $details->product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <img src="<?php echo e(asset('uploads/products/galleries/'.$img->image)); ?>" class="me-1 mb-1 rounded" width="40" height="40">
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>

                                            <td>
                                                <?php echo e($details->product->name); ?>

                                                <input type="hidden" name="products[<?php echo e($details->product->id); ?>][id]" value="<?php echo e($details->id); ?>">
                                            </td>

                                            <td>
                                                <input type="number"
                                                       name="products[<?php echo e($details->id); ?>][qty]"
                                                       class="form-control form-control-sm text-center qty"
                                                       value="<?php echo e($details->qty); ?>" min="1">
                                            </td>

                                            <td>
                                                <input type="text" class="form-control form-control-sm text-end price" value="<?php echo e($details->sale_price); ?>" readonly>
                                            </td>

                                            <td>
                                                <input type="number"
                                                       name="products[<?php echo e($details->id); ?>][discount]"
                                                       class="form-control form-control-sm text-end discount"
                                                       value="<?php echo e($details->discount); ?>" min="0">
                                            </td>

                                            <td class="fw-semibold text-end row-subtotal">
                                                <?php echo e($details->total_price); ?>

                                            </td>

                                            <td>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger remove-row text-white"
                                                        data-id="<?php echo e($details->id); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Customer + Summary -->
                            <div class="row g-4">

                                <!-- Customer Info -->
                                <div class="col-md-6">
                                    <input type="text" name="shipping_name"
                                           class="form-control mb-3" value="<?php echo e($order->shipping_name); ?>"
                                           placeholder="Shipping Name">



















                                    <input type="text" name="customer_number"
                                           class="form-control mb-3" value="<?php echo e($order->customer->mobile); ?>"
                                           placeholder="Customer Number" required>
                                    <?php $__errorArgs = ['customer_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                             <strong><?php echo e($message); ?></strong>
                                            </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    <textarea name="address"
                                              class="form-control mb-3"
                                              rows="3"
                                              placeholder="Address"><?php echo $order->shipping_address_1; ?></textarea>
                                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                             <strong><?php echo e($message); ?></strong>
                                            </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                                    <select name="shipping_areas" id="shipping_areas_select" class="form-select mb-3">
                                        <option value="">Select area..</option>
                                        <?php $__currentLoopData = $shipping_areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option data-charge = <?php echo e($area->charge); ?> value="<?php echo e($area->id); ?>"><?php echo e($area->name); ?> - charge <?php echo e($area->charge); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!-- Order Summary -->
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td class="text-end">
                                                <span class="sub-total sub-total"><?php echo e($totalPrice = $order->details->sum('total_price')); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Shipping Fee</td>
                                            <td class="text-end">
                                                <input type="number"
                                                       name="shipping_fee"
                                                       class="form-control form-control-sm text-end shipping_fee"
                                                       value="<?php echo e($order->shipping_cost ?? 0); ?>">
                                            </td>
                                        </tr>
                                        <tr class="fw-semibold">
                                            <td>Total</td>
                                            <td class="text-end">
                                                <span class="grand-total"><?php echo e($order->total_price); ?></span>
                                            </td>

                                            <input type="hidden"
                                                   name="grand_total"
                                                   class="form-control form-control-sm text-end grand_total"
                                                   value="<?php echo e(($order->shipping_cost ?? 0) + $order->total_price); ?>">
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <!-- Submit Button -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100 py-3 fw-semibold text-white">
                                    Update Order
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>

    
    
    
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    

    
    
    
    
    
</script>

<script>
    $(document).ready(function () {

        const productUrl = "<?php echo e(route('backend.create-order-getProduct', ':id')); ?>";

        $('#productSelect').on('change', function () {

            let productId = $(this).val();
            if (!productId) return;

            let url = productUrl.replace(':id', productId);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (product) {

                    $('.no-product').remove();

                    // Prevent duplicate product
                    if ($('#row-' + product.id).length) {
                        $('#productSelect').val('');
                        return;
                    }

                    let imagesHtml = '';
                    product.images.forEach(img => {
                        imagesHtml += `
                        <img src="${img}"
                             class="me-1 mb-1 rounded"
                             width="40" height="40">
                    `;
                    });

                    let row = `
                   <tr id="row-new-${product.id}">
    <td>${imagesHtml}</td>

    <td>
        ${product.name}
        <input type="hidden"
               name="products[new_${product.id}][product_id]"
               value="${product.id}">
    </td>

    <td>
        <input type="number"
               name="products[new_${product.id}][qty]"
               class="form-control form-control-sm text-center qty"
               value="1" min="1">
    </td>

    <td>
        <input type="text"
               class="form-control form-control-sm text-end price"
               value="${product.sale_price}"
               readonly>
    </td>

    <td>
        <input type="number"
               name="products[new_${product.id}][discount]"
               class="form-control form-control-sm text-end discount"
               value="0" min="0">
    </td>

    <td class="fw-semibold text-end row-subtotal">
        ${parseFloat(product.sale_price).toFixed(2)}
    </td>

    <td>
        <button type="button"
                class="btn btn-sm btn-danger remove-row text-white"
                data-id="new_${product.id}">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
                `;

                    $('#cartTable').append(row);

                    calculateTotals(); // ✅ recalculate totals
                    $('#productSelect').val('');
                }
            });
        });

        // remove row
        $(document).on('click', '.remove-row', function () {

            let id = $(this).data('id');
            $('#row-' + id).remove();

            if ($('#cartTable tr').length === 0) {
                $('#cartTable').html(`
                <tr class="no-product">
                    <td colspan="7" class="text-muted text-center">
                        No products added
                    </td>
                </tr>
            `);

                // Reset totals
                $('.sub-total').text('0.00');
                $('.grand-total').text('0.00');
                $('.grand_total').val(0);
                $('input[name="shipping_fee"]').val(0);

                return;
            }

            calculateTotals();
        });


        // when qty, discount, or shipping changes
        $(document).on('input', '.qty, .discount, input[name="shipping_fee"]', function () {
            calculateTotals();
        });

        function calculateTotals() {

            let subTotal = 0;
            let shippingFee = parseFloat($('input[name="shipping_fee"]').val()) || 0;

            $('#cartTable tr').each(function () {

                // Skip empty row
                if (!$(this).find('.qty').length) return;

                let qty = parseFloat($(this).find('.qty').val()) || 0;
                let price = parseFloat($(this).find('.price').val()) || 0;
                let discount = parseFloat($(this).find('.discount').val()) || 0;

                let rowSubTotal = (qty * price) - discount;
                if (rowSubTotal < 0) rowSubTotal = 0;

                $(this).find('.row-subtotal').text(rowSubTotal.toFixed(2));

                subTotal += rowSubTotal;
            });

            let grandTotal = subTotal + shippingFee;

            $('.sub-total').text(subTotal.toFixed(2));
            $('.grand-total').text(grandTotal.toFixed(2));
            $('.grand_total').val(grandTotal.toFixed(2));
        }

        $('#shipping_areas_select').on('change', function () {

            let charge = $(this).find(':selected').data('charge');

            // If no area selected
            if (charge === undefined) {
                charge = 0;
            }

            // Update shipping fee input
            $('input[name="shipping_fee"]').val(parseFloat(charge).toFixed(2));

            // Recalculate totals
            calculateTotals();
        });

    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/OrderManagement/Resources/views/orders/edit_order.blade.php ENDPATH**/ ?>
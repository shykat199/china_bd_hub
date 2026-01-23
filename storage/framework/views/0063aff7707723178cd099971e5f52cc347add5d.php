<?php $__env->startSection('title', 'Checkout'); ?>
<?php $__env->startPush('custom-css'); ?>
    <style>
        label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            height: 42px;
            border-radius: 4px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Billing Details Start -->
<section class="billing-details bg-light">
    <form action="<?php echo e(route('buynow.store', ['product_id' => $product->id])); ?>" method="post" class="ajaxform_instant_reload">
        <?php echo csrf_field(); ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow rounded-3">
                        <div class="card-body">
                            <div class="buy-more-check">
                                <h4 class="text-center">Order Submit OR</h4>
                                <h5 class="text-center"><a class="text-primary" href="<?php echo e(url('/')); ?>">Buy More</a> <span class="animation-pulse"></span></h5>
                            </div>
                            <div class="login-form mt-4">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label for="level" class="form-label">
                                            <?php echo e(__('নাম')); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               id="first_name"
                                               name="first_name"
                                               class="form-control"
                                               placeholder="Enter your full name">
                                    </div>

                                    <?php if(Auth::user()): ?>
                                        <input type="hidden" name="user_id" value="<?php echo e(auth()->user()->id); ?>">
                                    <?php endif; ?>

                                    <div class="col-12 mb-3">
                                        <label for="mobile" class="form-label">
                                            <?php echo e(__('মোবাইল')); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               id="mobile"
                                               name="mobile"
                                               class="form-control"
                                               placeholder="Enter your mobile number">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="billing_address" class="form-label">
                                            <?php echo e(__('ঠিকানা')); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               id="billing_address"
                                               name="billing_address"
                                               class="form-control"
                                               value="<?php echo e($billing->address_1 ?? ''); ?>"
                                               placeholder="Enter billing address">
                                    </div>
                                    <div class="col-sm-12 mb-4">
                                        <span class="label"><?php echo e(__('Shipping Area')); ?> <span class="text-danger">*</span></span>

                                        <div class="col-12">
                                            <select name="shipping_cost" id="shipping_cost">
                                                <?php $__currentLoopData = $shipping_areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipping_area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($shipping_area->charge); ?>"><?php echo e($shipping_area->name); ?> - <?php echo e($shipping_area->charge); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-12 text-center mb-3">
                                        <button type="submit" class="btn btn-primary px-5 py-2 submit-btn text-white">
                                            <?php echo e(__('অর্ডার কনফার্ম করুন')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-center"><?php echo e(__('ORDER SUMMARY')); ?></h4>
                            <div class="right-form mt-2">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Items')); ?></th>
                                            <th><?php echo e(__('Quantity')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                            <th><?php echo e(__('Action')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="row">
                                                <img height='70px' width="70px" class="rounded-circle" src="<?php echo e(asset('uploads/products/galleries')); ?>/<?php echo e($product->images->first()->image ?? ''); ?>" class="b-1" alt="<?php echo e($product->name); ?>">
                                                <p>
                                                    <?php echo e($product->name); ?>

                                                    <?php if(request('color')): ?>
                                                    <span class="badge bg-light text-dark">(<?php echo e(request('color')); ?>)</span>
                                                    <?php endif; ?>
                                                    <?php if(request('size')): ?>
                                                    - <span class="badge bg-light text-dark">(<?php echo e(request('size')); ?>)</span>
                                                    <?php endif; ?>
                                                </p>
                                            </td>
                                            <td>
                                                <?php echo e(request('qty')); ?>

                                            </td>
                                            <td>
                                                <?php echo e(currency($product->sale_price * request('qty'))); ?>

                                            </td>
                                            <td class="table-close-btn">
                                                <a href="<?php echo e(route('product', $product->slug)); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.995 511.995">
                                                        <path d="M437.126,74.939c-99.826-99.826-262.307-99.826-362.133,0C26.637,123.314,0,187.617,0,256.005s26.637,132.691,74.993,181.047c49.923,49.923,115.495,74.874,181.066,74.874s131.144-24.951,181.066-74.874C536.951,337.226,536.951,174.784,437.126,74.939z M409.08,409.006c-84.375,84.375-221.667,84.375-306.042,0c-40.858-40.858-63.37-95.204-63.37-153.001s22.512-112.143,63.37-153.021c84.375-84.375,221.667-84.355,306.042,0C493.435,187.359,493.435,324.651,409.08,409.006z" />
                                                        <path d="M341.525,310.827l-56.151-56.071l56.151-56.071c7.735-7.735,7.735-20.29,0.02-28.046 c-7.755-7.775-20.31-7.755-28.065-0.02l-56.19,56.111l-56.19-56.111c-7.755-7.735-20.31-7.755-28.065,0.02c-7.735,7.755-7.735,20.31,0.02,28.046l56.151,56.071l-56.151,56.071c-7.755,7.735-7.755,20.29-0.02,28.046c3.868,3.887,8.965,5.811,14.043,5.811s10.155-1.944,14.023-5.792l56.19-56.111l56.19,56.111c3.868,3.868,8.945,5.792,14.023,5.792c5.078,0,10.175-1.944,14.043-5.811C349.28,331.117,349.28,318.562,341.525,310.827z" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="order-cart mt-4">
                                    <ul id="order-details">
                                        <li><?php echo e(__('Subtotal')); ?><span class="sub-total"><?php echo e(currency($product->sale_price * request('qty'), 2)); ?></span></li>
                                        <li><?php echo e(__('Shipping Charge')); ?>

                                            
                                            <?php
                                            $shipping_cost = request('area') == 'inside' ? $product->shipping_cost : $product->outside_shipping_cost;
                                            ?>
                                            <?php if(optional($product->details)->is_free_shipping): ?>
                                            <span class="total-shipping"><?php echo e(__('Free')); ?></span>
                                            <?php else: ?>
                                            <span class="total-shipping"><?php echo e(currency(Cookie::get('totalShipping'))); ?></span>
                                            <?php endif; ?>
                                        </li>
                                        <?php if(Cookie::get('coupon_discount')): ?>
                                        <li><?php echo e(__('Coupon')); ?><span><?php echo e(currency(Cookie::get('coupon_discount'))); ?></span></li>
                                        <?php endif; ?>
                                        <li><?php echo e(__('Total')); ?><span class="grand-total">
                                                <?php if(optional($product->details)->is_free_shipping): ?>
                                                <?php echo e(currency(($product->sale_price * request('qty') ) - Cookie::get('coupon_discount'))); ?>

                                                <?php else: ?>
                                                <?php echo e(currency(($product->sale_price * request('qty') + Cookie::get('totalShipping')) - Cookie::get('coupon_discount'))); ?>

                                                <?php endif; ?>

                                            </span></li>
                                    </ul>
                                </div>

                                <h5 class="mb-2"><?php echo e(__('Promotional Code')); ?> (<?php echo e(__('Have a coupon?')); ?>)</h5>
                                <?php if(Cookie::get('coupon_infos')): ?>
                                <?php
                                $coupon_infos = json_decode(Cookie::get('coupon_infos'));
                                ?>
                                <div class="right-search input-group mb-0">
                                    <input type="text" name="code" id="code" placeholder="Enter your coupon code" value="<?php echo e($coupon_infos->code); ?>">
                                    <button type="button" class="btn-anime" id="apply-coupon"><?php echo e(__('Apply Coupon')); ?></button>
                                </div>
                                <div class="row mb-2 coupon-infos">
                                    <div class="col-11">
                                        <h5 class="text-warning"><?php echo e($coupon_infos->code); ?></h5>
                                    </div>
                                    <div class="col-1">
                                        <h5><a href="javascript:void(0)" onclick="removeCoupon()"><i class="fa-solid fa-xmark text-danger"></i></a></h5>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="right-search input-group mb-0">
                                    <input type="text" name="code" id="code" placeholder="Enter your coupon code">
                                    <button type="button" class="btn-anime" id="apply-coupon"><?php echo e(__('Apply Coupon')); ?></button>
                                </div>
                                <div class="row mb-2 coupon-infos">
                                    
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="pay-modal" tabindex="-1" Area-labelledby="exampleModalLabel" Area-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pay amount <span class="pay-amount"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" Area-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bank" class="col-form-label">Select Bank</label>
                            <select name="bank" id="bank" class="form-control">
                                <option value="bKash">bKash</option>
                                <option value="Rocket">Rocket</option>
                                <option value="Nagad">Nagad</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="paid_amount" class="col-form-label">Paid Amount</label>
                            <input type="number" step="any" class="form-control" name="paid_amount" id="paid_amount">
                        </div>
                        <div class="mb-3">
                            <label for="transaction_id" class="col-form-label">Transaction Id</label>
                            <input type="text" id="transaction_id" step="any" class="form-control" name="transaction_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn border-danger text-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn theme-btn completed">Complete</button>
                    </div>
                </div>
            </div>
        </div>
        


    </form>
</section>
<!-- Billing Details End -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $("#apply-coupon").click(function() {
        var code = $("#code").val();
        var csrf = "<?php echo e(@csrf_token()); ?>"
        $.ajax({
            url: "<?php echo e(route('customer.coupon')); ?>",
            data: {
                _token: csrf,
                code: code
            },
            type: 'post'
        }).done(function(res) {
            if (res.status !== 'error') {
                $('.coupon-infos').html(res.coupon_infos)
                $("#order-details").html(res.after_coupon);
                Swal.fire({
                    toast: true,
                    position: 'bottom-end', // bottom-right
                    icon: 'success',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

            } else {

                Swal.fire({
                    toast: true,
                    position: 'bottom-end', // bottom-right
                    icon: 'error',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

            }
        });
    })
    $('#shipping_cost').on('change', function() {

        var shipping_cost = $(this).val();
        var sub_total = $('.sub-total').text().replace('৳', '');
        var total = $('.grand-total').text().replace('৳', '');
        var grand_total = parseFloat(sub_total) + parseFloat(shipping_cost);
        $('.total-shipping').text('৳' + shipping_cost);
        $('.grand-total').text('৳' + grand_total);

        var csrf = "<?php echo e(@csrf_token()); ?>"

        $.ajax({
            url: `<?php echo e(route('customer.updateShipping')); ?>`,
            type: 'POST',
            data: {
                shipping_cost: shipping_cost,
                _token: csrf
            },
            success: function (response) {
                console.log('Shipping updated:', response);
            }
        });


    })

    function removeCoupon() {
        var csrf = "<?php echo e(@csrf_token()); ?>"
        $.ajax({
            url: "<?php echo e(route('customer.coupon.remove')); ?>",
            data: {
                _token: csrf
            },
            type: 'post'
        }).done(function(res) {
            $("#code").val('');
            $('.coupon-infos').html('');
            $("#order-details").html(res.data);

            Swal.fire({
                toast: true,
                position: 'bottom-end', // bottom-right
                icon: 'success',
                title: res.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    }

    $('.mobile-banking').on('click', function() {
        $('#pay-modal').modal('show');
        const amount_text = $('.grand-total').text();
        const amount = amount_text.replace('৳', '');
        $('.pay-amount').text(parseFloat(amount));
    })

    $('.completed').on('click', function() {
        let bank = $('#bank').val();
        let paid_amount = $('#paid_amount').val();
        let transaction_id = $('#transaction_id').val();

        if (bank == '' || paid_amount == '' || transaction_id == '') {
            Notify('error', null, 'All fields are required.');
        } else {
            $('#pay-modal').modal('hide');
        }
    })
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/customer/buynow/checkout_guest.blade.php ENDPATH**/ ?>
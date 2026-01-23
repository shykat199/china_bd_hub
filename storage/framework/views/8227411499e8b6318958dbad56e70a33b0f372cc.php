<?php $__env->startSection('title','Orders - '); ?>
<?php $__env->startPush('css'); ?>
    <style>

        /* my bazar invoice css */

            .maan-mybazar-invoice {
                max-width: 1200px;
                margin: 0 auto;
                padding: 50px;
            }

            .my-bazar-invoice-header {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-pack: justify;
                -ms-flex-pack: justify;
                justify-content: space-between;
                margin-bottom: 30px;
            }

            .my-bazar-invoice-header .logo {
                display: block;
            }

            .my-bazar-invoice-header .logo img {
                max-width: 100%;
            }

            .my-bazar-invoice-header .customer-detail {
                text-align: right;
            }

            .my-bazar-invoice-header .customer-detail p {
                font-size: 14px;
                font-weight: 400;
                padding: 0;
            }

            .mybazar-billing-info .billing-info {
                padding: 15px 30px;
                border: 1px dashed #ddd;
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 20px;
                height: 200px;
            }

            .mybazar-billing-info .billing-info h4 {
                font-size: 18px;
                font-weight: 700;
            }

            .mybazar-billing-info .billing-info ul li {
                font-size: 14px;
                font-weight: 400;
                display: flex;
                align-items: center;
                line-height: 24px;
            }

            .mybazar-billing-info .billing-info ul li span {
                width: 80px;
                font-weight: 500;
            }

            .mybazar-billing-info h5 {
                font-weight: 400;
                font-size: 14px;
                display: flex;
                align-items: center;
            }

            .mybazar-billing-info h5 span {
                font-weight: 700;
                width: 100px;
            }

            .mybazar-product-info-billing {
                margin-top: 30px;
            }

            .mybazar-product-info-billing .table thead tr {
                text-align: center;
                background: #eee;
                border-bottom: 1px solid #ddd;
            }

            .mybazar-product-info-billing .table thead tr th {
                font-size: 14px;
                font-weight: 600;
                padding: 20px;
                border-bottom: 1px solid #ddd;
            }

            .mybazar-product-info-billing .table thead tr th:first-child {
                text-align: left;
            }

            .mybazar-product-info-billing .table tbody {
                border: none;
            }

            .mybazar-product-info-billing .table tbody tr {
                text-align: center;
                border-bottom: 1px solid #ddd;
            }

            .mybazar-product-info-billing .table tbody tr td {
                font-size: 14px;
                font-weight: 400;
                border-bottom: 1px solid #ddd;
                padding: 15px;
            }

            .mybazar-product-info-billing .table tbody tr td:first-child {
                text-align: left;
            }

            .mybazar-total-info {
                text-align: right;
                margin-top: 30px;
            }

            .mybazar-total-info ul li {
                text-align: right;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-pack: end;
                -ms-flex-pack: end;
                justify-content: flex-end;
                font-size: 14px;
            }

            .mybazar-total-info ul li span {
                max-width: 160px;
                display: block;
                width: 100%;
            }

            .mybazar-total-info ul li:last-child {
                font-weight: 700;
            }

            .signature {
                margin-top: 30px;
            }
    </style>


    
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <div class="container d-print-block">
            <nav class="d-print-none">
                <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" Area-controls="nav-home" Area-selected="true">
                        <?php echo e(__('Order Details')); ?>

                    </button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" Area-controls="nav-profile" Area-selected="false">
                        <?php echo e(__('Invoice')); ?>

                    </button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active d-print-none" id="nav-home" role="tabpanel"
                     Area-labelledby="nav-home-tab">
                    <div class="invoice d-print-block">
                        <div class="invoice-title">
                            <h6><?php echo e(__('INVOICE')); ?>#
                                <?php echo e($order->order_no??''); ?>

                            </h6>
                            <p>CREATED AT <?php echo e(date("d M Y h:i A",strtotime($order->created_at))); ?></p>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card order-item">
                                    <div class="mybazar-product-info-billing" id="order_details">
                                        <div class="card-header">
                                            <h6><?php echo e(__('Item Details')); ?></h6>
                                        </div>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col"><?php echo e(__('Item')); ?></th>
                                                <th scope="col"><?php echo e(__('Image')); ?></th>
                                                
                                                <th scope="col"><?php echo e(__('Color')); ?></th>
                                                <th scope="col"><?php echo e(__('Size')); ?></th>
                                                <th scope="col"><?php echo e(__('HRS')); ?>/<?php echo e(__('QTY')); ?></th>
                                                <th scope="col"><?php echo e(__('Rate')); ?></th>
                                                <th scope="col"><?php echo e(__('Subtotal')); ?></th>
                                                <th scope="col"><?php echo e(__('Status')); ?></th>
                                                <th scope="col"><?php echo e(__('Action')); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $pId = $detail->product_id;
                                                   $productStocks = \App\Models\Productstock::with(['color','size'])->where('product_id',$pId)->get();
                                                   $matchedStock = $productStocks->first(function ($stock) use ($detail) {
                                                        return
                                                            strtolower($stock->color->name ?? '') === strtolower($detail->color ?? '') &&
                                                            strtolower($stock->size->name ?? '')  === strtolower($detail->size ?? '');
                                                    });
                                                 ?>

                                                <?php
                                                    $imagePath = $matchedStock->variant_image ?? null;
                                                    $fullPath  = base_path('uploads/products/galleries/' . $imagePath);
                                                ?>
                                                <tr data-product="<?php echo e($detail->product_id); ?>">
                                                    <td scope="row"> <?php echo e($detail->product->name??''); ?>

                                                        <a href="<?php if(auth()->guard('admin')->check()): ?><?php echo e(route('backend.order_details_seller',['order_id'=>$detail->order_id,'seller_id'=>$detail->seller_id])); ?><?php endif; ?>" class="text-primary" target="_blank">(<?php echo e($detail->seller->company_name??$website->website_name); ?>)</a>
                                                    </td>
                                                    <td>
                                                        <?php if($matchedStock && $imagePath && file_exists($fullPath)): ?>
                                                            <img
                                                                src="<?php echo e(asset('uploads/products/galleries/' . $imagePath)); ?>"
                                                                width="60"
                                                                alt="Variant Image"
                                                            >
                                                        <?php else: ?>
                                                            <span>No image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    
                                                    <td><?php echo e($detail->color??''); ?></td>
                                                    <td><?php echo e($detail->size??''); ?></td>
                                                    <td><?php echo e($detail->qty??''); ?></td>
                                                    <td><?php echo e($detail->sale_price??''); ?></td>
                                                    <td><?php echo e($detail->total_price??''); ?></td>
                                                    <td>
                                                        <div class="invoice-title">
                                                            <h6>
                                                                <?php if(optional($detail->orderStatus)->name == 'PENDING'): ?>
                                                                    <span class="badge bg-warning"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'CONFIRMED'): ?>
                                                                    <span class="badge bg-info"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'PROCESSING'): ?>
                                                                    <span class="badge bg-primary"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'PICKED'): ?>
                                                                    <span class="badge bg-secondary"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'SHIPPED'): ?>
                                                                    <span class="badge bg-light text-dark border"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'DELIVERED'): ?>
                                                                    <span class="badge bg-success"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'CANCELLED'): ?>
                                                                    <span class="badge bg-danger"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php elseif(optional($detail->orderStatus)->name == 'RETURNED'): ?>
                                                                    <span class="badge bg-dark"><?php echo e($detail->orderStatus->name ?? ''); ?></span>
                                                                <?php endif; ?>
                                                            </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="update-order-btn btn btn-warning btn-sm" data-product_id="<?php echo e($detail->product_id); ?>" data-orders_details_id="<?php echo e($detail->id); ?>">
                                                            <b><?php echo e(__('Change Status')); ?></b>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mybazar-total-info">
                                        <ul>

                                            <li><?php echo e(__('Item(s) Subtotal')); ?>

                                                :<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
                                            <li><?php echo e(__('Shipping Charge')); ?>

                                                :<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
                                            <li>-------------------------------------------</li>
                                            <li><?php echo e(__('SubTotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
                                            <li><?php echo e(__('Coupon')); ?>:<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
                                            <li>-------------------------------------------</li>
                                            <li><?php echo e(__('Total')); ?>:<span><?php echo e(number_format($order->total_price,2)); ?> ৳</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card timeline-item d-print-none">
                                    <div class="card-header">
                                        <h6><?php echo e(__('Timeline')); ?></h6>
                                        <span><?php echo e(__('Click on item to see timeline')); ?></span>
                                    </div>
                                    <div class="card-body">
                                        <ul>
                                            <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $detail->timelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $timeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="d-none product_<?php echo e($timeline->product_id); ?>">
                                                        <div class="time">
                                                            <?php echo e($timeline->order_stat_datetime); ?>

                                                            <span></span></div>
                                                        <p>
                                                            <?php echo e($timeline->order_stat_desc??''); ?>

                                                        </p>
                                                        <div class="option refunded"><?php echo e($timeline->status->name??''); ?></div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" Area-labelledby="nav-profile-tab">
                    <?php echo $__env->make('ordermanagement::orders.invoice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

        </div>

        <div class="modal fade" id="updateModal" tabindex="-1" Area-labelledby="updateModalLabel" Area-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel"><?php echo e(__('Update')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" Area-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo e(auth('seller')->user() ? url('/seller/orders_details') : url('/admin/orders_details')); ?>" class="ajaxform_instant_reload" method="post">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field("PUT"); ?>

                            <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                            <input type="hidden" name="orders_details_id" id="orders_details_id" value="">
                            <input type="hidden" name="product_id" id="product_id" value="">
                            <div class="mb-3 form-group input-group row">
                                <label for="message-text" class="col-form-label col-md-4"><?php echo e(__('Status')); ?></label>
                                <select name="order_stat" class="form-control col-md-6" required>
                                    <option value=""><?php echo e(__('Select Status')); ?></option>
                                    <option value="1"><?php echo e(__('Pending')); ?></option>
                                    <option value="2"><?php echo e(__('Confirmed')); ?></option>
                                    <option value="3"><?php echo e(__('Processing')); ?></option>
                                    <option value="4"><?php echo e(__('Picked')); ?></option>
                                    <option value="5"><?php echo e(__('Shipped')); ?></option>
                                    <option value="6"><?php echo e(__('Delivered')); ?></option>
                                    <option value="7"><?php echo e(__('Cancelled')); ?></option>
                                </select>
                            </div>
                            <div class="mb-3 form-group input-group row">
                                <label for="message-text" class="col-form-label col-md-4"><?php echo e(__('Description')); ?></label>
                                <textarea name="order_stat_desc" rows="3" class="form-control col-md-6" ></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary submit-btn"><i class="fa-solid fa-floppy-disk-circle-arrow-right"></i> <?php echo e(__('Save')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="sellerInvoice" tabindex="-1" Area-labelledby="sellerInvoiceLabel" Area-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sellerInvoiceLabel">Seller invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" Area-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- invoice start  -->
                        <div class="maan-mybazar-invoice">
                            <div class="my-bazar-invoice-header">
                                <a href="" class="logo">
                                    <img src="<?php if($website->logo): ?><?php echo e(URL::to('uploads').'/'.$website->logo); ?> <?php else: ?> 'uploads/logo.png' <?php endif; ?>"
                                         width="150" alt="logo">
                                </a>
                                <button class="maan-print-btn d-print-none" onclick="window.print()">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                         x="0px" y="0px" width="45px" height="45px" viewBox="0 0 45 45" style="enable-background:new 0 0 45 45;"
                                         xml:space="preserve">
        <path d="M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5   c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z    M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5   V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25   S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25   S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25   S33.189,18.792,32.5,18.792z"></path>
        </svg>
                                </button>
                                <div class="customer-detail">
                                    <p><b><?php echo e(ucfirst($website->website_name)); ?></b></p>
                                    <p><?php echo e($website->get_in_touch??''); ?></p>
                                    <p><?php echo e(ucfirst($website->city).'-'.$website->post_code??''); ?></p>
                                    <p><?php echo e($website->country->name??''); ?></p>
                                    <p><?php echo e($website->email??''); ?></p>
                                </div>
                            </div>
                            <div class="mybazar-billing-info">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="billing-info">
                                            <h4><?php echo e(__('Billing Address')); ?></h4>
                                            <ul>
                                                <li><span><?php echo e(__('Name')); ?>:</span><?php echo e($order->full_name()); ?></li>
                                                <?php if($order->user_mobile): ?>
                                                    <li><span><?php echo e(__('Phone')); ?> :</span><?php echo e($order->user_mobile??''); ?></li>
                                                <?php endif; ?>
                                                <li><span><?php echo e(__('Address')); ?>:</span> <?php echo e($order->shipping_address_2); ?> </li>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="billing-info">
                                            <h4><?php echo e(__('Shipping Address')); ?></h4>
                                            <ul>
                                                <li> <?php echo e($order->shipping_address_1); ?>

                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <h5><span><?php echo e(__('Invoice No')); ?>: </span>
                                    <?php echo e($order->order_no ?? ''); ?>

                                </h5>
                                <h5><span><?php echo e(__('Invoice Date')); ?>: </span><?php echo e(date("d M Y ",strtotime($order->created_at))); ?></h5>
                                <h5><span><?php echo e(__('Sold By')); ?>: </span>
                                    <?php echo e(ucfirst($website->website_name)); ?>

                                </h5>
                            </div>
                            <div class="mybazar-product-info-billing">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"><?php echo e(__('Item')); ?></th>
                                        
                                        <th scope="col"><?php echo e(__('Color')); ?></th>
                                        <th scope="col"><?php echo e(__('Size')); ?></th>
                                        <th scope="col"><?php echo e(__('HRS')); ?>/<?php echo e(__('QTY')); ?></th>
                                        <th scope="col"><?php echo e(__('Rate')); ?></th>
                                        <th scope="col"><?php echo e(__('Subtotal')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody id="seller-invoice-table">


                                    </tbody>
                                </table>
                            </div>
                            <div class="mybazar-total-info">
                                <ul>
                                    <li><?php echo e(__('Item(s) Subtotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
                                    <li><?php echo e(__('Shipping Charge')); ?>:<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
                                    <li>-------------------------------------------</li>
                                    <li><?php echo e(__('SubTotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
                                    <li><?php echo e(__('Coupon')); ?>:<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
                                    
                                    <li>-------------------------------------------</li>
                                    <li><?php echo e(__('Total')); ?>:<span><?php echo e(number_format($order->total_price,2)); ?> ৳</span></li>
                                </ul>
                            </div>
                            <div class="signature">
                                <p>signature</p>
                            </div>
                        </div>
                        <!-- invoice end  -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>

        $(function () {
            "use strict";

            $(document).ready(function () {

                function highlight(e) {
                    if (selected[0])
                        selected[0].className = '';
                    e.target.parentNode.className = 'selected';
                }

                var table = document.querySelector('#order_details .table'),
                    selected = table.getElementsByClassName('selected');
                table.onclick = highlight;

                $('.update-order-btn').on('click', function() {
                    var detail_id = $(this).data('orders_details_id');
                    var product_id = $(this).data('product_id');
                    $('#orders_details_id').val(detail_id);
                    $('#product_id').val(product_id);
                    $("#updateModal").modal('show')
                })

                $(document).on('click', '#order_details .table tr', function () {
                    let product = $(this).data('product');
                    $(document).find('.timeline-item ul').find('li').addClass('d-none');
                    $(document).find('.timeline-item ul').find('.product_' + product).removeClass('d-none');
                });

                $('#updateModal').on('hidden.bs.modal', function(event) {
                    $(this).find('form').trigger('reset');
                });
            });
            $('.seller-invoice').each(function () {
                var container = $(this);
                var service = container.data('id');
                $('#seller_invoice_'+service).on('click',function () {
                    var id = $('#seller_invoice_'+service).data('id');
                    var order_id = $('#seller_invoice_'+service).data('order-id');
                    var seller_id = $('#seller_invoice_'+service).data('seller-id');
                    //alert(order_id);
                    $.ajax({
                        url:"admin/orders_details_seller",
                        method:"GET",
                        dataType:"json",
                        data:{
                            'order_id':order_id,'seller_id':seller_id,
                        },
                        success: function(data){
                            alert(data);
                            console.log('helolo')
                            $.each(data,function (index,element){

                                $("#seller-invoice-table").append(
                                    '<tr data-product="">'+
                                        '<td>hello check</td>'+
                                    '</tr>'
                                );
                            });
                        },
                        /*error: function () {
                            alert('Error occur fetch data.....!!');
                            console.log('error')
                        }*/
                        error: function(req, err){ console.log('my message' + err); }


                    });
                });
            });
        });
    </script>

    <script>
        const printButton = document.getElementById('print_button');
        printButton.addEventListener('click', function(){
            const iFrame = document.createElement('iframe');
            iFrame.style.display = 'none';
            iFrame.srcdoc = `<div class="maan-mybazar-invoice" style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 10px; border: 1px solid #e0e0e0; font-size: 12px;">
    <div class="my-bazar-invoice-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <a href="" class="logo" style="text-decoration: none;">
            <img src="<?php if($website->logo): ?><?php echo e(URL::to('uploads').'/'.$website->logo); ?> <?php else: ?> 'uploads/logo.png' <?php endif; ?>" width="100" alt="logo" style="max-width: 100px;">
        </a>

        <div class="customer-detail" style="text-align: right; font-size: 10px;">
            <p style="margin: 2px 0;"><b><?php echo e(ucfirst($website->website_name)); ?></b></p>
            <p style="margin: 2px 0;"><?php echo e($website->get_in_touch??''); ?></p>
            <p style="margin: 2px 0;"><?php echo e(ucfirst($website->city).'-'.$website->post_code??''); ?></p>
            <p style="margin: 2px 0;"><?php echo e($website->country->name??''); ?></p>
            <p style="margin: 2px 0;"><?php echo e($website->email??''); ?></p>
        </div>
    </div>
    <div class="mybazar-billing-info" style="margin-bottom: 10px;">
        <div class="row" style="display: flex; justify-content: space-between;">
            <div class="col-6" style="width: 48%;">
                <div class="billing-info" style="background-color: #f9f9f9; padding: 5px; border-radius: 3px; font-size: 10px;">
                    <h4 style="margin: 0 0 5px 0; color: #333;"><?php echo e(__('Billing Address')); ?></h4>
                    <ul style="list-style-type: none; padding: 0; margin: 0;">
                        <li><span style="font-weight: bold;"><?php echo e(__('Name')); ?>:</span> <?php echo e($order->full_name()); ?></li>
                        <?php if($order->user_mobile): ?>
                            <li><span style="font-weight: bold;"><?php echo e(__('Phone')); ?>:</span> <?php echo e($order->user_mobile??''); ?></li>
                        <?php endif; ?>
                        <li><span style="font-weight: bold;"><?php echo e(__('Address')); ?>:</span> <?php echo e($order->shipping_address_2); ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-6" style="width: 48%;">
                <div class="billing-info" style="background-color: #f9f9f9; padding: 5px; border-radius: 3px; font-size: 10px;">
                    <h4 style="margin: 0 0 5px 0; color: #333;"><?php echo e(__('Shipping Address')); ?></h4>
                    <p style="margin: 0;"><?php echo e($order->shipping_address_1); ?></p>
                </div>
            </div>
        </div>
        <p style="margin: 5px 0; font-size: 10px;"><span style="font-weight: bold;"><?php echo e(__('Invoice No')); ?>: </span><?php echo e($order->order_no ?? ''); ?></p>
        <p style="margin: 5px 0; font-size: 10px;"><span style="font-weight: bold;"><?php echo e(__('Invoice Date')); ?>: </span><?php echo e(date("d M Y ",strtotime($order->created_at))); ?></p>
        <p style="margin: 5px 0; font-size: 10px;"><span style="font-weight: bold;"><?php echo e(__('Sold By')); ?>: </span><?php echo e(ucfirst($website->website_name)); ?></p>
    </div>
    <div class="mybazar-product-info-billing" style="margin-bottom: 10px;">
        <table class="table" style="width: 100%; border-collapse: collapse; font-size: 10px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th scope="col" style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;"><?php echo e(__('Item')); ?></th>
                    <th scope="col" style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;"><?php echo e(__('Color')); ?></th>
                    <th scope="col" style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;"><?php echo e(__('Size')); ?></th>
                    <th scope="col" style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;"><?php echo e(__('QTY')); ?></th>
                    <th scope="col" style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;"><?php echo e(__('Rate')); ?></th>
                    <th scope="col" style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;"><?php echo e(__('Subtotal')); ?></th>
                </tr>
            </thead>
            <tbody id="seller-invoice-table1">
                <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($detail->order_stat!=7): ?>
                    <tr data-product="<?php echo e($detail->product_id); ?>" style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 5px;"><?php echo e($detail->product->name??''); ?></td>
                        <td style="padding: 5px;"><?php echo e($detail->color??''); ?></td>
                        <td style="padding: 5px;"><?php echo e($detail->size??''); ?></td>
                        <td style="padding: 5px;"><?php echo e($detail->qty??''); ?></td>
                        <td style="padding: 5px;"><?php echo e($detail->sale_price??''); ?></td>
                        <td style="padding: 5px;"><?php echo e($detail->total_price??''); ?></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mybazar-total-info" style="margin-bottom: 10px;">
        <ul style="list-style-type: none; padding: 0; border-top: 1px solid #ddd; padding-top: 5px; font-size: 10px;">
            <li style="display: flex; justify-content: space-between; margin-bottom: 3px;"><?php echo e(__('Item(s) Subtotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
            <li style="display: flex; justify-content: space-between; margin-bottom: 3px;"><?php echo e(__('Shipping Charge')); ?>:<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
            <li style="border-top: 1px solid #ddd; margin: 3px 0;"></li>
            <li style="display: flex; justify-content: space-between; margin-bottom: 3px;"><?php echo e(__('SubTotal')); ?>:<span><?php echo e(number_format($order->details->sum('total_price'),2)); ?> ৳</span></li>
            <li style="display: flex; justify-content: space-between; margin-bottom: 3px;"><?php echo e(__('Coupon')); ?>:<span><?php echo e(number_format($order->shipping_cost,2)); ?> ৳</span></li>
            <li style="border-top: 1px solid #ddd; margin: 3px 0;"></li>
            <li style="display: flex; justify-content: space-between; font-weight: bold; font-size: 12px;"><?php echo e(__('Total')); ?>:<span><?php echo e(number_format($order->total_price,2)); ?> ৳</span></li>
        </ul>
    </div>
    <div class="signature" style="text-align: right; margin-top: 10px;">
        <p style="border-top: 1px solid #333; display: inline-block; padding-top: 5px; font-size: 10px;">Authorized Signature</p>
    </div>
</div>
`

            document.body.appendChild(iFrame);

            iFrame.contentWindow.focus();
            iFrame.contentWindow.print();
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/OrderManagement/Resources/views/orders/show.blade.php ENDPATH**/ ?>
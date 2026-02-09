
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Print</title>
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/bootstrap.min.css')); ?>" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<style>
    body{
        background:#F1F2F5
    }
    .customer-invoice {
        margin: 25px 0;
    }
    .invoice_btn{
        margin-bottom: 15px;
    }
    p{
        margin:0;
    }
    td{
        font-size: 16px;
    }
    @media  print {

        @page  {
            size: A4;
            margin: 10mm;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
        }

        body > *:first-child {
            margin-top: 0 !important;
        }

        .container,
        .row,
        .col-sm-12 {
            margin: 0 !important;
            padding: 0 !important;
        }

        .no-print,
        header,
        footer,
        nav {
            display: none !important;
        }

        .invoice-bar.invoice-text {
            background: #BE1E2D !important;
        }

        .customer-invoice {
            page-break-inside: avoid;
        }

        thead {
            background: #BE1E2D !important;
            color: #ffffff !important;
        }
    }

</style>







<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <section class="customer-invoice ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 mt-3">
                    <div class="invoice-innter" style="width:760px;margin: 0 auto;background: #fff;overflow: hidden;padding: 30px;padding-top: 0;">
                        <table style="width:100%">
                            <tr>
                                <td style="width: 40%; float: left; padding-top: 15px;">
                                    <img src="<?php echo e(asset('uploads/logo.svg')); ?>" width="190px" style="margin-top:25px !important" alt="">
                                    <p style="font-size: 14px; color: #222; margin: 20px 0;"><strong>Payment Method:</strong> <span style="text-transform: uppercase;"><?php echo e($order->payment?$order->payment->payment_method:'COD'); ?></span></p>
                                    <div class="invoice_form">
                                        <p style="font-size:16px;line-height:1.8;color:#222"><strong>Invoice From:</strong></p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">China Hub</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222"><?php echo e(maanAppearance('hotline_number')); ?></p>
                                    </div>
                                </td>
                                <td  style="width:60%;float: left;">
                                    <div class="invoice-bar invoice-text" style=" background: #BE1E2D; transform: skew(38deg); width: 100%; margin-left: 65px; padding: 20px 60px; ">
                                        <p style="font-size: 30px; color: #fff; transform: skew(-38deg); text-transform: uppercase; text-align: right; font-weight: bold;">Invoice</p>
                                    </div>
                                    <div class="invoice-bar" style="background: #fff; transform: skew(36deg); width: 72%; margin-left: 182px; padding: 12px 32px; margin-top: 6px;">
                                        <p style="font-size: 15px; color: #222;font-weight:bold; transform: skew(-36deg); text-align: right; padding-right: 18px">Invoice ID : <strong>#<?php echo e($order->order_no); ?></strong></p>
                                        <p style="font-size: 15px; color: #222;font-weight:bold; transform: skew(-36deg); text-align: right; padding-right: 32px">Invoice Date: <strong><?php echo e($order->created_at->format('d-m-y')); ?></strong></p>
                                    </div>
                                    <div class="invoice_to" style="padding-top: 20px;">
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;"><strong>Invoice To:</strong></p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;"><?php echo e($order->shipping?$order->shipping->name:$order->shipping_name); ?></p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;"><?php echo e($order->shipping?$order->shipping->phone:$order->shipping_mobile); ?></p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;"><?php echo e($order->shipping?$order->shipping->address:$order->shipping_address_1); ?></p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;"><?php echo e($order->shipping?$order->shipping->area:$order->shipping_address_2); ?></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table class="table" style="margin-top: 30px;margin-bottom: 0;">
                            <thead style="background: #BE1E2D; color: #fff;">
                            <tr>
                                <th>SL</th>
                                <th>Product </th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($value->product->name); ?> <br> <?php if($value->product_size): ?> <small>Size: <?php echo e($value->product_size); ?></small> <?php endif; ?>   <?php if($value->product_color): ?> <small>Color: <?php echo e($value->product_color); ?></small> <?php endif; ?></td>
                                    <td>৳<?php echo e($value->sale_price); ?></td>
                                    <td><?php echo e($value->qty); ?></td>
                                    <td>৳<?php echo e($value->sale_price*$value->qty); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="invoice-bottom">

                            <table class="table" style="width: 300px; float: right;    margin-bottom: 30px;">
                                <tbody style="background:#f1f9f8">
                                <tr>
                                    <td><strong>SubTotal</strong></td>
                                    <td><strong>৳<?php echo e($order->details->sum('total_price')); ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Discount(-)</strong></td>
                                    <td><strong>৳<?php echo e($order->discount ?? 0); ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Shipping(+)</strong></td>
                                    <td><strong>৳<?php echo e($order->shipping_cost ?? 0); ?></strong></td>
                                </tr>
                                <tr style="background:#BE1E2D;color:#fff">
                                    <td><strong>Final Total</strong></td>
                                    <td><strong>৳<?php echo e($order->total_price); ?></strong></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="terms-condition" style="overflow: hidden; width: 100%; text-align: center; padding: 20px 0; border-top: 1px solid #ddd;">
                                <h5 style="font-style: italic;"><a href="<?php echo e(route('backend.order-list-page',['slug'=>'terms-condition'])); ?>">Terms & Conditions</a></h5>
                                <p style="text-align: center; font-style: italic; font-size: 15px; margin-top: 10px;">* This is a computer generated invoice, does not require any signature.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script>
    function printFunction() {
        window.print();
    }
</script>
</body>
</html>
<?php /**PATH /var/www/html/china_hub/resources/views/frontend/order-invoice.blade.php ENDPATH**/ ?>
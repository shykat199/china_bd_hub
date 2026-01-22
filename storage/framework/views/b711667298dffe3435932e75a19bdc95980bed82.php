<?php $__env->startSection('title','Orders - '); ?>
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
    <div class="content-body">

    <!-- Tab Content Start -->



























        <!-- Tab Content End -->

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">All Orders (<?php echo e($order_overview??0); ?>)</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row order_page">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <?php echo $__env->make('frontend.includes.order-nav-bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="table-responsive ">
                            <table id="datatable-buttons" class="table table-striped   w-100">
                                <thead>
                                <tr>
                                    <th style="width:2%"><div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input checkall" value=""></label>
                                            <th style="width:2%">SL</th>
                                        </div></th>
                                    <th style="width:8%">Action</th>
                                    <th style="width:8%">Invoice</th>
                                    <th style="width:10%">Date</th>
                                    <th style="width:10%">Name</th>
                                    <th style="width:10%">Phone</th>
                                    <th style="width:10%">Check</th>
                                    <th style="width:10%">Amount</th>
                                    <th style="width:10%">Status</th>
                                </tr>
                                </thead>


                                <tbody>
                                <?php $__currentLoopData = $show_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php
                                        $show_route = auth('seller')->user() ? route('seller.orders.show', $value->id) : route('backend.orders.show', $value->id);
                                        $delete_route = auth('seller')->user() ? route('seller.orders.show', $value->id) : route('backend.orders.destroy', $value->id);
                                        $edit_route = route('backend.order.edit.show', $value->order_no);
                                        $order_process = route('backend.process_orders',$value->order_no);
                                    ?>

                                    <tr>
                                        <td><input type="checkbox" class="checkbox" value="<?php echo e($value->id); ?>"></td>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <div class="button-list custom-btn-list">
                                                <a href="<?php echo e($show_route); ?>" title="Invoice"><i class="fa fa-eye"></i></a>
                                                <a href="<?php echo e($order_process); ?>" title="Process"><i class="fa-solid fa-gear"></i></a>
                                                <a href="<?php echo e($edit_route); ?>" title="Edit"><i class="fa fa-pencil-square"></i></a>
                                                <form method="post" action="<?php echo e($delete_route); ?>" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field("DELETE"); ?>
                                                    <input type="hidden" value="<?php echo e($value->id); ?>" name="id">
                                                    <button type="submit" onclick="deleteWithSweetAlert(event,parentNode);" title="Delete" class="delete-confirm"><i class="fa fa-trash"></i></button>

                                                </form>
                                            </div>
                                        </td>
                                        <td><?php echo e($value->order_no); ?></td>
                                        <td><?php echo e(date('d-m-Y', strtotime($value->updated_at))); ?><br> <?php echo e(date('h:i:s a', strtotime($value->updated_at))); ?></td>
                                        <td>
                                            <strong><?php echo e($value->cuatomer?$value->cuatomer->first_name ." ".$value->cuatomer->last_name :$value->shipping_name); ?></strong> <br/>
                                            <strong><?php echo e($value->shipping_address_1?$value->shipping_address_1:''); ?></strong>
                                            <p><?php echo e($value->shipping_post?$value->shipping_post:''); ?></p>
                                            <p><?php echo e($value->shipping_town?$value->shipping_town:''); ?></p>
                                        </td>
                                        <td><?php echo e($value->shipping_mobile?$value->shipping_mobile:''); ?></td>
                                        <td> <a target="_blank" style="text-decoration: underline" href="https://greenviewit.com/check-fraud-customer" >Fraud Customer Check</a></td>
                                        <td>৳<?php echo e($value->total_price); ?></td>
                                        <td><?php echo e($value->newOrderStatus->name ?? 'Unknown'); ?></td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-paginate">
                            <?php echo e($show_data->links('pagination::bootstrap-4')); ?>

                        </div>
                    </div> <!-- end card body-->

                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>




    <!-- edit modal -->

    <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <?php echo $__env->make('backend.includes.datatable_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        $(".checkall").on('change',function(){
            $(".checkbox").prop('checked',$(this).is(":checked"));
        });

        
        

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

        
        
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/app/Modules/Backend/OrderManagement/Resources/views/orders/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Create Coupon | '); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <div class="container">
            <div class="content-tab-title">
                <h4><?php echo e(__('Create Coupon')); ?></h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" Area-labelledby="add-category-tab">
                <div class="container">
                    <form id="faqForm" method="post" action="<?php echo e(route('backend.coupon.store')); ?>" class="add-brand-form">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('backend.pages.coupon.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="col-lg-7 offset-3">
                            <div class="from-submit-btn">
                                <button class="submit-btn" type="submit"><?php echo e(__('Save')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        $("#type").change(function(){
            var type = $(this).val();
            var csrf = "<?php echo e(@csrf_token()); ?>"
            $.ajax({
                url: "<?php echo e(route('backend.coupon.product')); ?>",
                data: {_token:csrf,type:type},
                type: "post",
                beforeSuccess: function(){
                    console.log('loading...')
                }
            }).done(function(e){
                $("#coupon-form").html(e);

                $(".select2").select2(); // initialize select2
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/coupon/create.blade.php ENDPATH**/ ?>
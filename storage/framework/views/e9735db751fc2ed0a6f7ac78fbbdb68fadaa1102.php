<?php $__env->startSection('title','Profile'); ?>

<?php $__env->startSection('content'); ?>

    <section class="maan-user-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-md-5 wow fadeInUp" >
                    <div class="maan-author-profile">
                        <div class="user-info">
                            <div class="maan-user-thumb">
                                <label for="file">
                                    <img src="<?php echo e(asset('frontend/img/users')); ?>/<?php echo e($user->image); ?>" alt="<?php echo e($user->username); ?>" id="blah">
                                </label>
                            </div>
                            <form action="<?php echo e(route('profile.image',$user->id)); ?>" method="post" class="text-center" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type="file" id="file" class="profile-file" name="image" onchange="readURL(this)" accept="image/png, image/jpg">
                                <button type="submit" class="btn btn-sm btn-outline-success"><?php echo e(__('Update Image')); ?></button>
                            </form>
                            <div class="user-title">
                                <!-- <a href="#" class="user-name"><?php echo e($user->username); ?></a> -->
                                <a href="#" class="phone mt-2"><?php echo e(__('Last Login')); ?>: <?php echo e($user->last_login_datetime ? $user->last_login_datetime->format('Y-m-d') : ''); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-lg-9 col-md-7 wow fadeInUp" data-wow-delay="0.3s">
                    <form action="<?php echo e(route('profile.update', auth('customer')->id())); ?>" method="post" class="ajaxform">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="first_name"><?php echo e(__('First name')); ?></label>
                                    <input type="text" class="d-block w-100" name="first_name" value="<?php echo e($user->first_name); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="last_name"><?php echo e(__('Last Name')); ?></label>
                                    <input type="text" class="d-block w-100" name="last_name" value="<?php echo e($user->last_name); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="username"><?php echo e(__('Username')); ?></label>
                                    <input type="text" class="d-block w-100" name="username" value="<?php echo e($user->username); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="address"><?php echo e(__('Address')); ?></label>
                                    <input type="text" class="d-block w-100" name="address" value="<?php echo e($user->address); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="mobile"><?php echo e(__('Contact Number')); ?></label>
                                    <input type="text" class="d-block w-100" name="mobile" value="<?php echo e($user->mobile); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="dob"><?php echo e(__('Date of Birth')); ?></label>
                                    <input type="date" class="d-block w-100" name="dob" value="<?php echo e($user->dob ? $user->dob->format('Y-m-d') : ''); ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="old_password"><?php echo e(__(' Old Password')); ?></label>
                                    <input type="passoword" id="old_password" class="form-control d-block w-100" name="old_password">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="dob"><?php echo e(__('New password')); ?></label>
                                    <input type="passoword" class="form-control d-block w-100" name="password">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <button class="btn btn-warning submit-btn float-right"> <i class="fa fa-save" aria-hidden="true"></i> <?php echo e(__('Save')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function readURL(input) {
            "use strict";
            if (input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result).width(150).height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        <?php if(Session::has('success')): ?>
        swal("<?php echo e(__('Success!')); ?>", "<?php echo e(Session::get('success')); ?>", "success");
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/customer/pages/profile.blade.php ENDPATH**/ ?>
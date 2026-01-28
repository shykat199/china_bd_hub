<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .email-container{
            justify-content: center;
            display: grid;
            background-color: #ccc;
            padding: 0 5%
        }
        .email-header{
            text-align: center;
        }
        .email-header{
            background-color: orange;
            padding: 10px;
        }
        .hyper-link
        {
            color: orangered;
        }
        .hyper-link:hover
        {
            color: #1c881c;
        }
        .email-btn{
            border: 1px solid orange;
            padding: 10px;
            border-radius: 5px;
            background-color: orange;
            color: white;
            text-decoration: none;
            display: grid;
            justify-content: center;
            margin: 0 15rem;
            text-align: center;
        }

        .email-btn:hover{
            border: 1px solid #2ec618;
            background-color: #22b31d;
            color: #f4f4f4;
        }

        hr{
            border: 1px solid #bbbbbb;
            width: 100%
        }

        .copyright{
            color: #248c48;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h2 class="email-header"><?php echo e(config('app.name')); ?></h2>

    <div class="email-body">
        <p><?php echo e(__('You are having this email because you have requested for a password reset.')); ?></p>
        <p><?php echo e(__('Your verification code is: ')); ?><b><?php echo e($data['code']); ?></b></p>

        <p><?php echo e(__('Click the button below to go to password reset page.')); ?></p>

        <p><a href="<?php echo e(url($data['url'])); ?>" class="email-btn"><?php echo e(__('Password Reset Link')); ?></a></p>

        <p><?php echo e(__('This password reset link will expire in 60 minutes.')); ?></p>

        <p><?php echo e(__('If you did not request a password reset, no further action is required.')); ?></p>

        <p><?php echo e(__('Regards')); ?>,<br> <?php echo e(config('app.name')); ?></p>
    </div>

    <hr>

    <p class="email-footer"><?php echo e(__('If you\'re having trouble clicking the "Password Reset Link" button, copy and paste the URL below into your web browser')); ?>: <span class="hyper-link"><?php echo e($data['url']); ?></span></p>
    <p class="copyright">&copy; <?php echo e(config('app.name')); ?>. <?php echo e(__('All right reserved')); ?>.</p>
</div>
</body>
</html>
<?php /**PATH /var/www/html/china_hub/resources/views/frontend/mail/password-reset.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(front_locale_direction()); ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?php echo e(front_route('home.index')); ?>">
  <title><?php echo $__env->yieldContent('title', system_setting_locale('meta_title', 'InnoShop - 创新的开源电商系统 | 开源独立站系统 | Laravel 12，多语言和多货币支持')); ?></title>
  <meta name="description" content="<?php echo $__env->yieldContent('description', system_setting_locale('meta_description', 'innoshop是一款创新的开源电子商务平台，基于Laravel 12开发，具有多语言和多货币支持的特性。它采用了基于Hook的强大而灵活的插件架构，为用户提供了丰富的定制和扩展功能。欢迎体验innoshop，打造属于您自己的电子商务平台！')); ?>">
  <meta name="keywords" content="<?php echo $__env->yieldContent('keywords', system_setting_locale('meta_keywords', 'innoshop, 创新, 开源, 电商, 跨境电商, 开源独立站, Laravel 12, 多语言, 多货币, Hook, 插件架构, 灵活, 强大')); ?>">
  <meta name="generator" content="InnoShop <?php echo e(innoshop_version()); ?>">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="api-token" content="<?php echo e(session('front_api_token')); ?>">
  <link rel="shortcut icon" href="<?php echo e(image_origin(system_setting('favicon', 'images/favicon.png'))); ?>">
  <link rel="stylesheet" href="<?php echo e(mix('build/front/css/bootstrap.css')); ?>">
  <script src="<?php echo e(mix('build/front/js/app.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/jquery/jquery-3.7.1.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/layer/3.5.1/layer.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
  <link rel="stylesheet" href="<?php echo e(mix('build/front/css/app.css')); ?>">
  <script>
    let urls = {
      api_base: '<?php echo e(route('api.home.base')); ?>',
      base_url: '<?php echo e(front_route('home.index')); ?>',
      upload_images: '<?php echo e(front_root_route('upload.images')); ?>',
      cart_add: '<?php echo e(front_route('carts.store')); ?>',
      cart_mini: '<?php echo e(front_route('carts.mini')); ?>',
      cart: '<?php echo e(front_route('carts.index')); ?>',
      checkout: '<?php echo e(front_route('checkout.index')); ?>',
      login: '<?php echo e(front_route('login.index')); ?>',
      favorites: '<?php echo e(account_route('favorites.index')); ?>',
      favorite_cancel: '<?php echo e(account_route('favorites.cancel')); ?>',
    }

    let config = {
      isLogin: !!<?php echo e(current_customer()->id ?? 'null'); ?>,
    }

    let asset_url = '<?php echo e(asset('')); ?>';
  </script>
  <?php echo $__env->yieldPushContent('header'); ?>
   <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("front.layout.app.head.bottom",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
</head>

<body class="<?php echo $__env->yieldContent('body-class'); ?>">
  <?php if(!request('iframe')): ?>
    <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php endif; ?>

  <div class="m-0 p-0" id="appContent">
      <?php echo $__env->yieldContent('content'); ?>
  </div>

  <?php if(!request('iframe')): ?>
    <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php endif; ?>

  
  <?php if(!request('iframe')): ?>
    <?php echo $__env->make('components.mini-cart', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php endif; ?>

  <?php echo $__env->yieldPushContent('footer'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/layouts/app.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(panel_locale_direction()); ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?php echo e(panel_route('home.index')); ?>">
  <title><?php echo $__env->yieldContent('title'); ?><?php echo e(View::hasSection('title') ? ' - ' : ''); ?>InnoShop</title>
  <meta name="keywords" content="<?php echo $__env->yieldContent('keywords', 'InnoShop, 创新, 开源, CMS, Laravel 11, 多语言, 多货币, Hook, 插件架构, 灵活, 强大'); ?>">
  <meta name="generator" content="InnoShop <?php echo e(innoshop_version()); ?>">
  <meta name="asset" content="<?php echo e(asset('/')); ?>">
  <meta name="description" content="<?php echo $__env->yieldContent('description', 'InnoShop'); ?>">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="api-token" content="<?php echo e(session('panel_api_token')); ?>">
  <link rel="shortcut icon" href="<?php echo e(image_origin(system_setting('favicon', 'images/favicon.png'))); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('vendor/element-plus/index.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(mix('build/panel/css/bootstrap.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(mix('build/panel/css/app.css')); ?>">
  <script src="<?php echo e(asset('vendor/jquery/jquery-3.7.1.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/vue/3.5/vue.global' . (config('app.debug') ? '' : '.prod') . '.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/element-plus/index.full.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/element-plus/icons.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/layer/3.5.1/layer.js')); ?>"></script>
  <script src="<?php echo e(mix('build/panel/js/app.js')); ?>"></script>
  <script>
    let urls = {
      api_base: '<?php echo e(route('api.panel.base.index')); ?>',
      base_url: '<?php echo e(panel_route('home.index')); ?>',
      upload_images: '<?php echo e(panel_route('upload.images')); ?>',
      ai_generate: '<?php echo e(panel_route('content_ai.generate')); ?>',
    }

    const lang = {
      hint: '<?php echo e(__('panel/common.hint')); ?>',
      delete_confirm: '<?php echo e(__('panel/common.delete_confirm')); ?>',
      confirm: '<?php echo e(__('panel/common.confirm')); ?>',
      cancel: '<?php echo e(__('panel/common.cancel')); ?>',
    }
  </script>
  <?php echo $__env->yieldPushContent('header'); ?>
</head>

<body class="<?php echo $__env->yieldContent('body-class'); ?>">
  <?php echo $__env->make('panel::layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <div class="main-content">
    <aside class="sidebar-box navbar-expand-xs border-radius-xl">
      <div class="sidebar-body">
        <?php if (isset($component)) { $__componentOriginal6e72f3f32b856c17a7b139e268c539c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6e72f3f32b856c17a7b139e268c539c8 = $attributes; } ?>
<?php $component = InnoShop\Panel\Components\Layout\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel-layout-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\InnoShop\Panel\Components\Layout\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6e72f3f32b856c17a7b139e268c539c8)): ?>
<?php $attributes = $__attributesOriginal6e72f3f32b856c17a7b139e268c539c8; ?>
<?php unset($__attributesOriginal6e72f3f32b856c17a7b139e268c539c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6e72f3f32b856c17a7b139e268c539c8)): ?>
<?php $component = $__componentOriginal6e72f3f32b856c17a7b139e268c539c8; ?>
<?php unset($__componentOriginal6e72f3f32b856c17a7b139e268c539c8); ?>
<?php endif; ?>
      </div>
      <div class="mb-menu-close"><i class="bi bi-chevron-left"></i></div>
    </aside>

    <div id="content">
      <div class="page-title-box py-1 d-flex align-items-center justify-content-between">
        <div class="d-flex">
          <h4 class="page-title mb-0"><?php echo $__env->yieldContent('title'); ?></h4>
          <div class="ms-4 text-danger"><?php echo $__env->yieldContent('page-title-after'); ?></div>
        </div>
        <div class="text-nowrap">
          <?php echo $__env->yieldContent('page-title-right'); ?>
           <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("panel.layout.right.button.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
        </div>
      </div>

      <div class="container-fluid p-0 mt-3">
        <div class="content-info">
          <?php if(session()->has('errors')): ?>
            <?php if (isset($component)) { $__componentOriginal460c898d52f5daa82dc1a187798748ef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal460c898d52f5daa82dc1a187798748ef = $attributes; } ?>
<?php $component = InnoShop\Common\Components\Base\Alert::resolve(['type' => 'danger','msg' => ''.e(session('errors')->first()).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('common-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\InnoShop\Common\Components\Base\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal460c898d52f5daa82dc1a187798748ef)): ?>
<?php $attributes = $__attributesOriginal460c898d52f5daa82dc1a187798748ef; ?>
<?php unset($__attributesOriginal460c898d52f5daa82dc1a187798748ef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal460c898d52f5daa82dc1a187798748ef)): ?>
<?php $component = $__componentOriginal460c898d52f5daa82dc1a187798748ef; ?>
<?php unset($__componentOriginal460c898d52f5daa82dc1a187798748ef); ?>
<?php endif; ?>
          <?php endif; ?>
          <?php if(session('success')): ?>
            <?php if (isset($component)) { $__componentOriginal460c898d52f5daa82dc1a187798748ef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal460c898d52f5daa82dc1a187798748ef = $attributes; } ?>
<?php $component = InnoShop\Common\Components\Base\Alert::resolve(['type' => 'success','msg' => ''.e(session('success')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('common-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\InnoShop\Common\Components\Base\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal460c898d52f5daa82dc1a187798748ef)): ?>
<?php $attributes = $__attributesOriginal460c898d52f5daa82dc1a187798748ef; ?>
<?php unset($__attributesOriginal460c898d52f5daa82dc1a187798748ef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal460c898d52f5daa82dc1a187798748ef)): ?>
<?php $component = $__componentOriginal460c898d52f5daa82dc1a187798748ef; ?>
<?php unset($__componentOriginal460c898d52f5daa82dc1a187798748ef); ?>
<?php endif; ?>
          <?php endif; ?>
          <?php if(session('error')): ?>
            <?php if (isset($component)) { $__componentOriginal460c898d52f5daa82dc1a187798748ef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal460c898d52f5daa82dc1a187798748ef = $attributes; } ?>
<?php $component = InnoShop\Common\Components\Base\Alert::resolve(['type' => 'danger','msg' => ''.e(session('error')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('common-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\InnoShop\Common\Components\Base\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal460c898d52f5daa82dc1a187798748ef)): ?>
<?php $attributes = $__attributesOriginal460c898d52f5daa82dc1a187798748ef; ?>
<?php unset($__attributesOriginal460c898d52f5daa82dc1a187798748ef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal460c898d52f5daa82dc1a187798748ef)): ?>
<?php $component = $__componentOriginal460c898d52f5daa82dc1a187798748ef; ?>
<?php unset($__componentOriginal460c898d52f5daa82dc1a187798748ef); ?>
<?php endif; ?>
          <?php endif; ?>
          <?php echo $__env->yieldContent('content'); ?>
        </div>

        <div class="page-bottom-btns">
          <?php echo $__env->yieldContent('page-bottom-btns'); ?>
        </div>

        <p class="text-center text-secondary mt-5">
          <?php echo innoshop_brand_link(); ?>

          <?php echo e(innoshop_version()); ?> &copy; <?php echo e(date('Y')); ?> All Rights Reserved
        </p>
      </div>
    </div>
  </div>

  <?php echo $__env->make('panel::layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php echo $__env->yieldPushContent('footer'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\findmythings\innopacks/panel/resources/views/layouts/app.blade.php ENDPATH**/ ?>
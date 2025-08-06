<div class="header-box d-flex justify-content-between align-items-center">
  <div class="mb-menu d-lg-none"><i class="bi bi-list"></i></div>
  <div class="header-logo">
    <a href="<?php echo e(panel_route('home.index')); ?>" class="sidebar-logo">
      <img src="<?php echo e(image_origin(system_setting('panel_logo', 'images/logo-panel.png'))); ?>" class="img-fluid">
    </a>
  </div>
  <div class="d-flex justify-content-end right-tool">
    <!-- Market -->
    <div class="header-item dropdown d-none d-lg-flex align-items-center">
      <span class="dropdown-toggle" data-bs-toggle="dropdown">
        <i class="bi bi-grid me-1"></i><span><?php echo e(__('panel/common.market')); ?></span>
      </span>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="<?php echo e(panel_route('plugin_market.index')); ?>">
          <i class="bi bi-puzzle me-2"></i><?php echo e(__('panel/common.market_plugin')); ?>

        </a></li>
        <li><a class="dropdown-item" href="<?php echo e(panel_route('theme_market.index')); ?>">
          <i class="bi bi-palette me-2"></i><?php echo e(__('panel/common.market_theme')); ?>

        </a></li>
      </ul>
    </div>

    <!-- Language -->
    <div class="header-item dropdown d-none d-lg-flex align-items-center">
      <div class="wh-20 me-2"><img src="<?php echo e(image_origin('images/flag/'. panel_locale_code().'.png')); ?>" class="img-fluid"></div>
      <span class="dropdown-toggle" data-bs-toggle="dropdown">
        <span><?php echo e(current_panel_locale()['name']); ?></span>
      </span>
      <ul class="dropdown-menu dropdown-menu-end">
        <?php $__currentLoopData = panel_locales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo e(panel_route('locale.switch', ['code'=> $locale['code']])); ?>">
            <div class="wh-20 me-2"><img src="<?php echo e(image_origin($locale['image'])); ?>" class="img-fluid border"></div>
            <?php echo e($locale['name']); ?>

          </a>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>

    <!-- User -->
    <div class="header-item dropdown d-flex align-items-center">
      <span class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
        <div class="user-avatar me-2">
          <i class="bi bi-person-circle fs-5"></i>
        </div>
        <div class="user-info d-none d-lg-block">
          <div class="user-name"><?php echo e(current_admin()->name); ?></div>
        </div>
      </span>
      <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header">
          <div class="d-flex align-items-center">
            <div class="user-avatar me-2">
              <i class="bi bi-person-circle fs-4"></i>
            </div>
            <div>
              <div class="user-name"><?php echo e(current_admin()->name); ?></div>
              <div class="user-email small text-muted"><?php echo e(current_admin()->email); ?></div>
            </div>
          </div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo e(front_route('home.index')); ?>" target="_blank">
            <i class="bi bi-house me-2"></i>
            <span><?php echo e(__('panel/dashboard.frontend')); ?></span>
          </a>
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo e(panel_route('account.index')); ?>">
            <i class="bi bi-person me-2"></i>
            <span><?php echo e(__('panel/dashboard.profile')); ?></span>
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item d-flex align-items-center text-danger" href="<?php echo e(panel_route('logout.index')); ?>">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span><?php echo e(__('panel/dashboard.sign_out')); ?></span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div><?php /**PATH C:\laragon\www\findmythings\innopacks/panel/resources/views/layouts/header.blade.php ENDPATH**/ ?>
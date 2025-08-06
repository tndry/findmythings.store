<!-- Mobile Footer Navigation -->
<div class="mobile-footer d-lg-none fixed-bottom bg-white border-top">
  <div class="d-flex justify-content-around py-2">
    <!-- Market -->
    <div class="dropdown">
      <span class="d-flex flex-column align-items-center text-secondary" data-bs-toggle="dropdown">
        <i class="bi bi-grid fs-5"></i>
        <small><?php echo e(__('panel/common.market')); ?></small>
      </span>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo e(panel_route('plugin_market.index')); ?>">
          <i class="bi bi-puzzle me-2"></i><?php echo e(__('panel/common.market_plugin')); ?>

        </a></li>
        <li><a class="dropdown-item" href="<?php echo e(panel_route('theme_market.index')); ?>">
          <i class="bi bi-palette me-2"></i><?php echo e(__('panel/common.market_theme')); ?>

        </a></li>
      </ul>
    </div>

    <!-- Language -->
    <div class="dropdown">
      <span class="d-flex flex-column align-items-center text-secondary" data-bs-toggle="dropdown">
        <i class="bi bi-globe fs-5"></i>
        <small><?php echo e(current_panel_locale()['name']); ?></small>
      </span>
      <ul class="dropdown-menu">
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
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  if (window.innerWidth < 992) {
    document.querySelector('.container-fluid').style.marginBottom = '60px';
  }
});
</script>
<?php /**PATH C:\laragon\www\findmythings\innopacks/panel/resources/views/layouts/footer.blade.php ENDPATH**/ ?>
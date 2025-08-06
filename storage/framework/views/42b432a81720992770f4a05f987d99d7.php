<div class="breadcrumb-wrap">
  <div class="container <?php echo e(count($breadcrumbs) > 0 ? 'd-flex justify-content-between' : 'justify-content-center'); ?>">
    <ul class="breadcrumb">
      <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($breadcrumb['url']) && $breadcrumb['url']): ?>
          <li>
            <?php if($index == 0): ?>
              <i class="bi bi-house-door-fill home-icon"></i>
            <?php endif; ?>
            <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['title']); ?></a>
          </li>
        <?php else: ?>
          <li class="breadcrumb-item active" aria-current="page"><?php echo e($breadcrumb['title']); ?></li>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <?php if(count($breadcrumbs) > 0 && ($showFilter)): ?>
      <li class="d-block d-md-none" id="toggleFilterSidebar"><i class="fs-4 bi bi-funnel"></i></li>
    <?php endif; ?>
  </div>
</div>
<?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>
<?php if($product->type === 'bundle' && $bundle_items->count() > 0): ?>
  <div class="bundle-items-display mb-3">
    <h6 class="bundle-title"><?php echo e(__('front/product.bundle_includes')); ?>:</h6>
    <div class="bundle-products d-flex align-items-center flex-wrap">
      <?php $__currentLoopData = $bundle_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bundleItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($index > 0): ?>
          <span class="bundle-separator mx-2">+</span>
        <?php endif; ?>
        <div class="bundle-product-item d-flex align-items-center">
          <img src="<?php echo e($bundleItem->sku->getImageUrl(40, 40)); ?>"
               alt="<?php echo e($bundleItem->sku->full_name); ?>"
               class="bundle-product-image me-2"
               style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
          <div class="bundle-product-info">
            <div class="bundle-product-name" data-bs-toggle="tooltip" title="<?php echo e($bundleItem->sku->full_name); ?>">
              <?php echo e(sub_string($bundleItem->sku->full_name, 68)); ?>

            </div>
            <?php if($bundleItem->quantity > 0): ?>
              <small class="text-muted">× <?php echo e($bundleItem->quantity); ?></small>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
<?php endif; ?> <?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/products/_bundle_items.blade.php ENDPATH**/ ?>
<?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="review-item">
      <br/>
      <hr/>
      <div class="review-list row">
        <div class="row">
          <h5 class="col-2 mb-3"><?php echo e($review->customer->name ?? 'Pengguna Dihapus'); ?></h5>
          <span class="col-4 text-left"><?php if (isset($component)) { $__componentOriginald225e61f839b80f54d6d93b6c98f376c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald225e61f839b80f54d6d93b6c98f376c = $attributes; } ?>
<?php $component = InnoShop\Front\Components\Review::resolve(['rating' => $review->rating] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('front-review'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\InnoShop\Front\Components\Review::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald225e61f839b80f54d6d93b6c98f376c)): ?>
<?php $attributes = $__attributesOriginald225e61f839b80f54d6d93b6c98f376c; ?>
<?php unset($__attributesOriginald225e61f839b80f54d6d93b6c98f376c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald225e61f839b80f54d6d93b6c98f376c)): ?>
<?php $component = $__componentOriginald225e61f839b80f54d6d93b6c98f376c; ?>
<?php unset($__componentOriginald225e61f839b80f54d6d93b6c98f376c); ?>
<?php endif; ?></span>
          <span class="col-6 text-end date"><?php echo e($review->created_at); ?></span>
        </div>
        <p class="mb-3"><?php echo e($review['content']); ?></p>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/products/_review_list.blade.php ENDPATH**/ ?>
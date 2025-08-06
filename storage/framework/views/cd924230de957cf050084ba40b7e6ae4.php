<?php if($item->translation): ?>
  <div class="blog-item">
    <div class="image">
      <a href="<?php echo e($item->url); ?>">
        <img src="<?php echo e($item->translation->image); ?>" class="img-fluid">
      </a>
    </div>
    <div class="blog-item-info">
      <?php if($item->catalog->translation ?? ''): ?>
        <div class="blog-catalog"><a href="<?php echo e($item->url); ?>"><?php echo e($item->catalog->translation->title); ?></a></div>
      <?php endif; ?>
      <div class="blog-title"><?php echo e($item->translation->title); ?></div>
      <div class="author-wrap">
        <?php if($item->author): ?>
          <div class="blog-author"><i class="bi bi-person"></i> <?php echo e($item->author); ?></div>
        <?php endif; ?>
        <div class="blog-created"><i class="bi bi-clock"></i> <?php echo e($item->created_at->format('Y-m-d')); ?></div>
      </div>
    </div>
  </div>
<?php endif; ?><?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/shared/blog.blade.php ENDPATH**/ ?>
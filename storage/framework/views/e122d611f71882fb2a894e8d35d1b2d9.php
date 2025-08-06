<?php if(current_customer() && !system_setting('bought_review')): ?>

  <?php if(!$reviewed): ?>
    <?php echo $__env->make('shared.review', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php else: ?>
    <div class="m-5 text-center">
      <button class="btn btn-primary"><?php echo e(__('front/product.have_reviewed')); ?></button>
    </div>
  <?php endif; ?>
<?php else: ?>
  <div class="m-5 text-center">
    <?php if(!current_customer()): ?>
      <a class="btn btn-primary" href="javascript:inno.openLogin()"><?php echo e(__('front/product.please_login_first')); ?></a>
    <?php else: ?>
      <a class="btn btn-primary" href="<?php echo e(account_route('orders.index')); ?>"
         target="_blank"><?php echo e(__('front/product.visit_order_to_review')); ?></a>
    <?php endif; ?>
  </div>
<?php endif; ?>

<div class="review-list-container">
  <?php echo $__env->make('products._review_list', ['reviews' => $reviews], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<?php if($reviews->hasMorePages()): ?>
  <div class="text-center mt-3">
    <button class="btn btn-outline-primary load-more-reviews" data-page="2" data-product-id="<?php echo e($product->id); ?>">
      <?php echo e(__('front/common.load_more')); ?>

    </button>
  </div>
<?php endif; ?>

<?php $__env->startPush('footer'); ?>
  <script>
    $(document).ready(function () {
      $('.load-more-reviews').on('click', function () {
        const button = $(this);
        const page = button.data('page');

        button.prop('disabled', true).html(
          '<i class="bi bi-arrow-repeat spin"></i> <?php echo e(__('front/common.loading')); ?>');

        axios.get(`<?php echo e(front_route('products.reviews', ['product' => $product->id])); ?>`, {
          params: {
            page: page
          }
        }).then(function (response) {
          if (response.success) {
            $('.review-list-container').append(response.data.html);

            if (response.data.has_more) {
              button.data('page', page + 1).prop('disabled', false).text(
                '<?php echo e(__('front/product.load_more')); ?>');
            } else {
              button.remove();
            }
          }
        }).catch(function (error) {
          console.error('加载评论失败:', error);
          button.prop('disabled', false).text('<?php echo e(__('front/product.load_more')); ?>');
          inno.msg('<?php echo e(__('front/product.load_failed')); ?>');
        });
      });
    });
  </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/products/review.blade.php ENDPATH**/ ?>
<?php $__env->startSection('body-class', 'page-home'); ?>

<?php $__env->startPush('header'); ?>
  <script src="<?php echo e(asset('vendor/swiper/swiper-bundle.min.js')); ?>"></script>
  <link rel="stylesheet" href="<?php echo e(asset('vendor/swiper/swiper-bundle.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

   <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("home.content.top",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

  <section class="module-content">
    <?php if($slideshow): ?>
      <section class="module-line">
        <div class="swiper" id="module-swiper-1">
          <div class="module-swiper swiper-wrapper">
            <?php $__currentLoopData = $slideshow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if($slide['image'][front_locale_code()] ?? false): ?>
                <div class="swiper-slide">
                  <a href="<?php echo e($slide['link'] ?: 'javascript:void(0)'); ?>"><img
                      src="<?php echo e(image_origin($slide['image'][front_locale_code()])); ?>" class="img-fluid"></a>
                </div>
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </section>
      <script>
        var swiper = new Swiper('#module-swiper-1', {
          loop: true,
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          autoplay: {
            delay: 2500,
            disableOnInteraction: true,
          },
        });
      </script>
    <?php endif; ?>

     <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("home.swiper.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

    <?php if(0): ?>
      <section class="module-line">
        <div class="module-banner-2">
          <div class="container">
            <div class="row">
              <div class="col-12 col-md-4 mb-2 mb-lg-0">
                <a href=""><img src="<?php echo e(asset('images/demo/banner/banner-3.jpg')); ?>" class="img-fluid"></a>
              </div>
              <div class="col-12 col-md-8">
                <a href=""><img src="<?php echo e(asset('images/demo/banner/banner-4.jpg')); ?>" class="img-fluid"></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="module-line">
      <div class="module-product-tab">
        <div class="container">
          <div class="module-title-wrap">
            <div class="module-title"><?php echo e(__('front/home.feature_product')); ?></div>
            <div class="module-sub-title"><?php echo e(__('front/home.feature_product_text')); ?></div>

          </div>

          <ul class="nav nav-tabs">
            <?php $__currentLoopData = $tab_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo e($loop->first ? 'active' : ''); ?>" data-bs-toggle="tab"
                  data-bs-target="#module-product-tab-x-<?php echo e($loop->iteration); ?>"
                  type="button"><?php echo e($item['tab_title']); ?></button>
              </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>

          <div class="tab-content">
            <?php $__currentLoopData = $tab_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="tab-pane fade show <?php echo e($loop->first ? 'active' : ''); ?>"
                id="module-product-tab-x-<?php echo e($loop->iteration); ?>">
                <div class="row gx-3 gx-lg-4">
                  <?php $__currentLoopData = $item['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-md-4 col-lg-3">
                      <?php echo $__env->make('front::shared.product', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </div>
    </section>

    <?php if(0): ?>
      <section class="module-line">
        <div class="module-banner-1">
          <div class="container">
            <a href=""><img src="<?php echo e(asset('images/demo/banner/banner-5.jpg')); ?>" class="img-fluid"></a>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="module-line">
      <div class="module-product-tab">
        <div class="container">
          <div class="module-title-wrap">
            <div class="module-title"><?php echo e(__('front/home.news_blog')); ?></div>
            <div class="module-sub-title"><?php echo e(__('front/home.news_blog_text')); ?></div>
          </div>

          <div class="row gx-3 gx-lg-4">
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $new): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-6 col-md-4 col-lg-3">
                <?php echo $__env->make('shared.blog', ['item' => $new], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </div>
    </section>
  </section>

   <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("home.content.bottom",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/home.blade.php ENDPATH**/ ?>
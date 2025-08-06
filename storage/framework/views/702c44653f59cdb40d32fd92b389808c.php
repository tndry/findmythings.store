<?php if($product->fallbackName()): ?>
  <div class="product-grid-item <?php echo e(request('style_list') ?? ''); ?>">
    
    <div class="image position-relative">
     <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.list_item.image.before",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
      <a href="<?php echo e($product->url); ?>">
        <img src="<?php echo e($product->image_url); ?>" class="img-fluid">
      </a>
      <div class="wishlist-container add-wishlist" data-in-wishlist="<?php echo e($product->hasFavorite()); ?>"
           data-id="<?php echo e($product->id); ?>" data-price="<?php echo e($product->masterSku->price); ?>">
        <i class="bi bi-heart<?php echo e($product->hasFavorite() ? '-fill' : ''); ?>"></i> <?php echo e(__('front/product.add_wishlist')); ?>

      </div>
    </div>
    <div class="product-item-info">
      <div class="product-name">
        <a href="<?php echo e($product->url); ?>" data-bs-toggle="tooltip" title="<?php echo e($product->fallbackName()); ?>"
           data-placement="top">
          <?php echo e($product->fallbackName()); ?>

        </a>
      </div>

       <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.list_item.name.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

      <?php if(request('style_list') == 'list'): ?>
        <div class="sub-product-title"><?php echo e($product->fallbackName('summary')); ?></div>
      <?php endif; ?>

      <div class="product-bottom">
  
        <?php if(!system_setting('disable_online_order')): ?>
        
          <div class="product-bottom-btns">
            <div class="btn-add-cart cursor-pointer" data-id="<?php echo e($product->id); ?>"
               data-price="<?php echo e($product->masterSku->getFinalPrice()); ?>"
               data-sku-id="<?php echo e($product->masterSku->id); ?>"><?php echo e(__('front/cart.add_to_cart')); ?>

            </div>
          </div>
        <?php endif; ?>
      
        <div class="product-price">
          <?php if($product->masterSku->origin_price): ?>
            <div class="price-old"><?php echo e($product->masterSku->origin_price_format); ?></div>
          <?php endif; ?>
          <div class="price-new"><?php echo e($product->masterSku->getFinalPriceFormat()); ?></div>
        </div>
      </div>
      <?php if(request('style_list') == 'list'): ?>
        <div class="add-wishlist" data-in-wishlist="<?php echo e($product->hasFavorite()); ?>" data-id="<?php echo e($product->id); ?>"
             data-price="<?php echo e($product->masterSku->price); ?>">
          <i class="bi bi-heart<?php echo e($product->hasFavorite() ? '-fill' : ''); ?>"></i> <?php echo e(__('front/product.add_wishlist')); ?>

        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\findmythings\innopacks\front\src/../resources/views/shared/product.blade.php ENDPATH**/ ?>
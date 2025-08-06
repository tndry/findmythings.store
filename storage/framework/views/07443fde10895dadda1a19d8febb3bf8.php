<?php $__env->startSection('body-class', 'page-product'); ?>

<?php $__env->startSection('title', \InnoShop\Common\Libraries\MetaInfo::getInstance($product)->getTitle()); ?>
<?php $__env->startSection('description', \InnoShop\Common\Libraries\MetaInfo::getInstance($product)->getDescription()); ?>
<?php $__env->startSection('keywords', \InnoShop\Common\Libraries\MetaInfo::getInstance($product)->getKeywords()); ?>

<?php $__env->startPush('header'); ?>
    <script src="<?php echo e(asset('vendor/swiper/swiper-bundle.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/swiper/swiper-bundle.min.css')); ?>">

    <script src="<?php echo e(asset('vendor/photoswipe/umd/photoswipe.umd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/photoswipe/umd/photoswipe-lightbox.umd.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/photoswipe/photoswipe.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginalaa9843ad42da449158f07d4336183f2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa9843ad42da449158f07d4336183f2d = $attributes; } ?>
<?php $component = InnoShop\Front\Components\Breadcrumb::resolve(['type' => 'product','value' => $product] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('front-breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\InnoShop\Front\Components\Breadcrumb::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaa9843ad42da449158f07d4336183f2d)): ?>
<?php $attributes = $__attributesOriginalaa9843ad42da449158f07d4336183f2d; ?>
<?php unset($__attributesOriginalaa9843ad42da449158f07d4336183f2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaa9843ad42da449158f07d4336183f2d)): ?>
<?php $component = $__componentOriginalaa9843ad42da449158f07d4336183f2d; ?>
<?php unset($__componentOriginalaa9843ad42da449158f07d4336183f2d); ?>
<?php endif; ?>

     <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.show.top",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

    <div class="container">
        <div class="page-product-top">
            <div class="row">
                <div class="col-12 col-lg-6 product-left-col">
                    <div class="product-images">
                        <?php if(is_array($product->images)): ?>
                            <div class="sub-product-img">
                                <div class="swiper" id="sub-product-img-swiper">
                                    <div class="swiper-wrapper">
                                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="swiper-slide" style="height: 120px;">
                                                <?php
                                                    // Debug: pastikan $image adalah string
                                                    if (is_array($image)) {
                                                        $imageUrl = $image['url'] ?? $image['value'] ?? '';
                                                    } else {
                                                        $imageUrl = $image;
                                                    }
                                                    
                                                    // Pastikan $imageUrl adalah string yang valid
                                                    if (!is_string($imageUrl) || empty($imageUrl)) {
                                                        $imageUrl = 'images/placeholder.png'; // fallback image
                                                    }
                                                ?>
                                                <a href="<?php echo e(image_resize($imageUrl, 800, 800)); ?>" 
                                                   data-pswp-height="800">
                                                    <img src="<?php echo e(image_resize($imageUrl, 150, 150)); ?>" 
                                                         class="img-fluid" 
                                                         style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px;">
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="sub-product-btn">
                                        <div class="sub-product-prev"><i class="bi bi-chevron-compact-up"></i></div>
                                        <div class="sub-product-next"><i class="bi bi-chevron-compact-down"></i></div>
                                    </div>
                                    <div class="swiper-pagination sub-product-pagination"></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="main-product-img position-relative">
                             <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("front.product.show.image.before",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

                            <?php
                                $firstImage = $product->images[0] ?? null;
                                // Debug: pastikan format gambar utama juga konsisten
                                if ($firstImage) {
                                    if (is_array($firstImage)) {
                                        $mainImageUrl = $firstImage['url'] ?? $firstImage['value'] ?? '';
                                    } else {
                                        $mainImageUrl = $firstImage;
                                    }
                                    
                                    // Pastikan adalah string yang valid
                                    if (!is_string($mainImageUrl) || empty($mainImageUrl)) {
                                        $mainImageUrl = 'images/placeholder.png';
                                    }
                                } else {
                                    $mainImageUrl = 'images/placeholder.png';
                                }
                            ?>

                            <?php if($firstImage): ?>
                                <img src="<?php echo e(image_resize($mainImageUrl, 800, 800)); ?>" 
                                     class="img-fluid" 
                                     style="width: 100%; max-height: 500px; object-fit: contain; border-radius: 12px;">
                            <?php else: ?>
                                <img src="<?php echo e(asset('images/placeholder.png')); ?>" 
                                     class="img-fluid" 
                                     style="width: 100%; max-height: 500px; object-fit: contain; border-radius: 12px;" 
                                     alt="Gambar tidak tersedia">
                            <?php endif; ?>
                        </div>
                         <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("front.product.show.image.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="product-info">
                        <h1 class="product-title"><?php echo e($product->fallbackName()); ?></h1>
                        
                         <?php
                    $__hook_name="front.product.show.price";
                    ob_start();
                ?>
                        <div class="product-price">
                            <span class="price"><?php echo e($sku['price_format']); ?></span>
                            <?php if($sku['origin_price']): ?>
                                <span class="old-price ms-2"><?php echo e($sku['origin_price_format']); ?></span>
                            <?php endif; ?>
                        </div>
                         <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                $__hook_content = ob_get_clean();
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getWrapper("$__hook_name",["data"=>$__definedVars],function($data) { return null; },$__hook_content);
                unset($__hook_name);
                unset($__hook_content);
                if ($output)
                echo $output;
                ?>

                        <div class="stock-wrap">
                            <?php if($sku['quantity'] > 0): ?>
                                <div class="in-stock badge"><?php echo e(__('front/product.in_stock')); ?></div>
                            <?php else: ?>
                                <div class="out-stock badge d-none"><?php echo e(__('front/product.out_stock')); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="sub-product-title"><?php echo e($product->fallbackName('summary')); ?></div>

                        <?php echo $__env->make('products._bundle_items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <ul class="product-param">
                            <li class="sku">
                                <span class="title"><?php echo e(__('front/product.sku_code')); ?>:</span>
                                <span class="value"><?php echo e($sku['code']); ?></span>
                            </li>
                            <li class="model <?php echo e(!($sku['model'] ?? false) ? 'd-none' : ''); ?>">
                                <span class="title"><?php echo e(__('front/product.model')); ?>:</span>
                                <span class="value"><?php echo e($sku['model']); ?></span>
                            </li>
                            <?php if($product->categories->count()): ?>
                                <li class="category">
                                    <span class="title"><?php echo e(__('front/product.category')); ?>:</span>
                                    <span class="value">
                                        <?php $__currentLoopData = $product->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e($category->url); ?>" class="text-dark"><?php echo e($category->fallbackName()); ?></a><?php echo e(!$loop->last ? ', ' : ''); ?>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if($product->brand): ?>
                                <li class="brand">
                                    <span class="title"><?php echo e(__('front/product.brand')); ?>:</span>
                                    <span class="value">
                                        <a href="<?php echo e($product->brand->url); ?>"> <?php echo e($product->brand->name); ?> </a>
                                    </span>
                                </li>
                            <?php endif; ?>
                             <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.detail.brand.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
                        </ul>

                        
                        <?php if(!system_setting('disable_online_order')): ?>
                            <div class="product-info-bottom">
                                <div class="quantity-wrap">
                                    <div class="minus"><i class="bi bi-dash-lg"></i></div>
                                    <input type="number" class="form-control product-quantity" value="1"
                                          data-sku-id="<?php echo e($sku['id']); ?>">
                                    <div class="plus"><i class="bi bi-plus-lg"></i></div>
                                </div>
                                <div class="product-info-btns">
                                    <button class="btn btn-primary add-cart" data-id="<?php echo e($product->id); ?>"
                                            data-price="<?php echo e($product->masterSku->price); ?>">
                                        <?php echo e(__('front/product.add_to_cart')); ?>

                                    </button>
                                    <button class="btn buy-now ms-2" data-id="<?php echo e($product->id); ?>"
                                            data-price="<?php echo e($product->masterSku->price); ?>">
                                        <?php echo e(__('front/product.buy_now')); ?>

                                    </button>
                                     <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.detail.cart.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        

                        
                        <?php if(auth()->guard('customer')->check()): ?>
                        <?php if(isset($product->submission) && $product->submission->submitter_whatsapp): ?>
                            <div class="product-info-bottom mt-4">
                                
                                <?php
                                    // Mengambil nomor WA dari data submission melalui relasi yang sudah kita buat
                                    $wa_number = $product->submission->submitter_whatsapp;
                                    // Mengganti angka 0 di depan dengan 62 untuk format internasional
                                    if (substr($wa_number, 0, 1) === '0') {
                                        $wa_number = '62' . substr($wa_number, 1);
                                    }
                                    // Menyiapkan pesan default
                                    $product_name = $product->fallbackName();
                                    $message = urlencode("Halo, saya tertarik dengan produk '$product_name' di findmythings.");
                                ?>
                                <?php endif; ?>

                                <a href="https://wa.me/<?php echo e($wa_number); ?>?text=<?php echo e($message); ?>" target="_blank" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-whatsapp me-2"></i> Hubungi Penjual via WhatsApp
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="add-wishlist" data-in-wishlist="<?php echo e($product->hasFavorite()); ?>"
                             data-id="<?php echo e($product->id); ?>"
                             data-price="<?php echo e($product->masterSku->price); ?>">
                            <i class="bi bi-heart<?php echo e($product->hasFavorite() ? '-fill' : ''); ?>"></i> 
                            <?php echo e(__('front/product.add_wishlist')); ?>

                        </div>
                        
                         <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.detail.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-description">
            <ul class="nav nav-tabs tabs-plus">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab"
                            data-bs-target="#product-description-description"
                            type="button"><?php echo e(__('front/product.description')); ?></button>
                </li>
                <?php if($attributes): ?>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product-description-attribute"
                                type="button"><?php echo e(__('front/product.attribute')); ?></button>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product-review"
                            type="button"><?php echo e(__('front/product.review')); ?></button>
                </li>
                <li class="nav-item">
                    <button class="nav-link correlation" data-bs-toggle="tab"
                            data-bs-target="#product-description-correlation"
                            type="button"><?php echo e(__('front/product.related_product')); ?>

                    </button>
                </li>
                 <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.detail.tab.link.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane fade show active" id="product-description-description">
                    <?php if($product->fallbackName('selling_point')): ?>
                        <?php echo parsedown($product->fallbackName('selling_point')); ?>

                    <?php endif; ?>
                    <?php echo $product->fallbackName('content'); ?>

                </div>

                <?php if($attributes): ?>
                    <div class="tab-pane fade" id="product-description-attribute" role="tabpanel">
                        <table class="table table-bordered attribute-table">
                            <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <thead class="table-light">
                                <tr>
                                    <td colspan="2"><strong><?php echo e($group['attribute_group_name']); ?></strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $group['attributes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['attribute']); ?></td>
                                        <td><?php echo e($item['attribute_value']); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="tab-pane fade" id="product-review" role="tabpanel">
                    <?php echo $__env->make('products.review', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                
                <div class="tab-pane fade" id="product-description-correlation">
                    <div class="row gx-3 gx-lg-4">
                        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6 col-md-4 col-lg-3">
                               <?php echo $__env->make('front::shared.product', ['product' => $relatedItem], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                
                 <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.detail.tab.pane.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
            </div>
        </div>

         <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("product.show.bottom",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('footer'); ?>
    <script>
        const isMobile = window.innerWidth < 992;

        if (isMobile) {
            $('.sub-product-img .swiper-slide').each(function () {
                $(this).find('a > img').attr('src', $(this).find('a').attr('href'));
            });
        }

        let subProductSwiper = new Swiper('#sub-product-img-swiper', {
            direction: !isMobile ? 'vertical' : 'horizontal',
            autoHeight: !isMobile ? true : false,
            slidesPerView: !isMobile ? 5 : 1,
            spaceBetween: !isMobile ? 10 : 0,
            navigation: {
                nextEl: '.sub-product-next',
                prevEl: '.sub-product-prev',
            },
            pagination: {
                el: '.sub-product-pagination',
                clickable: true,
            },
            observer: true,
            observeParents: true,
        });

        let lightbox = new PhotoSwipeLightbox({
            gallery: '#sub-product-img-swiper',
            children: 'a',
            // dynamic import is not supported in UMD version
            pswpModule: PhotoSwipe
        });
        lightbox.init();

        $('.main-product-img').on('click', function () {
            $('#sub-product-img-swiper .swiper-slide').eq(0).find('a').get(0).click();
        });

        $('.quantity-wrap .plus, .quantity-wrap .minus').on('click', function () {
            if ($(this).parent().hasClass('disabled')) {
                return;
            }

            let quantity = parseInt($(this).siblings('input').val());
            if ($(this).hasClass('plus')) {
                $(this).siblings('input').val(quantity + 1);
            } else {
                if (quantity > 1) {
                    $(this).siblings('input').val(quantity - 1);
                }
            }
        });

        $('.add-cart, .buy-now').on('click', function () {
            const quantity = $('.product-quantity').val();
            const skuId = $('.product-quantity').data('sku-id');
            const isBuyNow = $(this).hasClass('buy-now');

            inno.addCart({skuId, quantity, isBuyNow}, this, function (res) {
                if (isBuyNow) {
                    window.location.href = '<?php echo e(front_route('carts.index')); ?>';
                }
            })
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/products/show.blade.php ENDPATH**/ ?>
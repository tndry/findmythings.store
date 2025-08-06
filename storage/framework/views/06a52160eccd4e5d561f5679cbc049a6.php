 <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("layout.header.top",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>

<header id="appHeader">
  <div class="header-top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="language-switch d-flex align-items-center">
        <div class="dropdown">
          <a class="btn dropdown-toggle" href="javascript:void(0)">
            <img src="<?php echo e(asset($current_locale->image)); ?>" class="img-fluid"> <?php echo e($current_locale->name); ?>

          </a>
          <div class="dropdown-menu">
            <?php $__currentLoopData = locales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a class="dropdown-item d-flex" href="<?php echo e(front_route('locales.switch', ['code' => $locale->code])); ?>">
                <div class="wh-20 me-2"><img src="<?php echo e(image_origin($locale['image'])); ?>" class="img-fluid border">
                </div>
                <?php echo e($locale->name); ?>

              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
        <div class="dropdown ms-4">
          <a class="btn dropdown-toggle" href="javascript:void(0)">
            <?php echo e(current_currency()->name); ?>

          </a>
          <div class="dropdown-menu">
            <?php $__currentLoopData = currencies(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a class="dropdown-item" href="<?php echo e(front_route('currencies.switch', ['code' => $currency->code])); ?>">
                <?php echo e($currency->name); ?> (<?php echo e($currency->symbol_left); ?>)
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
         <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("layouts.header.currency.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
      </div>

      <div class="top-info">
         <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("layouts.header.news.before",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
        <a href="<?php echo e(front_route('articles.index')); ?>">News</a>

         <?php
                    $__hook_name="layouts.header.telephone";
                    ob_start();
                ?>
        <?php if(system_setting('telephone')): ?>
          <a href="tel:<?php echo e(system_setting('telephone')); ?>">
            <span><i class="bi bi-telephone-outbound"></i> <?php echo e(system_setting('telephone')); ?></span>
          </a>
        <?php endif; ?>
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
      </div>
    </div>
  </div>
  <div class="header-desktop">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="left">
        <h1 class="logo">
          <a href="<?php echo e(front_route('home.index')); ?>">
            <img src="<?php echo e(image_origin(system_setting('front_logo', 'images/logo.svg'))); ?>" class="img-fluid">
          </a>
        </h1>
        <div class="menu">
          <nav class="navbar navbar-expand-md navbar-light">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" aria-current="page"
                   href="<?php echo e(front_route('home.index')); ?>"><?php echo e(__('front/common.home')); ?></a>
              </li>

               <?php
                    $__hook_name="layouts.header.menu.pc";
                    ob_start();
                ?>
              <?php $__currentLoopData = $header_menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($menu['children'] ?? []): ?>
                  <li class="nav-item">
                    <div class="dropdown">
                      <?php if($menu['name']): ?>
                        <a class="nav-link <?php echo e(equal_url($menu['url']) ? 'active' : ''); ?>"
                           href="<?php echo e($menu['url']); ?>"><?php echo e($menu['name']); ?></a>
                      <?php endif; ?>
                      <ul class="dropdown-menu">
                        <?php $__currentLoopData = $menu['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if($child['name']): ?>
                            <li><a class="dropdown-item" href="<?php echo e($child['url']); ?>"><?php echo e($child['name']); ?></a></li>
                          <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </ul>
                    </div>
                  </li>
                <?php else: ?>
                  <?php if($menu['name']): ?>
                    <li class="nav-item">
                      <a class="nav-link <?php echo e(equal_url($menu['url']) ? 'active' : ''); ?>"
                         href="<?php echo e($menu['url']); ?>"><?php echo e($menu['name']); ?></a>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            </ul>
          </nav>
        </div>
      </div>
      <div class="right">
        <form action="<?php echo e(front_route('products.index')); ?>" method="get" class="search-group">
          <input type="text" class="form-control" name="keyword" placeholder="<?php echo e(__('front/common.search')); ?>"
                 value="<?php echo e(request('keyword')); ?>">
          <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
        <div class="icons">
          <div class="item">
            <div class="dropdown account-icon">
              <a class="btn dropdown-toggle px-0" href="<?php echo e(front_route('account.index')); ?>">
                <img src="<?php echo e(asset('images/icons/account.svg')); ?>" class="img-fluid">
              </a>

              <div class="dropdown-menu dropdown-menu-end">
                <?php if(current_customer()): ?>
                  <a href="<?php echo e(front_route('account.index')); ?>"
                     class="dropdown-item"><?php echo e(__('front/account.account')); ?></a>
                  <a href="<?php echo e(front_route('account.orders.index')); ?>"
                     class="dropdown-item"><?php echo e(__('front/account.orders')); ?></a>
                  <a href="<?php echo e(front_route('account.favorites.index')); ?>"
                     class="dropdown-item"><?php echo e(__('front/account.favorites')); ?></a>
                  <a href="<?php echo e(front_route('account.logout')); ?>"
                     class="dropdown-item"><?php echo e(__('front/account.logout')); ?></a>
                <?php else: ?>
                  <a href="<?php echo e(front_route('login.index')); ?>" class="dropdown-item"><?php echo e(__('front/common.login')); ?></a>
                  <a href="<?php echo e(front_route('register.index')); ?>"
                     class="dropdown-item"><?php echo e(__('front/common.register')); ?></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="item">
            <a href="<?php echo e(account_route('favorites.index')); ?>"><img src="<?php echo e(asset('images/icons/love.svg')); ?>"
                                                                  class="img-fluid"><span
                class="icon-quantity"><?php echo e($fav_total); ?></span></a>
          </div>
          <?php if(!system_setting('disable_online_order')): ?>
          <div class="item">
            <a href="javascript:void(0)" class="header-cart-icon" data-bs-toggle="offcanvas"
               data-bs-target="#miniCart" aria-controls="miniCart">
              <img src="<?php echo e(asset('images/icons/cart.svg')); ?>" class="img-fluid">
              <span class="icon-quantity">0</span>
            </a>
          </div>
          <?php endif; ?>
           <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("layouts.header.cart.after",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
        </div>
      </div>
    </div>
  </div>
  <div class="header-mobile">
    <div class="mb-icon" data-bs-toggle="offcanvas" data-bs-target="#mobile-menu-offcanvas"
         aria-controls="offcanvasExample">
      <i class="bi bi-list"></i>
    </div>

    <div class="logo">
      <a href="<?php echo e(front_route('home.index')); ?>">
        <img src="<?php echo e(image_origin(system_setting('front_logo', 'images/logo.svg'))); ?>" class="img-fluid">
      </a>
    </div>
    <?php if(!system_setting('disable_online_order')): ?>
    <a href="<?php echo e(front_route('carts.index')); ?>" class="header-cart-icon"><img src="<?php echo e(asset('images/icons/cart.svg')); ?>"
                                                                             class="img-fluid"><span
        class="icon-quantity">0</span></a>
      <?php endif; ?>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobile-menu-offcanvas">
      <div class="offcanvas-header">
        <form action="" method="get" class="search-group">
          <input type="text" class="form-control" placeholder="Search">
          <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
        <a class="account-icon" href="<?php echo e(front_route('account.index')); ?>">
          <img src="<?php echo e(asset('images/icons/account.svg')); ?>" class="img-fluid">
        </a>
      </div>
      <div class="close-offcanvas" data-bs-dismiss="offcanvas"><i class="bi bi-chevron-compact-left"></i></div>
      <div class="offcanvas-body mobile-menu-wrap">
        <div class="accordion accordion-flush" id="menu-accordion">
          <div class="accordion-item">
            <div class="nav-item-text">
              <a class="nav-link <?php echo e(equal_route_name('home.index') ? 'active' : ''); ?>" aria-current="page"
                 href="<?php echo e(front_route('home.index')); ?>"><?php echo e(__('front/common.home')); ?></a>
            </div>
          </div>

           <?php
                    $__hook_name="layouts.header.menu.mobile";
                    ob_start();
                ?>
          <?php $__currentLoopData = $header_menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($menu['name']): ?>
              <div class="accordion-item">
                <div class="nav-item-text">
                  <a class="nav-link" href="<?php echo e($menu['url']); ?>"
                     data-bs-toggle="<?php echo e(!$menu['url'] ? 'collapse' : ''); ?>">
                    <?php echo e($menu['name']); ?>

                  </a>
                  <?php if(isset($menu['children']) && $menu['children']): ?>
                    <span class="collapsed" data-bs-toggle="collapse"
                          data-bs-target="#flush-menu-<?php echo e($key); ?>"><i class="bi bi-chevron-down"></i></span>
                  <?php endif; ?>
                </div>

                <?php if(isset($menu['children']) && $menu['children']): ?>
                  <div class="accordion-collapse collapse" id="flush-menu-<?php echo e($key); ?>"
                       data-bs-parent="#menu-accordion">
                    <div class="children-group">
                      <ul class="nav flex-column ul-children">
                        <?php $__currentLoopData = $menu['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c_key => $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if($child['name']): ?>
                            <li class="nav-item">
                              <a class="nav-link" href="<?php echo e($child['url']); ?>"><?php echo e($child['name']); ?></a>
                            </li>
                          <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </ul>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

        </div>
      </div>
    </div>
  </div>
</header>

 <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                
                $output = \InnoShop\Plugin\Core\Blade\Hook::getSingleton()->getHook("layout.header.bottom",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>
<?php /**PATH C:\laragon\www\findmythings\innopacks\front\resources\views/layouts/header.blade.php ENDPATH**/ ?>
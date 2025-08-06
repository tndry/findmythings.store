<div class="sidebar">
    <div class="accordion accordion-flush">
        
        <div class="accordion-item">
            <a class="accordion-button collapsed" href="<?php echo e(panel_route('dashboard.index')); ?>">
                <span class="icon"><i class="bi bi-house"></i></span> Dashboard
            </a>
        </div>
        <div class="accordion-item">
            <a class="accordion-button collapsed d-flex justify-content-between align-items-center" href="<?php echo e(panel_route('submissions.index')); ?>">
                <span>
                    <span class="icon"><i class="bi bi-inbox"></i></span>Titipan Masuk
                </span>
                <?php if(isset($pending_submissions_count) && $pending_submissions_count > 0): ?>
                    <span class="badge rounded-pill bg-danger"><?php echo e($pending_submissions_count); ?></span>
                <?php endif; ?>
            </a>
        </div>
        <hr class="dropdown-divider">
        

        <?php $__currentLoopData = $menuLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $menuLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(isset($menuLink['type']) && $menuLink['type'] == 'divider'): ?>
                <div class="px-3 mt-4">
                    <div class="text-secondary small opacity-75 mb-2"><?php echo e($menuLink['title']); ?></div>
                    <hr class="dropdown-divider mt-0 mb-2">
                </div>
            <?php else: ?>
                <div class="accordion-item">
                    <?php if(!$menuLink['has_children']): ?>
                        <?php if(($menuLink['url'] ?? '')): ?>
                            <a class="accordion-button <?php echo e($menuLink['active'] ? '' : 'collapsed'); ?>" href="<?php echo e($menuLink['url']); ?>">
                                <span class="icon"><i class="bi <?php echo e($menuLink['icon'] ?? 'bi-house'); ?>"></i></span> <?php echo e($menuLink['title']); ?>

                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button <?php echo e($menuLink['active'] ? '' : (system_setting('expand') ? '' : 'collapsed')); ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne-<?php echo e($index); ?>"
                                aria-expanded="<?php echo e($menuLink['active'] ? 'true' : 'false'); ?>"
                                aria-controls="flush-collapseOne-<?php echo e($index); ?>">
                                <span class="icon"><i class="bi <?php echo e($menuLink['icon'] ?? 'bi-house'); ?>"></i></span> <?php echo e($menuLink['title']); ?>

                            </button>
                        </h2>
                        <div id="flush-collapseOne-<?php echo e($index); ?>"
                             class="accordion-collapse collapse <?php echo e($menuLink['active'] ? 'show' : (system_setting('expand') ? 'show' : '')); ?>"
                             data-bs-parent="#sidebar-parent">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <?php $__currentLoopData = $menuLink['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="nav-item">
                                            <a href="<?php echo e($child['url']); ?>" <?php if($child['blank'] ?? false): ?> target="_blank" <?php endif; ?>
                                               class="nav-link <?php echo e($child['active'] ? 'active' : ''); ?>"><?php echo e($child['title']); ?></a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH C:\laragon\www\findmythings\innopacks/panel/resources/views/components/layout/sidebar.blade.php ENDPATH**/ ?>
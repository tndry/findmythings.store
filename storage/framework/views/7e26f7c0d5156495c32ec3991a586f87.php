
<?php $__env->startSection('title', 'Daftar Titipan Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link <?php echo e($active_tab == 'pending' ? 'active' : ''); ?>" href="<?php echo e(panel_route('submissions.index', ['tab' => 'pending'])); ?>">
                    Perlu Diproses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e($active_tab == 'history' ? 'active' : ''); ?>" href="<?php echo e(panel_route('submissions.index', ['tab' => 'history'])); ?>">
                    Riwayat
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Nama Produk</td>
                        <td>Penitip</td>
                        <td>Harga</td>
                        <td>Status</td>
                        <td>Tanggal</td>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($submission->id); ?></td>
                        <td><?php echo e($submission->product_name); ?></td>
                        <td><?php echo e($submission->user->name ?? 'N/A'); ?></td>
                        <td><?php echo e(currency_format($submission->price)); ?></td>
                        <td>
                            <?php
                                $status_classes = [
                                    'pending' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'revision_needed' => 'bg-info text-dark',
                                ];
                                $status_class = $status_classes[$submission->status] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?php echo e($status_class); ?>"><?php echo e(panel_trans('submission.status_' . $submission->status)); ?></span>
                        </td>   
                        <td><?php echo e($submission->created_at->format('d M Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('panel.submissions.show', $submission)); ?>" class="btn btn-sm btn-primary">Review</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <?php if($active_tab == 'pending'): ?>
                            <td colspan="7" class="text-center">Tidak ada titipan yang perlu diproses.</td>
                        <?php else: ?>
                            <td colspan="7" class="text-center">Tidak ada riwayat titipan.</td>
                        <?php endif; ?>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($submissions->appends(request()->query())->links('panel::vendor/pagination/bootstrap-4')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('panel::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\findmythings\innopacks/panel/resources/views/submissions/index.blade.php ENDPATH**/ ?>
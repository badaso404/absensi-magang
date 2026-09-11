<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <img src="<?php echo e($user->avatar ? asset('storage/images/avatar/'.$user->avatar) : asset('assets/img/portrait.png')); ?>"
                 alt="<?php echo e($user->name); ?>" class="rounded-circle mr-3"
                 style="width:48px;height:48px;object-fit:cover;">
            <div>
                <h4 class="mb-0 font-weight-bold text-white">
                    <?php echo e($user->name); ?>

                    <?php if($user->seksi): ?>
                        <span class="badge badge-<?php echo e($user->seksi->color()); ?> ml-1"><?php echo e($user->seksi->code()); ?></span>
                    <?php endif; ?>
                </h4>
                <small class="text-light">
                    Laporan kegiatan
                    <?php if($selectedMonth === 'all'): ?>
                        &mdash; seluruh periode
                    <?php else: ?>
                        &mdash; <?php echo e($months[$selectedMonth]); ?> <?php echo e($selectedYear); ?>

                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>

    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Data Laporan</h6>
            <span class="badge badge-light"><?php echo e($laporan->count()); ?> kegiatan</span>
        </div>

        
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="<?php echo e(route('admin-laporan.user', $user->id)); ?>" class="row align-items-end">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="far fa-calendar mr-1"></i>Bulan:</label>
                    <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="all" <?php echo e($selectedMonth === 'all' ? 'selected' : ''); ?>>Semua Bulan</option>
                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomor => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($nomor); ?>" <?php echo e((string) $selectedMonth === (string) $nomor ? 'selected' : ''); ?>>
                                <?php echo e($nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="far fa-calendar-alt mr-1"></i>Tahun:</label>
                    <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tahun); ?>" <?php echo e((string) $selectedYear === (string) $tahun ? 'selected' : ''); ?>>
                                <?php echo e($tahun); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo e(route('admin-laporan.export', $user->id)); ?>?month=<?php echo e($selectedMonth); ?>&year=<?php echo e($selectedYear); ?>"
                       class="btn btn-sm btn-success btn-block">
                        <i class="fas fa-file-excel mr-1"></i>Export XLSX
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="<?php echo e(route('admin-laporan.index')); ?>" class="btn btn-sm btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Hari/Tanggal</th>
                        <th>Detail Kegiatan</th>
                        <th>Lokasi</th>
                        <th>Dokumentasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($i + 1); ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold text-primary"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd')); ?></span>
                                    <span><?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY')); ?></span>
                                </div>
                            </td>
                            <td style="max-width:400px;"><?php echo e($item->detail_kegiatan); ?></td>
                            <td><?php echo e($item->lokasi); ?></td>
                            <td>
                                
                                <?php if($item->dokumentasi): ?>
                                    <a href="<?php echo e(asset('storage/' . $item->dokumentasi)); ?>" target="_blank" rel="noopener"
                                       title="Buka ukuran penuh">
                                        <img src="<?php echo e(asset('storage/' . $item->dokumentasi)); ?>"
                                             alt="Dokumentasi <?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY')); ?>"
                                             class="rounded shadow-sm"
                                             style="width:120px;height:80px;object-fit:cover;"
                                             loading="lazy">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada laporan kegiatan
                                <?php if($selectedMonth !== 'all'): ?>
                                    pada <?php echo e($months[$selectedMonth]); ?> <?php echo e($selectedYear); ?>.
                                <?php else: ?>
                                    .
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/admin/laporan/user.blade.php ENDPATH**/ ?>
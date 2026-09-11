<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-file-alt fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Laporan Kegiatan Bulanan</h4>
                <small class="text-light">Kelola dan export laporan kegiatan</small>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    <?php endif; ?>

    
    <div class="card card-fluid shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="<?php echo e(route('laporan-kegiatan.index')); ?>" class="row align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="form-control-label text-muted small mb-1">
                        <i class="far fa-calendar mr-1"></i>Bulan
                    </label>
                    <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e((string) $selectedMonth === (string) $num ? 'selected' : ''); ?>>
                                <?php echo e($name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="form-control-label text-muted small mb-1">
                        <i class="far fa-calendar-alt mr-1"></i>Tahun
                    </label>
                    <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e((string) $selectedYear === (string) $year ? 'selected' : ''); ?>>
                                <?php echo e($year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <?php if($selectedMonth || $selectedYear): ?>
                        <a href="<?php echo e(route('laporan-kegiatan.index')); ?>" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-times mr-1"></i>Tampilkan Semua
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-fluid shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-light">
                        <i class="fas fa-table mr-2"></i>Data Laporan Kegiatan
                    </h6>
                    <div>
                        <a href="<?php echo e(route('laporan-kegiatan.setting')); ?>" class="btn btn-info btn-sm text-white">
                            <i class="fas fa-cog mr-1"></i> Pengaturan
                        </a>
                        <a href="<?php echo e(route('laporan-kegiatan.create')); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Laporan
                        </a>
                        <a href="<?php echo e(route('laporan-kegiatan.export', ['year' => request('year'), 'month' => request('month')])); ?>"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                        </a>
                        <span class="badge badge-light"><?php echo e($laporan->count()); ?> data</span>
                    </div>
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-primary">
                                            <?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd')); ?>

                                        </span>
                                        <span>
                                            <?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY')); ?>

                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 400px;">
                                        <?php echo e($item->detail_kegiatan); ?>

                                    </div>
                                </td>
                                <td><?php echo e($item->lokasi); ?></td>
                                <td>
                                    
                                    <?php if($item->dokumentasi): ?>
                                    <a href="<?php echo e(asset('storage/' . $item->dokumentasi)); ?>" target="_blank" rel="noopener"
                                       title="Buka ukuran penuh">
                                        <img src="<?php echo e(asset('storage/' . $item->dokumentasi)); ?>"
                                             alt="Dokumentasi <?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY')); ?>"
                                             class="rounded shadow-sm"
                                             style="width:110px;height:74px;object-fit:cover;"
                                             loading="lazy">
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('laporan-kegiatan.edit', $item->id)); ?>"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('laporan-kegiatan.destroy', $item->id)); ?>"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')"
                                            class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <div>Belum ada data laporan kegiatan</div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/laporan-kegiatan/index.blade.php ENDPATH**/ ?>
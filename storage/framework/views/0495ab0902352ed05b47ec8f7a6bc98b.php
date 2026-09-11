<?php $__env->startSection('content'); ?>
<div class="page-content">
    
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-clipboard-check fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">Halo, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h5>
                <span class="text-sm text-white opacity-8">
                    Pemantauan absensi magang &mdash; <?php echo e($tanggal->isoFormat('dddd, D MMMM Y')); ?>

                    <?php if (! ($hariKerja)): ?> <span class="badge badge-warning ml-1">Libur</span> <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if(session('alert')): ?>
            <div class="col-12">
                <?php if (isset($component)) { $__componentOriginalb5e767ad160784309dfcad41e788743b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e767ad160784309dfcad41e788743b = $attributes; } ?>
<?php $component = App\View\Components\Alert::resolve(['title' => session('alert.title'),'type' => session('alert.type'),'messages' => [session('alert.message')],'display' => 'block'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Alert::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e767ad160784309dfcad41e788743b)): ?>
<?php $attributes = $__attributesOriginalb5e767ad160784309dfcad41e788743b; ?>
<?php unset($__attributesOriginalb5e767ad160784309dfcad41e788743b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e767ad160784309dfcad41e788743b)): ?>
<?php $component = $__componentOriginalb5e767ad160784309dfcad41e788743b; ?>
<?php unset($__componentOriginalb5e767ad160784309dfcad41e788743b); ?>
<?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Magang Aktif</h6>
                            <span class="h2 font-weight-bold mb-0"><?php echo e($totalMagang); ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Sudah Absen</h6>
                            <span class="h2 font-weight-bold mb-0 text-success"><?php echo e($sudahAbsen); ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Belum Absen</h6>
                            <span class="h2 font-weight-bold mb-0 <?php echo e($hariKerja ? 'text-danger' : 'text-muted'); ?>">
                                <?php echo e($hariKerja ? $belumAbsen : '—'); ?>

                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape <?php echo e($hariKerja ? 'bg-danger' : 'bg-secondary'); ?> text-white rounded-circle shadow">
                                <i class="fas <?php echo e($hariKerja ? 'fa-user-clock' : 'fa-mug-hot'); ?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Masuk Telat</h6>
                            <span class="h2 font-weight-bold mb-0 text-warning"><?php echo e($telat); ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Absensi Hari Ini
                    </h6>
                    <span class="badge badge-primary"><?php echo e($absensiHariIni->count()); ?> entri</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $absensiHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('rekapabsen.user', $a->user_id)); ?>" class="font-weight-bold">
                                            <?php echo e($a->user->name ?? '-'); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($a->checked_in_at?->format('H:i') ?? '-'); ?></td>
                                    <td><?php echo e($a->checked_out_at?->format('H:i') ?? '-'); ?></td>
                                    <td>
                                        <?php if($a->checked_in_status === App\Enums\AbsensiStatus::MasukTelat): ?>
                                            <span class="badge badge-warning">Telat</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Tepat Waktu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada yang absen hari ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right py-3">
                    <a href="<?php echo e(route('admin-absensi')); ?>" class="btn btn-sm btn-primary">
                        Lihat Semua Absensi
                    </a>
                </div>
            </div>
        </div>

        
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-clock mr-2"></i>Belum Absen
                    </h6>
                    <span class="badge badge-danger"><?php echo e($daftarBelum->count()); ?> orang</span>
                </div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $daftarBelum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $magang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-sm"><?php echo e($magang->name); ?></span>
                            <a href="<?php echo e(route('rekapabsen.user', $magang->id)); ?>"
                               class="btn btn-sm btn-outline-primary">Rekap</a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="list-group-item text-center text-muted py-4">
                            <?php if($hariKerja): ?>
                                Semua magang aktif sudah absen hari ini.
                            <?php else: ?>
                                Hari libur &mdash; tidak ada kewajiban absen.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($belumPulang > 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong><?php echo e($belumPulang); ?></strong> magang belum melakukan absen pulang.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/beranda/admin.blade.php ENDPATH**/ ?>
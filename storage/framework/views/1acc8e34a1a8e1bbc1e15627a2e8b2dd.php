<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-file-alt fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Laporan Kegiatan - Magang</h4>
                <small class="text-light">Daftar laporan kegiatan yang dibuat oleh user magang</small>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Total Magang','value' => $totalMagang,'icon' => 'fa-user-graduate','color' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total Magang','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalMagang),'icon' => 'fa-user-graduate','color' => 'warning']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Total Laporan','value' => $totalLaporan,'icon' => 'fa-book','color' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total Laporan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalLaporan),'icon' => 'fa-book','color' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
        </div>
    </div>


    <!-- Table daftar magang -->
    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between">
            <h6 class="mb-0 font-weight-bold text-white">
                Daftar Magang
                <?php if($selectedSeksi !== 'all'): ?>
                    &mdash; <?php echo e($selectedSeksi->code()); ?>

                <?php endif; ?>
            </h6>
            <span class="badge badge-light"><?php echo e($users->count()); ?> user</span>
        </div>

        
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="<?php echo e(route('admin-laporan.index')); ?>" class="row align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small">
                        <i class="fas fa-sitemap mr-1"></i>Unit / Seksi:
                    </label>
                    <select name="seksi" class="form-control form-control-sm js-select" onchange="this.form.submit()">
                        <option value="all">Semua Unit</option>
                        <?php $__currentLoopData = $seksiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->value); ?>"
                                <?php echo e($selectedSeksi !== 'all' && $selectedSeksi->value === $s->value ? 'selected' : ''); ?>>
                                <?php echo e($s->code()); ?> &mdash; <?php echo e($s->text()); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small">
                        <i class="far fa-calendar mr-1"></i>Bulan:
                    </label>
                    <select name="month" class="form-control form-control-sm js-select" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomor => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($nomor); ?>" <?php echo e((string) $selectedMonth === (string) $nomor ? 'selected' : ''); ?>>
                                <?php echo e($nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small">
                        <i class="far fa-calendar-alt mr-1"></i>Tahun:
                    </label>
                    <select name="year" class="form-control form-control-sm js-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tahun); ?>" <?php echo e((string) $selectedYear === (string) $tahun ? 'selected' : ''); ?>>
                                <?php echo e($tahun); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="<?php echo e(route('admin-laporan.index')); ?>" class="btn btn-sm btn-outline-secondary btn-block">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>Asal / Jurusan</th>
                        <th>Total Laporan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($u->avatar && $u->avatar !== '1' ? asset('storage/images/avatar/'.$u->avatar) : asset('default-avatar.png')); ?>"
                                     alt="<?php echo e($u->name); ?>" class="rounded-circle" style="width:42px;height:42px;object-fit:cover;">
                            </td>
                            <td>
                                <div class="font-weight-bold"><?php echo e($u->name); ?></div>
                                <small class="text-muted"><?php echo e($u->jenis_kelamin ?? '-'); ?> / <?php echo e($u->tanggal_lahir ? \Carbon\Carbon::parse($u->tanggal_lahir)->isoFormat('D MMM Y') : '-'); ?></small>
                            </td>
                            <td>
                                <?php if($u->seksi): ?>
                                    <span class="badge badge-<?php echo e($u->seksi->color()); ?>"
                                          title="<?php echo e($u->seksi->text()); ?>"><?php echo e($u->seksi->code()); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?php echo e($u->asal ?? '-'); ?></div>
                                <small class="text-muted"><?php echo e($u->jurusan ?? '-'); ?></small>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-info"><?php echo e($u->laporan_count ?? 0); ?></span>
                            </td>
                            <td>
                                
                                
                                <a href="<?php echo e(route('admin-laporan.user', array_filter([
                                        'user'  => $u->id,
                                        'month' => $selectedMonth,
                                        'year'  => $selectedYear,
                                   ]))); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye mr-1"></i>Lihat Laporan
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data magang</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-md-none p-3">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="lap-card">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e($u->avatar && $u->avatar !== '1' ? asset('storage/images/avatar/'.$u->avatar) : asset('default-avatar.png')); ?>"
                             alt="<?php echo e($u->name); ?>" class="rounded-circle" style="width:44px;height:44px;object-fit:cover;">
                        <div class="ml-3 flex-grow-1 min-w-0">
                            <div class="font-weight-bold text-dark text-truncate"><?php echo e($u->name); ?></div>
                            <small class="text-muted"><?php echo e($u->asal ?? '-'); ?><?php echo e($u->jurusan ? ' · '.$u->jurusan : ''); ?></small>
                        </div>
                        <?php if($u->seksi): ?>
                            <span class="badge badge-<?php echo e($u->seksi->color()); ?>"><?php echo e($u->seksi->code()); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <span class="text-sm text-muted"><i class="fas fa-book mr-1"></i><?php echo e($u->laporan_count ?? 0); ?> laporan</span>
                        <a href="<?php echo e(route('admin-laporan.user', array_filter([
                                'user'  => $u->id,
                                'month' => $selectedMonth,
                                'year'  => $selectedYear,
                           ]))); ?>" class="btn btn-sm btn-info"><i class="fas fa-eye mr-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-muted py-4">Belum ada data magang</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .lap-card {
        border: 1px solid #edf0f5; border-radius: 14px;
        padding: .9rem 1rem; margin-bottom: .75rem; background: #fff;
        box-shadow: 0 2px 8px rgba(50,50,93,.04);
    }
    .lap-card:last-child { margin-bottom: 0; }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/admin/laporan/index.blade.php ENDPATH**/ ?>
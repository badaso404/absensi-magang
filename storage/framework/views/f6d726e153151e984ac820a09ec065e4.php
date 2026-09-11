<?php $__env->startSection('content'); ?>
<div class="page-content">

    
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-clipboard-check fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">Pantau Absensi Magang</h5>
                <span class="text-sm text-white opacity-8">
                    <?php echo e($tanggal->isoFormat('dddd, D MMMM Y')); ?>

                    <?php if($tanggal->isToday()): ?> <span class="badge badge-light ml-1">Hari Ini</span> <?php endif; ?>
                    <?php if (! ($hariKerja)): ?> <span class="badge badge-warning ml-1">Libur</span> <?php endif; ?>
                    <?php if($selectedSeksi): ?> <span class="badge badge-light ml-1"><?php echo e($selectedSeksi->code()); ?></span> <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    
    <div class="row">
        <?php
            // Di hari libur "Belum Absen" tidak bermakna, jadi ditampilkan
            // sebagai strip abu-abu, bukan angka merah.
            $kartu = [
                ['Magang Aktif', $totalMagang, 'primary', 'fa-users'],
                ['Hadir',        $hadir,       'success', 'fa-user-check'],
                $hariKerja
                    ? ['Belum Absen', $belumAbsen, 'danger', 'fa-user-clock']
                    : ['Belum Absen', '—',        'muted',  'fa-mug-hot'],
                ['Masuk Telat',  $telat,       'warning', 'fa-exclamation-triangle'],
            ];
        ?>
        <?php $__currentLoopData = $kartu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $nilai, $warna, $ikon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-muted text-sm mb-1"><?php echo e($label); ?></h6>
                                <span class="h2 font-weight-bold mb-0 text-<?php echo e($warna); ?>"><?php echo e($nilai); ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-<?php echo e($warna); ?> text-white rounded-circle shadow">
                                    <i class="fas <?php echo e($ikon); ?>"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($belumPulang > 0): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong><?php echo e($belumPulang); ?></strong> magang sudah absen masuk tapi belum absen pulang.
        </div>
    <?php endif; ?>

    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Status Absensi Per Magang</h6>
            <span class="badge badge-light"><?php echo e($baris->count()); ?> magang</span>
        </div>

        
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="<?php echo e(route('admin-absensi')); ?>" class="row align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="far fa-calendar mr-1"></i>Tanggal:</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <a href="<?php echo e(route('admin-absensi', ['tanggal' => $tanggal->copy()->subDay()->format('Y-m-d'), 'seksi' => $selectedSeksi?->value])); ?>"
                               class="btn btn-outline-secondary" title="Hari sebelumnya">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <input type="date" name="tanggal" class="form-control form-control-sm text-center"
                               value="<?php echo e($tanggal->format('Y-m-d')); ?>" onchange="this.form.submit()">
                        <div class="input-group-append">
                            <a href="<?php echo e(route('admin-absensi', ['tanggal' => $tanggal->copy()->addDay()->format('Y-m-d'), 'seksi' => $selectedSeksi?->value])); ?>"
                               class="btn btn-outline-secondary" title="Hari berikutnya">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="fas fa-sitemap mr-1"></i>Unit / Seksi:</label>
                    <select name="seksi" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="all">Semua Unit</option>
                        <?php $__currentLoopData = $seksiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->value); ?>" <?php echo e($selectedSeksi?->value === $s->value ? 'selected' : ''); ?>>
                                <?php echo e($s->code()); ?> &mdash; <?php echo e($s->text()); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <a href="<?php echo e(route('admin-absensi')); ?>" class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fas fa-calendar-day mr-1"></i>Hari Ini
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo e(route('admin-absensi', ['tanggal' => $tanggal->format('Y-m-d')])); ?>"
                       class="btn btn-sm btn-outline-secondary btn-block">
                        <i class="fas fa-times mr-1"></i>Reset Unit
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Mode</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $baris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $u = $item['user']; $a = $item['absensi']; ?>
                        <tr class="<?php echo e($a || !$hariKerja ? '' : 'bg-soft-danger'); ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e($u->avatar ? asset('storage/images/avatar/'.$u->avatar) : asset('assets/img/portrait.png')); ?>"
                                         alt="<?php echo e($u->name); ?>" class="rounded-circle mr-2"
                                         style="width:36px;height:36px;object-fit:cover;">
                                    <div>
                                        <div class="font-weight-bold"><?php echo e($u->name); ?></div>
                                        <small class="text-muted"><?php echo e($u->asal ?? '-'); ?></small>
                                    </div>
                                </div>
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
                                <?php if(!$a && !$hariKerja): ?>
                                    <span class="badge badge-light text-muted">Libur</span>
                                <?php elseif(!$a): ?>
                                    <span class="badge badge-danger">Belum Absen</span>
                                <?php elseif($a->checked_out_at): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Sedang Bekerja</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($a?->checked_in_at): ?>
                                    <div class="font-weight-bold"><?php echo e($a->checked_in_at->format('H:i')); ?></div>
                                    <?php if($a->checked_in_status === App\Enums\AbsensiStatus::MasukTelat): ?>
                                        <small class="text-warning">Telat</small>
                                    <?php else: ?>
                                        <small class="text-success">Tepat waktu</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($a?->checked_out_at): ?>
                                    <div class="font-weight-bold"><?php echo e($a->checked_out_at->format('H:i')); ?></div>
                                    <?php if($a->checked_out_status === App\Enums\AbsensiStatus::PulangCepat): ?>
                                        <small class="text-warning">Pulang cepat</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($a?->wfhwfo): ?>
                                    <span class="badge badge-secondary"><?php echo e($a->wfhwfo); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="max-width:220px;">
                                <?php if($a?->lokasi_user): ?>
                                    <small class="d-block text-truncate" title="<?php echo e($a->lokasi_user); ?>">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i><?php echo e($a->lokasi_user); ?>

                                    </small>
                                    <?php if($a->latitude && $a->longitude): ?>
                                        <a href="https://maps.google.com/maps?q=<?php echo e($a->latitude); ?>,<?php echo e($a->longitude); ?>"
                                           target="_blank" rel="noopener" class="small">Lihat peta</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('rekapabsen.user', $u->id)); ?>"
                                   class="btn btn-sm btn-outline-info" title="Rekap absensi <?php echo e($u->name); ?>">
                                    <i class="fas fa-history"></i> Rekap
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada magang aktif
                                <?php if($selectedSeksi): ?> pada unit <?php echo e($selectedSeksi->code()); ?> <?php endif; ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/admin/absensi/index.blade.php ENDPATH**/ ?>
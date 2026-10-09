
<?php
    $warna = $r->seksi?->color() ?? 'primary';

    // Inisial untuk avatar cadangan kalau belum ada foto.
    $inisial = collect(explode(' ', trim($r->name)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');

    // Progress periode magang: berapa persen masa magang sudah dijalani dan
    // berapa hari tersisa. Hanya dihitung bila kedua tanggal ada dan valid.
    $now = \Carbon\Carbon::now();
    $awal = $r->tanggal_awal_magang ? \Carbon\Carbon::parse($r->tanggal_awal_magang) : null;
    $akhir = $r->tanggal_akhir_magang ? \Carbon\Carbon::parse($r->tanggal_akhir_magang) : null;
    $progress = null;
    $sisaHari = null;
    if ($awal && $akhir && $akhir->greaterThan($awal)) {
        $totalHari = $awal->diffInDays($akhir);
        $lewatHari = $awal->diffInDays($now->greaterThan($akhir) ? $akhir : $now);
        $progress = $totalHari > 0 ? min(100, max(0, round($lewatHari / $totalHari * 100))) : 0;
        $sisaHari = $now->lessThanOrEqualTo($akhir) ? $now->diffInDays($akhir) : 0;
    }
?>

<div class="col-lg-4 col-md-6 mb-4 tim-item" data-nama="<?php echo e(mb_strtolower($r->name)); ?>">
    <div class="card tim-card h-100 mb-0">
        <div class="tim-card__banner bg-<?php echo e($warna); ?>"></div>
        <div class="card-body text-center pt-0">
            <div class="tim-card__avatar-wrap">
                <?php if($r->avatar): ?>
                    <img src="<?php echo e(asset('storage/images/avatar/'.$r->avatar)); ?>"
                         alt="<?php echo e($r->name); ?>" class="tim-card__avatar" loading="lazy">
                <?php else: ?>
                    <div class="tim-card__avatar tim-card__avatar--initial bg-<?php echo e($warna); ?>"><?php echo e($inisial ?: '?'); ?></div>
                <?php endif; ?>
            </div>

            <h6 class="h5 mb-1 mt-3"><?php echo e($r->name); ?></h6>

            <?php if($tampilkanUnit ?? false): ?>
                <?php if($r->seksi): ?>
                    <span class="badge badge-<?php echo e($warna); ?> mb-2" title="<?php echo e($r->seksi->text()); ?>"><?php echo e($r->seksi->code()); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <p class="text-sm text-muted mb-1">
                <i class="fas fa-graduation-cap mr-1"></i><?php echo e($r->jurusan ?? 'Jurusan belum diisi'); ?>

            </p>
            <p class="text-sm text-muted mb-3">
                <i class="fas fa-university mr-1"></i><?php echo e($r->asal ?? '-'); ?>

            </p>

            <?php if($progress !== null): ?>
                <div class="tim-card__periode mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted"><i class="far fa-calendar mr-1"></i><?php echo e($awal->isoFormat('MMM Y')); ?> &ndash; <?php echo e($akhir->isoFormat('MMM Y')); ?></small>
                        <?php if($sisaHari > 0): ?>
                            <small class="font-weight-bold text-<?php echo e($sisaHari <= 7 ? 'danger' : 'success'); ?>"><?php echo e($sisaHari); ?> hari lagi</small>
                        <?php else: ?>
                            <small class="font-weight-bold text-muted">Selesai</small>
                        <?php endif; ?>
                    </div>
                    <div class="tim-progress">
                        <div class="tim-progress__bar bg-<?php echo e($warna); ?>" style="width: <?php echo e($progress); ?>%;"></div>
                    </div>
                </div>
            <?php elseif($r->tanggal_awal_magang || $r->tanggal_akhir_magang): ?>
                <p class="text-xs text-muted mb-3">
                    <i class="far fa-calendar mr-1"></i>
                    <?php echo e($r->tanggal_awal_magang ? \Carbon\Carbon::parse($r->tanggal_awal_magang)->isoFormat('MMM Y') : '?'); ?>

                    &ndash;
                    <?php echo e($r->tanggal_akhir_magang ? \Carbon\Carbon::parse($r->tanggal_akhir_magang)->isoFormat('MMM Y') : '?'); ?>

                </p>
            <?php endif; ?>

            
            <div class="d-flex justify-content-center">
                <?php if($r->no_telp): ?>
                    <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $r->no_telp))); ?>"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-success btn-icon-only rounded-circle mx-1"
                       title="WhatsApp <?php echo e($r->name); ?>">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                <?php endif; ?>
                <?php if($r->instagram): ?>
                    <a href="<?php echo e(Str::startsWith($r->instagram, ['http://', 'https://']) ? $r->instagram : 'https://instagram.com/' . ltrim($r->instagram, '@/')); ?>"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-danger btn-icon-only rounded-circle mx-1"
                       title="Instagram <?php echo e($r->name); ?>">
                        <i class="fab fa-instagram"></i>
                    </a>
                <?php endif; ?>
                <?php if($r->linkedin): ?>
                    <a href="<?php echo e(Str::startsWith($r->linkedin, ['http://', 'https://']) ? $r->linkedin : 'https://' . ltrim($r->linkedin, '/')); ?>"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-info btn-icon-only rounded-circle mx-1"
                       title="LinkedIn <?php echo e($r->name); ?>">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                <?php endif; ?>
                <?php if(!$r->no_telp && !$r->instagram && !$r->linkedin): ?>
                    <span class="text-xs text-muted">Belum ada kontak yang dibagikan</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/tim/_kartu.blade.php ENDPATH**/ ?>
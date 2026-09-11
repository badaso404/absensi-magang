
<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 mb-0">
        <div class="card-body text-center">
            <img src="<?php echo e($r->avatar ? asset('storage/images/avatar/'.$r->avatar) : asset('assets/img/portrait.png')); ?>"
                 alt="<?php echo e($r->name); ?>"
                 class="rounded-circle shadow mb-3"
                 style="width:90px;height:90px;object-fit:cover;"
                 loading="lazy">

            <h6 class="h5 mb-1"><?php echo e($r->name); ?></h6>

            <?php if($tampilkanUnit ?? false): ?>
                <?php if($r->seksi): ?>
                    <span class="badge badge-<?php echo e($r->seksi->color()); ?> mb-2"
                          title="<?php echo e($r->seksi->text()); ?>"><?php echo e($r->seksi->code()); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <p class="text-sm text-muted mb-1">
                <?php echo e($r->jurusan ?? 'Jurusan belum diisi'); ?>

            </p>
            <p class="text-sm text-muted mb-3">
                <i class="fas fa-university mr-1"></i><?php echo e($r->asal ?? '-'); ?>

            </p>

            <?php if($r->tanggal_awal_magang || $r->tanggal_akhir_magang): ?>
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
<?php $__env->startSection('content'); ?>
<div class="page-content">

    
    
    <div class="page-title tim-judul mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-user-friends fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">
                    Tim Saya
                    <?php if($saya->seksi): ?>
                        <span class="badge badge-light ml-1"><?php echo e($saya->seksi->code()); ?></span>
                    <?php endif; ?>
                </h5>
                <span class="text-sm text-white opacity-8">
                    <?php if($saya->seksi): ?>
                        <?php echo e($saya->seksi->text()); ?> &mdash; <?php echo e($rekan->count()); ?> rekan magang
                    <?php else: ?>
                        Unit belum ditentukan
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    <?php if(!$saya->seksi): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Akun Anda belum ditempatkan di unit mana pun, jadi daftar Tim Saya belum bisa ditampilkan.
            Hubungi admin untuk penempatan unit. Anda tetap bisa melihat rekan di bagian Lintas Tim di bawah.
        </div>
    <?php elseif($rekan->isEmpty()): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Belum ada rekan magang lain yang aktif di unit <?php echo e($saya->seksi->code()); ?> saat ini.
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $rekan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('tim._kartu', ['r' => $r, 'tampilkanUnit' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <div class="card card-fluid shadow-sm mt-4">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            
            <h6 class="mb-0 font-weight-bold text-white">
                <i class="fas fa-people-arrows mr-2"></i>Lintas Tim
                <?php if($seksiDipilih): ?>
                    <span class="badge badge-light ml-1"><?php echo e($seksiDipilih->code()); ?></span>
                <?php endif; ?>
            </h6>
            <span class="badge badge-light"><?php echo e($lintas->count()); ?> rekan</span>
        </div>

        
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="<?php echo e(route('tim')); ?>" class="row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label class="form-control-label text-muted small mb-1">
                        <i class="fas fa-sitemap mr-1"></i>Tampilkan unit
                    </label>
                    <select name="seksi" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="all" <?php echo e(!$seksiDipilih ? 'selected' : ''); ?>>Semua unit lain</option>
                        <?php $__currentLoopData = $daftarSeksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->value); ?>" <?php echo e($seksiDipilih?->value === $s->value ? 'selected' : ''); ?>>
                                <?php echo e($s->code()); ?> &mdash; <?php echo e($s->text()); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <?php if($seksiDipilih): ?>
                        <a href="<?php echo e(route('tim')); ?>" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-times mr-1"></i>Semua unit
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card-body">
            <?php if($lintas->isEmpty()): ?>
                <p class="text-center text-muted mb-0 py-3">
                    <?php if($seksiDipilih): ?>
                        Belum ada magang aktif di unit <?php echo e($seksiDipilih->code()); ?> saat ini.
                    <?php else: ?>
                        Belum ada magang aktif di unit lain saat ini.
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <div class="row">
                    <?php $__currentLoopData = $lintas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('tim._kartu', ['r' => $r, 'tampilkanUnit' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Banner ungu di layout tingginya 420px dari atas halaman, dan konten
       mulai ±110px di bawah navbar. Halaman lain memang sengaja membiarkan
       kartunya "menggantung" melewati batas ungu/putih, tapi untuk halaman
       ini kartu rekan harus utuh di area putih: judul diberi tinggi minimal
       sehingga ujung bawahnya tepat melewati batas banner, dan judulnya
       diletakkan di tengah area itu supaya tidak menggantung di atas. */
    .tim-judul {
        min-height: 220px;
        display: flex;
        align-items: center;
    }

    @media (max-width: 767.98px) {
        .tim-judul { min-height: 160px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/tim/index.blade.php ENDPATH**/ ?>
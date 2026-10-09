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

    
    <div class="row mb-2">
        <div class="col-6 col-md-3 mb-3">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Rekan Satu Unit','value' => $rekan->count(),'icon' => 'fa-user-friends','color' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Rekan Satu Unit','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rekan->count()),'icon' => 'fa-user-friends','color' => 'primary']); ?>
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
        <div class="col-6 col-md-3 mb-3">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Rekan Lintas Tim','value' => $lintas->count(),'icon' => 'fa-network-wired','color' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Rekan Lintas Tim','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lintas->count()),'icon' => 'fa-network-wired','color' => 'info']); ?>
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
        <div class="col-12 col-md-6 mb-3">
            <div class="tim-search h-100 d-flex align-items-center">
                <i class="fas fa-search"></i>
                <input type="text" id="tim-cari" class="form-control" placeholder="Cari nama rekan..." autocomplete="off">
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
                <?php if($tampilMentor): ?>
                    <i class="fas fa-chalkboard-teacher mr-2"></i>Mentor Pembimbing
                <?php else: ?>
                    <i class="fas fa-people-arrows mr-2"></i>Lintas Tim
                    <?php if($seksiDipilih): ?>
                        <span class="badge badge-light ml-1"><?php echo e($seksiDipilih->code()); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </h6>
            <span class="badge badge-light">
                <?php echo e($tampilMentor ? $admins->count() . ' mentor' : $lintas->count() . ' rekan'); ?>

            </span>
        </div>

        
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="<?php echo e(route('tim')); ?>" class="row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label class="form-control-label text-muted small mb-1">
                        <i class="fas fa-sitemap mr-1"></i>Tampilkan
                    </label>
                    <select name="seksi" class="form-control form-control-sm js-select" onchange="this.form.submit()">
                        <option value="all" <?php echo e(!$seksiDipilih && !$tampilMentor ? 'selected' : ''); ?>>Semua unit lain</option>
                        <option value="mentor" <?php echo e($tampilMentor ? 'selected' : ''); ?>>Mentor (Admin Pembimbing)</option>
                        <?php $__currentLoopData = $daftarSeksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->value); ?>" <?php echo e($seksiDipilih?->value === $s->value ? 'selected' : ''); ?>>
                                <?php echo e($s->code()); ?> &mdash; <?php echo e($s->text()); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <?php if($seksiDipilih || $tampilMentor): ?>
                        <a href="<?php echo e(route('tim')); ?>" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-times mr-1"></i>Semua unit
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card-body">
            <?php if($tampilMentor): ?>
                
                <?php if($admins->isEmpty()): ?>
                    <p class="text-center text-muted mb-0 py-3">Belum ada admin pembimbing yang bisa ditampilkan.</p>
                <?php else: ?>
                    <p class="text-sm text-muted mb-3">
                        <i class="fas fa-info-circle mr-1"></i>Mengalami kendala saat mengakses website atau ada hal yang membingungkan? Jangan ragu menghubungi admin pembimbing berikut.
                    </p>
                    <div class="row">
                        <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Admin selalu ungu (primary) sebagai penanda peran.
                                $inisialAdmin = collect(explode(' ', trim($a->name)))->filter()->take(2)
                                    ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card tim-card h-100 mb-0">
                                    <div class="tim-card__banner bg-primary"></div>
                                    <div class="card-body text-center pt-0">
                                        <div class="tim-card__avatar-wrap">
                                            <?php if($a->avatar): ?>
                                                <img src="<?php echo e(asset('storage/images/avatar/'.$a->avatar)); ?>"
                                                     alt="<?php echo e($a->name); ?>" class="tim-card__avatar" loading="lazy">
                                            <?php else: ?>
                                                <div class="tim-card__avatar tim-card__avatar--initial bg-primary"><?php echo e($inisialAdmin ?: '?'); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <h6 class="h5 mb-1 mt-3"><?php echo e($a->name); ?></h6>
                                        <span class="badge badge-primary mb-4">Admin Pembimbing</span>

                                        <div class="d-flex justify-content-center mt-2">
                                            <?php if($a->no_telp): ?>
                                                <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $a->no_telp))); ?>"
                                                   target="_blank" rel="noopener"
                                                   class="btn btn-sm btn-success btn-icon-only rounded-circle mx-1"
                                                   title="WhatsApp <?php echo e($a->name); ?>">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-xs text-muted">Belum ada kontak yang dibagikan</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php elseif($lintas->isEmpty()): ?>
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

    
    <div id="tim-no-result" class="text-center text-muted py-5" style="display:none;">
        <i class="fas fa-user-slash fa-2x mb-2 d-block opacity-3"></i>
        <div>Tidak ada rekan dengan nama itu.</div>
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

    /* ---- Kartu rekan ---- */
    .tim-card {
        border: none; border-radius: 16px; overflow: hidden;
        box-shadow: 0 2px 12px rgba(50,50,93,.07);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .tim-card:hover { transform: translateY(-5px); box-shadow: 0 16px 32px -12px rgba(50,50,93,.3); }
    .tim-card__banner { height: 64px; }
    .tim-card__avatar-wrap { margin-top: -42px; display: flex; justify-content: center; }
    .tim-card__avatar {
        width: 84px; height: 84px; border-radius: 50%; object-fit: cover;
        border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,.12); background: #fff;
    }
    .tim-card__avatar--initial {
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 1.9rem;
    }
    .tim-progress { height: 7px; background: #eef1f6; border-radius: 999px; overflow: hidden; }
    .tim-progress__bar { height: 100%; border-radius: 999px; transition: width .4s ease; }

    /* ---- Pencarian ---- */
    .tim-search { position: relative; }
    .tim-search i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #8898aa; z-index: 2; }
    .tim-search input { padding-left: 2.6rem; height: 52px; border-radius: 12px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        const cari = document.getElementById('tim-cari');
        if (!cari) return;
        const kosong = document.getElementById('tim-no-result');

        cari.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let tampil = 0;
            document.querySelectorAll('.tim-item').forEach(function (el) {
                const cocok = !q || (el.dataset.nama || '').indexOf(q) !== -1;
                el.style.display = cocok ? '' : 'none';
                if (cocok) tampil++;
            });
            if (kosong) kosong.style.display = (q && tampil === 0) ? '' : 'none';
        });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/tim/index.blade.php ENDPATH**/ ?>
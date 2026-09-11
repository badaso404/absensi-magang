<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-info text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-cog fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Pengaturan Laporan Kegiatan</h4>
                <small class="text-light">Atur informasi pekerjaan dan bidang untuk export</small>
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="mb-0 font-weight-bold text-white">
                        <i class="fas fa-edit mr-2"></i>Informasi Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('laporan-kegiatan.setting.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Info:</strong> Pengaturan ini akan digunakan untuk semua export laporan kegiatan Anda.
                        </div>

                        <div class="form-group">
                            <label for="bidang_suku_dinas" class="font-weight-bold">
                                Bidang / Suku Dinas <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['bidang_suku_dinas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="bidang_suku_dinas" 
                                   name="bidang_suku_dinas" 
                                   value="<?php echo e(old('bidang_suku_dinas', auth()->user()->bidang_suku_dinas ?? 'Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat')); ?>"
                                   placeholder="Contoh: Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat"
                                   required>
                            <?php $__errorArgs = ['bidang_suku_dinas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Nama bidang dan suku dinas tempat Anda bekerja
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="pekerjaan" class="font-weight-bold">
                                Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['pekerjaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="pekerjaan" 
                                   name="pekerjaan" 
                                   value="<?php echo e(old('pekerjaan', auth()->user()->pekerjaan ?? 'Technical Support Keamanan Informasi')); ?>"
                                   placeholder="Contoh: Technical Support Keamanan Informasi"
                                   required>
                            <?php $__errorArgs = ['pekerjaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Jabatan atau posisi Anda
                            </small>
                        </div>

                        <div class="form-group mb-0 mt-4">
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                            </button>
                            <a href="<?php echo e(route('laporan-kegiatan.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-lightbulb mr-2"></i>Tips
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Informasi ini akan muncul di header export Excel
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Pastikan nama bidang dan pekerjaan sesuai dengan data resmi
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Anda bisa mengubah informasi ini kapan saja
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">Preview Export</h6>
                    <div class="border p-3 bg-light" style="font-size: 0.85rem;">
                        <p class="mb-1"><strong>Nama:</strong> <?php echo e(auth()->user()->name); ?></p>
                        <p class="mb-1"><strong>Bidang / Suku Dinas:</strong></p>
                        <p class="mb-1 text-primary"><?php echo e(auth()->user()->bidang_suku_dinas ?? 'Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat'); ?></p>
                        <p class="mb-1"><strong>Pekerjaan:</strong></p>
                        <p class="mb-0 text-primary"><?php echo e(auth()->user()->pekerjaan ?? 'Technical Support Keamanan Informasi'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/laporan-kegiatan/setting.blade.php ENDPATH**/ ?>
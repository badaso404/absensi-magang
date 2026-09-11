<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-warning text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-edit fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Edit Laporan Kegiatan</h4>
                <small class="text-light">Perbarui data laporan kegiatan</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-warning text-white">
                    <h6 class="mb-0 font-weight-bold text-white">
                        <i class="fas fa-edit mr-2"></i>Form Edit Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('laporan-kegiatan.update', $laporan->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="form-group">
                            <label for="tanggal" class="font-weight-bold">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="<?php echo e(old('tanggal', $laporan->tanggal->format('Y-m-d'))); ?>"
                                   required>
                            <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="detail_kegiatan" class="font-weight-bold">
                                Detail Kegiatan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['detail_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="detail_kegiatan" 
                                      name="detail_kegiatan" 
                                      rows="5" 
                                      required><?php echo e(old('detail_kegiatan', $laporan->detail_kegiatan)); ?></textarea>
                            <?php $__errorArgs = ['detail_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="lokasi" class="font-weight-bold">
                                Lokasi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="lokasi" 
                                   name="lokasi" 
                                   value="<?php echo e(old('lokasi', $laporan->lokasi)); ?>"
                                   required>
                            <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="dokumentasi" class="font-weight-bold">
                                Dokumentasi (Opsional)
                            </label>
                            
                            <?php if($laporan->dokumentasi): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(asset('storage/' . $laporan->dokumentasi)); ?>" 
                                     alt="Dokumentasi" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                                <div class="mt-1">
                                    <small class="text-muted">Dokumentasi saat ini</small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input <?php $__errorArgs = ['dokumentasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="dokumentasi" 
                                       name="dokumentasi"
                                       accept="image/jpeg,image/png,image/jpg">
                                <label class="custom-file-label" for="dokumentasi">
                                    <?php echo e($laporan->dokumentasi ? 'Ganti gambar...' : 'Pilih file gambar...'); ?>

                                </label>
                            </div>
                            <?php $__errorArgs = ['dokumentasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.
                            </small>
                        </div>

                        <div class="form-group mb-0 mt-4">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save mr-2"></i>Update Laporan
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
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Informasi
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted">Dibuat pada:</small>
                        <div class="font-weight-bold">
                            <?php echo e($laporan->created_at->isoFormat('D MMMM Y, HH:mm')); ?>

                        </div>
                    </div>
                    <?php if($laporan->updated_at != $laporan->created_at): ?>
                    <div class="mb-2">
                        <small class="text-muted">Terakhir diupdate:</small>
                        <div class="font-weight-bold">
                            <?php echo e($laporan->updated_at->isoFormat('D MMMM Y, HH:mm')); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Preview nama file yang dipilih
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Pilih file gambar...';
        var label = e.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/laporan-kegiatan/edit.blade.php ENDPATH**/ ?>
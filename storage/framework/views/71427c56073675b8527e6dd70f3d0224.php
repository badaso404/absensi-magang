<?php $__env->startSection('content'); ?>
<style>
    .form-control:focus, .form-select:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }
    
    .card {
        animation: slideInUp 0.5s ease-out;
    }
    
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    
    .form-group {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn { to { opacity: 1; } }
    
    .avatar-preview {
        width: 120px; height: 120px; border-radius: 50%;
        object-fit: cover; border: 4px solid #5e72e4;
        transition: transform 0.3s ease;
    }
    .avatar-preview:hover { transform: scale(1.05); }
</style>

<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-success text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-plus fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Tambah User Magang</h4>
                <small class="text-light">Isi form di bawah untuk menambah user magang baru</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-edit mr-2"></i>Form Tambah User</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin-user-store')); ?>" method="POST" enctype="multipart/form-data" id="userForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.1s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-user text-primary mr-1"></i>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php echo e(old('name')); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.15s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-id-card text-secondary mr-1"></i>NISN / NIM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select class="form-control bg-light" name="identity_type" style="width: 85px; border-radius: 5px 0 0 5px;">
                                                <option value="NIM" <?php echo e(old('identity_type') == 'NIM' ? 'selected' : ''); ?>>NIM</option>
                                                <option value="NISN" <?php echo e(old('identity_type') == 'NISN' ? 'selected' : ''); ?>>NISN</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control <?php $__errorArgs = ['identity_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="identity_number" value="<?php echo e(old('identity_number')); ?>" placeholder="Masukkan Nomor Identitas" required style="border-radius: 0 5px 5px 0;">
                                    </div>
                                    <?php $__errorArgs = ['identity_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.2s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-envelope text-info mr-1"></i>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.25s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-lock text-warning mr-1"></i>Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.3s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-lock text-warning mr-1"></i>Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.35s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-venus-mars text-danger mr-1"></i>Jenis Kelamin</label>
                                    <select class="form-select <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" <?php echo e(old('jenis_kelamin') == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo e(old('jenis_kelamin') == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                    <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.4s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-birthday-cake text-success mr-1"></i>Tanggal Lahir</label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>">
                                    <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.1s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-university text-primary mr-1"></i>Asal Sekolah/Universitas</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="asal" value="<?php echo e(old('asal')); ?>">
                                    <?php $__errorArgs = ['asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.15s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-graduation-cap text-info mr-1"></i>Jurusan</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="jurusan" value="<?php echo e(old('jurusan')); ?>">
                                    <?php $__errorArgs = ['jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.2s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-sitemap text-warning mr-1"></i>Seksi <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['seksi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="seksi" required>
                                        <option value="">Pilih Seksi</option>
                                        <?php $__currentLoopData = $seksiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($seksi->value); ?>" <?php echo e(old('seksi') == $seksi->value ? 'selected' : ''); ?>><?php echo e($seksi->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['seksi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.25s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-calendar-alt text-success mr-1"></i>Tanggal Awal Magang</label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_awal_magang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggal_awal_magang" value="<?php echo e(old('tanggal_awal_magang')); ?>">
                                    <?php $__errorArgs = ['tanggal_awal_magang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.3s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-calendar-check text-danger mr-1"></i>Tanggal Akhir Magang</label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_akhir_magang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggal_akhir_magang" value="<?php echo e(old('tanggal_akhir_magang')); ?>">
                                    <?php $__errorArgs = ['tanggal_akhir_magang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.35s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-phone text-success mr-1"></i>No. Telepon (WhatsApp)</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['no_telp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="no_telp" value="<?php echo e(old('no_telp')); ?>" placeholder="08xxxx">
                                    <?php $__errorArgs = ['no_telp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.4s;">
                                    <label class="form-label font-weight-bold"><i class="fab fa-instagram text-danger mr-1"></i>Instagram URL</label>
                                    <input type="url" class="form-control" name="instagram" value="<?php echo e(old('instagram')); ?>" placeholder="https://instagram.com/username">
                                </div>
                                <div class="form-group mb-3" style="animation-delay: 0.45s;">
                                    <label class="form-label font-weight-bold"><i class="fab fa-linkedin text-info mr-1"></i>LinkedIn URL</label>
                                    <input type="url" class="form-control" name="linkedin" value="<?php echo e(old('linkedin')); ?>" placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.5s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-image text-primary mr-1"></i>Foto Profil</label>
                                    <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div class="text-center mt-3"><img id="preview" src="<?php echo e(asset('default-avatar.png')); ?>" alt="Preview" class="avatar-preview"></div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('admin-user')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i>Simpan Data</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById('userForm').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        btn.disabled = true;
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/admin/user/create.blade.php ENDPATH**/ ?>
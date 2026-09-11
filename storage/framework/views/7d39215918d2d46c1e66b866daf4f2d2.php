<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-warning text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-edit fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Edit User Magang</h4>
                <small class="text-light">Update informasi user <?php echo e($user->name); ?></small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-warning text-white">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-edit mr-2"></i>Form Edit User
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin-user-update', $user->id)); ?>" method="POST" enctype="multipart/form-data" id="userForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label font-weight-bold">
                                        <i class="fas fa-user text-primary mr-1"></i>Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
                                    <?php $__errorArgs = ['name'];
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

                                <div class="form-group mb-3">
                                    <label for="identity_number" class="form-label font-weight-bold">
                                        <i class="fas fa-id-card text-secondary mr-1"></i>NISN / NIM <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['identity_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="identity_number" name="identity_number"
                                           value="<?php echo e(old('identity_number', $user->identity_number)); ?>"
                                           placeholder="Masukkan Nomor Identitas" required>
                                    <?php $__errorArgs = ['identity_number'];
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

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label font-weight-bold">
                                        <i class="fas fa-envelope text-info mr-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                                    <?php $__errorArgs = ['email'];
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

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label font-weight-bold">
                                        <i class="fas fa-lock text-warning mr-1"></i>Password Baru
                                    </label>
                                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="password" name="password">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    <?php $__errorArgs = ['password'];
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

                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label font-weight-bold">
                                        <i class="fas fa-lock text-warning mr-1"></i>Konfirmasi Password Baru
                                    </label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="jenis_kelamin" class="form-label font-weight-bold">
                                        <i class="fas fa-venus-mars text-danger mr-1"></i>Jenis Kelamin
                                    </label>
                                    <select class="form-control custom-select <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                    <?php $__errorArgs = ['jenis_kelamin'];
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

                                <div class="form-group mb-3">
                                    <label for="tanggal_lahir" class="form-label font-weight-bold">
                                        <i class="fas fa-birthday-cake text-success mr-1"></i>Tanggal Lahir
                                    </label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir)); ?>">
                                    <?php $__errorArgs = ['tanggal_lahir'];
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
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="asal" class="form-label font-weight-bold">
                                        <i class="fas fa-university text-primary mr-1"></i>Asal Sekolah/Universitas
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="asal" name="asal" value="<?php echo e(old('asal', $user->asal)); ?>">
                                    <?php $__errorArgs = ['asal'];
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

                                <div class="form-group mb-3">
                                    <label for="jurusan" class="form-label font-weight-bold">
                                        <i class="fas fa-graduation-cap text-info mr-1"></i>Jurusan
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="jurusan" name="jurusan" value="<?php echo e(old('jurusan', $user->jurusan)); ?>">
                                    <?php $__errorArgs = ['jurusan'];
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

                                <div class="form-group mb-3">
                                    <label for="seksi" class="form-label font-weight-bold">
                                        <i class="fas fa-sitemap text-warning mr-1"></i>Seksi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control custom-select <?php $__errorArgs = ['seksi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="seksi" name="seksi" required>
                                        <option value="">Pilih Seksi</option>
                                        <?php $__currentLoopData = $seksiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <option value="<?php echo e($seksi->value); ?>" <?php echo e((int) old('seksi', $user->seksi?->value) === $seksi->value ? 'selected' : ''); ?>>
                                                <?php echo e($seksi->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['seksi'];
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

                                <div class="form-group mb-3">
                                    <label for="tanggal_awal_magang" class="form-label font-weight-bold">
                                        <i class="fas fa-calendar-alt text-success mr-1"></i>Tanggal Awal Magang
                                    </label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_awal_magang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="tanggal_awal_magang" name="tanggal_awal_magang" value="<?php echo e(old('tanggal_awal_magang', $user->tanggal_awal_magang)); ?>">
                                    <?php $__errorArgs = ['tanggal_awal_magang'];
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

                                <div class="form-group mb-3">
                                    <label for="tanggal_akhir_magang" class="form-label font-weight-bold">
                                        <i class="fas fa-calendar-check text-danger mr-1"></i>Tanggal Akhir Magang
                                    </label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tanggal_akhir_magang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="tanggal_akhir_magang" name="tanggal_akhir_magang" value="<?php echo e(old('tanggal_akhir_magang', $user->tanggal_akhir_magang)); ?>">
                                    <?php $__errorArgs = ['tanggal_akhir_magang'];
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

                                <div class="form-group mb-3">
                                    <label for="no_telp" class="form-label font-weight-bold">
                                        <i class="fas fa-phone text-success mr-1"></i>No. Telepon (WhatsApp)
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['no_telp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="no_telp" name="no_telp" value="<?php echo e(old('no_telp', $user->no_telp)); ?>" placeholder="08xxxx">
                                    <?php $__errorArgs = ['no_telp'];
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
                            </div>
                        </div>

                        <!-- Social Media & Avatar -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="instagram" class="form-label font-weight-bold">
                                        <i class="fab fa-instagram text-danger mr-1"></i>Instagram URL
                                    </label>
                                    <input type="url" class="form-control <?php $__errorArgs = ['instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="instagram" name="instagram" value="<?php echo e(old('instagram', $user->instagram)); ?>" placeholder="https://instagram.com/username">
                                    <?php $__errorArgs = ['instagram'];
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

                                <div class="form-group mb-3">
                                    <label for="linkedin" class="form-label font-weight-bold">
                                        <i class="fab fa-linkedin text-info mr-1"></i>LinkedIn URL
                                    </label>
                                    <input type="url" class="form-control <?php $__errorArgs = ['linkedin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="linkedin" name="linkedin" value="<?php echo e(old('linkedin', $user->linkedin)); ?>" placeholder="https://linkedin.com/in/username">
                                    <?php $__errorArgs = ['linkedin'];
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
                            </div>

                           <div class="col-md-6">
    <div class="form-group mb-3">
        <label for="avatar" class="form-label font-weight-bold">
            <i class="fas fa-image text-primary mr-1"></i>Foto Profil Baru
        </label>
        <input type="file" class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="avatar" name="avatar" accept="image/*" onchange="previewImage(event)">
        <small class="text-muted">Format: JPG, PNG, JPEG. Max: 2MB. Kosongkan jika tidak ingin mengubah</small>
        <?php $__errorArgs = ['avatar'];
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
    <div class="text-center mt-3">
     <div class="position-relative d-inline-block">
    <img src="<?php echo e($user->avatar && $user->avatar !== '1' ? asset('storage/images/avatar/'.$user->avatar) : asset('default-avatar.png')); ?>" 
         alt="Avatar <?php echo e($user->name); ?>" 
         class="rounded-circle border border-primary shadow" 
         style="width: 120px; height: 120px; object-fit: cover;">

    <div class="position-absolute top-50 start-50 translate-middle bg-primary bg-opacity-50 d-flex align-items-center justify-content-center rounded-circle opacity-0 hover-overlay" 
         style="width: 120px; height: 120px; transition: opacity 0.3s;">
        <i class="bi bi-camera text-white fs-3"></i>
    </div>
</div>

        <small class="text-muted d-block mt-2">Preview Foto Profil</small>
    </div>
</div>


                        <!-- Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('admin-user')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning text-white">
                                        <i class="fas fa-save mr-2"></i>Update Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-control:focus, .custom-select:focus {
        border-color: #fb6340;
        box-shadow: 0 0 0 0.2rem rgba(251, 99, 64, 0.25);
    }
    
    .card {
        animation: slideInUp 0.5s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .form-group {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }
    
    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    .form-group:nth-child(6) { animation-delay: 0.6s; }
    
   .position-relative:hover .hover-overlay {
    opacity: 1 !important;
}

    .avatar-overlay i {
        color: white;
        font-size: 30px;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.5);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .form-label {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control, .custom-select {
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        border: 1px solid #d1d3e2;
        background-color: #fff;
        transition: all 0.3s ease;
    }

    .custom-select {
        height: calc(2.25rem + 2px);
        background: #fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right 0.75rem center/8px 10px no-repeat;
    }

    .card {
        border-radius: 15px;
        border: none;
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        padding: 20px;
    }

    .card-body {
        padding: 30px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.style.animation = 'zoomIn 0.5s ease';
        };
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('userForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
                    submitBtn.disabled = true;
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/admin/user/edit.blade.php ENDPATH**/ ?>
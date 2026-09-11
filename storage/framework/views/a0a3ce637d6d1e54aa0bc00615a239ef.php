<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="h3 font-weight-400 mb-0 text-white">Selamat pagi, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h5>
                <span class="text-sm text-white opacity-8">Informasi profil akun Anda.</span>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if(session('alert')): ?>
        <div class="col-12">
            <?php if (isset($component)) { $__componentOriginalb5e767ad160784309dfcad41e788743b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e767ad160784309dfcad41e788743b = $attributes; } ?>
<?php $component = App\View\Components\Alert::resolve(['title' => session('alert.title'),'type' => session('alert.type'),'messages' => [session('alert.message')],'display' => 'block'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Alert::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e767ad160784309dfcad41e788743b)): ?>
<?php $attributes = $__attributesOriginalb5e767ad160784309dfcad41e788743b; ?>
<?php unset($__attributesOriginalb5e767ad160784309dfcad41e788743b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e767ad160784309dfcad41e788743b)): ?>
<?php $component = $__componentOriginalb5e767ad160784309dfcad41e788743b; ?>
<?php unset($__componentOriginalb5e767ad160784309dfcad41e788743b); ?>
<?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="col-lg-6">
            <div class="card card-fluid">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar rounded-circle">
                                <img alt="Avatar" class="avatar-cover" src="<?php echo e(auth()->user()->avatar ? asset('storage/images/avatar/'.auth()->user()->avatar) : asset('assets/img/portrait.png')); ?>">
                            </a>
                        </div>
                        <div class="col ml-md-n2">
                            <a href="#!" class="d-block h6 mb-0"><?php echo e(auth()->user()->name); ?></a>
                            <small class="d-block text-muted"><?php echo e(auth()->user()->seksi?->text() ?? '-'); ?></small>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo e(route('profil-edit')); ?>" class="btn btn-xs btn-primary btn-icon rounded-pill">
                                <span class="btn-inner--icon"><i class="far fa-edit"></i></span>
                                <span class="btn-inner--text">Edit Profil</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center text-center">
                        <div class="col-4">
                            <span class="h3 mb-0 text-info font-weight-bolder">0</span>
                            <span class="d-block text-sm">Kehadiran</span>
                        </div>
                        <div class="col-4">
                            <span class="h3 mb-0 text-info font-weight-bolder">0</span>
                            <span class="d-block text-sm">Tugas</span>
                        </div>
                        <div class="col-4">
                            <span class="h3 mb-0 text-info font-weight-bolder">0</span>
                            <span class="d-block text-sm">Laporan</span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <span class="form-control-label">Asal Universitas / Sekolah:</span>
                            <p class="text-sm mb-0"><?php echo e(auth()->user()->asal ?? '-'); ?></p>
                        </div>
                        <div class="col-12 mb-3">
                            <span class="form-control-label">Jurusan:</span>
                            <p class="text-sm mb-0"><?php echo e(auth()->user()->jurusan ?? '-'); ?></p>
                        </div>
                        <div class="col-12">
                            <span class="form-control-label">Jenis Kelamin:</span>
                            <p class="text-sm mb-0"><?php echo e(auth()->user()->jenis_kelamin ?? '-'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-6">
                            <small class="d-block">Awal Magang:</small>
                            <span class="h6 text-info"><?php echo e(auth()->user()->tanggal_awal_magang ? \Carbon\Carbon::parse(auth()->user()->tanggal_awal_magang)->isoFormat('D MMMM Y') : '-'); ?></span>
                        </div>
                        <div class="col-6 text-right">
                            <small class="d-block">Selesai Magang:</small>
                            <span class="h6 text-info"><?php echo e(auth()->user()->tanggal_akhir_magang ? \Carbon\Carbon::parse(auth()->user()->tanggal_akhir_magang)->isoFormat('D MMMM Y') : '-'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-fluid">
                <div class="card-body">
                    <h6 class="mb-4">Kontak & Media Sosial</h6>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fa fa-envelope mr-2"></i>Email</h6></div>
                        <div class="col-auto"><span class="text-sm"><?php echo e(auth()->user()->email); ?></span></div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fa fa-key mr-2"></i>Password</h6></div>
                        <div class="col-auto">
                            <span class="text-sm">••••••••</span>
                            <a data-toggle="modal" data-target="#modal-change-password" href="#"><i class="ml-2 fa fa-edit"></i></a>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fab fa-whatsapp mr-2"></i>WhatsApp</h6></div>
                        <div class="col-auto">
                            <a href="<?php echo e(auth()->user()->no_telp ? 'https://wa.me/' . preg_replace('/^0/', '+62', auth()->user()->no_telp) : '#'); ?>" target="_blank" class="text-sm"><?php echo e(auth()->user()->no_telp ?? '-'); ?></a>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fab fa-instagram mr-2"></i>Instagram</h6></div>
                        <div class="col-auto"><span class="text-sm"><?php echo e(auth()->user()->instagram ?? '-'); ?></span></div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fab fa-linkedin mr-2"></i>LinkedIn</h6></div>
                        <div class="col-auto"><span class="text-sm"><?php echo e(auth()->user()->linkedin ?? '-'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="<?php echo e(route('profil-update-password')); ?>" id="password-form">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mb-0">Edit Password</h6>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">Password Lama</label>
                        <input class="form-control" name="current_password" type="password" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Password Baru</label>
                                <input class="form-control" name="password" type="password" minlength="4" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Konfirmasi</label>
                                <input class="form-control" name="password_confirmation" type="password" minlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-save-password" class="btn btn-success btn-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#password-form').submit(function(e) {
            e.preventDefault();
            let btn = $('#btn-save-password');
            btn.attr('disabled', true).text('Menyimpan...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.redirect) window.location.href = res.redirect;
                    else location.reload();
                },
                error: function(xhr) {
                    alert('Gagal memperbarui password. Periksa kembali password lama Anda.');
                    btn.removeAttr('disabled').text('Simpan');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/profil/index.blade.php ENDPATH**/ ?>
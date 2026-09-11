<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-users fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Data User Magang</h4>
                <small class="text-light">Lihat daftar user magang yang terdaftar</small>
            </div>
        </div>
    </div>

    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-md-6 mb-3 mb-md-0">
            <h5 class="h3 font-weight-400 mb-0 text-white">Selamat pagi, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h5>
            <span class="text-sm text-white opacity-8">Ngerjain tugas apa hari ini kita?</span>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="<?php echo e(route('admin-user-create')); ?>" class="btn btn-success shadow-sm">
                <i class="fas fa-plus mr-2"></i>Tambah User Magang
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalUsers); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalAdmin); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Magang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalMagang); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">User Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($activeUsers); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
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

        <div class="col-xl-12">
            <div class="card card-fluid shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-light">
                        <i class="fas fa-table mr-2"></i>Data User
                    </h6>
                    <span class="badge badge-light"><?php echo e($users->total()); ?> user</span>
                </div>

                <div class="p-3 bg-white border-bottom">
                    <form method="GET" action="<?php echo e(route('admin-user')); ?>" class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-user-tag mr-1"></i>Filter Role:
                            </label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-secondary <?php echo e($currentRole == 'all' ? 'active' : ''); ?>">
                                    <input type="radio" name="role" value="all" <?php echo e($currentRole == 'all' ? 'checked' : ''); ?> onchange="this.form.submit()">
                                    <i class="fas fa-users"></i> Semua
                                </label>
                                <label class="btn btn-sm btn-outline-info <?php echo e($currentRole == 'admin' ? 'active' : ''); ?>">
                                    <input type="radio" name="role" value="admin" <?php echo e($currentRole == 'admin' ? 'checked' : ''); ?> onchange="this.form.submit()"> Admin
                                </label>
                                <label class="btn btn-sm btn-outline-warning <?php echo e($currentRole == 'magang' ? 'active' : ''); ?>">
                                    <input type="radio" name="role" value="magang" <?php echo e($currentRole == 'magang' ? 'checked' : ''); ?> onchange="this.form.submit()"> Magang
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-filter mr-1"></i>Filter Status:
                            </label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-secondary <?php echo e($currentStatus == 'all' ? 'active' : ''); ?>">
                                    <input type="radio" name="status" value="all" <?php echo e($currentStatus == 'all' ? 'checked' : ''); ?> onchange="this.form.submit()"> Semua
                                </label>
                                <label class="btn btn-sm btn-outline-success <?php echo e($currentStatus == 'active' ? 'active' : ''); ?>">
                                    <input type="radio" name="status" value="active" <?php echo e($currentStatus == 'active' ? 'checked' : ''); ?> onchange="this.form.submit()"> Aktif
                                </label>
                                <label class="btn btn-sm btn-outline-danger <?php echo e($currentStatus == 'inactive' ? 'active' : ''); ?>">
                                    <input type="radio" name="status" value="inactive" <?php echo e($currentStatus == 'inactive' ? 'checked' : ''); ?> onchange="this.form.submit()"> Mati
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-layer-group mr-1"></i>Filter Seksi:
                            </label>
                            <select name="seksi" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="all" <?php echo e($currentSeksi == 'all' ? 'selected' : ''); ?>>Semua Seksi</option>
                                <?php $__currentLoopData = $seksiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($seksi->value); ?>" <?php echo e($currentSeksi == $seksi->value ? 'selected' : ''); ?>>
                                        <?php echo e($seksi->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-sort-numeric-down mr-1"></i>Urutan Pendaftaran:
                            </label>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100 font-weight-bold" type="button" data-toggle="dropdown">
                                    <?php if(request('sort') == 'terbaru'): ?>
                                        Pendaftaran: Terbaru (A)
                                    <?php elseif(request('sort') == 'terlama'): ?>
                                        Pendaftaran: Terlama (Z)
                                    <?php else: ?>
                                        Urutkan Pendaftaran
                                    <?php endif; ?>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                    <a class="dropdown-item <?php echo e(request('sort') == 'terbaru' ? 'active' : ''); ?>" 
                                       href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'terbaru'])); ?>">
                                        <i class="fas fa-user-plus mr-2 text-primary"></i> Terbaru Mendaftar (A)
                                    </a>
                                    <a class="dropdown-item <?php echo e(request('sort') == 'terlama' ? 'active' : ''); ?>" 
                                       href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'terlama'])); ?>">
                                        <i class="fas fa-history mr-2 text-warning"></i> Terlama Mendaftar (Z)
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1 mb-3 mb-md-0">
                            <?php if($currentStatus != 'all' || $currentRole != 'all' || $currentSeksi != 'all' || request('sort')): ?>
                            <a href="<?php echo e(route('admin-user')); ?>" class="btn btn-sm btn-secondary w-100" title="Reset Filter">
                                <i class="fas fa-redo"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">seksi</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status</th>
                                <th scope="col">
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['direction' => request('direction') == 'desc' ? 'asc' : 'desc'])); ?>" 
                                       class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                        <span>Nama/JK/Tgl Lahir</span>
                                        <span class="ml-2">
                                            <?php if(request('direction') == 'asc'): ?>
                                                <i class="fas fa-sort-alpha-down text-primary"></i> <small>(A-Z)</small>
                                            <?php else: ?>
                                                <i class="fas fa-sort-alpha-up-alt text-danger"></i> <small>(Z-A)</small>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </th>
                                <th scope="col">Asal/Jurusan</th>
                                <th scope="col">Periode Magang</th>
                                <th scope="col">Alamat/Kontak</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-id="<?php echo e($u->id); ?>" class="<?php echo e(!$u->isActive() ? 'table-secondary' : ''); ?>">
                                <td class="text-center align-middle">
                                    <a href="#" class="avatar-wrapper d-inline-block mb-2 text-decoration-none">
                                        <img alt="Avatar <?php echo e($u->name); ?>"
                                            src="<?php echo e($u->avatar && $u->avatar !== '1' ? asset('storage/images/avatar/'.$u->avatar) : asset('default-avatar.png')); ?>"
                                            class="avatar-img rounded-circle"
                                            style="object-fit:cover;width:48px;height:48px;border:2px solid #5e72e4;">
                                    </a><br>
                                    <?php if($u->seksi): ?>
                                    <span class="badge mb-0 font-weight-bolder badge-<?php echo e($u->seksi->color()); ?>"><?php echo e($u->seksi->code()); ?></span>
                                    <?php else: ?>
                                    <span class="badge mb-0 font-weight-bolder badge-secondary">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <?php if($u->role_id == 1): ?>
                                    <span class="badge badge-info badge-pill px-3 py-2">Admin</span>
                                    <?php else: ?>
                                    <span class="badge badge-warning badge-pill px-3 py-2">Magang</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <span class="badge <?php echo e($u->getStatusBadgeClass()); ?> badge-pill px-3 py-2">
                                        <?php echo e($u->getStatusText()); ?>

                                    </span>
                                    <?php if(!$u->isActive() && $u->tanggal_akhir_magang): ?>
                                    <br><small class="text-muted">Berakhir: <?php echo e(\Carbon\Carbon::parse($u->tanggal_akhir_magang)->isoFormat('D MMM Y')); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 h6 text-sm font-weight-bold"><?php echo e($u->name); ?></p>
                                    <p class="mb-0 text-sm text-muted"><?php echo e($u->jenis_kelamin ?? '-'); ?> / <?php echo e($u->tanggal_lahir ? \Carbon\Carbon::parse($u->tanggal_lahir)->isoFormat('D MMMM Y') : '-'); ?></p>
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 h6 text-sm"><?php echo e($u->asal ?? '-'); ?></p>
                                    <p class="mb-0 text-sm text-muted"><?php echo e($u->jurusan ?? '-'); ?></p>
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 text-sm">
                                        <i class="fas fa-calendar-alt text-success mr-1"></i>
                                        <?php echo e($u->tanggal_awal_magang ? \Carbon\Carbon::parse($u->tanggal_awal_magang)->isoFormat('D MMMM Y') : '-'); ?>

                                    </p>
                                    <p class="mb-0 text-sm">
                                        <i class="fas fa-calendar-times text-danger mr-1"></i>
                                        <?php echo e($u->tanggal_akhir_magang ? \Carbon\Carbon::parse($u->tanggal_akhir_magang)->isoFormat('D MMMM Y') : '-'); ?>

                                    </p>
                                    <?php if($u->isActive() && $u->tanggal_akhir_magang): ?>
                                    <?php
                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($u->tanggal_akhir_magang), false);
                                    ?>
                                    <?php if($daysRemaining <= 7 && $daysRemaining >= 0): ?>
                                        <span class="badge badge-warning badge-sm">
                                            <i class="fas fa-exclamation-triangle mr-1"></i><?php echo e($daysRemaining); ?> hari lagi
                                        </span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <?php if($u->no_telp): ?>
                                    <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $u->no_telp)); ?>" target="_blank" class="d-block mb-1 text-decoration-none text-success">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                    <?php endif; ?>
                                    <span class="d-block text-muted text-xs"><i class="fas fa-envelope"></i> <?php echo e($u->email); ?></span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column">
                                        <a href="<?php echo e(route('rekapabsen.user', $u->id)); ?>" class="btn btn-sm btn-info mb-1" title="Rekap"><i class="fas fa-calendar-check"></i> Rekap</a>
                                        <a href="<?php echo e(route('admin-user-edit', $u->id)); ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="<?php echo e(route('admin-user-destroy', $u->id)); ?>" method="POST" class="delete-form">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger w-100" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    <div>Belum ada data user</div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="p-3 bg-white border-top">
                        <div class="d-flex justify-content-between">
                            <?php echo e($users->withQueryString()->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .table tbody tr { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    .table tbody tr:nth-child(1) { animation-delay: 0.05s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.15s; }
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
    .avatar-wrapper { transition: transform 0.3s ease; display: inline-block; }
    .avatar-wrapper:hover { transform: scale(1.15); }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .table-secondary { background-color: rgba(108, 117, 125, 0.1) !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data user ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/admin/user/index.blade.php ENDPATH**/ ?>
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
            <?php
                $jam = (int) \Carbon\Carbon::now()->format('H');
                $sapaan = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));
            ?>
            <h5 class="h3 font-weight-400 mb-0 text-white"><?php echo e($sapaan); ?>, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h5>
            <span class="text-sm text-white opacity-8">Kelola akun admin &amp; magang di sini.</span>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="<?php echo e(route('admin-user-create')); ?>" class="btn btn-success shadow-sm">
                <i class="fas fa-plus mr-2"></i>Tambah User Magang
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Total User','value' => $totalUsers,'icon' => 'fa-users','color' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total User','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalUsers),'icon' => 'fa-users','color' => 'primary']); ?>
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
        <div class="col-xl-3 col-md-6 mb-3">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Admin','value' => $totalAdmin,'icon' => 'fa-user-shield','color' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Admin','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalAdmin),'icon' => 'fa-user-shield','color' => 'info']); ?>
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
        <div class="col-xl-3 col-md-6 mb-3">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Magang','value' => $totalMagang,'icon' => 'fa-user-graduate','color' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Magang','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalMagang),'icon' => 'fa-user-graduate','color' => 'warning']); ?>
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
        <div class="col-xl-3 col-md-6 mb-3">
            <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'User Aktif','value' => $activeUsers,'icon' => 'fa-user-check','color' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'User Aktif','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeUsers),'icon' => 'fa-user-check','color' => 'success']); ?>
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
                    <form method="GET" action="<?php echo e(route('admin-user')); ?>" class="row align-items-end filter-bar">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-user-tag mr-1"></i>Filter Role:
                            </label>
                            <div class="btn-group btn-group-toggle filter-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-secondary <?php echo e($currentRole == 'all' ? 'active' : ''); ?>">
                                    <input type="radio" name="role" value="all" <?php echo e($currentRole == 'all' ? 'checked' : ''); ?> onchange="this.form.submit()"> Semua
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
                            <div class="btn-group btn-group-toggle filter-toggle d-flex" data-toggle="buttons">
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
                            <select name="seksi" class="form-control form-control-sm js-select" onchange="this.form.submit()">
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

                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-items-center mb-0 table-user">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Foto / Seksi</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status</th>
                                <th scope="col">
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['direction' => request('direction') == 'desc' ? 'asc' : 'desc'])); ?>"
                                       class="th-sort d-inline-flex align-items-center text-decoration-none">
                                        <span>Nama / JK / Lahir</span>
                                        <span class="ml-2 th-sort__badge">
                                            <?php if(request('direction') == 'asc'): ?>
                                                <i class="fas fa-sort-alpha-down text-primary"></i> A-Z
                                            <?php else: ?>
                                                <i class="fas fa-sort-alpha-up-alt text-danger"></i> Z-A
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </th>
                                <th scope="col">Asal / Jurusan</th>
                                <th scope="col">Periode Magang</th>
                                <th scope="col">Kontak</th>
                                <th scope="col" class="text-center">Aksi</th>
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
                                    <div class="action-btns d-flex flex-column">
                                        <a href="<?php echo e(route('rekapabsen.user', $u->id)); ?>" class="btn btn-sm btn-info mb-1" title="Rekap"><i class="fas fa-calendar-check mr-1"></i> Rekap</a>
                                        <a href="<?php echo e(route('admin-user-edit', $u->id)); ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fas fa-edit mr-1"></i> Edit</a>
                                        <form action="<?php echo e(route('admin-user-destroy', $u->id)); ?>" method="POST" class="delete-form mb-0">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger w-100" title="Hapus"><i class="fas fa-trash mr-1"></i> Hapus</button>
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
                </div>

                
                <div class="d-md-none p-3">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="user-card <?php echo e(!$u->isActive() ? 'user-card--off' : ''); ?>">
                            <div class="d-flex align-items-center mb-2">
                                <img alt="Avatar <?php echo e($u->name); ?>"
                                     src="<?php echo e($u->avatar && $u->avatar !== '1' ? asset('storage/images/avatar/'.$u->avatar) : asset('default-avatar.png')); ?>"
                                     class="rounded-circle" style="object-fit:cover;width:44px;height:44px;border:2px solid #5e72e4;">
                                <div class="ml-3 flex-grow-1 min-w-0">
                                    <div class="font-weight-bold text-dark text-truncate"><?php echo e($u->name); ?></div>
                                    <div class="text-xs text-muted"><?php echo e($u->jenis_kelamin ?? '-'); ?> · <?php echo e($u->tanggal_lahir ? \Carbon\Carbon::parse($u->tanggal_lahir)->isoFormat('D MMM Y') : '-'); ?></div>
                                </div>
                                <?php if($u->role_id == 1): ?>
                                    <span class="badge badge-info badge-pill px-3 py-2">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-warning badge-pill px-3 py-2">Magang</span>
                                <?php endif; ?>
                            </div>

                            <div class="user-card__grid">
                                <div><span class="user-card__k">Seksi</span>
                                    <?php if($u->seksi): ?>
                                        <span class="badge badge-<?php echo e($u->seksi->color()); ?>"><?php echo e($u->seksi->code()); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">N/A</span>
                                    <?php endif; ?>
                                </div>
                                <div><span class="user-card__k">Status</span>
                                    <span class="badge <?php echo e($u->getStatusBadgeClass()); ?>"><?php echo e($u->getStatusText()); ?></span>
                                </div>
                                <div><span class="user-card__k">Asal</span><?php echo e($u->asal ?? '-'); ?></div>
                                <div><span class="user-card__k">Jurusan</span><?php echo e($u->jurusan ?? '-'); ?></div>
                                <div><span class="user-card__k">Mulai</span><?php echo e($u->tanggal_awal_magang ? \Carbon\Carbon::parse($u->tanggal_awal_magang)->isoFormat('D MMM Y') : '-'); ?></div>
                                <div><span class="user-card__k">Selesai</span><?php echo e($u->tanggal_akhir_magang ? \Carbon\Carbon::parse($u->tanggal_akhir_magang)->isoFormat('D MMM Y') : '-'); ?></div>
                            </div>

                            <div class="user-card__contact">
                                <?php if($u->no_telp): ?>
                                    <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $u->no_telp)); ?>" target="_blank" class="text-success text-decoration-none mr-3"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                                <?php endif; ?>
                                <span class="text-muted"><i class="fas fa-envelope"></i> <?php echo e($u->email); ?></span>
                            </div>

                            <div class="d-flex" style="gap:.5rem;">
                                <a href="<?php echo e(route('rekapabsen.user', $u->id)); ?>" class="btn btn-sm btn-info flex-fill"><i class="fas fa-calendar-check mr-1"></i>Rekap</a>
                                <a href="<?php echo e(route('admin-user-edit', $u->id)); ?>" class="btn btn-sm btn-warning flex-fill"><i class="fas fa-edit mr-1"></i>Edit</a>
                                <form action="<?php echo e(route('admin-user-destroy', $u->id)); ?>" method="POST" class="delete-form flex-fill mb-0">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger w-100"><i class="fas fa-trash mr-1"></i>Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                            <div>Belum ada data user</div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-3 bg-white border-top">
                    <div class="d-flex justify-content-between">
                        <?php echo e($users->withQueryString()->links('pagination::bootstrap-5')); ?>

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

    /* ---- Tabel user dirapikan ---- */
    .table-user thead th {
        text-transform: uppercase;
        font-size: .72rem;
        letter-spacing: .04em;
        font-weight: 700;
        color: #8898aa;
        background: #f6f7fb;
        border-top: none;
        border-bottom: 1px solid #e9ecef;
        padding: .85rem 1rem;
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-user tbody td {
        padding: .85rem 1rem;
        border-top: 1px solid #f1f3f9;
        vertical-align: middle;
    }
    .table-user tbody tr:hover { background: #f9fafe; }

    /* Tombol sort pada header nama */
    .th-sort { color: #8898aa; }
    .th-sort:hover { color: #5e72e4; }
    .th-sort__badge {
        font-size: .68rem; font-weight: 700;
        background: #eef1ff; color: #5e72e4;
        padding: .1rem .4rem; border-radius: 999px;
    }

    /* Tombol aksi seragam */
    .action-btns .btn { width: 100%; font-weight: 600; }
    .action-btns .btn:hover { transform: translateY(-1px); }

    /* ---- Toolbar filter dirapikan ---- */
    .filter-bar label.small { font-weight: 600; }

    /* Semua kontrol filter setinggi 42px supaya sejajar rapi */
    .filter-bar .filter-toggle .btn,
    .filter-bar .dropdown > .btn,
    .filter-bar .col-md-1 .btn {
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;        /* cegah teks + ikon turun ke baris kedua */
        font-weight: 600;
        border-radius: 10px;
    }
    .filter-bar .select2-container--default .select2-selection--single { height: 42px; }

    /* Segmented control: sudut membulat hanya di ujung grup */
    .filter-toggle .btn { border-radius: 0; }
    .filter-toggle .btn:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    .filter-toggle .btn:last-child  { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

    /* Tombol reset ikut tinggi dan sejajar */
    .filter-bar .col-md-1 .btn { border-radius: 10px; width: 100%; }

    /* ---- Kartu user untuk HP ---- */
    .user-card {
        border: 1px solid #edf0f5; border-radius: 14px;
        padding: .9rem 1rem; margin-bottom: .75rem; background: #fff;
        box-shadow: 0 2px 8px rgba(50,50,93,.04);
    }
    .user-card:last-child { margin-bottom: 0; }
    .user-card--off { background: #f7f8fa; opacity: .85; }
    .user-card__grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .75rem;
        font-size: .85rem; color: #4a5568; margin-bottom: .65rem;
    }
    .user-card__k { display: block; font-size: .68rem; text-transform: uppercase; letter-spacing: .03em; color: #8898aa; font-weight: 700; }
    .user-card__contact { font-size: .8rem; margin-bottom: .75rem; padding-top: .5rem; border-top: 1px solid #f1f3f9; }
    .user-card .btn { font-weight: 600; }
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
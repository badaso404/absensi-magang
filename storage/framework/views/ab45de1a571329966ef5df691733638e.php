<nav class="navbar navbar-main navbar-expand-lg navbar-dark bg-transparent navbar-border" id="navbar-main">
    <div class="container-fluid">

        

        <form id="logout-form" method="post" action="<?php echo e(route('logout')); ?>" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>

        <!-- Mobile -->
        <div class="navbar-user d-lg-none ml-auto">
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon sidenav-toggler" data-action="sidenav-pin" data-target="#sidenav-main"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon" data-action="omnisearch-open" data-target="#omnisearch"><i class="fa fa-search"></i></a>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0">
                        <div class="py-3 px-3">
                            <h5 class="heading h6 mb-0">Notifications</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center" data-toggle="tooltip" data-placement="right" data-title="2 hrs ago">
                                    <div>
                                        <span class="avatar bg-primary text-white rounded-circle">AM</span>
                                    </div>
                                    <div class="flex-fill ml-3">
                                        <div class="h6 text-sm mb-0">Notifikasi <small class="float-right text-muted">2 hrs ago</small></div>
                                        <p class="text-sm lh-140 mb-0">
                                            On development progress.
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="py-3 text-center">
                            <a href="#" class="link link-sm link--style-3">View all notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Avatar" src="<?php echo e(auth()->user()->avatar ? asset('storage/images/avatar/'.auth()->user()->avatar) : asset('assets/img/portrait.png')); ?>">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0">Hai, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h6>
                        <a href="<?php echo e(route('profil')); ?>" class="dropdown-item">
                            <i class="fa fa-user"></i>
                            <span>Profil</span>
                        </a>
                        <a href="<?php echo e(route('profil-edit')); ?>" class="dropdown-item">
                            <i class="fa fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" onclick="document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Full resolution -->
        <div class="collapse navbar-collapse navbar-collapse-fade" id="navbar-main-collapse">
            <ul class="navbar-nav align-items-lg-center">
                <li class="border-top opacity-2 my-2"></li>
                <li class="nav-item ">
                    <a class="nav-link pl-lg-0" href="<?php echo e(route('home')); ?>">
                        Beranda
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow p-lg-0">
                        <div class="p-lg-4">
                            <div class="dropdown dropdown-animate dropdown-submenu" data-toggle="hover">
                                <a href="#" class="dropdown-item dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Portal
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="https://barat.jakarta.go.id/">Website Jakarta Barat</a>
                                    <a class="dropdown-item" href="https://barat.jakarta.go.id/batik">Microsite Batik Jakarta Barat</a>
                                    <a class="dropdown-item" href="https://barat.jakarta.go.id/ppid">Microsite PPID Jakarta Barat</a>
                                    <a class="dropdown-item" href="https://sourcecode.jakarta.go.id/">Source Code Jakarta Barat</a>
                                </div>
                            </div>
                            <div class="dropdown dropdown-animate dropdown-submenu" data-toggle="hover">
                                <a href="#" class="dropdown-item dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sosial Media Jakarta Barat
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="https://www.facebook.com/kotaadmjakartabarat">Facebook</a>
                                    <a class="dropdown-item" href="https://x.com/kotajakbar">Twitter/X</a>
                                    <a class="dropdown-item" href="https://www.youtube.com/channel/UChXtiMFK84Q1od1R_SvEbuQ/">YouTube</a>
                                    <a class="dropdown-item" href="https://www.instagram.com/kotajakartabarat">Instagram</a>
                                    <a class="dropdown-item" href="https://www.tiktok.com/discover/kota-jakarta-barat">TikTok</a>
                                </div>
                            </div>
                            <div class="dropdown dropdown-animate dropdown-submenu" data-toggle="hover">
                                <a href="#" class="dropdown-item dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Fitur
                                </a>
                                <div class="dropdown-menu">
                                    
                                    <?php if(auth()->user()->role_id == App\Enums\Role::Admin->value): ?>
                                        <a class="dropdown-item" href="<?php echo e(route('admin-absensi')); ?>">Absensi Magang</a>
                                    <?php else: ?>
                                        <a class="dropdown-item" href="<?php echo e(route('absensi')); ?>">Absensi</a>
                                    <?php endif; ?>
                                    <a class="dropdown-item" href="<?php echo e(route('profil')); ?>">Profil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="border-top opacity-2 my-2"></li>
            </ul>
            <ul class="navbar-nav ml-lg-auto align-items-center d-none d-lg-flex">
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon sidenav-toggler" data-action="sidenav-pin" data-target="#sidenav-main"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon" data-action="omnisearch-open" data-target="#omnisearch"><i class="fa fa-search"></i></a>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0">
                        <div class="py-3 px-3">
                            <h5 class="heading h6 mb-0">Notifications</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center" data-toggle="tooltip" data-placement="right" data-title="2 hrs ago">
                                    <div>
                                        <span class="avatar bg-primary text-white rounded-circle">AM</span>
                                    </div>
                                    <div class="flex-fill ml-3">
                                        <div class="h6 text-sm mb-0">Notifikasi<small class="float-right text-muted">2 hrs ago</small></div>
                                        <p class="text-sm lh-140 mb-0">
                                            On development progress.
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="py-3 text-center">
                            <a href="#" class="link link-sm link--style-3">View all notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media media-pill align-items-center">
                            <span class="avatar rounded-circle">
                                <img alt="Avatar" src="<?php echo e(auth()->user()->avatar ? asset('storage/images/avatar/'.auth()->user()->avatar) : asset('assets/img/portrait.png')); ?>">
                            </span>
                            <div class="ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold"><?php echo e(auth()->user()->name); ?></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0">Hai, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h6>
                        <a href="<?php echo e(route('profil')); ?>" class="dropdown-item">
                            <i class="fa fa-user"></i>
                            <span>Profil</span>
                        </a>
                        <a href="<?php echo e(route('profil-edit')); ?>" class="dropdown-item">
                            <i class="fa fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item" onclick="document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/layout/header.blade.php ENDPATH**/ ?>
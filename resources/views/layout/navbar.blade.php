<div class="sidenav" id="sidenav-main">
    <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/img/logo.png') }}" class="navbar-brand-img" alt="...">
        </a>
        <div class="ml-auto">
            <div class="sidenav-toggler sidenav-toggler-dark d-md-none" data-action="sidenav-unpin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-user d-flex flex-column align-items-center justify-content-between text-center">
        <div>
            <a href="#" class="avatar rounded-circle avatar-xl">
                <img alt="Avatar" src="{{ auth()->user()->avatar ? asset('storage/images/avatar/'.auth()->user()->avatar) : asset('assets/img/portrait.png') }}" class="">
            </a>
            <div class="mt-4">
                <h5 class="mb-0 text-white">{{ auth()->user()->name }}</h5>
                <span class="d-block text-sm text-white opacity-8 mb-3">{{ auth()->user()->seksi?->text() ?? '-' }}</span>
                <a href="{{ auth()->user()->no_telp ? 'https://wa.me/' . preg_replace('/^0/', '+62', auth()->user()->no_telp) : '#' }}" class="btn btn-sm btn-white btn-icon rounded-pill shadow hover-translate-y-n3">
                    <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
                    <span class="btn-inner--text">{{ auth()->user()->no_telp ?? '-' }}</span>
                </a>
            </div>
        </div>

        <!-- <div class="w-100 mt-4 actions d-flex justify-content-between">
            <a href="#" class="action-item action-item-lg text-white pl-0">
                <i class="fa fa-user"></i>
            </a>
            <a href="#modal-chat" class="action-item action-item-lg text-white" data-toggle="modal">
                <i class="fa fa-comment-alt"></i>
            </a>
            <a href="#" class="action-item action-item-lg text-white pr-0">
                <i class="fa fa-receipt"></i>
            </a>
        </div> -->
    </div>

    <div class="nav-application clearfix">
        <a href="{{ route('home') }}" class="btn btn-square text-sm {{ $mainMenu == 'Home' ? 'active' : ''}}">
            <span class="btn-inner--icon d-block"><i class="fa fa-home fa-2x"></i></span>
            <span class="btn-inner--icon d-block pt-2">Beranda</span>
        </a>
        <a href="{{ route('absensi') }}" class="btn btn-square text-sm {{ $mainMenu == 'Absensi' ? 'active' : ''}}">
            <span class="btn-inner--icon d-block"><i class="fa fa-tasks fa-2x"></i></span>
            <span class="btn-inner--icon d-block pt-2">Absensi</span>
        </a>
        <a href="{{ route('profil') }}" class="btn btn-square text-sm {{ $mainMenu == 'Profil' ? 'active' : ''}}">
            <span class="btn-inner--icon d-block"><i class="fa fa-user-ninja fa-2x"></i></span>
            <span class="btn-inner--icon d-block pt-2">Profil</span>
        </a>

      <a href="{{ route('laporan-kegiatan.index') }}" class="btn btn-square text-sm {{ $mainMenu == 'laporankegiatan' ? 'active' : ''}}">
    <span class="btn-inner--icon d-block"><i class="fa fa-calendar-check fa-2x"></i></span>
    <span class="btn-inner--icon d-block pt-2">Laporan Kegiatan</span>
</a>
        <!-- <a href="#" class="btn btn-square text-sm">
            <span class="btn-inner--icon d-block"><i class="fa fa-project-diagram fa-2x"></i></span>
            <span class="btn-inner--icon d-block pt-2">Kegiatan</span>
        </a> -->
    </div>

    @if(auth()->user()->role_id == App\Enums\Role::Admin->value)
    <hr><p class="text-center">Admin Menu</p>
    <div class="nav-application clearfix">
        <a href="{{ route('admin-user') }}" class="btn btn-square text-sm {{ $mainMenu == 'Admin User' ? 'active' : ''}}">
            <span class="btn-inner--icon d-block"><i class="fa fa-users fa-2x"></i></span>
            <span class="btn-inner--icon d-block pt-2">Users</span>
        </a>
        <a href="{{ route('admin-laporan.index') }}" class="btn btn-square text-sm {{ $mainMenu == 'laporanmagang' ? 'active' : ''}}">
            <span class="btn-inner--icon d-block"><i class="fa fa-book fa-2x"></i></span>
            <span class="btn-inner--icon d-block pt-2">Laporan Magang</span>
        </a>
    </div>
    
    @endif
</div>
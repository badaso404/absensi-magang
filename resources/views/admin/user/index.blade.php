@extends('layout.app')

@section('content')
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
            <h5 class="h3 font-weight-400 mb-0 text-white">Selamat pagi, {{ explode(' ', auth()->user()->name)[0] }}!</h5>
            <span class="text-sm text-white opacity-8">Ngerjain tugas apa hari ini kita?</span>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin-user-create') }}" class="btn btn-success shadow-sm">
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmin }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMagang }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeUsers }}</div>
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
        @if (session('alert'))
        <div class="col-12">
            <x-alert :title="session('alert.title')" :type="session('alert.type')" :messages="[session('alert.message')]" :display="'block'" />
        </div>
        @endif

        <div class="col-xl-12">
            <div class="card card-fluid shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-light">
                        <i class="fas fa-table mr-2"></i>Data User
                    </h6>
                    <span class="badge badge-light">{{ $users->total() }} user</span>
                </div>

                <div class="p-3 bg-white border-bottom">
                    <form method="GET" action="{{ route('admin-user') }}" class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-user-tag mr-1"></i>Filter Role:
                            </label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-secondary {{ $currentRole == 'all' ? 'active' : '' }}">
                                    <input type="radio" name="role" value="all" {{ $currentRole == 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <i class="fas fa-users"></i> Semua
                                </label>
                                <label class="btn btn-sm btn-outline-info {{ $currentRole == 'admin' ? 'active' : '' }}">
                                    <input type="radio" name="role" value="admin" {{ $currentRole == 'admin' ? 'checked' : '' }} onchange="this.form.submit()"> Admin
                                </label>
                                <label class="btn btn-sm btn-outline-warning {{ $currentRole == 'magang' ? 'active' : '' }}">
                                    <input type="radio" name="role" value="magang" {{ $currentRole == 'magang' ? 'checked' : '' }} onchange="this.form.submit()"> Magang
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-filter mr-1"></i>Filter Status:
                            </label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-secondary {{ $currentStatus == 'all' ? 'active' : '' }}">
                                    <input type="radio" name="status" value="all" {{ $currentStatus == 'all' ? 'checked' : '' }} onchange="this.form.submit()"> Semua
                                </label>
                                <label class="btn btn-sm btn-outline-success {{ $currentStatus == 'active' ? 'active' : '' }}">
                                    <input type="radio" name="status" value="active" {{ $currentStatus == 'active' ? 'checked' : '' }} onchange="this.form.submit()"> Aktif
                                </label>
                                <label class="btn btn-sm btn-outline-danger {{ $currentStatus == 'inactive' ? 'active' : '' }}">
                                    <input type="radio" name="status" value="inactive" {{ $currentStatus == 'inactive' ? 'checked' : '' }} onchange="this.form.submit()"> Mati
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-layer-group mr-1"></i>Filter Seksi:
                            </label>
                            <select name="seksi" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="all" {{ $currentSeksi == 'all' ? 'selected' : '' }}>Semua Seksi</option>
                                @foreach($seksiList as $seksi)
                                    <option value="{{ $seksi->value }}" {{ $currentSeksi == $seksi->value ? 'selected' : '' }}>
                                        {{ $seksi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- URUTAN PENDAFTARAN --}}
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="mb-2 text-muted small">
                                <i class="fas fa-sort-numeric-down mr-1"></i>Urutan Pendaftaran:
                            </label>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100 font-weight-bold" type="button" data-toggle="dropdown">
                                    @if(request('sort') == 'terbaru')
                                        Pendaftaran: Terbaru (A)
                                    @elseif(request('sort') == 'terlama')
                                        Pendaftaran: Terlama (Z)
                                    @else
                                        Urutkan Pendaftaran
                                    @endif
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                    <a class="dropdown-item {{ request('sort') == 'terbaru' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}">
                                        <i class="fas fa-user-plus mr-2 text-primary"></i> Terbaru Mendaftar (A)
                                    </a>
                                    <a class="dropdown-item {{ request('sort') == 'terlama' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'terlama']) }}">
                                        <i class="fas fa-history mr-2 text-warning"></i> Terlama Mendaftar (Z)
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1 mb-3 mb-md-0">
                            @if($currentStatus != 'all' || $currentRole != 'all' || $currentSeksi != 'all' || request('sort'))
                            <a href="{{ route('admin-user') }}" class="btn btn-sm btn-secondary w-100" title="Reset Filter">
                                <i class="fas fa-redo"></i>
                            </a>
                            @endif
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
                                    <a href="{{ request()->fullUrlWithQuery(['direction' => request('direction') == 'desc' ? 'asc' : 'desc']) }}" 
                                       class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                        <span>Nama/JK/Tgl Lahir</span>
                                        <span class="ml-2">
                                            @if(request('direction') == 'asc')
                                                <i class="fas fa-sort-alpha-down text-primary"></i> <small>(A-Z)</small>
                                            @else
                                                <i class="fas fa-sort-alpha-up-alt text-danger"></i> <small>(Z-A)</small>
                                            @endif
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
                            @forelse($users as $u)
                            <tr data-id="{{ $u->id }}" class="{{ !$u->isActive() ? 'table-secondary' : '' }}">
                                <td class="text-center align-middle">
                                    <a href="#" class="avatar-wrapper d-inline-block mb-2 text-decoration-none">
                                        <img alt="Avatar {{ $u->name }}"
                                            src="{{ $u->avatar && $u->avatar !== '1' ? asset('storage/images/avatar/'.$u->avatar) : asset('default-avatar.png') }}"
                                            class="avatar-img rounded-circle"
                                            style="object-fit:cover;width:48px;height:48px;border:2px solid #5e72e4;">
                                    </a><br>
                                    @if($u->seksi)
                                    <span class="badge mb-0 font-weight-bolder badge-{{ $u->seksi->color() }}">{{ $u->seksi->code() }}</span>
                                    @else
                                    <span class="badge mb-0 font-weight-bolder badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($u->role_id == 1)
                                    <span class="badge badge-info badge-pill px-3 py-2">Admin</span>
                                    @else
                                    <span class="badge badge-warning badge-pill px-3 py-2">Magang</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge {{ $u->getStatusBadgeClass() }} badge-pill px-3 py-2">
                                        {{ $u->getStatusText() }}
                                    </span>
                                    @if(!$u->isActive() && $u->tanggal_akhir_magang)
                                    <br><small class="text-muted">Berakhir: {{ \Carbon\Carbon::parse($u->tanggal_akhir_magang)->isoFormat('D MMM Y') }}</small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 h6 text-sm font-weight-bold">{{ $u->name }}</p>
                                    <p class="mb-0 text-sm text-muted">{{ $u->jenis_kelamin ?? '-' }} / {{ $u->tanggal_lahir ? \Carbon\Carbon::parse($u->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</p>
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 h6 text-sm">{{ $u->asal ?? '-' }}</p>
                                    <p class="mb-0 text-sm text-muted">{{ $u->jurusan ?? '-' }}</p>
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 text-sm">
                                        <i class="fas fa-calendar-alt text-success mr-1"></i>
                                        {{ $u->tanggal_awal_magang ? \Carbon\Carbon::parse($u->tanggal_awal_magang)->isoFormat('D MMMM Y') : '-' }}
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <i class="fas fa-calendar-times text-danger mr-1"></i>
                                        {{ $u->tanggal_akhir_magang ? \Carbon\Carbon::parse($u->tanggal_akhir_magang)->isoFormat('D MMMM Y') : '-' }}
                                    </p>
                                    @if($u->isActive() && $u->tanggal_akhir_magang)
                                    @php
                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($u->tanggal_akhir_magang), false);
                                    @endphp
                                    @if($daysRemaining <= 7 && $daysRemaining >= 0)
                                        <span class="badge badge-warning badge-sm">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $daysRemaining }} hari lagi
                                        </span>
                                    @endif
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($u->no_telp)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $u->no_telp) }}" target="_blank" class="d-block mb-1 text-decoration-none text-success">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                    @endif
                                    <span class="d-block text-muted text-xs"><i class="fas fa-envelope"></i> {{ $u->email }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('rekapabsen.user', $u->id) }}" class="btn btn-sm btn-info mb-1" title="Rekap"><i class="fas fa-calendar-check"></i> Rekap</a>
                                        <a href="{{ route('admin-user-edit', $u->id) }}" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('admin-user-destroy', $u->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    <div>Belum ada data user</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-3 bg-white border-top">
                        <div class="d-flex justify-content-between">
                            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
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
@endpush
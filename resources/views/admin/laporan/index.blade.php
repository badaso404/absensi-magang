@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-file-alt fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Laporan Kegiatan - Magang</h4>
                <small class="text-light">Daftar laporan kegiatan yang dibuat oleh user magang</small>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Magang</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $totalMagang }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Laporan</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $totalLaporan }}</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Table daftar magang -->
    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between">
            <h6 class="mb-0 font-weight-bold">Daftar Magang</h6>
            <span class="badge badge-light">{{ $users->count() }} user</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Asal / Jurusan</th>
                        <th>Total Laporan</th>
                       
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                <img src="{{ $u->avatar && $u->avatar !== '1' ? asset('storage/images/avatar/'.$u->avatar) : asset('default-avatar.png') }}"
                                     alt="{{ $u->name }}" class="rounded-circle" style="width:42px;height:42px;object-fit:cover;">
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->jenis_kelamin ?? '-' }} / {{ $u->tanggal_lahir ? \Carbon\Carbon::parse($u->tanggal_lahir)->isoFormat('D MMM Y') : '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $u->asal ?? '-' }}</div>
                                <small class="text-muted">{{ $u->jurusan ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-info">{{ $u->laporan_count ?? 0 }}</span>
                            </td>
                      
                            <td>
                                <a href="{{ route('admin-laporan.user', $u->id) }}" class="btn btn-sm btn-info">Lihat Laporan</a>
                                <a href="{{ route('admin-laporan.export', $u->id) }}" class="btn btn-sm btn-success">Export</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data magang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
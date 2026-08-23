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
            <h6 class="mb-0 font-weight-bold">
                Daftar Magang
                @if($selectedSeksi !== 'all')
                    &mdash; {{ $selectedSeksi->code() }}
                @endif
            </h6>
            <span class="badge badge-light">{{ $users->count() }} user</span>
        </div>

        {{-- Filter unit + periode. Controller sudah lama menyiapkan $years dan
             $months, tapi halaman ini belum pernah punya form untuk memakainya. --}}
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="{{ route('admin-laporan.index') }}" class="row align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small">
                        <i class="fas fa-sitemap mr-1"></i>Unit / Seksi:
                    </label>
                    <select name="seksi" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="all">Semua Unit</option>
                        @foreach($seksiList as $s)
                            <option value="{{ $s->value }}"
                                {{ $selectedSeksi !== 'all' && $selectedSeksi->value === $s->value ? 'selected' : '' }}>
                                {{ $s->code() }} &mdash; {{ $s->text() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small">
                        <i class="far fa-calendar mr-1"></i>Bulan:
                    </label>
                    <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach($months as $nomor => $nama)
                            <option value="{{ $nomor }}" {{ (string) $selectedMonth === (string) $nomor ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small">
                        <i class="far fa-calendar-alt mr-1"></i>Tahun:
                    </label>
                    <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $tahun)
                            <option value="{{ $tahun }}" {{ (string) $selectedYear === (string) $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin-laporan.index') }}" class="btn btn-sm btn-outline-secondary btn-block">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Unit</th>
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
                                @if($u->seksi)
                                    <span class="badge badge-{{ $u->seksi->color() }}"
                                          title="{{ $u->seksi->text() }}">{{ $u->seksi->code() }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $u->asal ?? '-' }}</div>
                                <small class="text-muted">{{ $u->jurusan ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-info">{{ $u->laporan_count ?? 0 }}</span>
                            </td>
                            <td>
                                {{-- Export dipindah ke halaman detail: dari sini admin
                                     belum memilih periode, jadi tombolnya selalu
                                     mengunduh rentang yang belum tentu dia maksud. --}}
                                {{-- Periode yang sedang difilter ikut dibawa, kalau tidak
                                     memfilter daftar ke Juni lalu mengklik seseorang akan
                                     membuka bulan ini yang isinya kosong. --}}
                                <a href="{{ route('admin-laporan.user', array_filter([
                                        'user'  => $u->id,
                                        'month' => $selectedMonth,
                                        'year'  => $selectedYear,
                                   ])) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye mr-1"></i>Lihat Laporan
                                </a>
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
@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-file-alt fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Laporan Kegiatan Bulanan</h4>
                <small class="text-light">Kelola dan export laporan kegiatan</small>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card only-gradient-border shadow-sm" style="border-radius: 10px;">
                <div class="card-body p-3">
                    <h6 class="mb-3 font-weight-bold text-primary" style="font-size: 1rem;">Filter Bulan & Tahun</h6>

                    <div class="dropdown w-100">
                        <button class="btn btn-outline-primary btn-sm w-100 d-flex justify-content-between align-items-center"
                            type="button" id="filterDropdown"
                            data-toggle="dropdown" data-display="static" aria-expanded="false"
                            style="border-radius: 8px; border-radius: 10px;">
                            <span>📅 Pilih Filter</span>
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>

                        <div class="dropdown-menu shadow p-3 w-100" aria-labelledby="filterDropdown"
                            style="min-width: 280px; border-radius: 10px;">

                            {{-- Tahun --}}
                            <div class="mb-3">
                                <div class="text-muted font-weight-bold mb-2" style="font-size:.9rem;">📅 Tahun</div>
                                <div class="d-flex flex-row flex-nowrap overflow-auto tahun-scroll" style="gap:6px; padding-bottom:4px;">
                                    @foreach($years as $year)
                                    <a href="?year={{ $year }}"
                                        class="badge {{ $selectedYear == $year ? 'badge-primary' : 'badge-light' }} p-2"
                                        style="cursor:pointer; font-size:.85rem; white-space: nowrap;">
                                        {{ $year }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            {{-- Bulan --}}
                            @if($selectedYear)
                            <div class="mb-3">
                                <div class="text-muted font-weight-bold mb-2" style="font-size:.9rem;">🗓️ Bulan</div>
                                <div class="d-flex flex-wrap" style="gap:6px;">
                                    @foreach($months as $num => $name)
                                    <a href="?year={{ $selectedYear }}&month={{ $num }}"
                                        class="badge {{ $selectedMonth == $num ? 'badge-success' : 'badge-light' }} p-2"
                                        style="cursor:pointer; font-size:.8rem;">
                                        {{ mb_substr($name,0,3) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="dropdown-divider"></div>

                            {{-- Reset --}}
                            @if($selectedYear)
                            <div class="text-right">
                                <a href="{{ route('laporan-kegiatan.index') }}"
                                    class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">
                                    🔄 Reset
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-fluid shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-light">
                        <i class="fas fa-table mr-2"></i>Data Laporan Kegiatan
                    </h6>
                    <div>
                        <a href="{{ route('laporan-kegiatan.setting') }}" class="btn btn-info btn-sm text-white">
                            <i class="fas fa-cog mr-1"></i> Pengaturan
                        </a>
                        <a href="{{ route('laporan-kegiatan.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Laporan
                        </a>
                        <a href="{{ route('laporan-kegiatan.export', ['year' => request('year'), 'month' => request('month')]) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                        </a>
                        <span class="badge badge-light">{{ $laporan->count() }} data</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Hari/Tanggal</th>
                                <th>Detail Kegiatan</th>
                                <th>Lokasi</th>
                                <th>Dokumentasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-primary">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd') }}
                                        </span>
                                        <span>
                                            {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 400px;">
                                        {{ $item->detail_kegiatan }}
                                    </div>
                                </td>
                                <td>{{ $item->lokasi }}</td>
                                <td>
                                    @if($item->dokumentasi)
                                    <a href="{{ asset('storage/' . $item->dokumentasi) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-image"></i> Lihat
                                    </a>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('laporan-kegiatan.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('laporan-kegiatan.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <div>Belum ada data laporan kegiatan</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
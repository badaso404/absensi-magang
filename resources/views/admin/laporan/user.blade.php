@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Laporan: {{ $user->name }}</h4>
                <small class="text-light">Daftar laporan kegiatan pengguna magang</small>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <a href="{{ route('admin-laporan.index') }}" class="btn btn-sm btn-outline-secondary">← Kembali</a>
        <a href="{{ route('admin-laporan.export', $user->id) }}?month={{ $selectedMonth }}&year={{ $selectedYear }}" class="btn btn-sm btn-success">Export XLSX</a>
    </div>

    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between">
            <h6 class="mb-0 font-weight-bold">Data Laporan</h6>
            <span class="badge badge-light">{{ $laporan->count() }} data</span>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold text-primary">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd') }}</span>
                                    <span>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY') }}</span>
                                </div>
                            </td>
                            <td style="max-width:400px;">{{ $item->detail_kegiatan }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                @if($item->dokumentasi)
                                    <a href="{{ asset('storage/' . $item->dokumentasi) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada laporan kegiatan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
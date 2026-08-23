@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ $user->avatar ? asset('storage/images/avatar/'.$user->avatar) : asset('assets/img/portrait.png') }}"
                 alt="{{ $user->name }}" class="rounded-circle mr-3"
                 style="width:48px;height:48px;object-fit:cover;">
            <div>
                <h4 class="mb-0 font-weight-bold text-white">
                    {{ $user->name }}
                    @if($user->seksi)
                        <span class="badge badge-{{ $user->seksi->color() }} ml-1">{{ $user->seksi->code() }}</span>
                    @endif
                </h4>
                <small class="text-light">
                    Laporan kegiatan
                    @if($selectedMonth === 'all')
                        &mdash; seluruh periode
                    @else
                        &mdash; {{ $months[$selectedMonth] }} {{ $selectedYear }}
                    @endif
                </small>
            </div>
        </div>
    </div>

    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Data Laporan</h6>
            <span class="badge badge-light">{{ $laporan->count() }} kegiatan</span>
        </div>

        {{-- Filter periode + export. Export ada di sini supaya berkas yang
             diunduh persis sama dengan periode yang sedang dilihat admin. --}}
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="{{ route('admin-laporan.user', $user->id) }}" class="row align-items-end">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="far fa-calendar mr-1"></i>Bulan:</label>
                    <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="all" {{ $selectedMonth === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        @foreach($months as $nomor => $nama)
                            <option value="{{ $nomor }}" {{ (string) $selectedMonth === (string) $nomor ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="far fa-calendar-alt mr-1"></i>Tahun:</label>
                    <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                        @foreach($years as $tahun)
                            <option value="{{ $tahun }}" {{ (string) $selectedYear === (string) $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <a href="{{ route('admin-laporan.export', $user->id) }}?month={{ $selectedMonth }}&year={{ $selectedYear }}"
                       class="btn btn-sm btn-success btn-block">
                        <i class="fas fa-file-excel mr-1"></i>Export XLSX
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('admin-laporan.index') }}" class="btn btn-sm btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </form>
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
                                {{-- Gambar tampil langsung; admin tidak perlu membuka
                                     tab baru satu per satu untuk memeriksa bukti. --}}
                                @if($item->dokumentasi)
                                    <a href="{{ asset('storage/' . $item->dokumentasi) }}" target="_blank" rel="noopener"
                                       title="Buka ukuran penuh">
                                        <img src="{{ asset('storage/' . $item->dokumentasi) }}"
                                             alt="Dokumentasi {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D/M/YYYY') }}"
                                             class="rounded shadow-sm"
                                             style="width:120px;height:80px;object-fit:cover;"
                                             loading="lazy">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada laporan kegiatan
                                @if($selectedMonth !== 'all')
                                    pada {{ $months[$selectedMonth] }} {{ $selectedYear }}.
                                @else
                                    .
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

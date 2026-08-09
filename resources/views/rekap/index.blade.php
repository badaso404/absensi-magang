@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-calendar-check fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Rekap Absensi</h4>
                <small class="text-light">Lihat dan filter data absensi karyawan</small>
            </div>
        </div>
    </div>

    <div class="card only-gradient-border shadow-sm" style="max-width: 310px; border-radius: 10px;">
        <div class="card-body p-3"> 
            <h6 class="mb-3 font-weight-bold text-primary" style="font-size: 1rem;">Filter Bulan & Tahun</h6>

            <div class="dropdown w-100">
                <button class="btn btn-outline-primary btn-sm w-100 d-flex justify-content-between align-items-center" 
                    type="button" id="filterDropdown" 
                    data-toggle="dropdown" data-display="static" aria-expanded="false"
                    style="border-radius: 8px; max-width: 340px; border-radius: 10px;">
                    <span>📅 Pilih Filter</span>
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>

                <div class="dropdown-menu shadow p-3 w-100" aria-labelledby="filterDropdown" 
                    style="min-width: 280px; border-radius: 10px; top:100% !important; bottom:auto !important;">

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
                        <a href="{{ $rekapUser ? route('rekapabsen.user', $rekapUser) : route('rekapabsen') }}"
                            class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">
                            🔄 Reset
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-fluid shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-light">
                        <i class="fas fa-table mr-2"></i>Data Rekap Absensi
                    </h6>
                    <div>
                        <a href="{{ $rekapUser
                                ? route('rekapabsen.user-export', ['user' => $rekapUser, 'year' => request('year'), 'month' => request('month')])
                                : route('rekapabsen.export', ['year' => request('year'), 'month' => request('month')]) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                        </a>
                        <span class="badge badge-light">{{ $rekap->count() }} data</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Mode Kerja</th>
                                <th>Lokasi Absen</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekap as $r)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-alt text-primary mr-2"></i>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bold text-primary">
                                                {{ \Carbon\Carbon::parse($r->created_at)->isoFormat('dddd') }}
                                            </span>
                                            <span>
                                                {{ \Carbon\Carbon::parse($r->created_at)->isoFormat('D MMMM Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-user-circle text-secondary mr-2"></i>
                                        {{ $r->user->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <p class="mb-0 font-weight-bolder text-{{ optional($r->checked_in_status)->color() }}">
                                        {{ optional($r->checked_in_status)->text() ?? '-' }}
                                    </p>
                                    <p class="mb-0">
                                        {{ $r->checked_in_at ? \Carbon\Carbon::parse($r->checked_in_at)->isoFormat('HH:mm:ss') : '-' }}
                                    </p>
                                </td>

                                <td>
                                    <p class="mb-0 font-weight-bolder text-{{ $r->checked_out_status->color() }}">
                                        {{ $r->checked_out_status->text() }}
                                    </p>
                                    <p class="mb-0">
                                        {{ $r->checked_out_at ? \Carbon\Carbon::parse($r->checked_out_at)->isoFormat('HH:mm:ss') : '-' }}
                                    </p>
                                </td>

                                <td>
                                    <span class="badge badge-pill badge-{{ optional($r->status)->color() }} px-3 py-2 shadow-sm">
                                        {{ optional($r->status)->text() ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    @if($r->wfhwfo)
                                        <span class="badge badge-{{ $r->wfhwfo == 'WFH' ? 'success' : 'primary' }} px-3 py-2">
                                            <i class="fas fa-{{ $r->wfhwfo == 'WFH' ? 'home' : 'building' }} mr-1"></i>
                                            {{ $r->wfhwfo }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td style="max-width: 250px;">
                                    @if($r->lokasi_user || ($r->latitude && $r->longitude))
                                        <div class="d-flex flex-column">
                                            <div class="mb-2">
                                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                <small class="text-muted">
                                                    {{ $r->lokasi_user ?? 'Lokasi tidak tersedia' }}
                                                </small>
                                            </div>
                                            @if($r->latitude && $r->longitude)
                                                <div class="d-flex align-items-center">
                                                    <button 
                                                        class="btn btn-sm btn-outline-primary btn-map" 
                                                        data-lat="{{ $r->latitude }}" 
                                                        data-lon="{{ $r->longitude }}"
                                                        data-lokasi="{{ $r->lokasi_user ?? 'Lokasi Absen' }}"
                                                        data-toggle="tooltip"
                                                        title="Lihat di Google Maps">
                                                        <i class="fas fa-map-marked-alt mr-1"></i>
                                                        Lihat Peta
                                                    </button>
                                                    <small class="text-muted ml-2">
                                                        <i class="fas fa-globe"></i> 
                                                        {{ number_format($r->latitude, 6) }},<br> {{ number_format($r->longitude, 6) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-map-marker-alt-slash"></i> 
                                            Tidak ada data lokasi
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @php
                                        $dayName = \Carbon\Carbon::parse($r->created_at)->isoFormat('dddd');
                                    @endphp

                                    @if(in_array($dayName, ['Sabtu', 'Minggu']))
                                        <span class="badge badge-light border px-3 py-2">
                                            <i class="fas fa-calendar-times mr-1"></i>Libur
                                        </span>
                                    @else
                                        <span class="badge badge-info px-3 py-2">
                                            <i class="fas fa-briefcase mr-1"></i>Hari Kerja
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <div>Belum ada data rekap absensi</div>
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

<!-- Modal untuk Google Maps -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-light" id="mapModalLabel">
                    <i class="fas fa-map-marker-alt mr-2 text-light"></i>Lokasi Absen
                </h5>
              <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>

            </div>
            <div class="modal-body p-0">
                <div id="map-container" style="width: 100%; height: 400px;">
                    <iframe 
                        id="google-map-iframe" 
                        width="100%" 
                        height="400" 
                        frameborder="0" 
                        style="border:0" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-3 bg-light">
                    <p class="mb-1"><strong>Alamat:</strong></p>
                    <p class="mb-0 text-muted" id="map-location-text"></p>
                    <p class="mb-0 mt-2">
                        <strong>Koordinat:</strong> 
                        <span class="text-muted" id="map-coordinates"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="open-google-maps" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt mr-1"></i>Buka di Google Maps
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
.modal-header .close {
    color: #fff !important;
    opacity: 1 !important;
}

.modal-header .close span {
    color: #fff !important;
}
</style>
@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Handle klik tombol lihat peta
    $('.btn-map').on('click', function() {
        const lat = $(this).data('lat');
        const lon = $(this).data('lon');
        const lokasi = $(this).data('lokasi');

        // Set iframe Google Maps
        const mapUrl = `https://www.google.com/maps?q=${lat},${lon}&hl=id&z=16&output=embed`;
        $('#google-map-iframe').attr('src', mapUrl);

        // Set text informasi
        $('#map-location-text').text(lokasi);
        $('#map-coordinates').text(`${lat}, ${lon}`);

        // Set link untuk buka di Google Maps
        const externalMapUrl = `https://www.google.com/maps?q=${lat},${lon}&hl=id`;
        $('#open-google-maps').attr('href', externalMapUrl);

        // Tampilkan modal
        $('#mapModal').modal('show');
    });

    // Clear iframe ketika modal ditutup untuk performa
    $('#mapModal').on('hidden.bs.modal', function() {
        $('#google-map-iframe').attr('src', '');
    });
});
</script>
@endpush
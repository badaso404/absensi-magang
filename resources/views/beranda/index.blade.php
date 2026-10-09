@extends('layout.app')

@php
    // Sapaan ikut waktu server, bukan "Selamat pagi" terus-terusan.
    $jam = (int) \Carbon\Carbon::now()->format('H');
    $sapaan = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));
    $namaDepan = explode(' ', auth()->user()->name)[0];
@endphp

@section('content')
<div class="page-content">
    {{-- Header Section --}}
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-hand-sparkles fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">{{ $sapaan }}, {{ $namaDepan }}! 👋</h5>
                <span class="text-sm text-white opacity-8">Jangan lupa absen ya, biar kehadiranmu tercatat.</span>
            </div>
        </div>
    </div>

    {{-- Notifikasi Alert --}}
    @if (session('alert'))
    <div class="row">
        <div class="col-12">
            <x-alert :title="session('alert.title')" :type="session('alert.type')" :messages="[session('alert.message')]" :display="'block'" />
        </div>
    </div>
    @endif

    <div class="row">
        {{-- =========================================================
             KARTU ABSEN (HERO) — aksi utama, selalu paling atas di HP
             ========================================================= --}}
        <div class="col-12 col-lg-5 mb-4">
            <div class="card card-fluid absen-hero h-100 mb-0">
                <div class="absen-hero__bg"></div>
                <div class="card-body d-flex flex-column text-center position-relative">

                    {{-- Jam real-time --}}
                    <div class="absen-clock">
                        <span id="absen-jam">--:--:--</span>
                    </div>
                    <div class="absen-tanggal mb-3">
                        <i class="far fa-calendar mr-1"></i>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </div>

                    {{-- Jadwal hari ini --}}
                    <div class="d-flex justify-content-center flex-wrap mb-4" style="gap:.5rem;">
                        <span class="jadwal-chip"><i class="fas fa-door-open mr-1"></i>Masuk {{ $schedule_in }}</span>
                        <span class="jadwal-chip"><i class="fas fa-door-closed mr-1"></i>Pulang {{ $schedule_out }}</span>
                    </div>

                    {{-- Status hari ini: masuk & pulang --}}
                    <div class="row no-gutters mb-4" style="gap:0;">
                        <div class="col-6 pr-1">
                            <div class="status-step {{ $absensiHariIni?->checked_in_at ? 'status-step--done' : '' }}">
                                <div class="status-step__icon"><i class="fas fa-sign-in-alt"></i></div>
                                <div class="status-step__label">Absen Pagi</div>
                                <div class="status-step__value">
                                    {{ $absensiHariIni?->checked_in_at ? \Carbon\Carbon::parse($absensiHariIni->checked_in_at)->format('H:i') : 'Belum' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6 pl-1">
                            <div class="status-step {{ $absensiHariIni?->checked_out_at ? 'status-step--done' : '' }}">
                                <div class="status-step__icon"><i class="fas fa-sign-out-alt"></i></div>
                                <div class="status-step__label">Absen Sore</div>
                                <div class="status-step__value">
                                    {{ $absensiHariIni?->checked_out_at ? \Carbon\Carbon::parse($absensiHariIni->checked_out_at)->format('H:i') : 'Belum' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Maskot: mengisi ruang kosong & berubah sesuai status.
                         Belum absen, sedang bertugas, dan sudah selesai tampil
                         beda supaya kartunya terasa hidup. --}}
                    <div class="absen-mascot flex-grow-1 d-flex flex-column align-items-center justify-content-center">
                        @if($toggleAbsenPagi == 'pagi')
                            <div class="absen-mascot__emoji mascot-wave">🌅</div>
                            <div class="absen-mascot__text">Hari baru, semangat baru!<br>Mulai dengan absen pagi dulu yuk.</div>
                        @elseif($toggleAbsenPagi == 'sore')
                            <div class="absen-mascot__emoji mascot-work">💻</div>
                            <div class="absen-mascot__text">Kamu sedang bertugas.<br>Tetap semangat, jangan lupa absen pulang nanti!</div>
                        @else
                            <div class="absen-mascot__emoji mascot-celebrate">🎉</div>
                            <div class="absen-mascot__text">Keren! Absensi hari ini sudah lengkap.<br>Sampai jumpa besok 👋</div>
                        @endif
                    </div>

                    {{-- Tombol absen dinamis --}}
                    @if($toggleAbsenPagi == 'pagi')
                        <button type="button" id="btn-absen-pemicu" class="btn btn-block btn-absen btn-absen--pagi mt-auto">
                            <i class="fas fa-fingerprint mr-2"></i> Absen Pagi Sekarang
                        </button>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-map-marker-alt mr-1"></i>Pastikan GPS aktif saat absen</small>
                    @elseif($toggleAbsenPagi == 'sore')
                        <button type="button" onclick="ambilLokasiDanKirim(null)" class="btn btn-block btn-absen btn-absen--sore mt-auto">
                            <i class="fas fa-sign-out-alt mr-2"></i> Absen Pulang Sekarang
                        </button>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle mr-1"></i>Absen sore dibuka setelah pukul 12:00</small>
                    @else
                        <div class="absen-selesai mt-auto">
                            <i class="fas fa-check-circle mr-2"></i>Absensi hari ini selesai
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- =========================================================
             RINGKASAN + GRAFIK
             ========================================================= --}}
        <div class="col-12 col-lg-7 mb-4">
            {{-- Mini stat tiles --}}
            <div class="row">
                <div class="col-6 mb-4">
                    <x-stat-card label="Kali Telat" :value="$kalkulasiKeterlambatan['total_telat'] ?? 0" icon="fa-hourglass-half" color="danger" />
                </div>
                <div class="col-6 mb-4">
                    <x-stat-card label="Pulang Cepat" :value="$kalkulasiKeterlambatan['total_pulang_cepat'] ?? 0" icon="fa-running" color="warning" />
                </div>
                <div class="col-12 mb-4">
                    <x-stat-card
                        label="Total keterlambatan bulan ini"
                        :value="$kalkulasiKeterlambatan['total_keterlambatan'] ?? '00:00'"
                        icon="fa-stopwatch"
                        color="info" />
                </div>
            </div>

            {{-- Grafik Absensi --}}
            <div class="card mb-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 font-weight-bolder"><i class="fas fa-chart-area text-primary mr-2"></i>Grafik Absensi</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('MMMM') }}</small>
                </div>
                <div class="card-body">
                    @if(!empty($absensiChart['x']))
                        <div id="absensi-chart" data-height="280"></div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted opacity-3"></i>
                            <p class="mt-3 text-muted mb-0">Belum ada data grafik bulan ini</p>
                            <small class="text-muted">Grafik muncul setelah kamu mulai absen 🙂</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- =========================================================
             RIWAYAT 7 HARI — tabel di desktop, kartu di HP
             ========================================================= --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white"><i class="fas fa-history mr-2"></i>Data Absensi <small class="opacity-7">(7 hari terakhir)</small></h6>
                    <a href="{{ route('absensi') }}" class="btn btn-sm btn-white">Lihat semua</a>
                </div>

                {{-- Tabel: hanya tampil di layar >= md --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Mode</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $a)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($a->created_at)->isoFormat('dddd') }}</div>
                                        <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($a->created_at)->isoFormat('D MMM Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bolder text-{{ $a->checked_in_status?->color() ?? 'secondary' }}">
                                            {{ $a->checked_in_status?->text() ?? '-' }}
                                        </div>
                                        <div class="text-xs text-muted">
                                            {{ $a->checked_in_at ? \Carbon\Carbon::parse($a->checked_in_at)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bolder text-{{ $a->checked_out_status?->color() ?? 'secondary' }}">
                                            {{ $a->checked_out_status?->text() ?? '-' }}
                                        </div>
                                        <div class="text-xs text-muted">
                                            {{ $a->checked_out_at ? \Carbon\Carbon::parse($a->checked_out_at)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-{{ $a->status?->color() ?? 'secondary' }}">
                                            {{ $a->status?->text() ?? 'Proses' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($a->wfhwfo)
                                            <span class="badge badge-soft-{{ $a->wfhwfo == 'WFH' ? 'success' : 'primary' }}">
                                                {{ $a->wfhwfo }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($a->latitude) && !empty($a->longitude))
                                            <button class="btn btn-xs btn-outline-primary btn-map"
                                                    data-lat="{{ $a->latitude }}"
                                                    data-lon="{{ $a->longitude }}"
                                                    data-lokasi="{{ $a->lokasi_user ?? 'Lokasi Tersimpan' }}">
                                                <i class="fas fa-map-marker-alt"></i> Peta
                                            </button>
                                        @else
                                            <span class="text-xs text-muted italic">No GPS</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $dayName = \Carbon\Carbon::parse($a->created_at)->isoFormat('dddd'); @endphp
                                        <span class="badge badge-{{ in_array($dayName, ['Sabtu', 'Minggu']) ? 'secondary' : 'info' }}">
                                            {{ in_array($dayName, ['Sabtu', 'Minggu']) ? 'Libur' : 'Kerja' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-5">Belum ada data absensi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Kartu: hanya tampil di HP (< md) --}}
                <div class="d-md-none p-3">
                    @forelse($absensi as $a)
                        <div class="riwayat-card">
                            <div class="riwayat-card__head">
                                <div>
                                    <div class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($a->created_at)->isoFormat('dddd') }}</div>
                                    <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($a->created_at)->isoFormat('D MMM Y') }}</div>
                                </div>
                                <span class="badge badge-pill badge-{{ $a->status?->color() ?? 'secondary' }}">
                                    {{ $a->status?->text() ?? 'Proses' }}
                                </span>
                            </div>
                            <div class="riwayat-card__body">
                                <div class="riwayat-card__cell">
                                    <span class="riwayat-card__k"><i class="fas fa-sign-in-alt text-success mr-1"></i>Masuk</span>
                                    <span class="riwayat-card__v">{{ $a->checked_in_at ? \Carbon\Carbon::parse($a->checked_in_at)->format('H:i') : '-' }}</span>
                                </div>
                                <div class="riwayat-card__cell">
                                    <span class="riwayat-card__k"><i class="fas fa-sign-out-alt text-warning mr-1"></i>Pulang</span>
                                    <span class="riwayat-card__v">{{ $a->checked_out_at ? \Carbon\Carbon::parse($a->checked_out_at)->format('H:i') : '-' }}</span>
                                </div>
                            </div>
                            <div class="riwayat-card__foot">
                                @if($a->wfhwfo)
                                    <span class="badge badge-soft-{{ $a->wfhwfo == 'WFH' ? 'success' : 'primary' }}">{{ $a->wfhwfo }}</span>
                                @endif
                                @if(!empty($a->latitude) && !empty($a->longitude))
                                    <button class="btn btn-xs btn-outline-primary btn-map"
                                            data-lat="{{ $a->latitude }}"
                                            data-lon="{{ $a->longitude }}"
                                            data-lokasi="{{ $a->lokasi_user ?? 'Lokasi Tersimpan' }}">
                                        <i class="fas fa-map-marker-alt"></i> Lihat Peta
                                    </button>
                                @else
                                    <span class="text-xs text-muted italic">Tanpa GPS</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 opacity-3"></i>
                            <div>Belum ada data absensi</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pilih Mode Kerja --}}
<div class="modal fade" id="wfhwfoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <i class="fas fa-street-view fa-3x text-primary"></i>
                </div>
                <h5 class="font-weight-bold">Mode Kerja Hari Ini</h5>
                <p class="text-sm text-muted">Pilih status kehadiranmu:</p>
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-block btn-success btn-wfhwfo mb-2" data-value="WFH"><i class="fas fa-home mr-2"></i>Work From Home</button>
                    <button type="button" class="btn btn-block btn-primary btn-wfhwfo mb-2" data-value="WFO"><i class="fas fa-building mr-2"></i>Work From Office</button>
                    {{-- Nilai harus persis "Dinas Luar": controller memvalidasi
                         in:WFH,WFO,Dinas Luar. Versi lama mengirim "DINAS LUAR"
                         sehingga selalu gagal validasi dan tidak bisa absen. --}}
                    <button type="button" class="btn btn-block btn-warning btn-wfhwfo" data-value="Dinas Luar"><i class="fas fa-car mr-2"></i>Dinas Luar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Peta --}}
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="map-location-text">Lokasi Absen</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="google-map-iframe" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <div class="modal-footer justify-content-between">
                <span class="text-muted text-sm" id="map-coordinates"></span>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --absen-grad: linear-gradient(135deg, #7c3aed 0%, #5824e2 55%, #3a1898 100%);
    }

    /* ---- Kartu absen (hero) ---- */
    .absen-hero { overflow: hidden; border: none; }
    .absen-hero__bg {
        position: absolute; top: 0; left: 0; right: 0; height: 100%;
        background: var(--absen-grad);
    }
    .absen-hero__bg::after {
        content: ""; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 15% 10%, rgba(255,255,255,.18) 0, transparent 38%),
            radial-gradient(circle at 90% 90%, rgba(255,255,255,.12) 0, transparent 40%);
    }
    .absen-hero .card-body { z-index: 1; color: #fff; padding: 1.75rem 1.5rem; }
    .absen-hero .text-muted { color: rgba(255,255,255,.75) !important; }

    .absen-clock {
        font-size: 2.6rem; font-weight: 800; line-height: 1.05;
        letter-spacing: .02em; font-variant-numeric: tabular-nums;
        text-shadow: 0 2px 12px rgba(0,0,0,.18);
    }
    .absen-tanggal { font-size: .85rem; color: rgba(255,255,255,.85); }

    .jadwal-chip {
        background: rgba(255,255,255,.16); color: #fff;
        padding: .3rem .75rem; border-radius: 999px;
        font-size: .78rem; font-weight: 600;
        backdrop-filter: blur(2px);
    }

    .status-step {
        background: rgba(255,255,255,.12); border-radius: 14px;
        padding: .75rem .5rem; text-align: center;
        border: 1px solid rgba(255,255,255,.18);
        transition: all .2s ease;
    }
    .status-step--done {
        background: rgba(45,206,137,.22);
        border-color: rgba(45,206,137,.55);
    }
    .status-step__icon { font-size: 1.1rem; margin-bottom: .25rem; opacity: .9; }
    .status-step__label { font-size: .7rem; text-transform: uppercase; letter-spacing: .04em; opacity: .8; }
    .status-step__value { font-size: 1.1rem; font-weight: 700; }

    .btn-absen {
        border: none; color: #fff; font-weight: 700; font-size: 1.05rem;
        padding: .95rem 1rem; border-radius: 14px;
        box-shadow: 0 10px 24px -8px rgba(0,0,0,.5);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-absen:hover { transform: translateY(-2px); box-shadow: 0 14px 28px -8px rgba(0,0,0,.55); color: #fff; }
    .btn-absen:active { transform: translateY(0); }
    .btn-absen--pagi { background: linear-gradient(135deg, #2dce89 0%, #1aae6f 100%); }
    .btn-absen--sore { background: linear-gradient(135deg, #fb6340 0%, #f5365c 100%); animation: pulseGlow 2.2s infinite; }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 10px 24px -8px rgba(245,54,92,.6); }
        50% { box-shadow: 0 10px 34px -4px rgba(245,54,92,.85); }
    }

    .absen-selesai {
        background: rgba(45,206,137,.2); border: 1px solid rgba(45,206,137,.5);
        border-radius: 14px; padding: .85rem 1rem; text-align: center; color: #fff;
        font-weight: 700;
    }

    /* ---- Maskot kartu absen ---- */
    .absen-mascot { padding: .5rem 0 1rem; }
    .absen-mascot__emoji {
        position: relative; font-size: 3.6rem; line-height: 1; margin-bottom: .85rem;
        filter: drop-shadow(0 6px 14px rgba(0,0,0,.25));
    }
    .absen-mascot__emoji::after {
        content: ""; position: absolute; z-index: -1;
        width: 130px; height: 130px; left: 50%; top: 50%;
        transform: translate(-50%, -50%);
        background: radial-gradient(circle, rgba(255,255,255,.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .absen-mascot__text { color: rgba(255,255,255,.9); font-size: .88rem; line-height: 1.45; max-width: 260px; }
    .mascot-wave { animation: mWave 2.4s ease-in-out infinite; transform-origin: 65% 80%; }
    .mascot-work { animation: mBob 2.6s ease-in-out infinite; }
    .mascot-celebrate { animation: mBounce 1.5s ease infinite; }
    @keyframes mWave { 0%,100% { transform: rotate(-7deg); } 50% { transform: rotate(10deg); } }
    @keyframes mBob  { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    @keyframes mBounce {
        0%,100% { transform: translateY(0) scale(1); }
        30% { transform: translateY(-14px) scale(1.08); }
        60% { transform: translateY(0) scale(.97); }
    }
    @media (prefers-reduced-motion: reduce) {
        .mascot-wave, .mascot-work, .mascot-celebrate { animation: none; }
    }


    /* ---- Kartu riwayat (mobile) ---- */
    .riwayat-card {
        border: 1px solid #edf0f5; border-radius: 14px;
        padding: .85rem 1rem; margin-bottom: .75rem; background: #fff;
        box-shadow: 0 2px 8px rgba(50,50,93,.04);
    }
    .riwayat-card:last-child { margin-bottom: 0; }
    .riwayat-card__head { display: flex; justify-content: space-between; align-items: flex-start; }
    .riwayat-card__body { display: flex; gap: .5rem; margin: .65rem 0; }
    .riwayat-card__cell {
        flex: 1; background: #f7f8fc; border-radius: 10px; padding: .5rem .65rem;
        display: flex; flex-direction: column;
    }
    .riwayat-card__k { font-size: .7rem; color: #8898aa; }
    .riwayat-card__v { font-size: 1.05rem; font-weight: 700; color: #32325d; font-variant-numeric: tabular-nums; }
    .riwayat-card__foot { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // ---- Jam real-time pada kartu absen ----
    const jamEl = document.getElementById('absen-jam');
    if (jamEl) {
        const tick = () => {
            const n = new Date();
            const p = (v) => String(v).padStart(2, '0');
            jamEl.textContent = `${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`;
        };
        tick();
        setInterval(tick, 1000);
    }

    // ---- Grafik Absensi ----
    const chartElement = document.querySelector("#absensi-chart");
    if (chartElement) {
        const dataMasuk = {!! json_encode($absensiChart['y'] ?? []) !!};
        const dataPulang = {!! json_encode($absensiChart['z'] ?? []) !!};
        const dataTanggal = {!! json_encode($absensiChart['x'] ?? []) !!};

        const options = {
            chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'inherit' },
            stroke: { width: 3, curve: 'smooth' },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 90] } },
            colors: ['#5e72e4', '#2dce89'],
            series: [
                { name: "Masuk", data: dataMasuk },
                { name: "Pulang", data: dataPulang }
            ],
            xaxis: { type: 'datetime', categories: dataTanggal, labels: { format: 'dd MMM' } },
            yaxis: {
                // Rentang jam dikunci 06:00–20:00 supaya label tidak menumpuk di
                // satu jam ketika datanya cuma sedikit (mis. semua ~18:00).
                min: 6 * 3600,
                max: 20 * 3600,
                tickAmount: 7,
                labels: { formatter: (val) => { let hours = Math.floor(val / 3600); return (hours < 10 ? '0' + hours : hours) + ":00"; } }
            },
            tooltip: { x: { format: 'dd MMMM yyyy' } },
            grid: { borderColor: '#eef1f6' }
        };
        new ApexCharts(chartElement, options).render();
    }

    // ---- Tombol Absen Pagi (buka modal mode kerja) ----
    $('#btn-absen-pemicu').on('click', function() {
        $('#wfhwfoModal').modal('show');
    });

    // ---- Pilih mode kerja (hanya pagi) ----
    $('.btn-wfhwfo').on('click', function() {
        const mode = $(this).data('value');
        $('#wfhwfoModal').modal('hide');
        ambilLokasiDanKirim(mode);
    });

    // ---- Map modal ----
    $('.btn-map').on('click', function() {
        const lat = $(this).data('lat');
        const lon = $(this).data('lon');
        const loc = $(this).data('lokasi');
        const mapUrl = `https://maps.google.com/maps?q=${lat},${lon}&hl=id&z=15&output=embed`;
        $('#google-map-iframe').attr('src', mapUrl);
        $('#map-location-text').text(loc || 'Lokasi Absensi');
        $('#map-coordinates').text(`Koordinat: ${lat}, ${lon}`);
        $('#mapModal').modal('show');
    });

    $('#mapModal').on('hidden.bs.modal', function() {
        $('#google-map-iframe').attr('src', '');
    });
});

// --- FUNGSI GLOBAL ---

// Jadwal pulang hari ini ("HH:MM") dari server, dipakai memperingatkan user
// yang absen pulang lebih awal dari jadwal.
const JADWAL_PULANG = "{{ $schedule_out }}";

function ambilLokasiDanKirim(mode = null) {
    // Validasi waktu sore: hanya boleh setelah jam 12.
    const sekarang = new Date();
    const jam = sekarang.getHours();

    if (mode === null && jam < 12) {
        Swal.fire({
            icon: 'info',
            title: 'Belum Waktunya Pulang',
            text: 'Absen sore hanya dapat dilakukan setelah pukul 12:00 WIB.',
            confirmButtonColor: '#fb6340'
        });
        return;
    }

    // Peringatan pulang lebih awal: kalau absen pulang sebelum jam pulang
    // terjadwal, konfirmasi dulu supaya tidak tercatat "Pulang Cepat" tanpa
    // sadar. Absen pagi (mode != null) tidak kena cek ini.
    if (mode === null && JADWAL_PULANG) {
        const [jamPulang, menitPulang] = JADWAL_PULANG.split(':').map(Number);
        const batasPulang = new Date(sekarang);
        batasPulang.setHours(jamPulang, menitPulang, 0, 0);

        if (sekarang < batasPulang) {
            Swal.fire({
                icon: 'warning',
                title: 'Pulang Lebih Awal?',
                html: `Jam pulang hari ini <b>${JADWAL_PULANG}</b>, sedangkan sekarang belum waktunya. ` +
                      'Absen ini akan tercatat sebagai <b>Pulang Cepat</b>.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Absen Pulang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#fb6340',
                cancelButtonColor: '#8898aa',
                reverseButtons: true
            }).then((hasil) => {
                if (hasil.isConfirmed) {
                    prosesAmbilLokasi(mode);
                }
            });
            return;
        }
    }

    prosesAmbilLokasi(mode);
}

function prosesAmbilLokasi(mode) {
    Swal.fire({
        title: 'Mencari Lokasi...',
        text: 'Sedang mengambil koordinat GPS Anda...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    if (!navigator.geolocation) {
        tolakTanpaLokasi(mode, 'Browser Anda tidak mendukung fitur lokasi.', false);
        return;
    }

    // Geolocation API hanya tersedia di secure context (HTTPS atau localhost).
    // Di HTTP biasa browser menolak dengan kode PERMISSION_DENIED tanpa pernah
    // memunculkan popup izin, sehingga pesan "aktifkan izin lokasi" menyesatkan
    // — masalahnya di alamat situs, bukan di setelan browser user.
    if (window.isSecureContext === false) {
        tolakTanpaLokasi(
            mode,
            'Situs ini diakses lewat koneksi tidak aman (HTTP), sehingga browser ' +
            'memblokir akses lokasi. Buka situs ini lewat <b>HTTPS</b> untuk bisa absen.',
            false
        );
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            kirimDataAbsen(mode, position.coords.latitude, position.coords.longitude);
        },
        function(error) {
            console.warn("GPS Error: " + error.message);
            tolakTanpaLokasi(mode, pesanGpsError(error), true);
        },
        // Timeout dilonggarkan jadi 10 detik: sekarang lokasi wajib, jadi GPS
        // yang cuma lambat mengunci tidak boleh langsung dianggap gagal.
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function pesanGpsError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            return 'Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser Anda.';
        case error.POSITION_UNAVAILABLE:
            return 'Lokasi tidak dapat dideteksi. Pastikan GPS perangkat Anda aktif.';
        case error.TIMEOUT:
            return 'Pencarian lokasi memakan waktu terlalu lama.';
        default:
            return 'Lokasi tidak dapat diambil.';
    }
}

/**
 * Absen tanpa koordinat ditolak sepenuhnya.
 *
 * Ini hanya lapis UX supaya pesannya jelas; penolakan yang sebenarnya ada di
 * AbsensiController, yang mewajibkan latitude/longitude. Jadi melewati dialog
 * ini lewat DevTools tetap tidak menghasilkan absensi.
 */
function tolakTanpaLokasi(mode, alasan, bolehCobaLagi) {
    Swal.fire({
        icon: 'error',
        title: 'Absen Tidak Dapat Dilakukan',
        html: alasan + '<br><br>Absensi <b>wajib</b> menyertakan lokasi. ' +
              'Aktifkan GPS dan izinkan akses lokasi, lalu coba lagi.',
        showCancelButton: bolehCobaLagi,
        confirmButtonText: bolehCobaLagi ? 'Coba Lagi' : 'Mengerti',
        cancelButtonText: 'Tutup',
        confirmButtonColor: '#fb6340',
        cancelButtonColor: '#8898aa',
        reverseButtons: true,
        allowOutsideClick: false
    }).then((hasil) => {
        if (bolehCobaLagi && hasil.isConfirmed) {
            // Langsung ambil lokasi lagi; konfirmasi pulang-awal tidak perlu
            // ditanyakan ulang karena sudah disetujui sebelumnya.
            prosesAmbilLokasi(mode);
        }
    });
}

function kirimDataAbsen(mode, lat, long) {
    const tipeAbsen = "{{ $toggleAbsenPagi == 'pagi' ? 'pagi' : 'sore' }}";
    const urlAbsen = "{{ route('absen', ':type') }}".replace(':type', tipeAbsen);

    // Fungsi ini hanya dipanggil setelah koordinat benar-benar terbaca.
    $.ajax({
        url: urlAbsen,
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            wfhwfo: mode,
            latitude: lat,
            longitude: long
        },
        success: function(res) {
            if (res.success) {
                Swal.fire('Berhasil!', res.message, 'success')
                    .then(() => { window.location.reload(); });
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        },
        error: function(xhr) {
            let pesan = 'Terjadi kesalahan sistem';
            if(xhr.responseJSON && xhr.responseJSON.message) pesan = xhr.responseJSON.message;
            Swal.fire('Error', pesan, 'error');
        }
    });
}
</script>
@endpush

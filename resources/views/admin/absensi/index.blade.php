@extends('layout.app')

@section('content')
<div class="page-content">

    {{-- Header --}}
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-clipboard-check fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">Pantau Absensi Magang</h5>
                <span class="text-sm text-white opacity-8">
                    {{ $tanggal->isoFormat('dddd, D MMMM Y') }}
                    @if($tanggal->isToday()) <span class="badge badge-light ml-1">Hari Ini</span> @endif
                    @unless($hariKerja) <span class="badge badge-warning ml-1">Libur</span> @endunless
                    @if($selectedSeksi) <span class="badge badge-light ml-1">{{ $selectedSeksi->code() }}</span> @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Kartu ringkasan --}}
    <div class="row">
        @php
            // Di hari libur "Belum Absen" tidak bermakna, jadi ditampilkan
            // sebagai strip abu-abu, bukan angka merah.
            $kartu = [
                ['Magang Aktif', $totalMagang, 'primary', 'fa-users'],
                ['Hadir',        $hadir,       'success', 'fa-user-check'],
                $hariKerja
                    ? ['Belum Absen', $belumAbsen, 'danger',    'fa-user-clock']
                    : ['Belum Absen', '—',        'secondary', 'fa-mug-hot'],
                ['Masuk Telat',  $telat,       'warning', 'fa-exclamation-triangle'],
            ];
        @endphp
        @foreach($kartu as [$label, $nilai, $warna, $ikon])
            <div class="col-lg-3 col-sm-6 mb-4">
                <x-stat-card :label="$label" :value="$nilai" :icon="$ikon" :color="$warna" />
            </div>
        @endforeach
    </div>

    @if($belumPulang > 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>{{ $belumPulang }}</strong> magang sudah absen masuk tapi belum absen pulang.
        </div>
    @endif

    <div class="card card-fluid shadow-sm">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-white">Status Absensi Per Magang</h6>
            <span class="badge badge-light">{{ $baris->count() }} magang</span>
        </div>

        {{-- Navigasi tanggal + filter unit --}}
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="{{ route('admin-absensi') }}" class="row align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="far fa-calendar mr-1"></i>Tanggal:</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <a href="{{ route('admin-absensi', ['tanggal' => $tanggal->copy()->subDay()->format('Y-m-d'), 'seksi' => $selectedSeksi?->value]) }}"
                               class="btn btn-outline-secondary" title="Hari sebelumnya">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <input type="date" name="tanggal" class="form-control form-control-sm text-center"
                               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
                        <div class="input-group-append">
                            <a href="{{ route('admin-absensi', ['tanggal' => $tanggal->copy()->addDay()->format('Y-m-d'), 'seksi' => $selectedSeksi?->value]) }}"
                               class="btn btn-outline-secondary" title="Hari berikutnya">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="mb-2 text-muted small"><i class="fas fa-sitemap mr-1"></i>Unit / Seksi:</label>
                    <select name="seksi" class="form-control form-control-sm js-select" onchange="this.form.submit()">
                        <option value="all">Semua Unit</option>
                        @foreach($seksiList as $s)
                            <option value="{{ $s->value }}" {{ $selectedSeksi?->value === $s->value ? 'selected' : '' }}>
                                {{ $s->code() }} &mdash; {{ $s->text() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <a href="{{ route('admin-absensi') }}" class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fas fa-calendar-day mr-1"></i>Hari Ini
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin-absensi', ['tanggal' => $tanggal->format('Y-m-d')]) }}"
                       class="btn btn-sm btn-outline-secondary btn-block">
                        <i class="fas fa-times mr-1"></i>Reset Unit
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Mode</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baris as $item)
                        @php $u = $item['user']; $a = $item['absensi']; @endphp
                        <tr class="{{ $a || !$hariKerja ? '' : 'bg-soft-danger' }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $u->avatar ? asset('storage/images/avatar/'.$u->avatar) : asset('assets/img/portrait.png') }}"
                                         alt="{{ $u->name }}" class="rounded-circle mr-2"
                                         style="width:36px;height:36px;object-fit:cover;">
                                    <div>
                                        <div class="font-weight-bold">{{ $u->name }}</div>
                                        <small class="text-muted">{{ $u->asal ?? '-' }}</small>
                                    </div>
                                </div>
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
                                @if(!$a && !$hariKerja)
                                    <span class="badge badge-light text-muted">Libur</span>
                                @elseif(!$a)
                                    <span class="badge badge-danger">Belum Absen</span>
                                @elseif($a->checked_out_at)
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-info">Sedang Bekerja</span>
                                @endif
                            </td>
                            <td>
                                @if($a?->checked_in_at)
                                    <div class="font-weight-bold">{{ $a->checked_in_at->format('H:i') }}</div>
                                    @if($a->checked_in_status === App\Enums\AbsensiStatus::MasukTelat)
                                        <small class="text-warning">Telat</small>
                                    @else
                                        <small class="text-success">Tepat waktu</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($a?->checked_out_at)
                                    <div class="font-weight-bold">{{ $a->checked_out_at->format('H:i') }}</div>
                                    @if($a->checked_out_status === App\Enums\AbsensiStatus::PulangCepat)
                                        <small class="text-warning">Pulang cepat</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($a?->wfhwfo)
                                    <span class="badge badge-secondary">{{ $a->wfhwfo }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td style="max-width:220px;">
                                @if($a?->lokasi_user)
                                    <small class="d-block text-truncate" title="{{ $a->lokasi_user }}">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ $a->lokasi_user }}
                                    </small>
                                    @if($a->latitude && $a->longitude)
                                        <a href="https://maps.google.com/maps?q={{ $a->latitude }},{{ $a->longitude }}"
                                           target="_blank" rel="noopener" class="small">Lihat peta</a>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('rekapabsen.user', $u->id) }}"
                                   class="btn btn-sm btn-outline-info" title="Rekap absensi {{ $u->name }}">
                                    <i class="fas fa-history"></i> Rekap
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada magang aktif
                                @if($selectedSeksi) pada unit {{ $selectedSeksi->code() }} @endif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Versi kartu untuk HP (< md) --}}
        <div class="d-md-none p-3">
            @forelse($baris as $item)
                @php $u = $item['user']; $a = $item['absensi']; @endphp
                <div class="absen-admin-card {{ $a || !$hariKerja ? '' : 'absen-admin-card--alpa' }}">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $u->avatar ? asset('storage/images/avatar/'.$u->avatar) : asset('assets/img/portrait.png') }}"
                             alt="{{ $u->name }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                        <div class="ml-2 flex-grow-1 min-w-0">
                            <div class="font-weight-bold text-dark text-truncate">{{ $u->name }}</div>
                            <small class="text-muted">{{ $u->asal ?? '-' }}</small>
                        </div>
                        @if(!$a && !$hariKerja)
                            <span class="badge badge-light text-muted">Libur</span>
                        @elseif(!$a)
                            <span class="badge badge-danger">Belum Absen</span>
                        @elseif($a->checked_out_at)
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-info">Bekerja</span>
                        @endif
                    </div>

                    <div class="absen-admin-card__grid">
                        <div>
                            <span class="absen-admin-card__k">Masuk</span>
                            @if($a?->checked_in_at)
                                <span class="font-weight-bold">{{ $a->checked_in_at->format('H:i') }}</span>
                                <small class="text-{{ $a->checked_in_status === App\Enums\AbsensiStatus::MasukTelat ? 'warning' : 'success' }}">
                                    {{ $a->checked_in_status === App\Enums\AbsensiStatus::MasukTelat ? 'Telat' : 'Tepat' }}
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        <div>
                            <span class="absen-admin-card__k">Pulang</span>
                            @if($a?->checked_out_at)
                                <span class="font-weight-bold">{{ $a->checked_out_at->format('H:i') }}</span>
                                @if($a->checked_out_status === App\Enums\AbsensiStatus::PulangCepat)
                                    <small class="text-warning">Cepat</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        <div>
                            <span class="absen-admin-card__k">Unit</span>
                            @if($u->seksi)<span class="badge badge-{{ $u->seksi->color() }}">{{ $u->seksi->code() }}</span>@else<span class="text-muted">-</span>@endif
                        </div>
                        <div>
                            <span class="absen-admin-card__k">Mode</span>
                            @if($a?->wfhwfo)<span class="badge badge-secondary">{{ $a->wfhwfo }}</span>@else<span class="text-muted">-</span>@endif
                        </div>
                    </div>

                    @if($a?->lokasi_user)
                        <div class="absen-admin-card__lokasi">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                            <span class="text-truncate">{{ $a->lokasi_user }}</span>
                            @if($a->latitude && $a->longitude)
                                <a href="https://maps.google.com/maps?q={{ $a->latitude }},{{ $a->longitude }}" target="_blank" rel="noopener" class="ml-1">Peta</a>
                            @endif
                        </div>
                    @endif

                    <a href="{{ route('rekapabsen.user', $u->id) }}" class="btn btn-sm btn-outline-info btn-block mt-2">
                        <i class="fas fa-history mr-1"></i>Rekap Absensi
                    </a>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    Tidak ada magang aktif
                    @if($selectedSeksi) pada unit {{ $selectedSeksi->code() }} @endif.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .absen-admin-card {
        border: 1px solid #edf0f5; border-radius: 14px;
        padding: .9rem 1rem; margin-bottom: .75rem; background: #fff;
        box-shadow: 0 2px 8px rgba(50,50,93,.04);
    }
    .absen-admin-card:last-child { margin-bottom: 0; }
    .absen-admin-card--alpa { background: rgba(245,54,92,.05); border-color: rgba(245,54,92,.25); }
    .absen-admin-card__grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .75rem; font-size: .85rem; }
    .absen-admin-card__k { display: block; font-size: .68rem; text-transform: uppercase; letter-spacing: .03em; color: #8898aa; font-weight: 700; }
    .absen-admin-card__lokasi { display: flex; align-items: center; font-size: .78rem; color: #8898aa; margin-top: .6rem; padding-top: .5rem; border-top: 1px solid #f1f3f9; }
</style>
@endpush

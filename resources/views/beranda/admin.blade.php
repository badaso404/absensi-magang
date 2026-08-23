@extends('layout.app')

@section('content')
<div class="page-content">
    {{-- Header Section --}}
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-clipboard-check fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">Halo, {{ explode(' ', auth()->user()->name)[0] }}!</h5>
                <span class="text-sm text-white opacity-8">
                    Pemantauan absensi magang &mdash; {{ $tanggal->isoFormat('dddd, D MMMM Y') }}
                    @unless($hariKerja) <span class="badge badge-warning ml-1">Libur</span> @endunless
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        @if (session('alert'))
            <div class="col-12">
                <x-alert :title="session('alert.title')" :type="session('alert.type')"
                         :messages="[session('alert.message')]" :display="'block'" />
            </div>
        @endif
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Magang Aktif</h6>
                            <span class="h2 font-weight-bold mb-0">{{ $totalMagang }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Sudah Absen</h6>
                            <span class="h2 font-weight-bold mb-0 text-success">{{ $sudahAbsen }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Belum Absen</h6>
                            <span class="h2 font-weight-bold mb-0 {{ $hariKerja ? 'text-danger' : 'text-muted' }}">
                                {{ $hariKerja ? $belumAbsen : '—' }}
                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape {{ $hariKerja ? 'bg-danger' : 'bg-secondary' }} text-white rounded-circle shadow">
                                <i class="fas {{ $hariKerja ? 'fa-user-clock' : 'fa-mug-hot' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted text-sm mb-1">Masuk Telat</h6>
                            <span class="h2 font-weight-bold mb-0 text-warning">{{ $telat }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Absensi hari ini --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Absensi Hari Ini
                    </h6>
                    <span class="badge badge-primary">{{ $absensiHariIni->count() }} entri</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absensiHariIni as $a)
                                <tr>
                                    <td>
                                        <a href="{{ route('rekapabsen.user', $a->user_id) }}" class="font-weight-bold">
                                            {{ $a->user->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td>{{ $a->checked_in_at?->format('H:i') ?? '-' }}</td>
                                    <td>{{ $a->checked_out_at?->format('H:i') ?? '-' }}</td>
                                    <td>
                                        @if ($a->checked_in_status === App\Enums\AbsensiStatus::MasukTelat)
                                            <span class="badge badge-warning">Telat</span>
                                        @else
                                            <span class="badge badge-success">Tepat Waktu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada yang absen hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right py-3">
                    <a href="{{ route('admin-absensi') }}" class="btn btn-sm btn-primary">
                        Lihat Semua Absensi
                    </a>
                </div>
            </div>
        </div>

        {{-- Belum absen --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-clock mr-2"></i>Belum Absen
                    </h6>
                    <span class="badge badge-danger">{{ $daftarBelum->count() }} orang</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($daftarBelum as $magang)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-sm">{{ $magang->name }}</span>
                            <a href="{{ route('rekapabsen.user', $magang->id) }}"
                               class="btn btn-sm btn-outline-primary">Rekap</a>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            @if($hariKerja)
                                Semua magang aktif sudah absen hari ini.
                            @else
                                Hari libur &mdash; tidak ada kewajiban absen.
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($belumPulang > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>{{ $belumPulang }}</strong> magang belum melakukan absen pulang.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

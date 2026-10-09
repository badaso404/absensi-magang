{{-- Kartu satu rekan magang. Dipakai di Tim Saya dan Lintas Tim.
     Hanya menampilkan kolom publik yang dikirim TimController. --}}
@php
    $warna = $r->seksi?->color() ?? 'primary';

    // Inisial untuk avatar cadangan kalau belum ada foto.
    $inisial = collect(explode(' ', trim($r->name)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');

    // Progress periode magang: berapa persen masa magang sudah dijalani dan
    // berapa hari tersisa. Hanya dihitung bila kedua tanggal ada dan valid.
    $now = \Carbon\Carbon::now();
    $awal = $r->tanggal_awal_magang ? \Carbon\Carbon::parse($r->tanggal_awal_magang) : null;
    $akhir = $r->tanggal_akhir_magang ? \Carbon\Carbon::parse($r->tanggal_akhir_magang) : null;
    $progress = null;
    $sisaHari = null;
    if ($awal && $akhir && $akhir->greaterThan($awal)) {
        $totalHari = $awal->diffInDays($akhir);
        $lewatHari = $awal->diffInDays($now->greaterThan($akhir) ? $akhir : $now);
        $progress = $totalHari > 0 ? min(100, max(0, round($lewatHari / $totalHari * 100))) : 0;
        $sisaHari = $now->lessThanOrEqualTo($akhir) ? $now->diffInDays($akhir) : 0;
    }
@endphp

<div class="col-lg-4 col-md-6 mb-4 tim-item" data-nama="{{ mb_strtolower($r->name) }}">
    <div class="card tim-card h-100 mb-0">
        <div class="tim-card__banner bg-{{ $warna }}"></div>
        <div class="card-body text-center pt-0">
            <div class="tim-card__avatar-wrap">
                @if($r->avatar)
                    <img src="{{ asset('storage/images/avatar/'.$r->avatar) }}"
                         alt="{{ $r->name }}" class="tim-card__avatar" loading="lazy">
                @else
                    <div class="tim-card__avatar tim-card__avatar--initial bg-{{ $warna }}">{{ $inisial ?: '?' }}</div>
                @endif
            </div>

            <h6 class="h5 mb-1 mt-3">{{ $r->name }}</h6>

            @if($tampilkanUnit ?? false)
                @if($r->seksi)
                    <span class="badge badge-{{ $warna }} mb-2" title="{{ $r->seksi->text() }}">{{ $r->seksi->code() }}</span>
                @endif
            @endif

            <p class="text-sm text-muted mb-1">
                <i class="fas fa-graduation-cap mr-1"></i>{{ $r->jurusan ?? 'Jurusan belum diisi' }}
            </p>
            <p class="text-sm text-muted mb-3">
                <i class="fas fa-university mr-1"></i>{{ $r->asal ?? '-' }}
            </p>

            @if($progress !== null)
                <div class="tim-card__periode mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted"><i class="far fa-calendar mr-1"></i>{{ $awal->isoFormat('MMM Y') }} &ndash; {{ $akhir->isoFormat('MMM Y') }}</small>
                        @if($sisaHari > 0)
                            <small class="font-weight-bold text-{{ $sisaHari <= 7 ? 'danger' : 'success' }}">{{ $sisaHari }} hari lagi</small>
                        @else
                            <small class="font-weight-bold text-muted">Selesai</small>
                        @endif
                    </div>
                    <div class="tim-progress">
                        <div class="tim-progress__bar bg-{{ $warna }}" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>
            @elseif($r->tanggal_awal_magang || $r->tanggal_akhir_magang)
                <p class="text-xs text-muted mb-3">
                    <i class="far fa-calendar mr-1"></i>
                    {{ $r->tanggal_awal_magang ? \Carbon\Carbon::parse($r->tanggal_awal_magang)->isoFormat('MMM Y') : '?' }}
                    &ndash;
                    {{ $r->tanggal_akhir_magang ? \Carbon\Carbon::parse($r->tanggal_akhir_magang)->isoFormat('MMM Y') : '?' }}
                </p>
            @endif

            {{-- Hanya kontak yang diisi sendiri oleh yang bersangkutan. --}}
            <div class="d-flex justify-content-center">
                @if($r->no_telp)
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $r->no_telp)) }}"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-success btn-icon-only rounded-circle mx-1"
                       title="WhatsApp {{ $r->name }}">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                @endif
                @if($r->instagram)
                    <a href="{{ Str::startsWith($r->instagram, ['http://', 'https://']) ? $r->instagram : 'https://instagram.com/' . ltrim($r->instagram, '@/') }}"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-danger btn-icon-only rounded-circle mx-1"
                       title="Instagram {{ $r->name }}">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @if($r->linkedin)
                    <a href="{{ Str::startsWith($r->linkedin, ['http://', 'https://']) ? $r->linkedin : 'https://' . ltrim($r->linkedin, '/') }}"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-info btn-icon-only rounded-circle mx-1"
                       title="LinkedIn {{ $r->name }}">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                @endif
                @if(!$r->no_telp && !$r->instagram && !$r->linkedin)
                    <span class="text-xs text-muted">Belum ada kontak yang dibagikan</span>
                @endif
            </div>
        </div>
    </div>
</div>

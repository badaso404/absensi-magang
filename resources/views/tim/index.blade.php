@extends('layout.app')

@section('content')
<div class="page-content">

    {{-- ===================== TIM SAYA ===================== --}}
    {{-- Area judul dibuat setinggi sisa banner ungu (lihat @push styles) agar
         kartu rekan tidak terpotong garis batas ungu/putih. --}}
    <div class="page-title tim-judul mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-user-friends fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">
                    Tim Saya
                    @if($saya->seksi)
                        <span class="badge badge-light ml-1">{{ $saya->seksi->code() }}</span>
                    @endif
                </h5>
                <span class="text-sm text-white opacity-8">
                    @if($saya->seksi)
                        {{ $saya->seksi->text() }} &mdash; {{ $rekan->count() }} rekan magang
                    @else
                        Unit belum ditentukan
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Ringkasan + pencarian --}}
    <div class="row mb-2">
        <div class="col-6 col-md-3 mb-3">
            <x-stat-card label="Rekan Satu Unit" :value="$rekan->count()" icon="fa-user-friends" color="primary" />
        </div>
        <div class="col-6 col-md-3 mb-3">
            <x-stat-card label="Rekan Lintas Tim" :value="$lintas->count()" icon="fa-network-wired" color="info" />
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="tim-search h-100 d-flex align-items-center">
                <i class="fas fa-search"></i>
                <input type="text" id="tim-cari" class="form-control" placeholder="Cari nama rekan..." autocomplete="off">
            </div>
        </div>
    </div>

    @if(!$saya->seksi)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Akun Anda belum ditempatkan di unit mana pun, jadi daftar Tim Saya belum bisa ditampilkan.
            Hubungi admin untuk penempatan unit. Anda tetap bisa melihat rekan di bagian Lintas Tim di bawah.
        </div>
    @elseif($rekan->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Belum ada rekan magang lain yang aktif di unit {{ $saya->seksi->code() }} saat ini.
        </div>
    @else
        <div class="row">
            @foreach($rekan as $r)
                @include('tim._kartu', ['r' => $r, 'tampilkanUnit' => false])
            @endforeach
        </div>
    @endif

    {{-- ===================== LINTAS TIM ===================== --}}
    <div class="card card-fluid shadow-sm mt-4">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            {{-- text-white dipasang langsung di h6: warna dari .card-header
                 tidak diwariskan ke heading oleh tema. --}}
            <h6 class="mb-0 font-weight-bold text-white">
                @if($tampilMentor)
                    <i class="fas fa-chalkboard-teacher mr-2"></i>Mentor Pembimbing
                @else
                    <i class="fas fa-people-arrows mr-2"></i>Lintas Tim
                    @if($seksiDipilih)
                        <span class="badge badge-light ml-1">{{ $seksiDipilih->code() }}</span>
                    @endif
                @endif
            </h6>
            <span class="badge badge-light">
                {{ $tampilMentor ? $admins->count() . ' mentor' : $lintas->count() . ' rekan' }}
            </span>
        </div>

        {{-- Filter unit. Unit sendiri tidak ada di pilihan karena sudah tampil
             di Tim Saya. Opsi "Mentor" menampilkan admin pembimbing. --}}
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="{{ route('tim') }}" class="row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label class="form-control-label text-muted small mb-1">
                        <i class="fas fa-sitemap mr-1"></i>Tampilkan
                    </label>
                    <select name="seksi" class="form-control form-control-sm js-select" onchange="this.form.submit()">
                        <option value="all" {{ !$seksiDipilih && !$tampilMentor ? 'selected' : '' }}>Semua unit lain</option>
                        <option value="mentor" {{ $tampilMentor ? 'selected' : '' }}>Mentor (Admin Pembimbing)</option>
                        @foreach($daftarSeksi as $s)
                            <option value="{{ $s->value }}" {{ $seksiDipilih?->value === $s->value ? 'selected' : '' }}>
                                {{ $s->code() }} &mdash; {{ $s->text() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    @if($seksiDipilih || $tampilMentor)
                        <a href="{{ route('tim') }}" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-times mr-1"></i>Semua unit
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body">
            @if($tampilMentor)
                {{-- Kartu mentor: tiga admin pembimbing, bisa dihubungi semua magang. --}}
                @if($admins->isEmpty())
                    <p class="text-center text-muted mb-0 py-3">Belum ada admin pembimbing yang bisa ditampilkan.</p>
                @else
                    <p class="text-sm text-muted mb-3">
                        <i class="fas fa-info-circle mr-1"></i>Mengalami kendala saat mengakses website atau ada hal yang membingungkan? Jangan ragu menghubungi admin pembimbing berikut.
                    </p>
                    <div class="row">
                        @foreach($admins as $a)
                            @php
                                // Admin selalu ungu (primary) sebagai penanda peran.
                                $inisialAdmin = collect(explode(' ', trim($a->name)))->filter()->take(2)
                                    ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                            @endphp
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card tim-card h-100 mb-0">
                                    <div class="tim-card__banner bg-primary"></div>
                                    <div class="card-body text-center pt-0">
                                        <div class="tim-card__avatar-wrap">
                                            @if($a->avatar)
                                                <img src="{{ asset('storage/images/avatar/'.$a->avatar) }}"
                                                     alt="{{ $a->name }}" class="tim-card__avatar" loading="lazy">
                                            @else
                                                <div class="tim-card__avatar tim-card__avatar--initial bg-primary">{{ $inisialAdmin ?: '?' }}</div>
                                            @endif
                                        </div>

                                        <h6 class="h5 mb-1 mt-3">{{ $a->name }}</h6>
                                        <span class="badge badge-primary mb-4">Admin Pembimbing</span>

                                        <div class="d-flex justify-content-center mt-2">
                                            @if($a->no_telp)
                                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $a->no_telp)) }}"
                                                   target="_blank" rel="noopener"
                                                   class="btn btn-sm btn-success btn-icon-only rounded-circle mx-1"
                                                   title="WhatsApp {{ $a->name }}">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @else
                                                <span class="text-xs text-muted">Belum ada kontak yang dibagikan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @elseif($lintas->isEmpty())
                <p class="text-center text-muted mb-0 py-3">
                    @if($seksiDipilih)
                        Belum ada magang aktif di unit {{ $seksiDipilih->code() }} saat ini.
                    @else
                        Belum ada magang aktif di unit lain saat ini.
                    @endif
                </p>
            @else
                <div class="row">
                    @foreach($lintas as $r)
                        @include('tim._kartu', ['r' => $r, 'tampilkanUnit' => true])
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Muncul saat pencarian tidak menemukan siapa pun --}}
    <div id="tim-no-result" class="text-center text-muted py-5" style="display:none;">
        <i class="fas fa-user-slash fa-2x mb-2 d-block opacity-3"></i>
        <div>Tidak ada rekan dengan nama itu.</div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Banner ungu di layout tingginya 420px dari atas halaman, dan konten
       mulai ±110px di bawah navbar. Halaman lain memang sengaja membiarkan
       kartunya "menggantung" melewati batas ungu/putih, tapi untuk halaman
       ini kartu rekan harus utuh di area putih: judul diberi tinggi minimal
       sehingga ujung bawahnya tepat melewati batas banner, dan judulnya
       diletakkan di tengah area itu supaya tidak menggantung di atas. */
    .tim-judul {
        min-height: 220px;
        display: flex;
        align-items: center;
    }

    @media (max-width: 767.98px) {
        .tim-judul { min-height: 160px; }
    }

    /* ---- Kartu rekan ---- */
    .tim-card {
        border: none; border-radius: 16px; overflow: hidden;
        box-shadow: 0 2px 12px rgba(50,50,93,.07);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .tim-card:hover { transform: translateY(-5px); box-shadow: 0 16px 32px -12px rgba(50,50,93,.3); }
    .tim-card__banner { height: 64px; }
    .tim-card__avatar-wrap { margin-top: -42px; display: flex; justify-content: center; }
    .tim-card__avatar {
        width: 84px; height: 84px; border-radius: 50%; object-fit: cover;
        border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,.12); background: #fff;
    }
    .tim-card__avatar--initial {
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 1.9rem;
    }
    .tim-progress { height: 7px; background: #eef1f6; border-radius: 999px; overflow: hidden; }
    .tim-progress__bar { height: 100%; border-radius: 999px; transition: width .4s ease; }

    /* ---- Pencarian ---- */
    .tim-search { position: relative; }
    .tim-search i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #8898aa; z-index: 2; }
    .tim-search input { padding-left: 2.6rem; height: 52px; border-radius: 12px; }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const cari = document.getElementById('tim-cari');
        if (!cari) return;
        const kosong = document.getElementById('tim-no-result');

        cari.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let tampil = 0;
            document.querySelectorAll('.tim-item').forEach(function (el) {
                const cocok = !q || (el.dataset.nama || '').indexOf(q) !== -1;
                el.style.display = cocok ? '' : 'none';
                if (cocok) tampil++;
            });
            if (kosong) kosong.style.display = (q && tampil === 0) ? '' : 'none';
        });
    })();
</script>
@endpush

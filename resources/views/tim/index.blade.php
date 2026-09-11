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
                <i class="fas fa-people-arrows mr-2"></i>Lintas Tim
                @if($seksiDipilih)
                    <span class="badge badge-light ml-1">{{ $seksiDipilih->code() }}</span>
                @endif
            </h6>
            <span class="badge badge-light">{{ $lintas->count() }} rekan</span>
        </div>

        {{-- Filter unit. Unit sendiri tidak ada di pilihan karena sudah tampil
             di Tim Saya. --}}
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="{{ route('tim') }}" class="row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label class="form-control-label text-muted small mb-1">
                        <i class="fas fa-sitemap mr-1"></i>Tampilkan unit
                    </label>
                    <select name="seksi" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="all" {{ !$seksiDipilih ? 'selected' : '' }}>Semua unit lain</option>
                        @foreach($daftarSeksi as $s)
                            <option value="{{ $s->value }}" {{ $seksiDipilih?->value === $s->value ? 'selected' : '' }}>
                                {{ $s->code() }} &mdash; {{ $s->text() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    @if($seksiDipilih)
                        <a href="{{ route('tim') }}" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-times mr-1"></i>Semua unit
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body">
            @if($lintas->isEmpty())
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
</style>
@endpush

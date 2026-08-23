@extends('layout.app')

@section('content')
<div class="page-content">

    <div class="page-title mb-4">
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
            Akun Anda belum ditempatkan di unit mana pun, jadi daftar rekan belum bisa ditampilkan.
            Hubungi admin untuk penempatan unit.
        </div>
    @elseif($rekan->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Belum ada rekan magang lain yang aktif di unit {{ $saya->seksi->code() }} saat ini.
        </div>
    @else
        <div class="row">
            @foreach($rekan as $r)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ $r->avatar ? asset('storage/images/avatar/'.$r->avatar) : asset('assets/img/portrait.png') }}"
                                 alt="{{ $r->name }}"
                                 class="rounded-circle shadow mb-3"
                                 style="width:90px;height:90px;object-fit:cover;"
                                 loading="lazy">

                            <h6 class="h5 mb-1">{{ $r->name }}</h6>

                            <p class="text-sm text-muted mb-1">
                                {{ $r->jurusan ?? 'Jurusan belum diisi' }}
                            </p>
                            <p class="text-sm text-muted mb-3">
                                <i class="fas fa-university mr-1"></i>{{ $r->asal ?? '-' }}
                            </p>

                            @if($r->tanggal_awal_magang || $r->tanggal_akhir_magang)
                                <p class="text-xs text-muted mb-3">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $r->tanggal_awal_magang ? \Carbon\Carbon::parse($r->tanggal_awal_magang)->isoFormat('MMM Y') : '?' }}
                                    &ndash;
                                    {{ $r->tanggal_akhir_magang ? \Carbon\Carbon::parse($r->tanggal_akhir_magang)->isoFormat('MMM Y') : '?' }}
                                </p>
                            @endif

                            {{-- Hanya kontak yang diisi sendiri oleh yang bersangkutan
                                 di profilnya yang ditampilkan di sini. --}}
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
            @endforeach
        </div>
    @endif
</div>
@endsection

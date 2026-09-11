{{-- Kartu satu rekan magang. Dipakai di Tim Saya dan Lintas Tim.
     Hanya menampilkan kolom publik yang dikirim TimController. --}}
<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 mb-0">
        <div class="card-body text-center">
            <img src="{{ $r->avatar ? asset('storage/images/avatar/'.$r->avatar) : asset('assets/img/portrait.png') }}"
                 alt="{{ $r->name }}"
                 class="rounded-circle shadow mb-3"
                 style="width:90px;height:90px;object-fit:cover;"
                 loading="lazy">

            <h6 class="h5 mb-1">{{ $r->name }}</h6>

            @if($tampilkanUnit ?? false)
                @if($r->seksi)
                    <span class="badge badge-{{ $r->seksi->color() }} mb-2"
                          title="{{ $r->seksi->text() }}">{{ $r->seksi->code() }}</span>
                @endif
            @endif

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

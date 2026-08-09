@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="h3 font-weight-400 mb-0 text-white">Selamat pagi, {{ explode(' ', auth()->user()->name)[0] }}!</h5>
                <span class="text-sm text-white opacity-8">Informasi profil akun Anda.</span>
            </div>
        </div>
    </div>

    <div class="row">
        @if (session('alert'))
        <div class="col-12">
            <x-alert :title="session('alert.title')" :type="session('alert.type')" :messages="[session('alert.message')]" :display="'block'" />
        </div>
        @endif

        <div class="col-lg-6">
            <div class="card card-fluid">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar rounded-circle">
                                <img alt="Avatar" class="avatar-cover" src="{{ auth()->user()->avatar ? asset('storage/images/avatar/'.auth()->user()->avatar) : asset('assets/img/portrait.png') }}">
                            </a>
                        </div>
                        <div class="col ml-md-n2">
                            <a href="#!" class="d-block h6 mb-0">{{ auth()->user()->name }}</a>
                            <small class="d-block text-muted">{{ auth()->user()->seksi?->text() ?? '-' }}</small>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('profil-edit') }}" class="btn btn-xs btn-primary btn-icon rounded-pill">
                                <span class="btn-inner--icon"><i class="far fa-edit"></i></span>
                                <span class="btn-inner--text">Edit Profil</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center text-center">
                        <div class="col-4">
                            <span class="h3 mb-0 text-info font-weight-bolder">0</span>
                            <span class="d-block text-sm">Kehadiran</span>
                        </div>
                        <div class="col-4">
                            <span class="h3 mb-0 text-info font-weight-bolder">0</span>
                            <span class="d-block text-sm">Tugas</span>
                        </div>
                        <div class="col-4">
                            <span class="h3 mb-0 text-info font-weight-bolder">0</span>
                            <span class="d-block text-sm">Laporan</span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <span class="form-control-label">Asal Universitas / Sekolah:</span>
                            <p class="text-sm mb-0">{{ auth()->user()->asal ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <span class="form-control-label">Jurusan:</span>
                            <p class="text-sm mb-0">{{ auth()->user()->jurusan ?? '-' }}</p>
                        </div>
                        <div class="col-12">
                            <span class="form-control-label">Jenis Kelamin:</span>
                            <p class="text-sm mb-0">{{ auth()->user()->jenis_kelamin ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-6">
                            <small class="d-block">Awal Magang:</small>
                            <span class="h6 text-info">{{ auth()->user()->tanggal_awal_magang ? \Carbon\Carbon::parse(auth()->user()->tanggal_awal_magang)->isoFormat('D MMMM Y') : '-' }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <small class="d-block">Selesai Magang:</small>
                            <span class="h6 text-info">{{ auth()->user()->tanggal_akhir_magang ? \Carbon\Carbon::parse(auth()->user()->tanggal_akhir_magang)->isoFormat('D MMMM Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-fluid">
                <div class="card-body">
                    <h6 class="mb-4">Kontak & Media Sosial</h6>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fa fa-envelope mr-2"></i>Email</h6></div>
                        <div class="col-auto"><span class="text-sm">{{ auth()->user()->email }}</span></div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fa fa-key mr-2"></i>Password</h6></div>
                        <div class="col-auto">
                            <span class="text-sm">••••••••</span>
                            <a data-toggle="modal" data-target="#modal-change-password" href="#"><i class="ml-2 fa fa-edit"></i></a>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fab fa-whatsapp mr-2"></i>WhatsApp</h6></div>
                        <div class="col-auto">
                            <a href="{{ auth()->user()->no_telp ? 'https://wa.me/' . preg_replace('/^0/', '+62', auth()->user()->no_telp) : '#' }}" target="_blank" class="text-sm">{{ auth()->user()->no_telp ?? '-' }}</a>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fab fa-instagram mr-2"></i>Instagram</h6></div>
                        <div class="col-auto"><span class="text-sm">{{ auth()->user()->instagram ?? '-' }}</span></div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col"><h6 class="text-sm mb-0"><i class="fab fa-linkedin mr-2"></i>LinkedIn</h6></div>
                        <div class="col-auto"><span class="text-sm">{{ auth()->user()->linkedin ?? '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ganti Password --}}
<div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="{{ route('profil-update-password') }}" id="password-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mb-0">Edit Password</h6>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">Password Lama</label>
                        <input class="form-control" name="current_password" type="password" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Password Baru</label>
                                <input class="form-control" name="password" type="password" minlength="4" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Konfirmasi</label>
                                <input class="form-control" name="password_confirmation" type="password" minlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-save-password" class="btn btn-success btn-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#password-form').submit(function(e) {
            e.preventDefault();
            let btn = $('#btn-save-password');
            btn.attr('disabled', true).text('Menyimpan...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.redirect) window.location.href = res.redirect;
                    else location.reload();
                },
                error: function(xhr) {
                    alert('Gagal memperbarui password. Periksa kembali password lama Anda.');
                    btn.removeAttr('disabled').text('Simpan');
                }
            });
        });
    });
</script>
@endpush
@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-6 mb-3">
                <h5 class="h3 font-weight-400 mb-0 text-white">Edit Profil</h5>
                <span class="text-sm text-white opacity-8">Perbarui informasi data diri Anda.</span>
            </div>
        </div>
    </div>

    {{-- Gunakan route('profil.update') atau sesuaikan dengan names di web.php --}}
    <form method="post" action="{{ route('profil-update') }}" id="edit-profil-form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fluid shadow-sm">
                    <div class="card-body">
                        {{-- Avatar Upload Section --}}
                        <div class="row align-items-center text-center">
                            <div class="col-12 mb-3">
                                <div class="avatar rounded-circle avatar-xxl shadow-lg" style="width: 150px; height: 150px; overflow: hidden; margin: 0 auto;">
                                    <img alt="Avatar" id="avatar-preview" 
                                         src="{{ auth()->user()->avatar ? asset('storage/images/avatar/'.auth()->user()->avatar) : asset('assets/img/portrait.png') }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="avatar-input" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-camera mr-2"></i>Pilih Foto Baru
                                </label>
                                <input type="file" id="avatar-input" name="avatar" accept="image/*" hidden>
                                <p class="text-muted text-xs mt-2">Gunakan foto formal. Maksimal 2MB (PNG, JPG).</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Form Fields --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Nama Lengkap</label>
                                <input class="form-control" name="name" type="text" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Asal Sekolah/Kampus</label>
                                <input class="form-control" name="asal" type="text" value="{{ auth()->user()->asal }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Jurusan</label>
                                <input class="form-control" name="jurusan" type="text" value="{{ auth()->user()->jurusan }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Jenis Kelamin</label>
                                <select class="form-control" name="jenis_kelamin">
                                    <option value="Laki-laki" {{ auth()->user()->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ auth()->user()->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Tanggal Lahir</label>
                                <input class="form-control" name="tanggal_lahir" type="date"
                                       value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir ? \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">No. WhatsApp</label>
                                <input class="form-control" name="no_telp" type="text" inputmode="numeric"
                                       placeholder="08xxxxxxxxxx"
                                       value="{{ old('no_telp', auth()->user()->no_telp) }}">
                                <small class="form-text text-muted">Hanya angka, diawali 0. Dipakai untuk tautan WhatsApp di profil.</small>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="form-control-label">Alamat Lengkap</label>
                                <textarea class="form-control" name="alamat" rows="2" required>{{ auth()->user()->alamat }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="heading-small text-muted mb-4">Media Sosial</h6>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-control-label"><i class="fab fa-instagram mr-1"></i> Instagram</label>
                                <input class="form-control" name="instagram" type="text"
                                       placeholder="https://instagram.com/username"
                                       value="{{ old('instagram', auth()->user()->instagram) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label"><i class="fab fa-linkedin mr-1"></i> LinkedIn</label>
                                <input class="form-control" name="linkedin" type="text"
                                       placeholder="https://linkedin.com/in/username"
                                       value="{{ old('linkedin', auth()->user()->linkedin) }}">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="heading-small text-muted mb-4">Periode Magang</h6>

                        {{-- Sengaja read-only: tanggal akhir magang menentukan apakah akun masih
                             boleh login (middleware MagangAktif). Kalau magang bisa mengubahnya
                             sendiri, blokir masa magang berakhir jadi tidak ada artinya. --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Awal Magang</label>
                                <input class="form-control bg-light" type="text" disabled
                                       value="{{ auth()->user()->tanggal_awal_magang ? \Carbon\Carbon::parse(auth()->user()->tanggal_awal_magang)->isoFormat('D MMMM Y') : 'Belum diatur' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Akhir Magang</label>
                                <input class="form-control bg-light" type="text" disabled
                                       value="{{ auth()->user()->tanggal_akhir_magang ? \Carbon\Carbon::parse(auth()->user()->tanggal_akhir_magang)->isoFormat('D MMMM Y') : 'Belum diatur' }}">
                            </div>
                            <div class="col-12">
                                <small class="form-text text-muted">
                                    <i class="fas fa-lock mr-1"></i>
                                    Periode magang hanya dapat diubah oleh admin. Hubungi admin bila tanggalnya keliru.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light">
                        <a href="{{ route('profil') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Batal
                        </a>
                        <button type="submit" id="btn-save-profile" class="btn btn-success btn-sm">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Preview Foto Profil
        $('#avatar-input').change(function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = (e) => { 
                    $('#avatar-preview').attr('src', e.target.result); 
                }
                reader.readAsDataURL(file);
            }
        });

        // Submit via AJAX
        $('#edit-profil-form').submit(function(e) {
            e.preventDefault();
            let btn = $('#btn-save-profile');
            let originalText = btn.html();
            
            btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(res) {
                    // Pastikan route('profil') ada di web.php Anda
                    window.location.href = "{{ route('profil') }}";
                },
                error: function(err) {
                    let message = 'Gagal menyimpan data.';
                    if (err.status === 413) {
                        message = 'Ukuran file terlalu besar (Maksimal 2MB).';
                    }
                    alert(message);
                    btn.removeAttr('disabled').html(originalText);
                }
            });
        });
    });
</script>
@endpush
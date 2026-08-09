@extends('layout.app')

@section('content')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }
    
    .card {
        animation: slideInUp 0.5s ease-out;
    }
    
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    
    .form-group {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn { to { opacity: 1; } }
    
    .avatar-preview {
        width: 120px; height: 120px; border-radius: 50%;
        object-fit: cover; border: 4px solid #5e72e4;
        transition: transform 0.3s ease;
    }
    .avatar-preview:hover { transform: scale(1.05); }
</style>

<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-success text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-plus fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Tambah User Magang</h4>
                <small class="text-light">Isi form di bawah untuk menambah user magang baru</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-edit mr-2"></i>Form Tambah User</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin-user-store') }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.1s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-user text-primary mr-1"></i>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.15s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-id-card text-secondary mr-1"></i>NISN / NIM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select class="form-control bg-light" name="identity_type" style="width: 85px; border-radius: 5px 0 0 5px;">
                                                <option value="NIM" {{ old('identity_type') == 'NIM' ? 'selected' : '' }}>NIM</option>
                                                <option value="NISN" {{ old('identity_type') == 'NISN' ? 'selected' : '' }}>NISN</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control @error('identity_number') is-invalid @enderror" name="identity_number" value="{{ old('identity_number') }}" placeholder="Masukkan Nomor Identitas" required style="border-radius: 0 5px 5px 0;">
                                    </div>
                                    @error('identity_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.2s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-envelope text-info mr-1"></i>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.25s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-lock text-warning mr-1"></i>Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.3s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-lock text-warning mr-1"></i>Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.35s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-venus-mars text-danger mr-1"></i>Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.4s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-birthday-cake text-success mr-1"></i>Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.1s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-university text-primary mr-1"></i>Asal Sekolah/Universitas</label>
                                    <input type="text" class="form-control @error('asal') is-invalid @enderror" name="asal" value="{{ old('asal') }}">
                                    @error('asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.15s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-graduation-cap text-info mr-1"></i>Jurusan</label>
                                    <input type="text" class="form-control @error('jurusan') is-invalid @enderror" name="jurusan" value="{{ old('jurusan') }}">
                                    @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.2s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-sitemap text-warning mr-1"></i>Seksi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('seksi') is-invalid @enderror" name="seksi" required>
                                        <option value="">Pilih Seksi</option>
                                        @foreach($seksiList as $seksi)
                                            <option value="{{ $seksi->value }}" {{ old('seksi') == $seksi->value ? 'selected' : '' }}>{{ $seksi->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('seksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.25s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-calendar-alt text-success mr-1"></i>Tanggal Awal Magang</label>
                                    <input type="date" class="form-control @error('tanggal_awal_magang') is-invalid @enderror" name="tanggal_awal_magang" value="{{ old('tanggal_awal_magang') }}">
                                    @error('tanggal_awal_magang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.3s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-calendar-check text-danger mr-1"></i>Tanggal Akhir Magang</label>
                                    <input type="date" class="form-control @error('tanggal_akhir_magang') is-invalid @enderror" name="tanggal_akhir_magang" value="{{ old('tanggal_akhir_magang') }}">
                                    @error('tanggal_akhir_magang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group mb-3" style="animation-delay: 0.35s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-phone text-success mr-1"></i>No. Telepon (WhatsApp)</label>
                                    <input type="text" class="form-control @error('no_telp') is-invalid @enderror" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxx">
                                    @error('no_telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.4s;">
                                    <label class="form-label font-weight-bold"><i class="fab fa-instagram text-danger mr-1"></i>Instagram URL</label>
                                    <input type="url" class="form-control" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/username">
                                </div>
                                <div class="form-group mb-3" style="animation-delay: 0.45s;">
                                    <label class="form-label font-weight-bold"><i class="fab fa-linkedin text-info mr-1"></i>LinkedIn URL</label>
                                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" style="animation-delay: 0.5s;">
                                    <label class="form-label font-weight-bold"><i class="fas fa-image text-primary mr-1"></i>Foto Profil</label>
                                    <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div class="text-center mt-3"><img id="preview" src="{{ asset('default-avatar.png') }}" alt="Preview" class="avatar-preview"></div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin-user') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i>Simpan Data</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById('userForm').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        btn.disabled = true;
    });
</script>
@endsection
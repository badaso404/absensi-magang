@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-plus fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Tambah Laporan Kegiatan</h4>
                <small class="text-light">Input data laporan kegiatan baru</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="mb-0 font-weight-bold text-light">
                        <i class="fas fa-edit mr-2"></i>Form Laporan Kegiatan
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="tanggal" class="font-weight-bold">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal') }}"
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="detail_kegiatan" class="font-weight-bold">
                                Detail Kegiatan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('detail_kegiatan') is-invalid @enderror" 
                                      id="detail_kegiatan" 
                                      name="detail_kegiatan" 
                                      rows="5" 
                                      required>{{ old('detail_kegiatan') }}</textarea>
                            @error('detail_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Jelaskan kegiatan yang dilakukan secara detail
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="lokasi" class="font-weight-bold">
                                Lokasi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('lokasi') is-invalid @enderror" 
                                   id="lokasi" 
                                   name="lokasi" 
                                   value="{{ old('lokasi') }}"
                                   placeholder="Contoh: Sudin Kominfotik Jakarta Barat"
                                   required>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="dokumentasi" class="font-weight-bold">
                                Dokumentasi (Opsional)
                            </label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('dokumentasi') is-invalid @enderror" 
                                       id="dokumentasi" 
                                       name="dokumentasi"
                                       accept="image/jpeg,image/png,image/jpg">
                                <label class="custom-file-label" for="dokumentasi">Pilih file gambar...</label>
                            </div>
                            @error('dokumentasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                        </div>

                        <div class="form-group mb-0 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Laporan
                            </button>
                            <a href="{{ route('laporan-kegiatan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Petunjuk Pengisian
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Isi tanggal sesuai dengan waktu kegiatan dilakukan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Detail kegiatan harus jelas dan lengkap
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Cantumkan lokasi kegiatan dengan tepat
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Upload dokumentasi jika ada (opsional)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview nama file yang dipilih
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Pilih file gambar...';
        var label = e.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>
@endpush
@endsection

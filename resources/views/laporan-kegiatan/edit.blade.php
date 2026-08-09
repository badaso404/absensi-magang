@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-warning text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-edit fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Edit Laporan Kegiatan</h4>
                <small class="text-light">Perbarui data laporan kegiatan</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-warning text-white">
                    <h6 class="mb-0 font-weight-bold text-white">
                        <i class="fas fa-edit mr-2"></i>Form Edit Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-kegiatan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="tanggal" class="font-weight-bold">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', $laporan->tanggal->format('Y-m-d')) }}"
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
                                      required>{{ old('detail_kegiatan', $laporan->detail_kegiatan) }}</textarea>
                            @error('detail_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="lokasi" class="font-weight-bold">
                                Lokasi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('lokasi') is-invalid @enderror" 
                                   id="lokasi" 
                                   name="lokasi" 
                                   value="{{ old('lokasi', $laporan->lokasi) }}"
                                   required>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="dokumentasi" class="font-weight-bold">
                                Dokumentasi (Opsional)
                            </label>
                            
                            @if($laporan->dokumentasi)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $laporan->dokumentasi) }}" 
                                     alt="Dokumentasi" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                                <div class="mt-1">
                                    <small class="text-muted">Dokumentasi saat ini</small>
                                </div>
                            </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('dokumentasi') is-invalid @enderror" 
                                       id="dokumentasi" 
                                       name="dokumentasi"
                                       accept="image/jpeg,image/png,image/jpg">
                                <label class="custom-file-label" for="dokumentasi">
                                    {{ $laporan->dokumentasi ? 'Ganti gambar...' : 'Pilih file gambar...' }}
                                </label>
                            </div>
                            @error('dokumentasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.
                            </small>
                        </div>

                        <div class="form-group mb-0 mt-4">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save mr-2"></i>Update Laporan
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
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Informasi
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted">Dibuat pada:</small>
                        <div class="font-weight-bold">
                            {{ $laporan->created_at->isoFormat('D MMMM Y, HH:mm') }}
                        </div>
                    </div>
                    @if($laporan->updated_at != $laporan->created_at)
                    <div class="mb-2">
                        <small class="text-muted">Terakhir diupdate:</small>
                        <div class="font-weight-bold">
                            {{ $laporan->updated_at->isoFormat('D MMMM Y, HH:mm') }}
                        </div>
                    </div>
                    @endif
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
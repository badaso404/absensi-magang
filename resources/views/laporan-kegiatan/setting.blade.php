@extends('layout.app')

@section('content')
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-info text-white mr-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-cog fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Pengaturan Laporan Kegiatan</h4>
                <small class="text-light">Atur informasi pekerjaan dan bidang untuk export</small>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="mb-0 font-weight-bold text-white">
                        <i class="fas fa-edit mr-2"></i>Informasi Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-kegiatan.setting.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Info:</strong> Pengaturan ini akan digunakan untuk semua export laporan kegiatan Anda.
                        </div>

                        <div class="form-group">
                            <label for="bidang_suku_dinas" class="font-weight-bold">
                                Bidang / Suku Dinas <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('bidang_suku_dinas') is-invalid @enderror" 
                                   id="bidang_suku_dinas" 
                                   name="bidang_suku_dinas" 
                                   value="{{ old('bidang_suku_dinas', auth()->user()->bidang_suku_dinas ?? 'Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat') }}"
                                   placeholder="Contoh: Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat"
                                   required>
                            @error('bidang_suku_dinas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Nama bidang dan suku dinas tempat Anda bekerja
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="pekerjaan" class="font-weight-bold">
                                Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('pekerjaan') is-invalid @enderror" 
                                   id="pekerjaan" 
                                   name="pekerjaan" 
                                   value="{{ old('pekerjaan', auth()->user()->pekerjaan ?? 'Technical Support Keamanan Informasi') }}"
                                   placeholder="Contoh: Technical Support Keamanan Informasi"
                                   required>
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Jabatan atau posisi Anda
                            </small>
                        </div>

                        <div class="form-group mb-0 mt-4">
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
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
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-lightbulb mr-2"></i>Tips
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Informasi ini akan muncul di header export Excel
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Pastikan nama bidang dan pekerjaan sesuai dengan data resmi
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Anda bisa mengubah informasi ini kapan saja
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">Preview Export</h6>
                    <div class="border p-3 bg-light" style="font-size: 0.85rem;">
                        <p class="mb-1"><strong>Nama:</strong> {{ auth()->user()->name }}</p>
                        <p class="mb-1"><strong>Bidang / Suku Dinas:</strong></p>
                        <p class="mb-1 text-primary">{{ auth()->user()->bidang_suku_dinas ?? 'Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat' }}</p>
                        <p class="mb-1"><strong>Pekerjaan:</strong></p>
                        <p class="mb-0 text-primary">{{ auth()->user()->pekerjaan ?? 'Technical Support Keamanan Informasi' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
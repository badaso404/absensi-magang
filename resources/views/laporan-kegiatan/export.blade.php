<table>
    {{-- Header Instansi --}}
    <thead>
        <tr>
            <th colspan="5">SUKU DINAS KOMUNIKASI INFORMATIKA DAN STATISTIK KOTA ADMINISTRASI JAKARTA BARAT</th>
        </tr>
        <tr>
            <th colspan="5">SEKSI APLIKASI SIBER DAN STATISTIK</th>
        </tr>
        <tr>
            <th colspan="5">LAPORAN KEGIATAN</th>
        </tr>
        
        {{-- Baris kosong --}}
        <tr><th colspan="5"></th></tr>
        
        {{-- Info Section - Label di A+B, Value di C-E --}}
        <tr>
            <th colspan="2">Nama</th>
            <th colspan="3">: {{ $user->name }}</th>
        </tr>
        <tr>
            <th colspan="2">Bidang / Suku Dinas</th>
            <th colspan="3">: {{ $user->bidang_suku_dinas ?? 'Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat' }}</th>
        </tr>
        <tr>
            <th colspan="2">Pekerjaan</th>
            <th colspan="3">: {{ $user->pekerjaan ?? 'Technical Support Keamanan Informasi' }}</th>
        </tr>
        <tr>
            <th colspan="2">Bulan</th>
            <th colspan="3">: {{ $bulan ?: 'Semua Data' }}</th>
        </tr>
        
        {{-- Baris kosong --}}
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="5"></th></tr>
        
        {{-- Header Tabel (Row 13) --}}
        <tr>
            <th>NO</th>
            <th>HARI/TANGGAL</th>
            <th>DETAIL KEGIATAN</th>
            <th>LOKASI</th>
            <th>DOKUMENTASI</th>
        </tr>
    </thead>
    
    {{-- Data --}}
    <tbody>
        @forelse($laporan as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D/M/YYYY') }}</td>
            <td>{{ $item->detail_kegiatan }}</td>
            <td>{{ $item->lokasi }}</td>
            <td></td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center;">Tidak ada data laporan kegiatan</td>
        </tr>
        @endforelse
    </tbody>
</table>
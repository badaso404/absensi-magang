<table>
    
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
        
        
        <tr><th colspan="5"></th></tr>
        
        
        <tr>
            <th colspan="2">Nama</th>
            <th colspan="3">: <?php echo e($user->name); ?></th>
        </tr>
        <tr>
            <th colspan="2">Bidang / Suku Dinas</th>
            <th colspan="3">: <?php echo e($user->bidang_suku_dinas ?? 'Aplikasi Siber dan Statistik / Kominfotik Jakarta Barat'); ?></th>
        </tr>
        <tr>
            <th colspan="2">Pekerjaan</th>
            <th colspan="3">: <?php echo e($user->pekerjaan ?? 'Technical Support Keamanan Informasi'); ?></th>
        </tr>
        <tr>
            <th colspan="2">Bulan</th>
            <th colspan="3">: <?php echo e($bulan ?: 'Semua Data'); ?></th>
        </tr>
        
        
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="5"></th></tr>
        
        
        <tr>
            <th>NO</th>
            <th>HARI/TANGGAL</th>
            <th>DETAIL KEGIATAN</th>
            <th>LOKASI</th>
            <th>DOKUMENTASI</th>
        </tr>
    </thead>
    
    
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($index + 1); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D/M/YYYY')); ?></td>
            <td><?php echo e($item->detail_kegiatan); ?></td>
            <td><?php echo e($item->lokasi); ?></td>
            <td></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="5" style="text-align: center;">Tidak ada data laporan kegiatan</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/laporan-kegiatan/export.blade.php ENDPATH**/ ?>
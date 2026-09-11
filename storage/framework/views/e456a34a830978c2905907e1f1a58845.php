<?php $__env->startSection('content'); ?>
<div class="page-content">
    
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white-transparent mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-calendar-check fa-lg text-white"></i>
            </div>
            <div>
                <h5 class="h3 font-weight-400 mb-0 text-white">Selamat pagi, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h5>
                <span class="text-sm text-white opacity-8">Ngerjain tugas apa hari ini kita?</span>
            </div>
        </div>
    </div>

    <div class="row">
        
        <?php if(session('alert')): ?>
        <div class="col-12">
            <?php if (isset($component)) { $__componentOriginalb5e767ad160784309dfcad41e788743b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e767ad160784309dfcad41e788743b = $attributes; } ?>
<?php $component = App\View\Components\Alert::resolve(['title' => session('alert.title'),'type' => session('alert.type'),'messages' => [session('alert.message')],'display' => 'block'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Alert::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e767ad160784309dfcad41e788743b)): ?>
<?php $attributes = $__attributesOriginalb5e767ad160784309dfcad41e788743b; ?>
<?php unset($__attributesOriginalb5e767ad160784309dfcad41e788743b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e767ad160784309dfcad41e788743b)): ?>
<?php $component = $__componentOriginalb5e767ad160784309dfcad41e788743b; ?>
<?php unset($__componentOriginalb5e767ad160784309dfcad41e788743b); ?>
<?php endif; ?>
        </div>
        <?php endif; ?>

        
        <div class="col-xl-8 col-md-6">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bolder">Grafik Absensi <small class="text-muted">(<?php echo e(\Carbon\Carbon::now()->isoFormat('MMMM')); ?>)</small></h6>
                </div>
                <div class="card-body">
                    <?php if(!empty($absensiChart['x'])): ?>
                        <div id="absensi-chart" data-height="400"></div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted opacity-3"></i>
                            <p class="mt-3 text-muted">Belum ada data grafik bulan ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6">
            <div class="card card-fluid">
                <div class="card-header text-center">
                    <h6 class="mb-0 font-weight-bolder"><?php echo e(\Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y')); ?></h6>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5 class="mb-4 text-dark font-weight-bold">Statistik Kehadiran</h5>
                    
                    
                    <div class="progress-circle progress-lg mx-auto" 
                         id="progress-attendance" 
                         data-progress="100" 
                         data-text="<?php echo e($kalkulasiKeterlambatan['total_keterlambatan'] ?? '00:00'); ?>" 
                         data-textclass="h4" 
                         data-color="info">
                    </div>
                    
                    <div class="my-4 text-left px-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-sm"><i class="fas fa-clock text-primary mr-2"></i>Total Telat:</span>
                            <span class="badge badge-soft-danger font-weight-bold"><?php echo e($kalkulasiKeterlambatan['total_telat'] ?? 0); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-sm"><i class="fas fa-running text-success mr-2"></i>Pulang Cepat:</span>
                            <span class="badge badge-soft-danger font-weight-bold"><?php echo e($kalkulasiKeterlambatan['total_pulang_cepat'] ?? 0); ?></span>
                        </div>
                    </div>

                    
                    <?php if($toggleAbsenPagi == 'pagi'): ?>
                        <button type="button" id="btn-absen-pemicu" class="btn btn-block btn-primary mt-auto shadow-lg py-3">
                            <i class="fas fa-fingerprint mr-2"></i> Absen Pagi
                        </button>
                    <?php elseif($toggleAbsenPagi == 'sore'): ?>
                        <button type="button" onclick="ambilLokasiDanKirim(null)" class="btn btn-block btn-warning mt-auto shadow-lg py-3">
                            <i class="fas fa-sign-out-alt mr-2"></i> Absen Sore
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-block btn-success mt-auto py-3" disabled>
                            <i class="fas fa-check-double mr-2"></i> Absensi Selesai
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-xl-12">
            <div class="card card-fluid">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white">Data Absensi <small class="opacity-7">(7 hari terakhir)</small></h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Mode</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark"><?php echo e(\Carbon\Carbon::parse($a->created_at)->isoFormat('dddd')); ?></div>
                                        <div class="text-xs text-muted"><?php echo e(\Carbon\Carbon::parse($a->created_at)->isoFormat('D MMM Y')); ?></div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bolder text-<?php echo e($a->checked_in_status?->color() ?? 'secondary'); ?>">
                                            <?php echo e($a->checked_in_status?->text() ?? '-'); ?>

                                        </div>
                                        <div class="text-xs text-muted">
                                            <?php echo e($a->checked_in_at ? \Carbon\Carbon::parse($a->checked_in_at)->format('H:i') : '-'); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bolder text-<?php echo e($a->checked_out_status?->color() ?? 'secondary'); ?>">
                                            <?php echo e($a->checked_out_status?->text() ?? '-'); ?>

                                        </div>
                                        <div class="text-xs text-muted">
                                            <?php echo e($a->checked_out_at ? \Carbon\Carbon::parse($a->checked_out_at)->format('H:i') : '-'); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-<?php echo e($a->status?->color() ?? 'secondary'); ?>">
                                            <?php echo e($a->status?->text() ?? 'Proses'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($a->wfhwfo): ?>
                                            <span class="badge badge-soft-<?php echo e($a->wfhwfo == 'WFH' ? 'success' : 'primary'); ?>">
                                                <?php echo e($a->wfhwfo); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($a->latitude) && !empty($a->longitude)): ?>
                                            <button class="btn btn-xs btn-outline-primary btn-map" 
                                                    data-lat="<?php echo e($a->latitude); ?>" 
                                                    data-lon="<?php echo e($a->longitude); ?>" 
                                                    data-lokasi="<?php echo e($a->lokasi_user ?? 'Lokasi Tersimpan'); ?>">
                                                <i class="fas fa-map-marker-alt"></i> Peta
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-muted italic">No GPS</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $dayName = \Carbon\Carbon::parse($a->created_at)->isoFormat('dddd'); ?>
                                        <span class="badge badge-<?php echo e(in_array($dayName, ['Sabtu', 'Minggu']) ? 'secondary' : 'info'); ?>">
                                            <?php echo e(in_array($dayName, ['Sabtu', 'Minggu']) ? 'Libur' : 'Kerja'); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="7" class="text-center text-muted py-5">Belum ada data absensi</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="wfhwfoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <i class="fas fa-street-view fa-3x text-primary"></i>
                </div>
                <h5 class="font-weight-bold">Mode Kerja</h5>
                <p class="text-sm text-muted">Pilih kehadiran hari ini:</p>
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-block btn-success btn-wfhwfo mb-2" data-value="WFH">Work From Home</button>
                    <button type="button" class="btn btn-block btn-primary btn-wfhwfo mb-2" data-value="WFO">Work From Office</button>
                    <button type="button" class="btn btn-block btn-warning btn-wfhwfo" data-value="DINAS LUAR">Dinas Luar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="map-location-text">Lokasi Absen</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="google-map-iframe" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <div class="modal-footer justify-content-between">
                <span class="text-muted text-sm" id="map-coordinates"></span>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // 1. Grafik Absensi
    const chartElement = document.querySelector("#absensi-chart");
    if (chartElement) {
        const dataMasuk = <?php echo json_encode($absensiChart['y'] ?? []); ?>;
        const dataPulang = <?php echo json_encode($absensiChart['z'] ?? []); ?>;
        const dataTanggal = <?php echo json_encode($absensiChart['x'] ?? []); ?>;

        const options = {
            chart: { type: 'line', height: 400, toolbar: { show: false }, fontFamily: 'inherit' },
            stroke: { width: 3, curve: 'smooth' },
            colors: ['#5e72e4', '#2dce89'],
            series: [
                { name: "Masuk", data: dataMasuk },
                { name: "Pulang", data: dataPulang }
            ],
            xaxis: { type: 'datetime', categories: dataTanggal, labels: { format: 'dd MMM' } },
            yaxis: { labels: { formatter: (val) => { let hours = Math.floor(val / 3600); return (hours < 10 ? '0' + hours : hours) + ":00"; } } },
            tooltip: { x: { format: 'dd MMMM yyyy' } }
        };
        new ApexCharts(chartElement, options).render();
    }

    // 2. Tombol Absen Pagi (Buka Modal)
    $('#btn-absen-pemicu').on('click', function() {
        $('#wfhwfoModal').modal('show');
    });

    // 3. Klik Pilihan Mode di Modal (Hanya Pagi)
    $('.btn-wfhwfo').on('click', function() {
        const mode = $(this).data('value');
        $('#wfhwfoModal').modal('hide');
        ambilLokasiDanKirim(mode);
    });

    // 4. Map Modal Handler
    $('.btn-map').on('click', function() {
        const lat = $(this).data('lat');
        const lon = $(this).data('lon');
        const loc = $(this).data('lokasi');
        const mapUrl = `https://maps.google.com/maps?q=${lat},${lon}&hl=id&z=15&output=embed`;
        $('#google-map-iframe').attr('src', mapUrl);
        $('#map-location-text').text(loc || 'Lokasi Absensi');
        $('#map-coordinates').text(`Koordinat: ${lat}, ${lon}`);
        $('#mapModal').modal('show');
    });
});

// --- FUNGSI GLOBAL ---

function ambilLokasiDanKirim(mode = null) {
    // FIX: Validasi Waktu Sore (Hanya boleh jika jam >= 12)
    const sekarang = new Date();
    const jam = sekarang.getHours();

    if (mode === null && jam < 12) {
        Swal.fire({
            icon: 'info',
            title: 'Belum Waktunya Pulang',
            text: 'Absen sore hanya dapat dilakukan setelah pukul 12:00 WIB.',
            confirmButtonColor: '#fb6340'
        });
        return;
    }

    Swal.fire({
        title: 'Mencari Lokasi...',
        text: 'Sedang mengambil koordinat GPS Anda...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    if (!navigator.geolocation) {
        tolakTanpaLokasi(mode, 'Browser Anda tidak mendukung fitur lokasi.', false);
        return;
    }

    // Geolocation API hanya tersedia di secure context (HTTPS atau localhost).
    // Di HTTP biasa browser menolak dengan kode PERMISSION_DENIED tanpa pernah
    // memunculkan popup izin, sehingga pesan "aktifkan izin lokasi" menyesatkan
    // — masalahnya di alamat situs, bukan di setelan browser user.
    if (window.isSecureContext === false) {
        tolakTanpaLokasi(
            mode,
            'Situs ini diakses lewat koneksi tidak aman (HTTP), sehingga browser ' +
            'memblokir akses lokasi. Buka situs ini lewat <b>HTTPS</b> untuk bisa absen.',
            false
        );
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            kirimDataAbsen(mode, position.coords.latitude, position.coords.longitude);
        },
        function(error) {
            console.warn("GPS Error: " + error.message);
            tolakTanpaLokasi(mode, pesanGpsError(error), true);
        },
        // Timeout dilonggarkan jadi 10 detik: sekarang lokasi wajib, jadi GPS
        // yang cuma lambat mengunci tidak boleh langsung dianggap gagal.
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function pesanGpsError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            return 'Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser Anda.';
        case error.POSITION_UNAVAILABLE:
            return 'Lokasi tidak dapat dideteksi. Pastikan GPS perangkat Anda aktif.';
        case error.TIMEOUT:
            return 'Pencarian lokasi memakan waktu terlalu lama.';
        default:
            return 'Lokasi tidak dapat diambil.';
    }
}

/**
 * Absen tanpa koordinat ditolak sepenuhnya.
 *
 * Ini hanya lapis UX supaya pesannya jelas; penolakan yang sebenarnya ada di
 * AbsensiController, yang mewajibkan latitude/longitude. Jadi melewati dialog
 * ini lewat DevTools tetap tidak menghasilkan absensi.
 */
function tolakTanpaLokasi(mode, alasan, bolehCobaLagi) {
    Swal.fire({
        icon: 'error',
        title: 'Absen Tidak Dapat Dilakukan',
        html: alasan + '<br><br>Absensi <b>wajib</b> menyertakan lokasi. ' +
              'Aktifkan GPS dan izinkan akses lokasi, lalu coba lagi.',
        showCancelButton: bolehCobaLagi,
        confirmButtonText: bolehCobaLagi ? 'Coba Lagi' : 'Mengerti',
        cancelButtonText: 'Tutup',
        confirmButtonColor: '#fb6340',
        cancelButtonColor: '#8898aa',
        reverseButtons: true,
        allowOutsideClick: false
    }).then((hasil) => {
        if (bolehCobaLagi && hasil.isConfirmed) {
            ambilLokasiDanKirim(mode);
        }
    });
}

function kirimDataAbsen(mode, lat, long) {
    const tipeAbsen = "<?php echo e($toggleAbsenPagi == 'pagi' ? 'pagi' : 'sore'); ?>";
    const urlAbsen = "<?php echo e(route('absen', ':type')); ?>".replace(':type', tipeAbsen);

    // Fungsi ini hanya dipanggil setelah koordinat benar-benar terbaca.
    $.ajax({
        url: urlAbsen,
        type: 'POST',
        data: {
            _token: "<?php echo e(csrf_token()); ?>",
            wfhwfo: mode,
            latitude: lat,
            longitude: long
        },
        success: function(res) {
            if (res.success) {
                Swal.fire('Berhasil!', res.message, 'success')
                    .then(() => { window.location.reload(); });
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        },
        error: function(xhr) {
            let pesan = 'Terjadi kesalahan sistem';
            if(xhr.responseJSON && xhr.responseJSON.message) pesan = xhr.responseJSON.message;
            Swal.fire('Error', pesan, 'error');
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/beranda/index.blade.php ENDPATH**/ ?>
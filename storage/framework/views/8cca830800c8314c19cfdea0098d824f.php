<?php $__env->startSection('content'); ?>
<div class="page-content">
    <div class="page-title mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-azure text-white mr-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                <i class="fas fa-user-check fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold text-white">Riwayat Absensi</h4>
                <small class="text-light">Rekaman kehadiran Anda — ringkas dan mudah dibaca</small>
            </div>
        </div>
    </div>

    <!-- Quick summary -->
    <div class="row mb-3">
        <div class="col-sm-4 mb-2">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrap mr-3 bg-soft-azure">
                        <i class="fas fa-calendar-alt text-azure"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Entri</div>
                        <div class="h5 font-weight-bold"><?php echo e($absensi->count()); ?></div>
                        <small class="text-muted">Seluruh data pada daftar</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-2">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrap mr-3 bg-soft-green">
                        <i class="fas fa-sign-in-alt text-green"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Masuk Tepat Waktu</div>
                        <div class="h5 font-weight-bold">
                            <?php echo e($absensi->where('checked_in_status', 'on_time')->count()); ?>

                        </div>
                        <small class="text-muted">Masuk sesuai jadwal</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-2">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrap mr-3 bg-soft-red">
                        <i class="fas fa-exclamation-triangle text-red"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Izin / Tidak Hadir</div>
                        <div class="h5 font-weight-bold">
                            <?php echo e($absensi->where('status', '!=', 'present')->count()); ?>

                        </div>
                        <small class="text-muted">Tidak hadir atau catatan khusus</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card card-fluid shadow-sm">
         <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                  <h6 class="mb-0 font-weight-bold text-light"><i class="fas fa-list mr-2"></i> Daftar Absensi</h6>
            <small class="text-light-50"><?php echo e($absensi->count()); ?> entri</small>
        </div>

        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0 table-absensi">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Mode</th>
                            <th >Lokasi</th>
                            <th>Detail absen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        
                        <tr class="cursor-pointer" data-toggle="tooltip" title="Klik untuk detail"
                            data-note="<?php echo e($a->description ?? ''); ?>"
                            data-lokasi="<?php echo e($a->lokasi_user ?? ''); ?>"
                            data-checked-in="<?php echo e($a->checked_in_at?->format('H:i:s') ?? ''); ?>"
                            data-checked-out="<?php echo e($a->checked_out_at?->format('H:i:s') ?? ''); ?>">
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-sm font-weight-600"><?php echo e(\Carbon\Carbon::parse($a->created_at)->isoFormat('dddd')); ?></span>
                                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($a->created_at)->isoFormat('D MMMM Y')); ?></small>
                                </div>
                            </td>

                            <td>
                                <div>
                                    <div class="font-weight-bold text-<?php echo e(optional($a->checked_in_status)->color() ?? 'muted'); ?>">
                                        <?php echo e(optional($a->checked_in_status)->text() ?? '-'); ?>

                                    </div>
                                    <small class="text-muted"><?php echo e($a->checked_in_at ? \Carbon\Carbon::parse($a->checked_in_at)->isoFormat('HH:mm:ss') : '-'); ?></small>
                                </div>
                            </td>

                            <td>
                                <div>
                                    <div class="font-weight-bold text-<?php echo e(optional($a->checked_out_status)->color() ?? 'muted'); ?>">
                                        <?php echo e(optional($a->checked_out_status)->text() ?? '-'); ?>

                                    </div>
                                    <small class="text-muted"><?php echo e($a->checked_out_at ? \Carbon\Carbon::parse($a->checked_out_at)->isoFormat('HH:mm:ss') : '-'); ?></small>
                                </div>
                            </td>

                            <td>
                                <span class="badge badge-pill badge-<?php echo e(optional($a->status)->color() ?? 'secondary'); ?>">
                                    <i class="fas <?php echo e(optional($a->status)->icon() ?? 'fa-info-circle'); ?> mr-1"></i>
                                    <?php echo e(optional($a->status)->text() ?? '-'); ?>

                                </span>
                                <?php if($a->note): ?>
                                    <div><small class="text-muted d-block d-md-none"><?php echo e(\Illuminate\Support\Str::limit($a->note,60)); ?></small></div>
                                <?php endif; ?>
                            </td>

                            <td class="d-none d-md-table-cell">
                                <?php if($a->wfhwfo): ?>
                                    <span class="badge badge-<?php echo e($a->wfhwfo == 'WFH' ? 'success' : 'primary'); ?>">
                                        <?php echo e($a->wfhwfo); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td>

                               <?php if($a->lokasi_user || ($a->latitude && $a->longitude)): ?>
                                        <div class="d-flex flex-column">
                                            <div class="mb-2">
                                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                <small class="text-muted">
                                                    <?php echo e($a->lokasi_user ?? 'Lokasi tidak tersedia'); ?>

                                                </small>
                                            </div>
                                            <?php if($a->latitude && $a->longitude): ?>
                                                <div class="d-flex align-items-center">
                                                    <button 
                                                        class="btn btn-sm btn-outline-primary btn-map" 
                                                        data-lat="<?php echo e($a->latitude); ?>" 
                                                        data-lon="<?php echo e($a->longitude); ?>"
                                                        data-lokasi="<?php echo e($a->lokasi_user ?? 'Lokasi Absen'); ?>"
                                                        data-toggle="tooltip"
                                                        title="Lihat di Google Maps">
                                                        <i class="fas fa-map-marked-alt mr-1"></i>
                                                        Lihat Peta
                                                    </button>
                                                    <small class="text-muted ml-2">
                                                        <i class="fas fa-globe"></i> 
                                                        <?php echo e(number_format($a->latitude, 6)); ?>,<br> <?php echo e(number_format($a->longitude, 6)); ?>

                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">
                                            <i class="fas fa-map-marker-alt-slash"></i> 
                                            Tidak ada data lokasi
                                        </span>
                                    <?php endif; ?>
                            </td>
                            <td>
                                 <div class="d-flex align-items-center">
                                                    <button 
                                                        class="btn btn-sm btn-outline-info btn-detail" 
                                                      >
                                                        <i class="fas fa-eye"></i>
                                                        Detail Absensi
                                                    </button>
                                                
                                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <div>Belum ada data absensi</div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal detail singkat -->
<div class="modal fade" id="absenDetailModal" tabindex="-1" role="dialog" aria-labelledby="absenDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-azure text-white">
                <h5 class="modal-title" id="absenDetailLabel"><i class="fas fa-info-circle mr-2"></i>Detail Absensi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1"><strong>Jam Masuk:</strong> <span id="modal-checked-in" class="text-muted">-</span></p>
                <p class="mb-1"><strong>Jam Pulang:</strong> <span id="modal-checked-out" class="text-muted">-</span></p>
                <p class="mb-1"><strong>Lokasi:</strong> <span id="modal-lokasi" class="text-muted">-</span></p>
                <hr>
                <p class="mb-0"><strong>Catatan:</strong></p>
                <p class="text-muted" id="modal-note">-</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-light" id="mapModalLabel">
                    <i class="fas fa-map-marker-alt mr-2 text-light"></i>Lokasi Absen
                </h5>
              <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>

            </div>
            <div class="modal-body p-0">
                <div id="map-container" style="width: 100%; height: 400px;">
                    <iframe 
                        id="google-map-iframe" 
                        width="100%" 
                        height="400" 
                        frameborder="0" 
                        style="border:0" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-3 bg-light">
                    <p class="mb-1"><strong>Alamat:</strong></p>
                    <p class="mb-0 text-muted" id="map-location-text"></p>
                    <p class="mb-0 mt-2">
                        <strong>Koordinat:</strong> 
                        <span class="text-muted" id="map-coordinates"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="open-google-maps" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt mr-1"></i>Buka di Google Maps
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root{
        --azure: #2b9bd7;
        --green: #2dce89;
        --red: #f5365c;
        --card-radius: 8px;
    }

    .bg-azure {
        background: linear-gradient(90deg, rgba(43,155,215,1) 0%, rgba(37,118,180,0.95) 100%);
    }

    .summary-card {
        border-radius: var(--card-radius);
        overflow: hidden;
        border: 1px solid rgba(30,30,30,0.04);
    }

    .icon-wrap {
        width:48px;
        height:48px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:8px;
        font-size:18px;
    }

    .bg-soft-azure { background: rgba(43,155,215,0.08); }
    .bg-soft-green { background: rgba(45,206,137,0.08); }
    .bg-soft-red { background: rgba(245,54,92,0.06); }

    .text-azure { color: var(--azure); }
    .text-green { color: var(--green); }
    .text-red { color: var(--red); }

    .table-absensi thead th {
        background: rgba(0,0,0,0.03);
        border-bottom: none;
        font-weight:700;
    }

    .table-absensi tbody tr {
        transition: transform .14s ease, box-shadow .14s ease;
        background: transparent;
    }

    .table-absensi tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(40,40,80,0.06);
        background: #fff;
    }

    .badge-pill { border-radius: 999px; padding: .35rem .6rem; font-size: .85rem; }

    .cursor-pointer { cursor: pointer; }

    @media (max-width:767px){
        .d-none.d-md-table-cell { display: none !important; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function(){
    if($.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Klik baris -> buka modal dengan detail minimal
    $('.btn-detail').on('click', function(){
        // Atribut data-* menempel di <tr>, bukan di tombolnya. Versi lama
        // membacanya dari $(this) (tombol), jadi semua nilai undefined dan
        // modal selalu menampilkan "-" meski datanya ada di baris tabel.
        var $baris = $(this).closest('tr');

        $('#modal-note').text($baris.data('note') || '-');
        $('#modal-lokasi').text($baris.data('lokasi') || '-');
        $('#modal-checked-in').text($baris.data('checked-in') || '-');
        $('#modal-checked-out').text($baris.data('checked-out') || '-');

        $('#absenDetailModal').modal('show');
    });

    $('.btn-map').on('click', function() {
        const lat = $(this).data('lat');
        const lon = $(this).data('lon');
        const lokasi = $(this).data('lokasi');

        // Set iframe Google Maps
        const mapUrl = `https://www.google.com/maps?q=${lat},${lon}&hl=id&z=16&output=embed`;
        $('#google-map-iframe').attr('src', mapUrl);

        // Set text informasi
        $('#map-location-text').text(lokasi);
        $('#map-coordinates').text(`${lat}, ${lon}`);

        // Set link untuk buka di Google Maps
        const externalMapUrl = `https://www.google.com/maps?q=${lat},${lon}&hl=id`;
        $('#open-google-maps').attr('href', externalMapUrl);

        // Tampilkan modal
        $('#mapModal').modal('show');
    });

    // Clear iframe ketika modal ditutup untuk performa
    $('#mapModal').on('hidden.bs.modal', function() {
        $('#google-map-iframe').attr('src', '');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/absensi/index.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="Purpose Application UI is the following chapter we've finished in order to create a complete and robust solution next to the already known Purpose Website UI.">
    <meta name="author" content="Webpixels">
    <title>Magang Kominfotik - <?php echo e($mainMenu ?? 'Jakarta Barat'); ?></title>
    <link rel="icon" href="#" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/@fortawesome/fontawesome-pro/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/flatpickr/dist/flatpickr.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/select2/dist/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/purpose.css')); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- daterangepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<style>
    .tahun-scroll {
  overflow-x: auto;
  overflow-y: hidden;
  -ms-overflow-style: none;  /* IE lama */
  scrollbar-width: none;     /* Firefox */
}
.tahun-scroll::-webkit-scrollbar {
  display: none;             /* Chrome, Safari */
}

/* --- Footer menempel di bawah --------------------------------------------
   Sebelumnya footer mengikuti tinggi konten, jadi saat tabel kosong ia naik
   dan menggantung di tengah layar. Main-content dijadikan kolom flex setinggi
   minimal satu layar, lalu footer didorong ke dasar dengan margin-top:auto. */
.application .main-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.application .main-content > .footer {
    margin-top: auto;
}

/* Header kartu berisi judul + beberapa tombol jadi berdesakan di layar kecil:
   judulnya terpecah beberapa baris dan tombolnya menumpuk rapat. Dibiarkan
   membungkus dan diberi jarak. */
@media (max-width: 767.98px) {
    .card-header.d-flex {
        flex-wrap: wrap;
        gap: .5rem;
    }
    .card-header.d-flex > * { min-width: 0; }
}

/* --- Latar header dashboard ----------------------------------------------
   Versi lama memakai satu warna ungu rata dengan opasitas .6 di atas foto
   gedung: fotonya jadi keruh, warnanya datar, dan potongan bawahnya keras
   karena hanya sudut kiri yang dibulatkan. Sekarang gradient diagonal dengan
   kedalaman, kedua sudut dibulatkan, dan tepi bawah diberi bayangan lembut. */
.application-offset .container-application:before {
    height: 420px;
    background-image:
        linear-gradient(135deg,
            rgba(88, 36, 226, 0.94) 0%,
            rgba(124, 58, 237, 0.86) 42%,
            rgba(58, 24, 152, 0.94) 100%),
        url("<?php echo e(asset('images/bg-dashboard.jpeg')); ?>?v=2");
    background-size: cover;
    /* Gedung berada di tengah foto; 45% memotong sebagian langit kosong di
       atas supaya banner yang pendek-lebar tetap memperlihatkan gedungnya. */
    background-position: center 45%;
    border-bottom-left-radius: 2rem;
    border-bottom-right-radius: 2rem;
    box-shadow: 0 18px 40px -18px rgba(58, 24, 152, 0.55);
}

/* --- Dropdown (Select2) yang lebih ramah --------------------------------
   Dropdown .js-select diubah dari <select> bawaan OS menjadi Select2 yang
   bisa ditata: tombol terlihat seragam dengan input, daftarnya membulat,
   dan opsi terpilih/hover memakai aksen ungu tema aplikasi. */
.select2-container { width: 100% !important; }

.select2-container--default .select2-selection--single {
    height: 44px;
    display: flex;
    align-items: center;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 6px 28px 6px 12px;
    background-color: #fff;
    transition: border-color .15s ease, box-shadow .15s ease;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #4a5568;
    font-weight: 500;
    line-height: 1.4;
    padding: 0;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder { color: #a0aec0; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px; right: 10px; }
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #8898aa transparent transparent transparent;
}
.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #8898aa transparent;
}
.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, .15);
    outline: none;
}

.select2-dropdown {
    border: none;
    border-radius: 12px;
    box-shadow: 0 12px 32px rgba(50, 50, 93, .18), 0 4px 12px rgba(0, 0, 0, .08);
    overflow: hidden;
    padding: 6px;
    z-index: 2000;
}
.select2-container--default .select2-results__option {
    border-radius: 8px;
    padding: 10px 12px;
    font-size: .9rem;
    color: #4a5568;
    transition: background .12s ease;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: linear-gradient(135deg, #7c3aed, #5e72e4);
    color: #fff;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background: rgba(124, 58, 237, .1);
    color: #5e2ea8;
    font-weight: 600;
}
.select2-container--default .select2-results__option[aria-selected=true].select2-results__option--highlighted {
    background: linear-gradient(135deg, #7c3aed, #5e72e4);
    color: #fff;
}
.select2-results__options { max-height: 260px; }
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 10px;
    outline: none;
}
.select2-container--default .select2-search--dropdown .select2-search__field:focus { border-color: #7c3aed; }

/* --- Kartu ringkasan seragam (komponen x-stat-card) ---------------------
   Satu gaya untuk semua kartu angka ringkasan di aplikasi: ikon kotak
   membulat bergradien + label kapital kecil + angka tebal. */
.stat-card { border: none; border-radius: 14px; box-shadow: 0 2px 10px rgba(50, 50, 93, .06); }
.stat-card .card-body { padding: 1.1rem 1.25rem; }
.min-w-0 { min-width: 0; }
.stat-card__icon {
    width: 52px; height: 52px; flex: 0 0 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.25rem; box-shadow: 0 6px 14px -4px rgba(0, 0, 0, .25);
}
.stat-card__label { font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; color: #8898aa; font-weight: 700; }
.stat-card__value { font-size: 1.7rem; font-weight: 800; line-height: 1.15; color: #32325d; word-break: break-word; }
.stat-card__sub { font-size: .75rem; color: #8898aa; margin-top: .1rem; }
.stat-card__icon--primary   { background: linear-gradient(135deg, #7c3aed, #5e72e4); }
.stat-card__icon--success   { background: linear-gradient(135deg, #2dce89, #1aae6f); }
.stat-card__icon--danger    { background: linear-gradient(135deg, #f5365c, #f56036); }
.stat-card__icon--warning   { background: linear-gradient(135deg, #fb6340, #fbb140); }
.stat-card__icon--info      { background: linear-gradient(135deg, #11cdef, #1171ef); }
.stat-card__icon--secondary { background: linear-gradient(135deg, #adb5bd, #8898aa); }
    </style>

    
    <?php echo $__env->yieldPushContent('styles'); ?>

</head>

<body class="application application-offset">
    <div class="container-fluid container-application">
        <?php if(auth()->guard()->check()): ?>
            <?php echo $__env->make('layout.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <div class="main-content position-relative">
            <?php if(auth()->guard()->check()): ?>
                <?php echo $__env->make('layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->make('layout.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
                
                <?php if(auth()->user()->role_id != App\Enums\Role::Admin->value && auth()->user()->profilBelumLengkap()): ?>
                    <div class="page-content">
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-transparent">
                                    <div class="card-body p-3">
                                        <p class="text-white mb-0">Anda belum mengisi data yang diperlukan, lengkapi informasi akun anda <a href="<?php echo e(route('profil-edit')); ?>" class="text-white font-weight-bolder">di sini!</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
                <?php echo $__env->make('layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <?php if(auth()->guard()->guest()): ?>
                <?php echo $__env->yieldContent('login'); ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?php echo e(asset('assets/js/purpose.core.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/progressbar.js/dist/progressbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/apexcharts/dist/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/moment/min/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/fullcalendar/dist/fullcalendar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/flatpickr/dist/flatpickr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/select2/dist/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/purpose.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/demo.js')); ?>"></script>

    <!-- daterangepicker JS dan dependency -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<?php $__env->startPush('scripts'); ?>
<script>
$(function() {
    $('#date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            applyLabel: 'Pilih',
            format: 'DD/MM/YYYY'
        }
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Ubah setiap <select class="js-select"> jadi dropdown Select2 bergaya.
    // Kotak pencarian hanya muncul kalau opsinya banyak (>8), supaya di HP
    // keyboard tidak langsung nongol untuk pilihan singkat seperti bulan/unit.
    $('.js-select').each(function () {
        var $s = $(this);
        if ($s.data('select2')) return; // jangan init dua kali
        $s.select2({
            width: '100%',
            // Tanpa dropdownParent: daftar dilampirkan ke <body> sehingga tidak
            // terpotong kartu yang ber-overflow hidden.
            minimumResultsForSearch: $s.find('option').length > 8 ? 0 : Infinity,
            language: {
                noResults: function () { return 'Tidak ada hasil'; },
                searching: function () { return 'Mencari…'; }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/layout/app.blade.php ENDPATH**/ ?>
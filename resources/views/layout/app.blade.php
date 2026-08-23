<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Purpose Application UI is the following chapter we've finished in order to create a complete and robust solution next to the already known Purpose Website UI.">
    <meta name="author" content="Webpixels">
    <title>Magang Kominfotik - {{ $mainMenu ?? 'Jakarta Barat'  }}</title>
    <link rel="icon" href="#" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-pro/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/purpose.css') }}">
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
        url("/images/bg-dashboard.jpeg");
    background-size: cover;
    background-position: center 30%;
    border-bottom-left-radius: 2rem;
    border-bottom-right-radius: 2rem;
    box-shadow: 0 18px 40px -18px rgba(58, 24, 152, 0.55);
}
    </style>

    {{-- Tanpa stack ini, setiap @push('styles') di view dibuang diam-diam:
         CSS halaman login, absensi, dan manajemen user tidak pernah termuat. --}}
    @stack('styles')

</head>

<body class="application application-offset">
    <div class="container-fluid container-application">
        @auth
            @include('layout.navbar')
        @endauth
        <div class="main-content position-relative">
            @auth
                @include('layout.header')
                @include('layout.search')
            
                @if(collect((array) auth()->user())->except(['remember_token', 'created_at', 'updated_at'])->contains(null))
                    <div class="page-content">
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-transparent">
                                    <div class="card-body p-3">
                                        <p class="text-white mb-0">Anda belum mengisi data yang diperlukan, lengkapi informasi akun anda <a href="{{ route('profil-edit') }}" class="text-white font-weight-bolder">di sini!</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
                @include('layout.footer')
            @endauth

            @guest
                @yield('login')
            @endguest
        </div>
    </div>
    <script src="{{ asset('assets/js/purpose.core.js') }}"></script>
    <script src="{{ asset('assets/libs/progressbar.js/dist/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/purpose.js') }}"></script>
    <script src="{{ asset('assets/js/demo.js') }}"></script>

    <!-- daterangepicker JS dan dependency -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@push('scripts')
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
</script>
@endpush
    @stack('scripts')
</body>

</html>
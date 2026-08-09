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
    </style>

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
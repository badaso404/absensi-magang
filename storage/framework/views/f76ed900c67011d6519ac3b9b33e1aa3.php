<?php $__env->startSection('login'); ?>
<div class="login-split">

    
    <div class="login-brand">
        <div class="login-brand__inner">
            <img src="<?php echo e(asset('images/logo kominfotik.png')); ?>" alt="Logo Kominfotik Jakarta Barat"
                 class="login-brand__logo">
            <h1 class="login-brand__title">Sistem Magang</h1>
            <p class="login-brand__subtitle">Suku Dinas Kominfotik<br>Kota Administrasi Jakarta Barat</p>

            <ul class="login-brand__points">
                <li><i class="fa fa-map-marker-alt"></i> Absensi harian berbasis lokasi</li>
                <li><i class="fa fa-calendar-check"></i> Laporan kegiatan harian</li>
                <li><i class="fa fa-user-friends"></i> Terhubung dengan rekan satu unit</li>
            </ul>
        </div>
        <p class="login-brand__footer">&copy; 2026 by TomGan. All rights reserved.</p>
    </div>

    
    <div class="login-form-wrap">
        <div class="login-form">
            <img src="<?php echo e(asset('images/logo kominfotik.png')); ?>" alt="Logo Kominfotik"
                 class="login-form__logo-mobile">

            <h2 class="login-form__title">Selamat Datang</h2>
            <p class="login-form__lead">Masuk ke akun Anda untuk melanjutkan.</p>

            <form method="post" action="<?php echo e(route('login-attempt')); ?>" id="login-form">
                <?php if (isset($component)) { $__componentOriginalb5e767ad160784309dfcad41e788743b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e767ad160784309dfcad41e788743b = $attributes; } ?>
<?php $component = App\View\Components\Alert::resolve(['title' => 'Login Gagal','type' => 'danger','messages' => [],'display' => 'none'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-control-label" for="input-email">Email</label>
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" id="input-email"
                               placeholder="nama@email.com" autocomplete="username" autofocus required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-control-label" for="input-password">Password</label>
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" id="input-password"
                               placeholder="Masukkan password" autocomplete="current-password" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <a href="#" data-toggle="password-text" data-target="#input-password"
                                   aria-label="Tampilkan password">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-control-label" for="input-captcha">Kode Captcha</label>
                    <div class="login-captcha">
                        <img src="<?php echo e(captcha_src()); ?>" alt="Kode captcha" id="img-captcha"
                             class="login-captcha__img">
                        <input type="text" name="captcha" class="form-control" id="input-captcha"
                               placeholder="Ketik kode" autocomplete="off" required>
                        <button type="button" id="button-refresh-captcha"
                                class="btn btn-outline-secondary login-captcha__refresh"
                                title="Ganti kode captcha" aria-label="Ganti kode captcha">
                            <i class="fa fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btn-login" class="btn btn-primary btn-block login-submit">
                    <span class="btn-label">Masuk</span>
                    <i class="fa fa-long-arrow-alt-right ml-2"></i>
                </button>
            </form>

            <p class="login-form__help">
                Lupa password atau kendala akun? Hubungi admin Kominfotik.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Halaman login tetap dirender di dalam shell dashboard, yang punya
       max-width 1420px dan banner ungu setinggi 430px (.application-offset
       .container-application:before). Dua-duanya menembus di balik layout ini
       dan membuatnya tampak saling menimpa. position:fixed melepaskannya dari
       shell tersebut sepenuhnya. */
    .login-split {
        position: fixed;
        inset: 0;
        z-index: 10;
        display: flex;
        overflow-y: auto;
        background: #f8f9fe;
    }

    /* Banner dan latar shell disembunyikan supaya tidak terlihat saat halaman
       dipaksa scroll di layar pendek. */
    .application-offset .container-application:before { display: none; }

    /* --- Panel branding --- */
    .login-brand {
        position: relative;
        flex: 0 0 45%;
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 3rem;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(94, 44, 237, .84), rgba(45, 20, 120, .90)),
            url('<?php echo e(asset('images/bg-dashboard.jpeg')); ?>?v=2') center/cover no-repeat;
    }

    .login-brand__inner { margin-top: auto; margin-bottom: auto; }

    /* Logonya berwarna gelap di atas latar terang. brightness(0) invert(1)
       membuat SELURUH kotaknya jadi putih polos, bukan cuma tulisannya —
       jadi logo diletakkan di atas kartu putih agar tetap terbaca di panel ungu. */
    .login-brand__logo {
        display: block;
        width: 210px;
        max-width: 75%;
        padding: .85rem 1.1rem;
        margin-bottom: 2rem;
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .18);
    }

    .login-brand__title {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: .75rem;
        color: #fff;
    }

    .login-brand__subtitle {
        font-size: 1.0625rem;
        line-height: 1.6;
        opacity: .85;
        margin-bottom: 2.5rem;
    }

    .login-brand__points {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .login-brand__points li {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .5rem 0;
        font-size: .9375rem;
        opacity: .9;
    }

    .login-brand__points i {
        flex: 0 0 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, .15);
        font-size: .875rem;
    }

    .login-brand__footer {
        margin: 0;
        font-size: .8125rem;
        opacity: .6;
    }

    /* --- Panel form --- */
    .login-form-wrap {
        flex: 1 1 auto;
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        background: #f8f9fe;
    }

    .login-form {
        width: 100%;
        max-width: 400px;
    }

    .login-form__logo-mobile { display: none; }

    .login-form__title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: .35rem;
    }

    .login-form__lead {
        color: #8898aa;
        margin-bottom: 2rem;
    }

    .login-form .form-control-label {
        font-weight: 600;
        font-size: .8125rem;
    }

    /* Tinggi disamakan untuk SEMUA bagian input-group. Kalau hanya
       .form-control yang ditinggikan, ikon prepend/append tetap setinggi
       aslinya dan garis pembungkusnya menyembul di bawah field. */
    .login-form .form-control,
    .login-form .input-group,
    .login-form .input-group-prepend,
    .login-form .input-group-append,
    .login-form .input-group-text {
        height: calc(1.5em + 1.25rem + 2px);
    }

    .login-form .input-group-text {
        display: flex;
        align-items: center;
    }

    /* Captcha: gambar, input, dan tombol muat sebaris tanpa saling menindih. */
    .login-captcha {
        display: flex;
        align-items: stretch;
        gap: .5rem;
    }

    .login-captcha__img {
        height: calc(1.5em + 1.25rem + 2px);
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        background: #fff;
        flex: 0 0 auto;
        max-width: 45%;
        object-fit: contain;
    }

    .login-captcha .form-control { flex: 1 1 auto; min-width: 0; }

    /* Lebar dipatok supaya tombol tidak ikut menyusut jadi gepeng saat
       gambar captcha melebar. */
    .login-captcha__refresh {
        flex: 0 0 46px;
        width: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: .375rem;
        border-color: #dee2e6;
        color: #5e72e4;
        background: #fff;
    }

    .login-captcha__refresh:hover {
        background: #5e72e4;
        border-color: #5e72e4;
        color: #fff;
    }

    .login-submit {
        height: calc(1.5em + 1.25rem + 2px);
        font-weight: 600;
        margin-top: .5rem;
    }

    .login-submit:disabled { opacity: .65; }

    .login-form__help {
        margin-top: 1.75rem;
        margin-bottom: 0;
        text-align: center;
        font-size: .8125rem;
        color: #8898aa;
    }

    /* --- Layar kecil: panel branding jadi header ringkas --- */
    @media (max-width: 991.98px) {
        .login-split { flex-direction: column; }

        /* min-height:100% dilepas: saat menumpuk, tiap panel setinggi layar
           penuh akan mendorong form jauh ke bawah lipatan. */
        .login-brand {
            flex: 0 0 auto;
            min-height: auto;
            padding: 2rem 1.5rem;
            text-align: center;
            align-items: center;
        }

        .login-brand__logo { width: 150px; margin-bottom: 1rem; }
        .login-brand__title { font-size: 1.75rem; }
        .login-brand__subtitle { margin-bottom: 0; font-size: .9375rem; }
        .login-brand__points,
        .login-brand__footer { display: none; }

        /* Di layar tinggi seperti tablet, memusatkan form secara vertikal
           menyisakan jurang kosong besar di bawah header. Ditempel ke atas. */
        .login-form-wrap {
            flex: 1 0 auto;
            min-height: auto;
            align-items: flex-start;
            padding: 2.5rem 1.25rem 3rem;
        }
    }

    @media (max-width: 575.98px) {
        .login-brand { display: none; }
        .login-form-wrap { min-height: 100%; background: #fff; }
        .login-form__logo-mobile {
            display: block;
            width: 160px;
            margin: 0 auto 1.5rem;
        }
        .login-form__title,
        .login-form__lead { text-align: center; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
    // Handler lama memanggil e.preventDefault() padahal parameter e tidak
    // pernah diterima — setiap klik melempar ReferenceError dan captcha tidak
    // pernah berganti. Elemennya juga diubah dari <a href="#"> jadi <button>
    // supaya tidak ada lompatan hash.
    $('#button-refresh-captcha').on('click', function (e) {
        e.preventDefault();
        refreshCaptcha();
    });

    function refreshCaptcha() {
        $.ajax({
            type: 'GET',
            url: '<?php echo e(route('refresh-captcha')); ?>',
            success: function (data) {
                $('#img-captcha').attr('src', data.captcha);
                $('#input-captcha').val('').focus();
            }
        });
    }

    function setLoading(loading) {
        var tombol = $('#btn-login');

        tombol.prop('disabled', loading);
        tombol.find('.btn-label').text(loading ? 'Memproses...' : 'Masuk');
    }

    $('#login-form').submit(function (e) {
        e.preventDefault();

        // Tanpa ini, klik ganda mengirim dua percobaan login dan menghabiskan
        // jatah throttle 5 percobaan per menit. Kode lama menargetkan
        // #submit-form yang tidak pernah ada di halaman.
        if ($('#btn-login').prop('disabled')) {
            return;
        }
        setLoading(true);

        var formData = new FormData(this);
        var alertContainer = $('#alert-container');
        var errorMessages = $('#error-messages');

        alertContainer.hide();
        errorMessages.empty();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }

                setLoading(false);
                tampilkanError(['Terjadi kesalahan, harap muat ulang halaman dan login kembali']);
            },
            error: function (xhr) {
                setLoading(false);
                refreshCaptcha();

                var pesan = [];

                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (_, daftar) {
                        pesan = pesan.concat(daftar);
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    // Mencakup 401 (kredensial salah) dan 403 (masa magang
                    // berakhir). Sebelumnya 403 jatuh ke pesan generik,
                    // sehingga user tidak tahu alasan sebenarnya.
                    pesan = [xhr.responseJSON.error];
                } else if (xhr.status === 429) {
                    pesan = ['Terlalu banyak percobaan login. Silakan tunggu satu menit.'];
                } else {
                    pesan = ['Silakan coba kembali.'];
                }

                tampilkanError(pesan);
            }
        });
    });

    function tampilkanError(daftar) {
        var errorMessages = $('#error-messages');

        errorMessages.empty();
        daftar.forEach(function (pesan) {
            errorMessages.append($('<p class="mb-0"></p>').text(pesan));
        });

        $('#alert-container').fadeIn();
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/auth/login.blade.php ENDPATH**/ ?>
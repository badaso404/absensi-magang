@extends('layout.app')

@section('login')
<div class="page-content">
    <div class="min-vh-100 py-5 d-flex align-items-center">
        <div class="w-100">
            <div class="row justify-content-center">
                <div class="col-sm-8 col-lg-4">
                    <div class="card shadow zindex-100 mb-0">
                        <div class="card-body px-md-5 py-5">
                            <div class="mb-5">
                                <h6 class="h3">Login</h6>
                                <p class="text-muted mb-0">Masuk ke akun anda untuk melanjutkan.</p>
                            </div>
                            <span class="clearfix"></span>
                            <form method="post" action="{{ route('login-attempt') }}" id="login-form">
                                <x-alert :title="'Validasi Gagal!'" :type="'danger'" :messages="[]" :display="'none'" />
                                @csrf
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        </div>
                                        <input type="email" name="email" class="form-control" id="input-email" placeholder="nama@email.com" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-control-label">Password</label>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                                        </div>
                                        <input type="password" name="password" class="form-control" id="input-password" placeholder="Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href="#" data-toggle="password-text" data-target="#input-password">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-control-label">Captcha</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <img src="{{ captcha_src() }}" alt="captcha" id="img-captcha" class="w-100">
                                        </div>
                                        <input type="text" name="captcha" class="form-control pl-3" id="input-captcha" placeholder="Captcha" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href="#" id="button-refresh-captcha">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                                        <span class="btn-inner--text">Login</span>
                                        <span class="btn-inner--icon"><i class="fa fa-long-arrow-alt-right"></i></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $('#button-refresh-captcha').click(function () {
        e.preventDefault();
        refreshCaptcha();
    });

    function refreshCaptcha() {
        $.ajax({
            type: 'GET',
            url: 'refresh-captcha',
            success: function (data) {
                $("#img-captcha").attr("src", data.captcha);
            }
        });
    }

    $('#login-form').submit(function(e) {
        e.preventDefault();

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
            success: function(response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    alertContainer.fadeIn();
                    errorMessages.append('<p class="mb-0">Terjadi kesalahan, harap muat ulang halaman dan login kembali</p>');
                    $('#submit-form').removeAttr('disabled');
                }
            },
            error: function(xhr) {
                console.log(xhr.status);
                refreshCaptcha();
                
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errors[key].forEach(function(error) {
                                errorMessages.append('<p class="mb-0">' + error + '</p>');
                            });
                        }
                    }
                } else if (xhr.status === 401) {
                    var error = xhr.responseJSON.error;
                    errorMessages.append('<p class="mb-0">' + error + '</p>');
                } else {
                    errorMessages.append('<p class="mb-0">Silahkan coba kembali</p>');
                }
                alertContainer.fadeIn();
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });
    });
</script>
@endpush
@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-7">
                <img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/2.jpg') }}" alt="loginpage">
            </div>
            <div class="col-xl-5 p-0">
                <div class="login-card">
                    <div>
                        <div style="width:121px">
                            <a class="logo" href="{{ route('auth.login') }}">
                                <img class="img-fluid for-light" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="loginpage">
                                <img class="img-fluid for-dark" src="{{ asset(\App\Helpers\Helper::getLogoDark()) }}" alt="loginpage">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" id="loginForm" method="POST">
                                <h4>Sign in to account</h4>
                                <p>Enter your email & password to login</p>
                                @csrf
                                <div class="form-group">
                                    <label for="email" class="col-form-label">{{ __('Email Address') }}</label>
                                    <input id="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" type="email" value="{{ old('email') }}" required
                                        autocomplete="email" autofocus placeholder="example@gmail.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                    <div class="form-input position-relative">
                                        <input id="password" class="form-control @error('password') is-invalid @enderror"
                                            type="password" name="password" required autocomplete="current-password"
                                            placeholder="*********">
                                        <div class="show-hide"><span class="show"></span></div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <div class="checkbox p-0">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="text-muted" for="remember">{{ __('Remember password') }}</label>
                                    </div>
                                    {{-- @if (Route::has('password.request'))
                                        <a class="link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif --}}

                                    @if(config('captcha.version') === 'v3')
                                        {!! \App\Helpers\Helper::renderRecaptcha('loginForm', 'register') !!}
                                    @elseif(config('captcha.version') === 'v2')
                                        <div class="form-field-block">
                                            {!! app('captcha')->display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100" type="submit">{{ __('Sign in') }}</button>
                                    </div>
                                </div>

                                {{-- <h6 class="text-muted mt-4 or">Or Sign in with</h6>

                                <div class="social mt-4">
                                    <div class="btn-showcase">
                                        <a href="javacript:">
                                            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" style="margin-left: 3em;">
                                        </a>

                                        <a class="btn btn-light" href="javacript:" target="_blank" id="btn-fblogin">
                                            <i class="txt-fb" data-feather="facebook" aria-hidden="true"></i>facebook
                                        </a>
                                    </div>
                                </div> --}}
                                <p class="mt-4 text-center">Don't have an account?<a class="ms-2" href="{{ route('register') }}">Create Account</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {!! NoCaptcha::renderJs() !!}

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                let email = $('#email').val().trim();
                let password = $('#password').val().trim();
                let isValid = true;

                // Frontend Validation
                if (!email) {
                    showError('#email', 'Email is required.');
                    isValid = false;
                } else if (!validateEmail(email)) {
                    showError('#email', 'Please enter a valid email address.');
                    isValid = false;
                }

                if (!password) {
                    showError('#password', 'Password is required.');
                    isValid = false;
                }

                if (!isValid) return;

                // Serialize form data
                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('api.login') }}',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log(response);

                        // if (response.redirect_url) {
                        //     window.location.href = response.redirect_url;
                        // } else {
                        //     alert('Login successful.');
                        //     // Optionally reload or redirect
                        // }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function (field, messages) {
                                showError('[name="' + field + '"]', messages[0]);
                            });
                        } else {
                            alert('Invalid credentials or server error.');
                        }
                    }
                });
            });

            function showError(selector, message) {
                $(selector).addClass('is-invalid');
                $(selector).after('<span class="invalid-feedback d-block"><strong>' + message + '</strong></span>');
            }

            function validateEmail(email) {
                var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
@endsection

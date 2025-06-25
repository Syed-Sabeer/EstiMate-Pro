@extends('layouts.authentication.master')
@section('title', 'Registration')

@section('css')
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/3.jpg') }}"
                    alt="loginpage"></div>
            <div class="col-xl-7 p-0">
                <div class="login-card">
                    <div>
                        <div style="width:121px">
                            <a class="logo" href="{{ route('auth.register') }}"><img class="img-fluid for-light"
                                    src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="loginpage"><img
                                    class="img-fluid for-dark" src="{{ asset(\App\Helpers\Helper::getLogoDark()) }}"
                                    alt="loginpage"></a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('api.register') }}" id="registrationForm">
                                <h4>Create your account</h4>
                                <p>Enter your personal details to create an account</p>
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="col-form-label pt-0">{{ __('Your Name') }}</label>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <input id="name" class="form-control @error('name') is-invalid @enderror"
                                                name="name" type="text" value="{{ old('name') }}" required
                                                autocomplete="name" autofocus placeholder="First name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label">{{ __('Email Address') }}</label>
                                    <input id="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" type="email" value="{{ old('email') }}" required
                                        autocomplete="email" placeholder="example@gmail.com">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control @error('password') is-invalid @enderror" id="password"
                                            type="password" name="password" required autocomplete="new-password"
                                            placeholder="*********">
                                        <div class="show-hide"><span class="show"></span></div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="password-confirm"
                                        class="col-form-label">{{ __('Confirm Password') }}</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control @error('password_confirmation') is-invalid @enderror" id="password-confirm" type="password"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="*********">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="form-group mb-0">
                                    <div class="checkbox p-0">
                                        <input id="checkbox1" type="checkbox" name="agreed_with_privacy" class="@error('agreed_with_privacy') is-invalid @enderror" {{ old('agreed_with_privacy') == 'on' ? 'checked' : '' ; }}>
                                        <label class="text-muted" for="checkbox1">Agree with<a class="ms-2"
                                                href="javascript:;">Privacy Policy</a></label>
                                        @error('agreed_with_privacy')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    @if(config('captcha.version') === 'v3')
                                        {!! \App\Helpers\Helper::renderRecaptcha('registrationForm', 'register') !!}
                                    @elseif(config('captcha.version') === 'v2')
                                        {!! \App\Helpers\Helper::renderRecaptcha('registrationForm') !!}
                                        <div class="form-field-block">
                                            {!! app('captcha')->display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <button class="btn btn-primary btn-block mt-2" type="submit">
                                        {{ __('Create Account') }}</button>
                                </div>
                                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2"
                                        href="{{ route('auth.login') }}">Sign in</a></p>
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
            $('#registrationForm').on('submit', function (e) {
                e.preventDefault();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                let name = $('#name').val().trim();
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

                if (!name) {
                    showError('#password', 'Password is required.');
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
                    url: '{{ route('api.register') }}',
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

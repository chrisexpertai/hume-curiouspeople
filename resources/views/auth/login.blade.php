@extends('layouts.app')

@section('content')
    <!-- **************** MAIN CONTENT START **************** -->
    <section class="p-0 d-flex align-items-center position-relative overflow-hidden">
        <div class="container-fluid">
            <div class="col-12 col-lg-6 m-auto">
                <div class="row my-5">
                    <div class="col-sm-10 col-xl-8 m-auto">
                        <h1 class="fs-2">@lang('frontend.login_to')</h1>
                        <p class="lead mb-4">@lang('frontend.nice_to_see_you') @lang('frontend.please_login')</p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="exampleInputEmail1" class="form-label">{{ tr('Email address') }}
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i
                                            class="bi bi-envelope-fill"></i></span>
                                    <input class="form-control border-0 bg-light rounded-end ps-1"
                                        placeholder="{{ tr('Email Address') }}" id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                            <div class="mb-4">
                                <label for="inputPassword5" class="form-label">{{ tr('Password') }} *</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i
                                            class="fas fa-lock"></i></span>
                                    <input id="password" type="password"
                                        class="form-control border-0 bg-light rounded-end ps-1 @error('password') is-invalid @enderror"
                                        name="password" placeholder="{{ tr('Password') }}" required
                                        autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    {{ tr('Your password must be 8 characters at least') }}
                                </div>
                            </div>
                            <div class="mb-4 d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">{{ tr('Remember me') }}</label>
                                </div>
                                <div class="text-primary-hover">
                                    <a href="{{ route('password.request') }}" class="text-secondary">
                                        <u>{{ tr('Forgot password?') }}</u>
                                    </a>
                                </div>
                            </div>
                            <div class="align-items-center mt-0">
                                <div class="d-grid">
                                    <button class="btn btn-primary mb-0" type="submit">{{ tr('Login') }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="position-relative my-4">
                                <hr>
                            </div>
                            @if (get_option('social_login.google.enable'))
                                <div class="col-xxl-6 d-grid">
                                    <a href="{{ route('google_redirect') }}" class="btn bg-google mb-2 mb-xxl-0">
                                        <i class="fab fa-fw fa-google text-white me-2"></i>{{ tr('Login with Google') }}
                                    </a>
                                </div>
                            @endif

                            @if (get_option('social_login.facebook.enable'))
                                <div class="col-xxl-6 d-grid">
                                    <a href="{{ route('facebook_redirect') }}" class="btn bg-facebook mb-0">
                                        <i class="fab fa-fw fa-facebook-f me-2"></i>{{ tr('Login with Facebook') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="mt-8 text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

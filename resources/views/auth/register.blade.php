
@extends('layouts.app')

@section('content')
@php
    $instructor_can_register = get_option('lms_options.instructor_can_register');
@endphp




<!-- **************** MAIN CONTENT START **************** -->
<main>
	<section class="p-0 d-flex align-items-center position-relative overflow-hidden">

		<div class="container-fluid">
			<div class="row">
				<!-- Center -->
                <div class="col-12 col-lg-6 m-auto">
                    <div class="row my-5">
                        <div class="col-sm-10 col-xl-8 m-auto">
                            <!-- Title -->
                            <h2>{{ tr('Sign up for your account!') }}</h2>
                       <p class="lead mb-4">{{__t('nice_to_see_you')}} <br> {{__t('please_sign_up')}}</p>

                            <!-- Form START -->
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}

                                <!-- Display errors -->
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{session('error')}}
                                    </div>
                                @endif

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{__t('name')}} *</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="bi bi-person"></i></span>
                                        <input id="name" type="text" class="form-control border-0 bg-light rounded-end ps-1" placeholder="{{__t('name')}}" name="name" value="{{ old('name') }}" required autofocus>
                                    </div>
                                    @if ($errors->has('name'))
                                        <div class="alert alert-danger mt-2">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="form-label">{{__t('email_address')}} *</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="bi bi-envelope-fill"></i></span>
                                        <input type="email" class="form-control border-0 bg-light rounded-end ps-1" placeholder="{{__t('e_mail')}}" id="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    @if ($errors->has('email'))
                                        <div class="alert alert-danger mt-2">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label">{{__t('password')}} *</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control border-0 bg-light rounded-end ps-1" placeholder="{{__t('password')}}" id="password" name="password" required>
                                    </div>
                                    @if ($errors->has('password'))
                                        <div class="alert alert-danger mt-2">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label">{{__t('confirm_password')}} *</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control border-0 bg-light rounded-end ps-1" placeholder="{{__t('confirm_password')}}" id="password-confirm" name="password_confirmation" required>
                                    </div>
                                </div>
                                @if($instructor_can_register)



                                <!-- User Type -->
                                <div class="mb-4">
                                    <label for="user-as" class="form-label">{{__t('i_am')}}</label>
                                    <div class="input-group input-group-lg">
                                        <div class="col-md-6">
                                            <label class="mr-3">
                                                <input type="radio" name="user_as" value="student" {{ old('user_as') ? (old('user_as') == 'student' ? 'checked' : '') : 'checked' }}>
                                                {{__t('student')}}
                                            </label>
                                            <label>
                                                <input type="radio" name="user_as" value="instructor" {{ old('user_as') == 'instructor' ? 'checked' : '' }}>
                                                {{__t('instructor')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                                <!-- Button -->
                                <div class="align-items-center mt-0">
                                    <div class="d-grid">
                                        <button class="btn btn-primary mb-0" type="submit">{{__t('sign_up')}}</button>
                                    </div>
                                </div>
                            </form>
                            <!-- Form END -->

                            <!-- Social buttons and divider -->
                            <div class="row">
                                <!-- Divider with text -->
                                <div class="position-relative my-4">
                                    <hr>
                                    <p class="small position-absolute top-50 start-50 translate-middle bg-body px-5">{{ tr('Or') }}</p>
                                </div>
                                @if(get_option('social_login.google.enable'))

                                <!-- Social btn -->
                                <div class="col-xxl-6 d-grid">
                                    <a href="{{ route('google_redirect') }}" class="btn bg-google mb-2 mb-xxl-0"><i class="fab fa-fw fa-google text-white me-2"></i>{{ tr('Login with Google') }}</a>
                                </div>

                                @endif

                                @if(get_option('social_login.facebook.enable'))

                                <!-- Social btn -->
                                <div class="col-xxl-6 d-grid">
                                    <a href="{{ route('facebook_redirect') }}" class="btn bg-facebook mb-0"><i class="fab fa-fw fa-facebook-f me-2"></i>{{ tr('Login with Facebook') }}</a>
                                </div>

                                @endif


                            </div>

                            <!-- Sign up link -->
                            <div class="mt-4 text-center">
								<span>{{ tr('Already have an account?') }}<a href="{{ route('login') }}"> {{ tr('Sign In here') }}</a></span>
							</div>
                        </div>
                        </div> <!-- Row END -->

                        </div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<!-- **************** MAIN CONTENT END **************** -->


@endsection

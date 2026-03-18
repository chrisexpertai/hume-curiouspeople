@extends('layouts.dashboard')

@section('content')
    <div class="col-xl-12">
        <!-- Edit profile START -->
        <div class="card bg-transparent border rounded-3">


            <!-- Card header -->
            <div class="card-header bg-transparent border-bottom">
                <h3 class="card-header-title mb-0">{{ tr('Edit Profile') }}</h3>
            </div>
            <!-- Card body START -->
            <div class="card-body">

                <div class="dashboard-inline-submenu-wrap mb-4 border-bottom">
                    <a href="{{ route('profile_settings') }}" class="active">{{ tr('Profile Settings') }}</a>
                    <a href="{{ route('profile_reset_password') }}" class="">{{ tr('Password Reset') }}</a>
                </div>



                <div class="profile-settings-wrap">


                    <form action="{{ route('profile_reset_password') }}" method="post">
                        @csrf
                        <!-- Profile picture -->
                        <div class="col-12 justify-content-center align-items-center">
                            <div class="profile-basic-info bg-white p-3">

                                <div class="form-row">
                                    <div class="form-group col-md-12 {{ form_error($errors, 'old_password')->class }}">
                                        <label>{{ __t('old_password') }}</label>
                                        <input type="password" class="form-control" name="old_password">
                                        {!! form_error($errors, 'old_password')->message !!}
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 {{ form_error($errors, 'new_password')->class }}">
                                        <label>{{ __t('new_password') }}</label>
                                        <input type="password" class="form-control" name="new_password">
                                        {!! form_error($errors, 'new_password')->message !!}
                                    </div>

                                    <div
                                        class="form-group col-md-6 {{ form_error($errors, 'new_password_confirmation')->class }}">
                                        <label>{{ __t('new_password_confirmation') }}</label>
                                        <input type="password" class="form-control" name="new_password_confirmation">
                                        {!! form_error($errors, 'new_password_confirmation')->message !!}
                                    </div>

                                </div>


                                <button type="submit" class="btn btn-purple btn-lg"> Update Profile</button>


                            </div>

                        </div>


                    </form>


                </div>

            </div>
        @endsection

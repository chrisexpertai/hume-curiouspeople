@extends('layouts.admin')

@section('content')

<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12 mb-3">
            <h1 class="h3 mb-2 mb-sm-0">Email Settings</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left side START -->
        @include('admin.partials.adminside')
        <!-- Left side END -->

        <!-- Right side START -->
        <div class="col-xl-9">
            <!-- Tab Content START -->
            <div class="tab-content">
                <!-- Email Settings content START -->
                <div class="tab-pane show active" id="tab-email" role="tabpanel">
                    <div class="card shadow">
                        <!-- Card header -->
                        <div class="card-header border-bottom">
                            <h5 class="card-header-title">{{ tr('Email Configuration') }}</h5>
                        </div>
                        <!-- Card body START -->
                        <div class="card-body">
                            <form action="{{route('save_settings')}}" class="form-horizontal" method="post" enctype="multipart/form-data"> @csrf
                                @csrf
                                <!-- Add your form fields here -->


                                <div class="form-group">
                                    <label for="mail_mailer">{{ tr('Mail Mailer') }}</label>
                                     <input type="text" class="form-control" id="mail_mailer" value="{{ get_option('mail.mail_mailer') }}" name="mail[mail_mailer]" placeholder="{{ tr('Usually smtp') }}">
                               </div>

                                <div class="form-group">
                                    <label for="smtp_host">{{ tr('SMTP Host') }}</label>
                                     <input type="text" class="form-control" id="smtp_host" value="{{ get_option('mail.smtp_host') }}" name="mail[smtp_host]" placeholder="{{ tr('us.. mail.website.com') }}">
                               </div>

                                <!-- SMTP Port -->

                               <div class="form-group">
                                <label for="smtp_port">{{ tr('SMTP Port') }}</label>
                                 <input type="text" class="form-control" id="smtp_port" value="{{ get_option('mail.smtp_port') }}" name="mail[smtp_port]" placeholder="{{ tr('us... 46') }}">
                             </div>

                                <!-- SMTP Username -->
                                <div class="form-group">
                                    <label for="smtp_username">{{ tr('SMTP Username') }}</label>
                                    <input type="text" class="form-control" id="smtp_username" value="{{ get_option('mail.smtp_username') }}"name="mail[smtp_username]" placeholder="{{ tr('Mail') }}">
                                </div>

                                <!-- SMTP Password -->
                                <div class="form-group">
                                    <label for="smtp_password">{{ tr('SMTP Password') }}</label>
                                    <input type="password" class="form-control" id="smtp_password" value="{{ get_option('mail.smtp_password') }}" name="mail[smtp_password]" placeholder="{{ tr('Password') }}">
                                </div>

                                <!-- SMTP Encryption -->
                                <div class="form-group">
                                    <label for="smtp_encryption">{{ tr('SMTP Encryption') }}</label>
                                    <input type="text" class="form-control" id="smtp_encryption" value="{{ get_option('mail.smtp_encryption') }}" name="mail[smtp_encryption]" placeholder="{{ tr('us... ssl or tls') }}">
                                </div>

                                 <!-- From Address -->
                                 <div class="form-group">
                                    <label for="smtp_from_address">{{ tr('Mailer from Address') }}</label>
                                    <input type="text" class="form-control" id="smtp_from_address" value="{{ get_option('mail.smtp_from_address') }}" name="mail[smtp_from_address]" placeholder="{{ tr('Users will receive this email') }}">
                                </div>

                                <!-- From Name -->
                                <div class="form-group">
                                    <label for="smtp_from_name">{{ tr('Mailer from Name') }}</label>
                                    <input type="text" class="form-control" id="smtp_from_name" value="{{ get_option('mail.smtp_from_name') }}" name="mail[smtp_from_name]" placeholder="{{ tr('Users will receive this name') }}">
                                </div>

                                <!-- Add more form fields as needed -->
                                <button type="submit" class="btn btn-primary">{{ tr('Save Settings') }}</button>
                            </form>
                        </div>
                        <!-- Card body END -->
                    </div>
                </div>
                <!-- Email Settings content END -->
            </div>
            <!-- Tab Content END -->
        </div>
        <!-- Right side END -->
    </div> <!-- Row END -->
</div>

@endsection

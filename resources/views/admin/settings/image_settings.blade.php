@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">

        <!-- Title -->
        <div class="row">
            <div class="col-12 mb-3">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Admin Settings') }}</h1>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left side START -->
            @include('admin.partials.pikserasettings')
            <!-- Left side END -->

            <!-- Right side START -->
            <div class="col-xl-9">

                <!-- Tab Content START -->
                <div class="tab-content">


                    <!-- Personal Information content START -->
                    <div class="tab-pane show active" id="tab-1" role="tabpanel">
                        <div class="card shadow">

                            <!-- Card header -->
                            <div class="card-header border-bottom">
                                <h5 class="card-header-title">{{ tr('Website Settings') }}</h5>
                            </div>

                            <!-- Card body START -->
                            <div class="card-body">
                                <form action="{{ route('save_settings') }}" class="form-horizontal" method="post"
                                    enctype="multipart/form-data">
                                    @csrf


                                    <!-- Logo Image -->
                                    <h4 class="mb-2 text-2xl font-bold text-black">Logo Light Image</h4>
                                    @include('components.ImageUploadSection', [
                                        'identifier' => 'logo_light',
                                    ])


                                    <!-- Logo Image -->
                                    <h4 class="mb-2 text-2xl font-bold text-black">Logo Dark Image</h4>
                                    @include('components.ImageUploadSection', [
                                        'identifier' => 'logo_dark',
                                    ])

                                    {{-- <!-- Login Image -->
        <h4 class="mb-2 text-2xl font-bold text-black">Login Image</h4>
        @include('components.ImageUploadSection', ['identifier' => 'login']) --}}

                                    {{-- <!-- Register Image -->
        <h4 class="mb-2 text-2xl font-bold text-black">Register Image</h4>
        @include('components.ImageUploadSection', ['identifier' => 'register']) --}}

                                    {{-- <!-- Categories Page Image -->
        <h4 class="mb-2 text-2xl font-bold text-black">Categories Image 1 & 2</h4>

        @include('components.ImageUploadSection', ['identifier' => 'categories-1'])


        @include('components.ImageUploadSection', ['identifier' => 'categories-2']) --}}

                                    <!-- NoData Image -->
                                    <h4 class="mb-2 text-2xl font-bold text-black">No Data Image</h4>
                                    @include('components.ImageUploadSection', ['identifier' => 'nodata'])


                                    <hr />

                                    <div class="form-group row">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button type="submit" id="settings_save_btn" class="btn btn-primary">
                                                {{ __a('save_settings') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>


                                <!-- Card body END -->

                            </div>
                        </div>
                        <!-- Personal Information content END -->

                    </div>
                    <!-- Tab Content END -->
                </div>
                <!-- Right side END -->
            </div> <!-- Row END -->

            <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script>


            <script src="/assets/admin/js/custom.js"></script>

            < {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Function to toggle display based on radio button selection
                    function toggleDisplay(uploadContainer, urlContainer, value) {
                        if (value === "upload") {
                            uploadContainer.style.display = "block";
                            urlContainer.style.display = "none";
                        } else {
                            uploadContainer.style.display = "none";
                            urlContainer.style.display = "block";
                        }
                    }

                    // Call the function for each unique identifier
                    function setupImageUploadSection(identifier) {
                        var uploadContainer = document.getElementById(identifier + "_upload-container");
                        var urlContainer = document.getElementById(identifier + "_url-container");
                        var uploadRadio = document.querySelector('input[name="' + identifier +
                            '_image_source"][value="upload"]');
                        var urlRadio = document.querySelector('input[name="' + identifier + '_image_source"][value="url"]');

                        // Initial display based on the selected radio button
                        toggleDisplay(uploadContainer, urlContainer, uploadRadio.checked ? "upload" : "url");

                        // Add event listeners to the radio buttons
                        uploadRadio.addEventListener("change", function() {
                            toggleDisplay(uploadContainer, urlContainer, "upload");
                        });

                        urlRadio.addEventListener("change", function() {
                            toggleDisplay(uploadContainer, urlContainer, "url");
                        });
                    }

                    // Call the function for each unique identifier
                    setupImageUploadSection('logo_light');
                    setupImageUploadSection('logo_dark');
                    setupImageUploadSection('login');
                    setupImageUploadSection('register');
                    setupImageUploadSection('categories-1');
                    setupImageUploadSection('categories-2');
                    setupImageUploadSection('nodata');


                });
            </script>
        @endsection

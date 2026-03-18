@extends('layouts.dashboard')

@section('content')
    @php
        $user = $auth_user;
        $countries = countries();
    @endphp

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

                <!-- Form -->
                <form action="" class="row g-4" method="post">

                    @csrf

                    <!-- Profile picture -->
                    <div class="col-12 justify-content-center align-items-center">
                        <label class="form-label">{{ tr('Profile picture') }}</label>
                        <div class="d-flex align-items-center">
                            <label class="position-relative me-4 w-50 rounded-4" for="uploadfile-1"
                                title="{{ tr('Replace this pic') }}">
                                {!! avatar_upload_form('photo', $user->photo) !!}
                            </label>
                            <!-- Upload button -->
                            <input id="uploadfile-1" class="form-control d-none" type="file">
                        </div>
                    </div>

                    <!-- Full name -->

                    <div class="row mt-3">
                        <div class="form-group col-md-6 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label>{{ __t('name') }}</label>
                            <input type="tel" class="form-control" name="name" value="{{ $user->name }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('job_title') ? ' has-error' : '' }}">
                            <label>{{ __t('job_title') }}</label>
                            <input type="text" class="form-control" name="job_title" value="{{ $user->job_title }}">
                            @if ($errors->has('job_title'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('job_title') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="form-group col-md-6">
                            <label>{{ __t('email') }}</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>{{ __t('phone') }}</label>
                            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="form-group col-md-6">
                            <label>{{ __t('address') }}</label>
                            <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __t('address_2') }}</label>
                            <input type="text" class="form-control" name="address_2" value="{{ $user->address_2 }}">
                        </div>

                    </div>


                    <div class="row mt-3">
                        <div class="form-group col-md-6">
                            <label>{{ __t('city') }}</label>
                            <input type="text" class="form-control" name="city" value="{{ $user->city }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label>{{ __t('zip') }}</label>
                            <input type="text" class="form-control" name="zip_code" value="{{ $user->zip_code }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputState">{{ __t('country') }}</label>

                            <select class="form-control" name="country_id">
                                <option value="">{{ tr('Choose...') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ selected($user->country_id, $country->id) }}>
                                        {!! $country->name !!}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-9 mt-3">
                            <label>{{ __t('about_me') }}</label>
                            <textarea class="form-control" name="about_me" rows="5">{{ $user->about_me }}</textarea>
                        </div>



                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="occupation" class="form-label">{{ tr('Select Occupation') }}</label>
                            <select name="occupation[]" id="occupation"
                                class="form-select @error('occupation') is-invalid @enderror" multiple required>
                                <option value="" disabled selected>{{ tr('Select Occupation') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, $userOccupations) ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('occupation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Education -->
                    <div class="col-12">
                        <label class="form-label">{{ tr('Education') }}</label>
                        <div id="educationContainer">
                            @foreach ($educations as $education)
                                <div class="education-item d-flex justify-content-between align-items-center mb-2"
                                    data-education-id="{{ $education->id }}">
                                    <p class="form-control mb-0 flex-grow-1" type="text"
                                        value="{{ $education->school_name }}">{{ $education->school_name }} •
                                        {{ $education->degree }}</p>
                                    <button
                                        class="btn border btn-danger delete-education ms-2">{{ tr('Delete') }}</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-light mb-0" data-toggle="modal"
                            data-target="#addEducationModal">
                            <i class="bi bi-plus me-1"></i> {{ tr('Add Education') }}
                        </button>
                    </div>



                    <h4 class="my-4">{{ tr('Social Share') }} </h4>


                    <div class="row p-3">
                        <div class="form-group col-md-4">
                            <label>{{ tr('Website') }}</label>
                            <input type="text" class="form-control" name="social[website]"
                                value="{{ $user->get_option('social.website') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ tr('Twitter') }}</label>
                            <input type="text" class="form-control" name="social[twitter]"
                                value="{{ $user->get_option('social.twitter') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ tr('Facebook') }}</label>
                            <input type="text" class="form-control" name="social[facebook]"
                                value="{{ $user->get_option('social.facebook') }}">
                        </div>
                    </div>

                    <div class="row p-3">
                        <div class="form-group col-md-4">
                            <label>{{ tr('LinkedIn') }}</label>
                            <input type="text" class="form-control" name="social[linkedin]"
                                value="{{ $user->get_option('social.linkedin') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ tr('Youtube') }}</label>
                            <input type="text" class="form-control" name="social[youtube]"
                                value="{{ $user->get_option('social.youtube') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ tr('Instagram') }}</label>
                            <input type="text" class="form-control" name="social[instagram]"
                                value="{{ $user->get_option('social.instagram') }}">
                        </div>
                    </div>

                    <!-- Save button -->
                    <div class="d-sm-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mb-0">{{ tr('Save Changes') }}</button>
                    </div>

                </form>





            </div>
            <!-- Card body END -->
        </div>
        <!-- Edit profile END -->




        <!-- Modal -->
        <div class="modal fade" id="addEducationModal" tabindex="-1" role="dialog"
            aria-labelledby="addEducationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEducationModalLabel">Add Education</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to add education entry -->

                        <form id="addEducationForm">

                            @csrf
                            <div class="form-group">
                                <label for="schoolName">School Name</label>
                                <input type="text" class="form-control" id="schoolName" name="schoolName"
                                    placeholder="Enter school name" required>
                            </div>
                            <div class="form-group">
                                <label for="degree">Degree</label>
                                <input type="text" class="form-control" id="degree" name="degree"
                                    placeholder="Enter degree" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>








        <!-- jquery latest version -->
        {{-- <script src="/assets/vendor/jquery/jquery-1.12.0.min.js"></script> --}}

        
        <script src="{{ asset('assets/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/filemanager/filemanager.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.edit-education-btn').click(function() {
                    var educationId = $(this).data('education-id');
                    var listItem = $(this).closest('li');
                    var schoolName = listItem.find('strong:eq(0)').text().split(':')[1].trim();
                    var degree = listItem.find('strong:eq(1)').text().split(':')[1].trim();
                    $('#education_id').val(educationId);
                    $('#editSchoolName').val(schoolName);
                    $('#editDegree').val(degree);
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $(document).on('click', '.delete-education', function(event) {
                    event.preventDefault();

                    var educationId = $(this).closest('.education-item').data('education-id');
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('delete_education') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            education_id: educationId
                        },
                        success: function(response) {
                            $('.education-item[data-education-id="' + educationId + '"]').remove();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#addEducationForm').submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Serialize the form data
                    var formData = $(this).serialize();

                    // Send an AJAX request to add education
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('add_education') }}',
                        data: formData,
                        success: function(response) {
                            // Append the new education entry to the container
                            $('#educationContainer').append(response);
                            $('#addEducationModal').modal('hide'); // Close the modal
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            console.error(xhr.responseText);
                            // You may want to display an error message here
                        }
                    });
                });
            });


            // Function to load education data via AJAX
            function loadEducationData() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_education') }}', // Update this to the correct route for fetching education data
                    success: function(response) {
                        // Append the new education entry to the container
                        $('#educationContainer').append(response);
                        $('#addEducationModal').modal('hide'); // Hide the modal after loading education data
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        // You may want to display an error message here
                    }
                });
            }
        </script>
    @endsection

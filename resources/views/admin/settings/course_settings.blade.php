
@extends('layouts.admin')

@section('content')



@php
$courseLayouts = [
    'advance' => 'front.course.advance',
    'classic' => 'front.course.classic',
    'minimal' => 'front.course.minimal',
    'module' => 'front.course.module',
];

$selectedCourseType = get_option("course_settings.course_type");
$selectedCourseLayout = isset($courseLayouts[$selectedCourseType]) ? $courseLayouts[$selectedCourseType] : $courseLayouts['advance'];
@endphp

<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12 mb-3">
            <h1 class="h3 mb-2 mb-sm-0">{{ tr('LMS Settings') }}</h1>
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
                            <h5 class="card-header-title">{{ tr('Course Settings') }}</h5>
                        </div>

                        <!-- Card body START -->
                        <div class="card-body">
                            <form action="{{ route('save_settings') }}" method="post" enctype="multipart/form-data" class="container mt-5">
                                @csrf




                                <!-- Course Details Settings -->
                                <div class="mb-5">
                                    <h3 class="text-left mb-4">{{ tr('Course Details') }}</h3>
                                    <div class="row">
                                        @foreach ($courseLayouts as $type => $label)
                                            <div class="col-md-6 mb-4">
                                                <label class="course-card" data-type="{{ $type }}" data-group="course-details">
                                                    <input type="radio" name="course_settings[course_type]" value="{{ $type }}" class="d-none" {!! $selectedCourseType === $type ? 'checked' : '' !!}>
                                                    <img src="{{ asset('/assets/images/' . $type . '.png') }}" alt="{{ ucfirst($type) }} Image" class="img-fluid selected-image mb-3" style="">
                                                    <div class="card-details">
                                                        <p class="text-left font-weight-bold"></p>
                                                     </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="text-left">
                                    <button type="submit" id="settings_save_btn" class="btn btn-primary">
                                        {{ tr('Save Settings') }}
                                    </button>
                                </div>

                                <!-- Custom JS and CSS -->
                              <!-- ... (your existing HTML code) ... -->

<!-- Custom JS and CSS -->
<style>

</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const courseCards = document.querySelectorAll('.course-card');

        courseCards.forEach(card => {
            card.addEventListener('click', function () {
                const group = this.getAttribute('data-group');
                const type = this.getAttribute('data-type');
                const radioInput = document.querySelector(`input[name="${group}_settings[${group}_type]]"][value="${type}"]`);

                // Remove selected class from all cards in the same group
                courseCards.forEach(otherCard => {
                    const otherGroup = otherCard.getAttribute('data-group');
                    if (otherGroup === group) {
                        otherCard.classList.remove(`selected-${group}`);
                    }
                });

                // Add selected class to the clicked card
                this.classList.add(`selected-${group}`);

                if (radioInput) {
                    radioInput.checked = true;
                }
            });
        });

        // Set initial selected cards based on checked radio inputs
        const checkedRadioInputsCourses = document.querySelectorAll('input[name="courses_settings[courses_type]"]:checked');
        const checkedRadioInputsCourseDetails = document.querySelectorAll('input[name="course_settings[course_type]"]:checked');

        setInitialSelectedCards(checkedRadioInputsCourses, 'courses');
        setInitialSelectedCards(checkedRadioInputsCourseDetails, 'course-details');

        function setInitialSelectedCards(checkedInputs, group) {
            checkedInputs.forEach(input => {
                const type = input.value;
                const correspondingCard = document.querySelector(`.course-card[data-group="${group}"][data-type="${type}"]`);
                if (correspondingCard) {
                    correspondingCard.classList.add(`selected-${group}`);
                }
            });
        }
    });
</script>



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

    <link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
 @endsection


@section('page-js')
    <script>
        $(document).ready(function(){

            $('input[type="checkbox"], input[type="radio"]').click(function(){
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')){
                    input_value = $(this).val();
                }
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' }
                });
            });
        });
    </script>
@endsection

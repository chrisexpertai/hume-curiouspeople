@extends('layouts.app')

@section('content')


@php
      $user = Auth::user();

    // If $user is not defined, you might want to handle it accordingly
    if (!$user) {
        // Redirect or display an error message
        return redirect()->route('login')->with('error', 'Please log in to apply for teacher.');
    }
@endphp



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                @if ($user->isTeacherApplied())
                @if ($user->teacherApplication->status == 'pending')
                    <div class="alert alert-info" role="alert">
                        {{ tr('Your teacher application is being reviewed. Please wait for the approval.') }}
                    </div>
                @elseif ($user->teacherApplication->status == 'approved')
                    <div class="alert alert-success" role="alert">
                        {{ tr('Congratulations! Your teacher application has been approved. You are now an instructor on our platform.') }}
                    </div>
                @elseif ($user->teacherApplication->status == 'declined')
                    <div class="alert alert-danger" role="alert">
                        {{ tr('We are very sorry, but your application to become an instructor on our platform has been declined. If you have any questions, please contact us.') }}
                    </div>
                @endif
            @elseif ($user->user_type == 'instructor')
                <div class="alert alert-primary" role="alert">
                  {{ tr('Congrats! You are already an instructor on our platform.') }}
                </div>
            @else


                <h1 class="card-header">{{ tr('Teacher Application Form') }}</h1>

                <div class="card-body">
                    <form method="post" action="{{ route('submit.teacher.application') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <h3>{{ tr('Personal Information') }}</h3>
                        <div class="form-group">
                            <label for="name">{{ tr('Full Name:') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ tr('Email Address:') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">{{ tr('Contact Number:') }}</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="address">{{ tr('Address:') }}</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">{{ tr('Date of Birth:') }}</label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>

                        <!-- Educational Background -->
                        <h3>{{ tr('Educational Background') }}</h3>
                        <div class="form-group">
                            <label for="highest_degree">{{ tr('Highest Degree Earned:') }}</label>
                            <input type="text" name="highest_degree" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="institution_name">{{ tr('Name of Institution:') }}</label>
                            <input type="text" name="institution_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="field_of_study">{{ tr('Field of Study:') }}</label>
                            <input type="text" name="field_of_study" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="year_of_graduation">{{ tr('Year of Graduation:') }}</label>
                            <input type="text" name="year_of_graduation" class="form-control" required>
                        </div>

                        <!-- Teaching Experience -->
                        <h3>{{ tr('Teaching Experience') }}</h3>
                        <div class="form-group">
                            <label for="teaching_experience">{{ tr('Number of Years of Teaching Experience:') }}</label>
                            <input type="text" name="teaching_experience" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="previous_positions">{{ tr('>Previous Teaching Positions Held:') }}</label>
                            <textarea name="previous_positions" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="subjects_taught">{{ tr('Subjects or Courses Taught:') }}</label>
                            <textarea name="subjects_taught" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="institutions_worked_at">{{ tr('Educational Institutions Worked At') }}:</label>
                            <textarea name="institutions_worked_at" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- Certifications and Qualifications -->
                        <h3>{{ tr('Certifications and Qualifications') }}</h3>
                        <div class="form-group">
                            <label for="teaching_certificates">{{ tr('Teaching Certificates:') }}</label>
                            <textarea name="teaching_certificates" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="specialized_training">{{ tr('Specialized Training or Workshops Attended:') }}</label>
                            <textarea name="specialized_training" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- Teaching Philosophy -->
                        <h3>Teaching Philosophy</h3>
                        <div class="form-group">
                            <label for="teaching_philosophy"{{ tr('>Teaching Philosophy:') }}</label>
                            <textarea name="teaching_philosophy" class="form-control" rows="5" required></textarea>
                        </div>

                        <!-- Technical Skills -->
                        <h3>{{ tr('Technical Skills') }}</h3>
                        <div class="form-group">
                            <label for="lms_familiarity">{{ tr('Familiarity with LMS Platforms:') }}</label>
                            <input type="text" name="lms_familiarity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edu_technologies_experience">{{ tr('Experience with Educational Technologies:') }}</label>
                            <textarea name="edu_technologies_experience" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="software_proficiency">{{ tr('Proficiency in Relevant Software or Tools:') }}</label>
                            <textarea name="software_proficiency" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- References -->
                        <h3>{{ tr('References') }}</h3>
                        <div class="form-group">
                            <label for="professional_references">{{ tr('Professional References:') }}</label>
                            <textarea name="professional_references" class="form-control" rows="3" required></textarea>
                        </div>


                        <!-- Additional Questions or Custom Fields -->
                        <h3>{{ tr('Additional Questions or Custom Fields') }}</h3>
                        <div class="form-group">
                            <label for="additional_questions">{{ tr('Additional Questions or Custom Fields:') }}</label>
                            <textarea name="additional_questions" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Preferences for course levels or subjects to teach -->
                        <h3>{{ tr('Preferences for Course Levels or Subjects to Teach') }}</h3>
                        <div class="form-group">
                            <label for="course_preferences">{{ tr('Preferences for Course Levels or Subjects to Teach:') }}</label>
                            <textarea name="course_preferences" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Availability for teaching hours or scheduling constraints -->
                        <h3>{{ tr('Availability for Teaching Hours or Scheduling Constraints') }}</h3>
                        <div class="form-group">
                            <label for="teaching_availability">{{ tr('Availability for Teaching Hours or Scheduling Constraints:') }}</label>
                            <textarea name="teaching_availability" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="terms_conditions" required>
                            <label class="form-check-label" for="terms_conditions">{{ tr('I agree to the terms and conditions of employment or contractor agreement.') }}</label>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ tr('Submit Application') }}</button>
                    </form>


                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

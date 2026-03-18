<!-- resources/views/admin/instructor_requests/details.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        <div>
            <p>{{ tr('User') }}: {{ $request->user->name }}</p>
            <p>{{ tr('Email') }}: {{ $request->user->email }}</p>
            <p>{{ tr('Contact Number') }}: {{ $request->user->contact_number }}</p>
            <p>{{ tr('Address') }}: {{ $request->user->address }}</p>
            <p>{{ tr('Date of Birth') }}: {{ $request->user->date_of_birth }}</p>
            <hr>
            <p>{{ tr('Highest Degree Earned') }}: {{ $request->highest_degree }}</p>
            <p>{{ tr('Name of Institution') }}: {{ $request->institution }}</p>
            <p>{{ tr('Field of Study') }}: {{ $request->field_of_study }}</p>
            <p>{{ tr('Year of Graduation') }}: {{ $request->year_of_graduation }}</p>
            <hr>
            <p>{{ tr('Teaching Experience') }}: {{ $request->teaching_experience }} years</p>
            <p>{{ tr('Previous Teaching Positions Held') }}: {{ $request->previous_positions }}</p>
            <p>{{ tr('Subjects or Courses Taught') }}: {{ $request->subjects_taught }}</p>
            <p>{{ tr('Educational Institutions Worked At') }}: {{ $request->institutions_worked }}</p>
            <hr>
            <p>{{ tr('Teaching Certificates') }}: {{ $request->teaching_certificates }}</p>
            <p>{{ tr('Specialized Training or Workshops Attended') }}: {{ $request->training_attended }}</p>
            <hr>
            <p>{{ tr('Teaching Philosophy') }}: {{ $request->teaching_philosophy }}</p>
            <hr>
            <p>{{ tr('LMS Familiarity') }}: {{ $request->lms_familiarity }}</p>
            <p>{{ tr('Experience with Educational Technologies') }}: {{ $request->edu_technology_experience }}</p>
            <p>{{ tr('Proficiency in Relevant Software or Tools') }}: {{ $request->software_proficiency }}</p>
            <hr>
            <p>{{ tr('References') }}: {{ $request->references }}</p>
            <hr>
            <p>{{ tr('Additional Questions or Custom Fields') }}: {{ $request->additional_questions }}</p>
            <p>{{ tr('Preferences for Course Levels or Subjects') }}: {{ $request->preferences }}</p>
            <p>{{ tr('Availability for Teaching Hours') }}: {{ $request->availability }}</p>
            <hr>
            <p>{{ tr('Status') }}: {{ ucfirst($request->status) }}</p>
        </div>
    </div>
@endsection

@extends(('layouts.dashboard'))

@section('content')
    <h1>Messages</h1>

  <!-- Recent Messages Section -->
  <h2>Recent Messages</h2>
  @foreach ($recentMessages as $message)
      <p>
          <strong>{{ $message->sender->name }}</strong> to <strong>{{ $message->receiver->name }}</strong>
          <br>
          {{ $message->content }}
          <br>
          <a href="{{ route('messages.show', ['courseId' => $message->course_id, 'userId' => $message->sender_id]) }}">
              View Message
          </a>
      </p>
  @endforeach

    <!-- Enrolled Courses Section -->
    @foreach ($enrolledCourses as $course)
        <h2>{{ $course->title }}</h2>
        <ul>
            @foreach ($instructors[$course->id] as $instructor)
                <li>
                    <a href="{{ route('messages.show', ['courseId' => $course->id, 'userId' => $instructor->id]) }}">
                        Message {{ $instructor->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach

    <h2>New Message</h2>
    <form action="{{ route('messages.store') }}" method="post">
        @csrf
        <label for="course_id">Select Course:</label>
        <select name="course_id" id="course_id">
            @foreach ($enrolledCourses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
            @endforeach
        </select>
        
        <label for="receiver_id">Select Receiver:</label>
        <select name="receiver_id" id="receiver_id">
            @foreach ($instructors as $courseId => $courseInstructors)
                @foreach ($courseInstructors as $instructor)
                    <option value="{{ $instructor->id }}">{{ $instructor->name }} ({{ $course->title }})</option>
                @endforeach
            @endforeach
        </select>    
        <label for="content">Message:</label>
        <textarea name="content" id="content" rows="3"></textarea>
        <button type="submit">Send Message</button>
    </form>

<script>
    $(document).ready(function () {
        // On change event for the "Select Course" dropdown
        $('#course_id').on('change', function () {
            // Get the selected course ID
            var courseId = $(this).val();

            // Filter the instructors based on the selected course ID
            var filteredInstructors = @json($instructors);

            // Clear and update the "Select Receiver" dropdown options
            $('#receiver_id').empty();
            $.each(filteredInstructors[courseId], function (index, instructor) {
                // Use instructor.course_title to display the course title
                $('#receiver_id').append('<option value="' + instructor.id + '">' + instructor.name + ' (' + instructor.course_title + ')</option>');
            });
        });
    });
</script>
    

    
@endsection

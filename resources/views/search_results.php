<!-- search_results.blade.php -->
@foreach($instructors as $instructor)
    <!-- Display instructor information as per your requirement -->
    <p>{{ $instructor->name }}</p>
    <!-- Add a form for each instructor to add them to the course -->
    <form action="{{ route('add_instructor', $course_id) }}" method="post">
        @csrf
        <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">
        <button type="submit">Add Instructor</button>
    </form>
@endforeach

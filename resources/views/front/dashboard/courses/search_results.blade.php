@foreach($instructorData as $instructor)
<div class="col-md-12 border rounded-3 p-4 mb-4">
    <div class="card h-100">
        <div class="card-body d-flex align-items-center">
            <div class="profile-image mr-4">
                <a href="{{ $instructor['profile_link'] }}">
                    <img src="{{ $instructor['photo'] }}" alt="{{ $instructor['name'] }}" class="img-fluid rounded-circle" style="width: 70px; height: 70px;">
                </a>
            </div>
            <div class="instructor-details flex-grow-1">
                <h4 class="card-title mb-1">{{ $instructor['name'] }}</h4>
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="la la-play-circle text-primary"></i>
                        <span class="text-muted">{{ $instructor['courses_count'] }} courses</span>
                    </div>
                    <div class="mr-3">
                        <i class="la la-user-circle text-primary"></i>
                        <span class="text-muted">{{ $instructor['students_count'] }} students</span>
                    </div>
                    <div class="mr-3">
                        <i class="la la-comments text-primary"></i>
                        {{-- <span class="text-muted">{{ $instructor['instructor_rating'] }}</span> --}}
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <form action="{{ route('attach_instructor', $course->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="instructor_id" value="{{ $instructor['id'] }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary add-instructor-btn"><i class="la la-plus"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@extends('layouts.dashboard')

@section('content')
    @php
        $auth_user = auth()->user();

    @endphp

    @if ($auth_user->courses->count())
        <div class="table-responsive border-0">
            <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">
                <!-- Table head -->
                <thead>
                    <tr>
                        <th scope="col" class="border-0 rounded-start">{{ tr('Title') }}</th>
                        <th scope="col" class="border-0">{{ tr('Enrolled') }}</th>
                        <th scope="col" class="border-0">{{ tr('Status') }}</th>
                        <th scope="col" class="border-0">{{ tr('Price') }}</th>
                        <th scope="col" class="border-0 rounded-end">{{ tr('Action') }}</th>
                    </tr>
                </thead>
                <!-- Table body START -->
                <tbody>
                    @foreach ($auth_user->courses as $course)
                        <tr>
                            <!-- Course item -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Image -->
                                    <div class="w-60px">
                                        <img src="{{ $course->thumbnail_url }}" class="rounded" width="60"
                                            alt="">
                                    </div>
                                    <div class="mb-0 ms-2">
                                        <!-- Title -->
                                        <h6><a href="{{ route('course', $course->slug) }}">{{ $course->title }}</a></h6>
                                        <!-- Info -->
                                        <div class="d-sm-flex">
                                            <p class="h6 fw-light mb-0 small me-3"><i
                                                    class="fas fa-table text-orange me-2"></i>{{ $course->lectures->count() }}
                                                {{ __t('lectures') }}</p>
                                            <!-- You may need to adjust the condition based on your business logic -->
                                            {!! $course->status_html() !!}

                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- Enrolled item -->
                            <td class="text-center text-sm-start"><?php echo number_format($course->enrolled_students); ?></td>
                            <!-- Status item -->
                            <td>
                                <!-- You may need to adjust the condition based on your business logic -->
                                @if ($course->status == 1)
                                    <div class="badge bg-warning bg-opacity-10 text-warning">{{ tr('Published') }}</div>
                                @else
                                    <div class="badge bg-info bg-opacity-10 text-info">{{ tr('Preview') }}</div>
                                @endif
                            </td>
                            <!-- Price item -->
                            <td>{!! $course->price_html() !!}</td>
                            <!-- Action item -->


                            <form action="{{ route('courses.delete', $course->id) }}" method="post">

                                <td>
                                    <a href="{{ route('edit_course_information', $course->id) }}"
                                        class="btn btn-sm btn-success-soft btn-round me-1 mb-0"><i
                                            class="far fa-fw fa-edit"></i></a>
                                    <!-- Add the delete button here -->
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger-soft btn-round mb-0"><i
                                            class="fas fa-fw fa-times"></i></button>
                                </td>

                            </form>
                        </tr>
                    @endforeach
                </tbody>
                <!-- Table body END -->
            </table>
        </div>
    @else
        {!! no_data() !!}
        <div class="no-data-wrap text-center">
            <a href="{{ route('create_course') }}" class="btn btn-lg btn-primary">{{ __t('create_course') }}</a>
        </div>
    @endif
@endsection

@extends('layouts.admin')

@section('content')

    @php

        $allCoursesCount = \App\Models\Course::countAllCourses();

        $PublishedCourses = \App\Models\Course::publish()->count();
        $PendingCourses = \App\Models\Course::countPendingCourses();

    @endphp

    <!-- Page main content START -->
    <div class="page-content-wrapper border">
        <!-- Title -->
        <div class="row mb-3">
            <div class="col-12 d-sm-flex justify-content-between align-items-center">
                <h1 class="h3 mb-2 mb-sm-0">{{ tr('Courses') }}</h1>
                <a href="{{ route('create_course') }}" class="btn btn-xl btn-primary mb-0">{{ tr('Create a Course') }}</a>
            </div>
        </div>

        <!-- Course boxes START -->
        <div class="row g-4 mb-4">
            <!-- Course item -->
            <div class="col-sm-6 col-lg-4">
                <div class="text-center p-4 bg-primary bg-opacity-10 border border-primary rounded-3">
                    <h6>{{ tr('Total Courses') }}</h6>
                    <h2 class="mb-0 fs-1 text-primary">{{ $allCoursesCount }}</h2>
                </div>
            </div>

            <!-- Course item -->
            <div class="col-sm-6 col-lg-4">
                <div class="text-center p-4 bg-success bg-opacity-10 border border-success rounded-3">
                    <h6>{{ tr('Published Courses') }}</h6>
                    <h2 class="mb-0 fs-1 text-success">{{ $PublishedCourses }}</h2>
                </div>
            </div>

            <!-- Course item -->
            <div class="col-sm-6 col-lg-4">
                <div class="text-center p-4  bg-warning bg-opacity-15 border border-warning rounded-3">
                    <h6>{{ tr('Pending Courses') }}</h6>
                    <h2 class="mb-0 fs-1 text-warning">{{ $PendingCourses }}</h2>
                </div>
            </div>
        </div>
        <!-- Course boxes END -->

        <!-- Card START -->
        <div class=" bg-transparent  ">
            <form action="" method="get">

                <div class="container-fluid mt-3">
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="input-group">
                                <select name="status" class="form-select">
                                    <option value="">{{ tr('Set Status') }}</option>
                                    <option value="1" {{ selected('1', request('status')) }}>{{ tr('Publish') }}
                                    </option>
                                    <option value="2" {{ selected('2', request('status')) }}>{{ tr('Pending') }}
                                    </option>
                                    <option value="3" {{ selected('3', request('status')) }}>{{ tr('Block') }}
                                    </option>
                                    <option value="4" {{ selected('4', request('status')) }}>{{ tr('Unpublish') }}
                                    </option>
                                </select>
                                <button type="submit" name="bulk_action_btn" value="update_status"
                                    class="btn btn-primary"><i class="la la-refresh"></i> {{ tr('Update') }}</button>
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="la la-cog"></i> {{ tr('Actions') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><button type="submit" name="bulk_action_btn" value="mark_as_popular"
                                                class="dropdown-item"><i class="la la-star"></i>
                                                {{ tr('Mark as Popular') }}</button></li>
                                        <li><button type="submit" name="bulk_action_btn" value="remove_from_popular"
                                                class="dropdown-item"><i class="la la-star-o"></i>
                                                {{ tr('Remove from Popular') }}</button></li>
                                        <li><button type="submit" name="bulk_action_btn" value="mark_as_feature"
                                                class="dropdown-item"><i class="la la-bookmark"></i>
                                                {{ tr('Mark as Feature') }}</button></li>
                                        <li><button type="submit" name="bulk_action_btn" value="remove_from_feature"
                                                class="dropdown-item"><i class="la la-remove"></i>
                                                {{ tr('Remove from Feature') }}</button></li>
                                        <li><button type="submit" name="bulk_action_btn" value="delete"
                                                class="dropdown-item delete_confirm"><i class="la la-trash"></i>
                                                {{ tr('Delete') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                                    placeholder="Search by course name">
                                <select name="filter_status" class="form-select">
                                    <option value="">{{ tr('Filter by Status') }}</option>
                                    <option value="1" {{ selected('1', request('filter_status')) }}>
                                        {{ tr('Publish') }}</option>
                                    <option value="2" {{ selected('2', request('filter_status')) }}>
                                        {{ tr('Pending') }}</option>
                                    <option value="3" {{ selected('3', request('filter_status')) }}>
                                        {{ tr('Block') }}</option>
                                    <option value="4" {{ selected('4', request('filter_status')) }}>
                                        {{ tr('Unpublish') }}</option>
                                </select>
                                <button type="submit" class="btn btn-primary"><i class="la la-search"></i>
                                    {{ tr('Filter') }}</button>
                            </div>
                        </div>
                    </div>


                    @if ($courses->count() > 0)
                        <div class="table-responsive">
                            <table class="table bg-white">
                                <thead>
                                    <tr>
                                        <th>
                                            <input class="bulk_check_all" type="checkbox" />
                                        </th>
                                        <th>{{ tr('Thumbnail') }}</th>
                                        <th>{{ tr('Title') }}</th>
                                        <th>{{ tr('Category') }}</th>
                                        <th>{{ tr('Author') }}</th>
                                        <th>{{ tr('Price') }}</th>
                                        <th>{{ tr('Updated') }}</th>
                                        <th>{{ tr('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td>
                                                <label>
                                                    <input class="check_bulk_item" name="bulk_ids[]" type="checkbox"
                                                        value="{{ $course->id }}" />
                                                    <small class="text-muted">#{{ $course->id }}</small>
                                                </label>
                                            </td>
                                            <td>
                                                <img src="{{ $course->thumbnail_url }}" width="80"
                                                    style="max-width: 110px;" />
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $course->title }}</strong>
                                                    {!! $course->status_html() !!}
                                                </div>
                                                <div class="text-muted">
                                                    <span
                                                        class="course-list-lecture-count">{{ $course->lectures->count() }}
                                                        {{ tr('Lectures') }}</span>
                                                    @if ($course->quizzes->count())
                                                        , <span
                                                            class="course-list-assignment-count">{{ $course->quizzes->count() }}
                                                            {{ tr('Quizzes') }}</span>
                                                    @endif
                                                    @if ($course->assignments->count())
                                                        , <span
                                                            class="course-list-assignment-count">{{ $course->assignments->count() }}
                                                            {{ tr('Assignments') }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($course->second_category_id)
                                                    <?php $category = App\Models\Category::find($course->second_category_id); ?>
                                                    {{ $category->category_name }}
                                                @endif
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('profile', $course->user_id) }}">{{ $course->author->name }}</a>
                                            </td>
                                            <td>{!! $course->price_html() !!}</td>
                                            <td>{{ $course->last_updated_at->format('d F Y') }}</td>
                                            <td>
                                                @if ($course->status == 1)
                                                    <a href="{{ route('course', $course->slug) }}"
                                                        class="btn btn-sm btn-primary mt-2" target="_blank"><i
                                                            class="la la-eye"></i> {{ tr('View') }} </a>
                                                @else
                                                    <a href="{{ route('edit_course_information', $course->id) }}"
                                                        class="btn btn-sm btn-purple mt-2" target="_blank"><i
                                                            class="la la-eye"></i> {{ tr('Review') }} </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {!! $courses->appends(['q' => request('q'), 'status' => request('status')])->links() !!}
                        </div>
                    @else
                        {!! no_data() !!}
                    @endif
            </form>
      
        </div>
    </div>
@endsection
<style>
    nav[role="navigation"] svg {
        width: 16px !important;
        height: 16px !important;
    }
</style>
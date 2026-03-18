@extends('layouts.admin')

@section('content')

<!-- Page main content START -->
<div class="page-content-wrapper border">

    <!-- Title and Create Button -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3">Users</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Create New User</a>
        </div>
    </div>

    <!-- Filter and Search -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="input-group">
                    <select name="status" class="form-select mr-2">
                        <option value="">{{ tr('Set Status') }}</option>
                        <option value="1">Active</option>
                        <option value="2">Blocked</option>
                    </select>
                    <button type="submit" name="bulk_action_btn" value="update_status" class="btn btn-primary mr-2">{{ tr('Update') }}</button>
                    <button type="submit" name="bulk_action_btn" value="delete" class="btn btn-danger delete_confirm"><i class="la la-trash"></i>{{ tr('Delete') }}</button>
                </div>
            </div>

            <div class="col-md-7">
                <div class="input-group">
                    <input type="text" class="form-control mr-2" name="q" value="{{ request('q') }}" placeholder="Filter by Name or E-Mail">
                    <select name="filter_status" class="form-select mr-2">
                        <option value="">{{ tr('Status') }}</option>
                        <option value="1" {{ selected(1, request('filter_status')) }}>Active</option>
                        <option value="2" {{ selected(2, request('filter_status')) }}>Blocked</option>
                    </select>
                    <select name="filter_user_group" class="form-select mr-2">
                        <option value="">User Group</option>
                        <option value="student" {{ selected('student', request('filter_user_group')) }}>{{ tr('Students') }}</option>
                        <option value="instructor" {{ selected('instructor', request('filter_user_group')) }}>{{ tr('Instructors') }}</option>
                        <option value="admin" {{ selected('admin', request('filter_user_group')) }}>Admins</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="la la-search-plus"></i>{{ tr('Filter Results') }}</button>
                </div>
            </div>
        </div>


    <!-- Users Table -->
    <!-- Users Table -->
@if ($users->total())
<div class="row">
    <div class="col">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><input class="bulk_check_all" type="checkbox" /></th>
                    <th>{{ tr('name') }}</th>
                    <th>{{ tr('email') }}</th>
                    <th>{{__a('type')}}</th>
                    <th>{{__a('actions')}}</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                <tr>
                    <td>
                        <label>
                            <input class="check_bulk_item" name="bulk_ids[]" type="checkbox" value="{{ $u->id }}" />
                            <small class="text-muted">#{{ $u->id }}</small>
                        </label>
                    </td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @if ($u->isAdmin)
                        <span class="badge text-dark">Admin</span>
                        @elseif ($u->isInstructor)
                        <span class="badge text-dark">{{ tr('Instructor') }}</span>
                        @else
                        <span class="badge text-dark">Student</span>
                        @endif

                        @if ($u->active_status == 2)
                        <span class="badge badge-danger">Blocked</span>
                        @endif
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-primary">
                            <i class="la la-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {!! $users->appends(request()->input())->links() !!}
    </div>
</form>
</div>
@else
<!-- No Data Message -->
{!! no_data() !!}
@endif

</div>
<!-- Page main content END -->

@endsection

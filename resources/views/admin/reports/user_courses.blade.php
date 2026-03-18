@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">
        <!-- DataTables CSS (CDN) -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

        <div class="row">
            <div class="col-12 mb-3">
                <h1 class="h3 mb-2 mb-sm-0">Reports - User Courses</h1>
                <p class="text-muted">Per-user course enrolment and completion overview</p>
            </div>
        </div>

        <form method="GET" class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Filter by user</label>
                        <select id="user_id" name="user_id" class="form-select">
                            <option value="">All users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ (string)$selectedUserId === (string)$u->id ? 'selected' : '' }}>{{ $u->name }} (ID: {{ $u->id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-funnel me-1"></i> Apply Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                @if(isset($rows) && count($rows))
                    <div class="table-responsive">
                        <table id="user-courses-table" class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <!-- <th>Status</th> -->
                                    <th>Enrollment Status</th>
                                    <th>Enrolled At</th>
                                    <th>Completion Status</th>
                                    <th>Completed At</th>
                                    <!-- <th class="text-end">Pass</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $r)
                                    <tr>
                                        <td>{{ $r->user_id }}</td>
                                        <td>{{ $r->user_name }}</td>
                                        <td>{{ $r->course_id }}</td>
                                        <td>{{ $r->course_name }}</td>
                                        <!-- <td>{{ $r->status }}</td> -->
                                        <td>{{ $r->enrolment_status }}</td>
                                        <td>{{ $r->enrolled_at }}</td>
                                        <td>{{ $r->completion_status }}</td>
                                        <td>{{ $r->completed_at }}</td>
                                        <!-- <td class="text-end">{{ $r->pass_flag ? '1' : '0' }}</td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mb-0 text-muted">No data found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <!-- DataTables JS (CDN) -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        (function($){
            $(function(){
                var $tbl = $('#user-courses-table');
                if ($tbl.length) {
                    $tbl.DataTable({
                        pageLength: 25,
                        order: [],
                        columnDefs: [
                            { targets: [9], className: 'text-end' }
                        ]
                    });
                }
            });
        })(jQuery);
    </script>
@endsection


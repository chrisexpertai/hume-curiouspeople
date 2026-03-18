@extends('layouts.admin')

@section('content')
    <div class="page-content-wrapper border">
        <!-- DataTables CSS (CDN) -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

        <div class="row">
            <div class="col-12 mb-3">
                <h1 class="h3 mb-2 mb-sm-0">Reports</h1>
                <p class="text-muted">Managers and Directors course completion summary</p>
            </div>
        </div>

        <!-- <form method="GET" class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="jt" class="form-label">Job title contains (use | for OR)</label>
                        <input type="text" id="jt" name="jt" class="form-control" placeholder="Manager|Director" value="{{ old('jt', $filters['jt'] ?? 'Manager|Director') }}" />
                        <small class="text-muted">Default: Manager|Director</small>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="hidden" name="users_active" value="0" />
                            <input class="form-check-input" type="checkbox" value="1" id="users_active" name="users_active" {{ ($filters['users_active'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="users_active">Only active users</label>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="courses_active" value="0" />
                            <input class="form-check-input" type="checkbox" value="1" id="courses_active" name="courses_active" {{ ($filters['courses_active'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="courses_active">Only active courses</label>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-funnel me-1"></i> Apply Filters</button>
                    </div>
                </div>
            </div>
        </form> -->

        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        @isset($lastSnapshotMonth)
                            <small class="text-muted">Comparing against snapshot: {{ date('F Y', strtotime($lastSnapshotMonth)) }}</small>
                        @else
                            <small class="text-muted">No previous snapshot found</small>
                        @endisset
                    </div>
                    <form method="POST" action="{{ route('admin.reports.snapshot') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Take Snapshot (this month)
                        </button>
                    </form>
                </div>
                @if(isset($rows) && count($rows))
                    <div class="table-responsive">
                        <table id="reports-table" class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'user_id', 'dir' => (($filters['sort'] ?? 'job_title') === 'user_id' && ($filters['dir'] ?? 'asc') === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">User ID</a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'full_name', 'dir' => (($filters['sort'] ?? 'job_title') === 'full_name' && ($filters['dir'] ?? 'asc') === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">Full Name</a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'job_title', 'dir' => (($filters['sort'] ?? 'job_title') === 'job_title' && ($filters['dir'] ?? 'asc') === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">Job Title</a>
                                    </th>
                                    <th class="text-end">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_required', 'dir' => (($filters['sort'] ?? 'job_title') === 'total_required' && ($filters['dir'] ?? 'asc') === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">Total Required</a>
                                    </th>
                                    <th class="text-end">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_completed', 'dir' => (($filters['sort'] ?? 'job_title') === 'total_completed' && ($filters['dir'] ?? 'asc') === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">Total Completed</a>
                                    </th>
                                    <th class="text-end">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'completion_pct', 'dir' => (($filters['sort'] ?? 'job_title') === 'completion_pct' && ($filters['dir'] ?? 'asc') === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">Completion %</a>
                                    </th>
                                    <th class="text-end">Diff vs last month</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $r)
                                    <tr>
                                        <td>{{ $r->user_id }}</td>
                                        <td>{{ $r->full_name }}</td>
                                        <td>{{ $r->job_title }}</td>
                                        <td class="text-end">{{ number_format($r->total_required) }}</td>
                                        <td class="text-end">{{ number_format($r->total_completed) }}</td>
                                        <td class="text-end">{{ number_format($r->completion_pct, 1) }}%</td>
                                        @php
                                            $uid = (int) $r->user_id;
                                            $prev = isset($lastSnapshotPctByUser[$uid]) ? (float) $lastSnapshotPctByUser[$uid] : null;
                                            $diff = is_null($prev) ? null : (float) $r->completion_pct - $prev;
                                        @endphp
                                        <td class="text-end">
                                            @if (is_null($prev))
                                                <span class="text-muted">n/a</span>
                                            @else
                                                @if ($diff > 0)
                                                    <span class="text-success">+{{ number_format($diff, 1) }}%</span>
                                                @elseif ($diff < 0)
                                                    <span class="text-danger">{{ number_format($diff, 1) }}%</span>
                                                @else
                                                    <span>{{ number_format($diff, 1) }}%</span>
                                                @endif
                                            @endif
                                        </td>
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

        <!-- Debug footer: snapshot matching diagnostics -->
        <!-- <div class="card mt-3">
            <div class="card-body small text-muted">
                @php
                    $currentCount = isset($rows) ? count($rows) : 0;
                    $snapshotCount = isset($lastSnapshotPctByUser) ? count($lastSnapshotPctByUser) : 0;
                    $matched = 0;
                    if ($currentCount && $snapshotCount) {
                        foreach ($rows as $rr) {
                            $uidDbg = (int) $rr->user_id;
                            if (isset($lastSnapshotPctByUser[$uidDbg])) { $matched++; }
                        }
                    }
                    $u77Current = null; $u77Prev = null;
                    if (isset($rows)) {
                        foreach ($rows as $rr) { if ((int)$rr->user_id === 77) { $u77Current = (float)$rr->completion_pct; break; } }
                    }
                    if (isset($lastSnapshotPctByUser[77])) { $u77Prev = (float)$lastSnapshotPctByUser[77]; }
                @endphp
                <div>Current rows: {{ $currentCount }} | Snapshot rows: {{ $snapshotCount }} | Matched users: {{ $matched }}</div>
                <div>
                    Snapshot month: @if(isset($lastSnapshotMonth) && $lastSnapshotMonth) {{ date('F Y', strtotime($lastSnapshotMonth)) }} @else none @endif
                </div>
                <div>User 77 - current %: {{ is_null($u77Current) ? 'n/a' : number_format($u77Current,1) }} | prev %: {{ is_null($u77Prev) ? 'n/a' : number_format($u77Prev,1) }} | diff: @if(!is_null($u77Current) && !is_null($u77Prev)) {{ number_format($u77Current - $u77Prev,1) }}% @else n/a @endif</div>
            </div>
        </div>
    </div> -->
@endsection

@section('page-js')
    <!-- DataTables JS (CDN) -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        (function($){
            $(function(){
                var $tbl = $('#reports-table');
                if ($tbl.length) {
                    $tbl.DataTable({
                        pageLength: 25,
                        order: [], // keep server-provided or unsorted by default
                        columnDefs: [
                            { targets: [3,4,5,6], className: 'text-end' }
                        ]
                    });
                }
            });
        })(jQuery);
    </script>
@endsection

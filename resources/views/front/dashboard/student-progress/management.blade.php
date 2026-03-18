@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $title }}</h4>
        @if(function_exists('route_has') ? route_has('student_progress') : true)
            <a href="{{ route('student_progress') }}" class="btn btn-outline-secondary btn-sm">{{ __('Back to Student Progress') }}</a>
        @endif
    </div>

    @if(!empty($results) && count($results))
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('User ID') }}</th>
                        <th>{{ __('Full Name') }}</th>
                        <th>{{ __('Job Title') }}</th>
                        <th>{{ __('Total Required') }}</th>
                        <th>{{ __('Total Completed') }}</th>
                        <th>{{ __('Completion %') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $row)
                        <tr>
                            <td>{{ $row->user_id }}</td>
                            <td>{{ $row->full_name }}</td>
                            <td>{{ $row->job_title }}</td>
                            <td>{{ $row->total_required }}</td>
                            <td>{{ $row->total_completed }}</td>
                            <td>{{ number_format((float)$row->completion_pct, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center my-5">
            {!! no_data(null, __('No matching users found'), 'my-5' ) !!}
        </div>
    @endif
</div>
@endsection


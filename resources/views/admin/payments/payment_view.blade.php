@extends('layouts.admin')

@section('page-header-right')
    <a href="{{route('payments')}}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{__a('payments')}}"> <i class="la la-arrow-circle-left"></i> {{__a('back_to_payments')}} </a>
@endsection

@section('content')

<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-2 mb-sm-4">{{ tr('Payment Details') }}</h1>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card bg-transparent">
                <div class="card-body">
                    <table class="table table-striped table-bordered table-sm">
                        @foreach($payment->toArray() as $col_name => $col_value)
                            @if(trim($col_value))
                                <tr>
                                    <th>{{ucwords(str_replace('_', ' ', $col_name))}}</th>
                                    <td>
                                        @if($col_name === 'status')
                                            {!! $payment->status_context !!}
                                        @elseif($col_name === 'amount' || $col_name === 'fees_amount' || $col_name === 'total_amount' || $col_name === 'fees_total')
                                            {!! price_format($col_value) !!}
                                        @elseif($col_name === 'created_at' || $col_name === 'updated_at')
                                            {!! date(date_time_format(), strtotime($col_value)) !!}
                                        @else
                                            {{$col_value}}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <!-- Courses Associated -->
        <div class="col-lg-6">
            <div class="card bg-transparent">
                <div class="card-header bg-transparent border-bottom">
                    <h3 class="h5 mb-0">{{ tr('Associated to') }}</h3>
                </div>
                <div class="card-body">
                    @if($payment->courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered bg-white">
                            <!-- Table header -->
                            <thead>
                                <tr>
                                    <th>{{ tr('Thumbnail') }}</th>
                                    <th>{{ tr('Title') }}</th>
                                    <th>{{ tr('Price') }}</th>
                                    <th>{{ tr('Action') }}</th>
                                </tr>
                            </thead>
                            <!-- Table body -->
                            <tbody>
                                @foreach($payment->courses as $course)
                                    <tr>
                                        <td><img src="{{$course->thumbnail_url}}" width="80" /></td>
                                        <td>
                                            <p><strong>{{$course->title}}</strong>{!! $course->status_html() !!}</p>
                                            <p class="text-muted">
                                                @php
                                                    $lectures_count = $course->lectures->count();
                                                    $assignments_count = $course->assignments->count();
                                                @endphp
                                                <span class="course-list-lecture-count">{{$lectures_count}} {{ tr('Lectures') }}</span>
                                                @if($assignments_count)
                                                    , <span class="course-list-assignment-count">{{$assignments_count}} {{ tr('Assignments') }}</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td>{!! $course->price_html() !!}</td>
                                        <td>
                                            <a href="{{route('course', $course->slug)}}" class="btn btn-sm btn-primary" target="_blank"><i class="la la-eye"></i> {{ tr('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif($payment->subscriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered bg-white">
                        <!-- Table header -->
                        <thead>
                            <tr>
                                {{-- <th>Thumbnail</th> --}}
                                <th>{{ tr('Title') }}</th>
                                <th>{{ tr('Price') }}</th>
                                <th>{{ tr('Action') }}</th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody>
                    @foreach($payment->subscriptions as $subscription)
                        <p><strong>{{$subscription->name}}</strong> </p>


                <tr>
                    {{-- <td><img src="{{$subscription->thumbnail_url}}" width="80" /></td> --}}
                    <td>
                        <p><strong>{{$subscription->title}}</strong>{{$subscription->name}}</p>

                    </td>
                    <td>{{$subscription->price}}</td>
                    <td>
                        <a href="{{route('subscription-plans.show', $subscription->id)}}" class="btn btn-sm btn-primary" target="_blank"><i class="la la-eye"></i> View</a>
                    </td>
                </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
                @else
                    <div class="no-data-wrap">
                        <h3>{{ tr('No data available') }}</h3>
                    </div>
                @endif



                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Form -->
    <div class="row mt-4">
        <div class="col-md-6">
            <form action="{{route('update_status', $payment->id)}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="status">{{ tr('Update Payment Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">{{ tr('Filter by Status') }}</option>
                        <option value="initial" {{selected('initial', $payment->status)}} >{{ tr('Initial') }}</option>
                        <option value="pending" {{selected('pending', $payment->status)}} >{{ tr('Pending') }}</option>
                        <option value="onhold" {{selected('onhold', $payment->status)}} >{{ tr('On Hold') }}</option>
                        <option value="success" {{selected('success', $payment->status)}} >{{ tr('Success') }}</option>
                        <option value="failed" {{selected('failed', $payment->status)}} >{{ tr('Failed') }}</option>
                        <option value="declined" {{selected('declined', $payment->status)}} >{{ tr('Declined') }}</option>
                        <option value="dispute" {{selected('dispute', $payment->status)}} >{{ tr('Dispute') }}</option>
                        <option value="expired" {{selected('expired', $payment->status)}} >{{ tr('Expired') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info">{{__a('update_status')}}</button>
            </form>
        </div>
    </div>
</div>

@endsection

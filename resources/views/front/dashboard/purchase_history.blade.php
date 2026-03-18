@extends('layouts.dashboard')

@section('content')

@php
$auth_user = auth()->user();


$purchases = $auth_user->purchases()->paginate(50);
@endphp

<div class="col-xl-12">
    <div class="card bg-transparent border rounded-3">
        <!-- Card header START -->
        <div class="card-header bg-transparent border-bottom">
            <h3 class="mb-0">{{ tr('Purchase History') }}</h3>
        </div>
        <!-- Card header END -->

        <!-- Card body START -->
        <div class="card-body">

            <!-- Title and search START -->
            <div class="row g-3 align-items-center justify-content-between mb-4">
                <!-- Search form -->
                <div class="col-md-8">
                    <form class="rounded position-relative">
                        <input class="form-control pe-5 bg-transparent" type="search" placeholder="{{ tr('Search') }}" aria-label="Search">
                        <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                            <i class="fas fa-search fs-6 "></i>
                        </button>
                    </form>
                </div>

                <!-- Sort by filter -->
                <div class="col-md-3">
                    <form>
                        <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false">
                            <div class="choices__inner">
                                <select class="form-select js-choice border-0 z-index-9 bg-transparent choices__input" aria-label=".form-select-sm" hidden="" tabindex="-1" data-choice="active">
                                    <option value="" data-custom-properties="[object Object]">{{ tr('Sort by') }}</option>
                                </select>
                                <div class="choices__list choices__list--single">
                                    <div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">{{ tr('Sort by') }}</div>
                                </div>
                            </div>
                            <div class="choices__list choices__list--dropdown" aria-expanded="false">
                                <input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Sort by" placeholder="">
                                <div class="choices__list" role="listbox">
                                    <div id="choices--u1g8-item-choice-4" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="4" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">{{ tr('Sort by') }}</div>
                                    <div id="choices--u1g8-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="Free" data-select-text="Press to select" data-choice-selectable="">Free</div>
                                    <div id="choices--u1g8-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Newest" data-select-text="Press to select" data-choice-selectable="">{{ tr('Newest') }}</div>
                                    <div id="choices--u1g8-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="Oldest" data-select-text="Press to select" data-choice-selectable="">{{ tr('Oldest') }}</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Title and search END -->

            <!-- Purchase list table START -->
            <div class="table-responsive border-0">
                <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">
                    <!-- Table head -->
                    <thead>
                        <tr>
                            <th scope="col" class="border-0 rounded-start">ID</th>
                            <th scope="col" class="border-0">{{ tr('Amount') }}</th>
                            <th scope="col" class="border-0">{{ tr('Method') }}</th>
                            <th scope="col" class="border-0">{{ tr('Time') }}</th>
                            <th scope="col" class="border-0">{{ tr('Status') }}</th>
                            <th scope="col" class="border-0">{{ tr('Action') }}</th>
                        </tr>
                    </thead>
                    <!-- Table body START -->
                    <tbody>
                        @if($purchases->count() > 0)
                        @foreach($purchases as $purchase)
                        <tr>
                            <td>
                                <small class="text-muted">#{{$purchase->id}}</small>
                            </td>
                            <td>
                                {!!price_format($purchase->amount)!!}
                            </td>
                            <td>{!!ucwords(str_replace('_', ' ', $purchase->payment_method))!!}</td>
                            <td>
                                <small>
                                    {!!$purchase->created_at->format(get_option('date_format'))!!} <br />
                                    {!!$purchase->created_at->format(get_option('time_format'))!!}
                                </small>
                            </td>
                            <td>
                                {!! $purchase->status_context !!}
                            </td>
                            <td>
                                @if($purchase->status == 'success')
                                <span class="text-success" data-toggle="tooltip" title="{!!$purchase->status!!}"><i class="fa fa-check-circle-o"></i> </span>
                                @else
                                <span class="text-warning" data-toggle="tooltip" title="{!!$purchase->status!!}"><i class="fa fa-exclamation-circle"></i> </span>
                                @endif

                                <a href="{!!route('purchase_view', $purchase->id)!!}" class="btn btn-info"><i class="la la-eye"></i> </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">{!! no_data() !!}</td>
                        </tr>
                        @endif
                    </tbody>
                    <!-- Table body END -->
                </table>
            </div>
            <!-- Purchase list table END -->

            <!-- Pagination START -->
            <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
                <!-- Content -->
                <p class="mb-0 text-center text-sm-start">Showing {{ $purchases->count() }} to {{ $purchases->total() }} of {{ $purchases->count() }} entries</p>
                <!-- Pagination -->
                <nav class="d-flex justify-content-center mb-0" aria-label="navigation">
                    {!! $purchases->appends(request()->input())->links() !!}
                </nav>
            </div>
            <!-- Pagination END -->
        </div>
        <!-- Card body START -->
    </div>
    <!-- Payout END -->
</div>

@endsection

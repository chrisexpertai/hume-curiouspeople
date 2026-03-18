@extends(('layouts.dashboard'))

@section('content')

    @php
    $reviews = $auth_user->get_reviews()->with('course', 'user', 'user.photo_query')->orderBy('created_at', 'desc')->paginate(20);
    $auth_user = auth()->user();


   @endphp



<div class="col-xl-12">
    <!-- Student review START -->
    <div class="card border bg-transparent rounded-3">
        <!-- Header START -->
        <div class="card-header bg-transparent border-bottom">
            <div class="row justify-content-between align-middle">
                <!-- Title -->
                <div class="col-sm-6">
                    <h3 class="card-header-title mb-2 mb-sm-0">{{ tr('Student review') }}</h3>
                </div>

                <!-- Short by filter -->
                <div class="col-sm-4">
                    <form>
                        <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-select js-choice z-index-9 bg-white choices__input" aria-label=".form-select-sm" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">{{ tr('Sort by') }}</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">{{ tr('Sort by') }}</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Sort by" placeholder=""><div class="choices__list" role="listbox"><div id="choices--032x-item-choice-6" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="6" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">{{ tr('Sort by') }}</div><div id="choices--032x-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="★★★★★ (5/5)" data-select-text="Press to select" data-choice-selectable="">★★★★★ (5/5)</div><div id="choices--032x-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="★★★★☆ (4/5)" data-select-text="Press to select" data-choice-selectable="">★★★★☆ (4/5)</div><div id="choices--032x-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="★★★☆☆ (3/5)" data-select-text="Press to select" data-choice-selectable="">★★★☆☆ (3/5)</div><div id="choices--032x-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="★★☆☆☆ (2/5)" data-select-text="Press to select" data-choice-selectable="">★★☆☆☆ (2/5)</div><div id="choices--032x-item-choice-5" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="5" data-value="★☆☆☆☆ (1/5)" data-select-text="Press to select" data-choice-selectable="">★☆☆☆☆ (1/5)</div></div></div></div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Header END -->

        <!-- Reviews START -->
        <div class="card-body mt-2 mt-sm-4">

            <!-- Review item START -->

            @foreach($reviews as $review)

            <div class="d-sm-flex">
                <!-- Avatar image -->
                <img class="avatar avatar-lg rounded-circle float-start me-3" src="{!! $review->user->get_photo !!} " alt="avatar">
                <div>
                    <div class="mb-3 d-sm-flex justify-content-sm-between align-items-center">
                        <!-- Title -->
                        <div>
                            <h5 class="m-0"> {!! $review->user->name !!}</h5>
                            <span class="me-3 small">{{ $review->created_at->format('M d, Y') }} </span>
                        </div>
                        <!-- Review star -->
                        <ul class="list-inline mb-0">
                            @php
                            $rating = $review->rating; // Assuming rating field is present in your Review model
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $rating)
                                <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                            @else
                                <li class="list-inline-item me-0"><i class="far fa-star text-warning"></i></li>
                            @endif
                        @endfor
                        </ul>
                    </div>
                    <!-- Content -->
                    <h6><span class="text-body fw-light">Review on:</span> {{$review->course->title}}</h6>
                    <p>{{$review->course->short_description}}</p>
                    <!-- Button -->
                    <div class="text-end">
                        <a href="{{route('course', $review->course->slug)}}" class="btn btn-sm btn-primary-soft mb-1 mb-sm-0">{{ tr('View')}}</a>

                        <!-- collapse textarea -->
                        <div class="collapse hide" id="collapseComment">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Divider -->
        <hr>
            @endforeach

            <!-- Review item END -->

        </div>
        <!-- Reviews END -->

        <div class="card-footer border-top">
            <!-- Pagination START -->
            {!! $reviews->links(); !!}

            <!-- Pagination END -->
        </div>
    </div>
    <!-- Student review END -->
</div>



@endsection

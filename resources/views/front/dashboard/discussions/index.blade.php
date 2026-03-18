@extends(('layouts.dashboard'))


@section('content')

    @php

         $auth_user = auth()->user();

        $discussions = $auth_user->instructor_discussions()->with('course', 'content')->orderBy('replied', 'asc')->orderBy('updated_at', 'desc')->paginate(20);
    @endphp





<div class="col-xl-12">
    <!-- Student review START -->
    <div class="card border bg-transparent rounded-3">
        <!-- Header START -->
        <div class="card-header bg-transparent border-bottom">
            <div class="row justify-content-between align-middle">
                <!-- Title -->
                <div class="col-sm-6">
                    <h3 class="card-header-title mb-2 mb-sm-0">{{ tr('Discussions') }}</h3>
                </div>

                <!-- Short by filter -->
                <div class="col-sm-4">
                    <form>
                        <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-select js-choice z-index-9 bg-white choices__input" aria-label=".form-select-sm" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">{{ tr('Sort by') }}</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">{{ tr('Sort by') }}</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Sort by" placeholder=""><div class="choices__list" role="listbox"><div id="choices--im5i-item-choice-6" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="6" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">{{ tr('Sort by') }}</div><div id="choices--im5i-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="★★★★★ (5/5)" data-select-text="Press to select" data-choice-selectable="">★★★★★ (5/5)</div><div id="choices--im5i-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="★★★★☆ (4/5)" data-select-text="Press to select" data-choice-selectable="">★★★★☆ (4/5)</div><div id="choices--im5i-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="★★★☆☆ (3/5)" data-select-text="Press to select" data-choice-selectable="">★★★☆☆ (3/5)</div><div id="choices--im5i-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="★★☆☆☆ (2/5)" data-select-text="Press to select" data-choice-selectable="">★★☆☆☆ (2/5)</div><div id="choices--im5i-item-choice-5" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="5" data-value="★☆☆☆☆ (1/5)" data-select-text="Press to select" data-choice-selectable="">★☆☆☆☆ (1/5)</div></div></div></div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Header END -->

        <!-- Reviews START -->
        <div class="card-body mt-2 mt-sm-4">




    @if($discussions->count())
    @foreach($discussions as $discussion)


            <!-- Review item START -->
            <div class="d-sm-flex">
                <!-- Avatar image -->
                <img class="avatar avatar-lg rounded-circle float-start me-3"  href="{{route('profile', $discussion->user->id)}}" src="{!! $discussion->user->get_photo !!}" alt="avatar">
                <div class="w-100">
                    <div class="mb-3 d-sm-flex justify-content-sm-between align-items-center">
                        <!-- Title -->
                        <div>
                            <h5 class="m-0">{!! $discussion->user->name !!}</h5>
                            <span class="me-3 small">{{$discussion->created_at->diffForHumans()}}</span>
                        </div>

                    </div>
                    <!-- Content -->
                    <a href="{{$discussion->course->url}}"><span class="text-body fw-light">Course & Content:</span> {!! $discussion->course->title !!} •  {{$discussion->content->title}} </a>
                    <h6><span class="text-body fw-light">Discussion Title:</span> {{$discussion->title}}</h6>

                    {!! nl2br($discussion->message) !!}



                    @if($discussion->replies->count())
                    @foreach($discussion->replies as $reply)
                    <div class="d-md-flex mb-4 ps-4 ps-md-5 mt-4">
                        <!-- Avatar -->
                        <div class="avatar avatar-lg me-4 flex-shrink-0">
                            <img class="avatar-img rounded-circle" href="{{route('profile', $reply->user->id)}}" src="{!! $reply->user->get_photo !!}" alt="avatar">
                        </div>
                        <!-- Text -->
                        <div>
                            <div class="d-sm-flex mt-1 mt-md-0 align-items-center">
                                <h5 class="me-3 mb-0">{!! $reply->user->name !!}</h5>
                            </div>
                            <!-- Info -->
                            <p class="small mb-2">{{$reply->created_at->diffForHumans()}}</p>

                            <p class="mb-2">{!! nl2br($reply->message) !!}</p>
                        </div>
                    </div>


                    @endforeach
                    @endif

                    <!-- Button -->
                    <div class="text-end">
                        {{-- <a href="#" class="btn btn-sm btn-primary-soft mb-1 mb-sm-0">Direct message</a> --}}
                        <a class="btn btn-sm btn-light mb-0" data-bs-toggle="collapse" href="#collapse{{$discussion->id}}" role="button" aria-expanded="false" aria-controls="collapse{{$discussion->id}}">
                            Reply
                        </a>



                            <!-- collapse textarea -->
                            <div class="collapse" id="collapse{{$discussion->id}}">
                                <form action="{{ route('discussion_reply_post', $discussion->id) }}" method="post">
                                    <div class="d-flex mt-3">

                                        @csrf
                                        <textarea class="form-control mb-0" name="message" placeholder="Add a comment..." rows="2" spellcheck="false"></textarea>
                                        {!! form_error($errors, 'message')->message !!}

                                        <button type="submit" class="btn btn-sm btn-primary-soft ms-2 px-4 mb-0 flex-shrink-0"><i class="fas fa-paper-plane fs-5"></i></button>
                                    </div>

                                    </form>


                            </div>

                    </div>
                </div>
            </div>


            <!-- Divider -->
            <hr>
            <!-- Review item END -->
     @endforeach

    @else
        {!! no_data() !!}
    @endif

        </div>
        <!-- Reviews END -->

        <div class="card-footer">
            <!-- Pagination START -->
            <div class="d-sm-flex justify-content-sm-between align-items-sm-center">
                <!-- Content -->
                 {!! $discussions->links() !!}

            <!-- Pagination END -->
        </div>
    </div>
    <!-- Student review END -->
</div>




@endsection

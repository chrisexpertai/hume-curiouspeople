@extends('layouts.dashboard')

@section('content')

<div class="col-xl-12">
    <!-- Student review START -->
    <div class="card border bg-transparent rounded-3">
        <!-- Header START -->
        <div class="card-header bg-transparent border-bottom">
            <div class="row justify-content-between align-middle">
                <!-- Title -->
                <div class="col-sm-6">
                    <h3 class="card-header-title mb-2 mb-sm-0">{{ tr('Ticket:') }} {{ $ticket->subject }} </h3>
                </div>
            </div>
        </div>

        <div class="card-body mt-2 mt-sm-4">

            <!-- Review item START -->
            <div class="d-sm-flex">
                <!-- Avatar image -->
                <img class="avatar avatar-lg rounded-circle float-start me-3" src="{{ $ticket->user->get_photo }}" alt="avatar">
                <div class="w-100">
                    <div class="mb-3 justify-content-sm-between align-items-center">
                        <!-- Title -->
                        <div>
                            <h5 class="m-0">{{ $ticket->user->name }}</h5>
                            <span class="me-3 small">{{ $ticket->created_at->format('F j, Y \a\t g:i a') }}</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <h6><span class="text-body fw-light">{{ tr('Subject:') }}</span> {{ $ticket->subject }}</h6>
                    {!! $ticket->content !!}


                    @if($replies->count() > 0)
                        <h4 class="py-3">@lang('frontend.replies'):</h4>
                        <ul class="list-group">
                            @foreach($replies->sortBy('created_at') as $reply)
                                <li class="list-group-item reply-item">
                                    <div class="reply-header">
                                        <strong>{{ $reply->user->name }}</strong>
                                        <small>{{ $reply->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="reply-content">{{ $reply->content }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="no-replies-msg">@lang('frontend.no_replies')</p>
                    @endif

                    <div class="text-end">
                        <!-- collapse textarea -->
                        <div class="collapse show" id="collapseComment">
                            <form method="post" action="{{ route('user.tickets.respond', $ticket->id) }}" class="reply-form">
                                @csrf
                                <div class="d-flex mt-3">
                                    <textarea class="form-control mb-0" id="reply" name="reply" placeholder="{{ tr('Add a comment...') }}" rows="2" spellcheck="false" required></textarea>
                                    <button type="submit" class="btn btn-sm btn-primary-soft ms-2 px-4 mb-0 flex-shrink-0"><i class="fas fa-paper-plane fs-5"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr>
                </div>
            </div>
            <!-- Review item END -->
        </div> <!-- This closing div was missing -->
    </div>
</div>
@endsection

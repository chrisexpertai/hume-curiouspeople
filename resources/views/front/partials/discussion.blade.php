<div class="piksera-course-player-discussions .bg-light">
   <div class="piksera-course-player-discussions__wrapper">
       <div class="piksera-course-player-discussions__mobile-header">
           <h3 class="piksera-course-player-discussions__mobile-title">{{ tr('Discussions') }}</h3>
           <span class="piksera-course-player-discussions__mobile-close"></span>
       </div>
       <div class="piksera-course-player-discussions__content">
           <div class="piksera-discussions">
               <!-- Discussion Form -->
               <div id="course-discussion-wrap" class="container py-4">
                <div class="discussion-calltoaction-wrap text-center mb-5">
                    <h1><i class="far fa-question-circle-o"></i></h1>
                    <h2 class="mb-3">{{ tr('Discussion') }}</h2>
                    <p class="mb-4">{{ tr('Get answers directly from your instructor if you have any questions about this topic.') }}</p>
                </div>

                <div id="content-discussions-list-wrap">
                    @foreach($content->discussions as $discussion)
                        <div class="discussion-single-wrap mb-5 p-3 border rounded-4">
                            <div class="discussion-user d-flex align-items-center mb-3">
                                <div class="reviewed-user-photo me-3">
                                    <a href="{{ route('profile', $discussion->user->id) }}">

                                        <img src="{!! $discussion->user->get_photo !!}" class="avatar avatar-lg rounded-circle float-start me-3">

                                    </a>
                                </div>
                                <div class="discussion-user-info flex-grow-1">
                                    <a href="{{ route('profile', $discussion->user->id) }}" class="text-decoration-none fw-bold">{{ $discussion->user->name }}</a>
                                    <p class="text-muted mb-0">{{ $discussion->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <h3 class="discussion-title mb-3">{{ $discussion->title }}</h3>

                            <div class="discusison-details-wrap mb-3">
                                {!! nl2br(clean_html($discussion->message)) !!}
                            </div>

                            @if($discussion->replies->count())
                                @foreach($discussion->replies as $reply)
                                    <div class="discussion-reply-wrap bg-light p-3 mb-3">
                                        <div class="discussion-user d-flex align-items-center mb-3">
                                            <div class="reviewed-user-photo me-3">
                                                <a href="{{ route('profile', $reply->user->id) }}">
                                                    {!! $reply->user->get_photo !!}
                                                </a>
                                            </div>
                                            <div class="discussion-user-info flex-grow-1">
                                                <a href="{{ route('profile', $reply->user->id) }}" class="text-decoration-none fw-bold">{{ $reply->user->name }}</a>
                                                <p class="text-muted mb-0">{{ $reply->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('discussion_reply', $reply->id) }}" class="text-decoration-none"><h4 class="discussion-reply-title mb-3">{{ $reply->title }}</h4></a>
                                        <div class="discusison-details-wrap">
                                            {!! nl2br(clean_html($reply->message)) !!}
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div class="discussion-reply-form bg-light p-3">
                                <form action="{{ route('discussion_reply_student', $discussion->id) }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" rows="5" placeholder="Type your reply here..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="far fa-reply"></i> {{ __t('send_reply') }}</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="discussion-form-wrap">
                    <h2 class="mb-3">{{ __t('ask_question') }}</h2>
                    <form id="discussion-form" action="{{ route('ask_question') }}" method="post">
                        @csrf
                        <input type="hidden" name="content_id" value="{{ $content->id }}">
                        <div class="form-group mb-3">
                            <label for="question-title" class="form-label">{{ __t('question_title') }}</label>
                            <input type="text" class="form-control" id="question-title" name="title" value="">
                        </div>
                        <div class="form-group mb-4">
                            <label for="question-details" class="form-label">{{ __t('question_details') }}</label>
                            <textarea class="form-control" id="question-details" name="message" rows="5" placeholder="Type your question here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __t('ask_question') }}</button>
                    </form>
                </div>
            </div>

       </div>
   </div>
</div>


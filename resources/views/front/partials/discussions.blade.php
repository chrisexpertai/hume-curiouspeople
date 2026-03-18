    <!-- Ask Your Question -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">{{ __t('discussions') }}</h5>

            {{-- <!-- Comment box -->
            <div class="d-flex mb-4">
                <!-- Avatar -->
                <div class="avatar avatar-sm flex-shrink-0 me-2">
                    <a href="#">
                        <img class="avatar-img rounded-circle" src="{{ Auth::user()->get_photo }}" alt="{{ Auth::user()->name }}">
                    </a>
                </div>

                <form class="w-100 d-flex" action="{{ route('ask_question') }}" method="post">
                    @csrf
                    <textarea class="one form-control pe-4 bg-light" id="autoheighttextarea" rows="1" placeholder="{{ __t('add_comment') }}" name="message"></textarea>
                    <button class="btn btn-primary ms-2 mb-0" type="submit">{{ __t('post') }}</button>
                </form>
            </div> --}}

            @php
            
                        $content = \App\Models\Content::where('course_id', $course->id)->get();
            @endphp

            <!-- Discussion items -->
            @if(isset($content) && $content->count() > 0)
            @foreach($content as $contentItem)
                @if($contentItem->discussions->count() > 0)
                    @foreach($contentItem->discussions as $discussion)

                    <div class="border p-2 p-sm-4 rounded-3 mb-4">
                    <ul class="list-unstyled mb-0">
                        <li class="comment-item">
                            <div class="d-flex mb-3">
                                <!-- Avatar -->
                                <div class="avatar avatar-sm flex-shrink-0">
                                    <a href="{{ route('profile', $discussion->user->id) }}">
                                        <img class="avatar-img rounded-circle" src="{{ $discussion->user->get_photo }}" alt="{{ $discussion->user->name }}">
                                    </a>
                                </div>
                                <div class="ms-2">
                                    <!-- Comment by -->
                                    <div class="bg-light p-3 rounded">
                                        <div class="d-flex justify-content-center">
                                            <div class="me-2">
                                                <h6 class="mb-1 lead fw-bold"> <a href="{{ route('profile', $discussion->user->id) }}">{{ $discussion->user->name }}</a></h6>
                                                <p class="h6 mb-0">{{ $discussion->message }}</p>
                                            </div>
                                            <small>{{ $discussion->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <!-- Comment react -->
                                    <ul class="nav nav-divider py-2 small">
                                        <!-- Here you can add options like Like, Reply, View Replies if needed -->
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                @endforeach
                @endif
            @endforeach
        @endif

    </div>
    </div>

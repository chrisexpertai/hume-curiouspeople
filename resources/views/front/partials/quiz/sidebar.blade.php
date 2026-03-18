@php
    $auth_user = auth()->user();

    $previous = $content->previous;
    $next = $content->next;
    $is_completed = false;
    if ($auth_user && $content->is_completed){
        $is_completed = true;
    }
@endphp
<script defer="" src="/assets/js/toggler.js"></script>

<div class="piksera-course-player-curriculum">
   <div class="piksera-course-player-curriculum__wrapper">
      <div class="piksera-course-player-curriculum__mobile-header"><h3 class="piksera-course-player-curriculum__mobile-title"> Curriculum</h3> <span class="piksera-course-player-curriculum__mobile-close"></span></div>      <div class="piksera-course-player-curriculum__content">
       <div class="piksera-course-player-curriculum__title-wrapper">
           <h3 class="piksera-course-player-curriculum__title"> {{$course->title}}</h3>

   @php do_action('lecture_single_after_course_title', $course, $content); @endphp

   @if($auth_user)
       @php
           $drip_items = $course->drip_items;
           $review = has_review($auth_user->id, $course->id);
           $completed_percent = $course->completed_percent();
       @endphp

       @php do_action('lecture_single_before_progressbar', $course, $content); @endphp

       <div class="lecture-page-course-progress mb-4 px-4 p-20 text-center">
           <div class="progress mb-3">
               <div class="progress-bar bg-info" style="width: {{$completed_percent}}%"></div>
           </div>
           <div class="course-progress-percentage text-info d-flex justify-content-between">
               <p class="m-0">
               <span class="percentage">
                   {{$completed_percent}}%
               </span>
                   {{__t('complete')}}
               </p>
               @if($completed_percent >= 10)


               <div class="d-flex align-items-center mt-2 mt-md-0">
                <a href="#" class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#writeReviewModal">{{ $review ? __t('update_review') : __t('write_review')}}</a>
              </div>

               @endif
           </div>
       </div>

       @php do_action('lecture_single_after_progressbar', $course, $content); @endphp

   @endif

</div>

         <div class="piksera-curriculum-accordion ">
            <!-- Loop through sections -->
            @foreach($course->sections as $section)

            <div id="course-section-{{$section->id}}" class="course-course-section mb-4">

                <div class="section-header p-2 border-bottom d-flex">
                <span class="section-name flex-grow-1 ml-2 d-flex">
                    <strong class="flex-grow-1">{{$section->section_name}}</strong>

                    @if($auth_user && $drip_items && is_array($drip_items) && isset($drip_items['sections']) && in_array($section->id, $drip_items['sections']))

                    <i class="fas fa-lock pt-1"></i>
                    @endif
                </span>
                </div>

                <div class="course-section-body">

                    @if($section->items->count())
                        @foreach($section->items as $item)

                            @php
                                $is_completed = false;
                                if ($auth_user && $item->is_completed){
                                    $is_completed = true;
                                }
                                $runTime = $item->runtime;
                            @endphp

                            <div class="sidebar-section-item {{$item->id == $content->id? 'active' : ''}} {{$is_completed? 'completed' : ''}}">
                                <div class="section-item-title ">

                                    <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="piksera-curriculum-accordion__section" @if($is_completed) data-toggle="tooltip" title="{{__t('completed')}}" @endif>
                                        <span class="lecture-status-icon border-right pr-1">
                                            @if($is_completed)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @else
                                                <i class="fas fa-circle-fill"></i>
                                            @endif
                                        </span>
                                        <div class="title-container pl-2 flex-grow-1 d-flex">
                                            <span class="lecture-icon"> {!! $item->icon_html !!}</span>
                                            <span class="lecture-name flex-grow-1 piksera-curriculum-accordion__title-wrapper">
                                            {{$item->title}} {!! $runTime ? "<small>($runTime)</small>" : "" !!}
                                            </span>

                                            @if($auth_user && $drip_items && is_array($drip_items))
                                            @if(in_array($section->id, $drip_items['sections'] ?? []))
                                                <span><i class="fas fa-lock pt-1"></i></span>
                                            @elseif(in_array($item->id, $drip_items['contents'] ?? []))
                                                <span><i class="fas fa-lock pt-1"></i></span>
                                            @endif
                                            @endif

                                        </div>
                                    </a>

                                </div>
                            </div>
                        @endforeach

                    @endif


                </div>

            </div>
        @endforeach

         </div>
      </div>
   </div>
</div>

@if($auth_user)
    <div class="modal fade" id="writeReviewModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$course->title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{route('save_review', $course->id)}}" method="post">
                    <div class="modal-body">
                        <div id="review-writing-box" class="course-review-write-box-wrap">
                            @csrf

                            @php
                                $ratingValue = 5;
                                $review_text = '';
                                if ($review){
                                    $ratingValue = $review->rating;
                                    $review_text = $review->review;
                                }
                            @endphp
                            {!! star_rating_field($ratingValue) !!}

                            <div class="form-group">
                                <textarea name="review" class="form-control" rows="4">{!! $review_text !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="review-modal-footer">
                        <p class="review-modal-nofity-text">
                            <i class="fas fa-globe"></i> {{ tr('Your review will be posted publicly. Under') }} <strong>{{$auth_user->name}} {{$auth_user->last_name}}</strong>
                        </p>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-comment"></i>
                            @if($review)
                                {{__t('update_review')}}
                            @else
                                {{__t('write_review')}}
                            @endif
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__t('cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Wait for the document to be ready
        $(document).ready(function () {
            // Listen for the click event on the button with the specified ID
            $('#writeReviewButton').click(function () {
                // Trigger the modal to show
                $('#writeReviewModal').modal('show');
            });
        });
    </script>



@endif

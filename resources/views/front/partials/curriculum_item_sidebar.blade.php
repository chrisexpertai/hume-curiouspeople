<div class="piksera-course-player-curriculum">
    <div class="piksera-course-player-curriculum__wrapper">
       <!-- ... other HTML code ... -->
       <div class="piksera-course-player-curriculum__content">
        <div class="masterstudy-course-player-curriculum__title-wrapper">
            <h3 class="masterstudy-course-player-curriculum__title"></h3>

    @php do_action('lecture_single_after_course_title', $course, $content); @endphp

    @if($auth_user)
        @php
            $drip_items = $course->drip_items;
            $review = has_review($auth_user->id, $course->id);
            $completed_percent = $course->completed_percent();
        @endphp

        @php do_action('lecture_single_before_progressbar', $course, $content); @endphp

        <div class="lecture-page-course-progress mb-4 px-4 text-center">
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
                    <a href="#" class="text-center d-block write-review-text" data-toggle="modal" data-target="#writeReviewModal">
                        <i class="far fa-comment"></i> {{ $review ? __t('update_review') : __t('write_review')}}
                    </a>
                @endif
            </div>
        </div>

        @php do_action('lecture_single_after_progressbar', $course, $content); @endphp

    @endif

</div>

          <div class="piksera-curriculum-accordion ">
             <!-- Loop through sections -->
             @foreach($course->sections as $section)
                <div class="piksera-curriculum-accordion__wrapper ">
                   <div class="piksera-curriculum-accordion__section">
                      <h4 class="piksera-curriculum-accordion__section-title">{{ $section->section_name }}</h4>
                      <span class="piksera-curriculum-accordion__section-count">0/{{ $section->items->count() }}</span>
                      <span class="piksera-curriculum-accordion__toggler">
                         <img src="/images/chevron_up.svg" class="piksera-curriculum-accordion__toggler-icon">
                      </span>
                   </div>
                   <ul class="piksera-curriculum-accordion__list" style="display:none">
                      <!-- Loop through items in the section -->
                      @foreach($section->items as $item)
                         <li class="piksera-curriculum-accordion__item">
                            <a href="{{ route('single_'.$item->item_type, [$course->slug, $item->id]) }}" class="piksera-curriculum-accordion__link ">
                               <div class="piksera-curriculum-accordion__title-wrapper">
                                  <div class="piksera-curriculum-accordion__title">{{ $item->title }}</div>
                                  <span class="piksera-curriculum-accordion__check "></span>
                               </div>
                               <div class="piksera-curriculum-accordion__meta-wrapper">
                                  <!-- Use dynamic data from the database for runtime, icon, etc. -->
                                   <div class="piksera-curriculum-accordion__meta">{{ $item->runtime }}</div>
                               </div>
                            </a>
                         </li>
                      @endforeach
                   </ul>
                </div>
             @endforeach
          </div>
       </div>
    </div>
 </div>

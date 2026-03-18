@php
    $nav_items = course_edit_navs();
    $step_number = 1; // Initialize the step number
@endphp

<div id="stepper" class="bs-stepper stepper-outline">
    <!-- Card header -->
    <div class="card-header rounded-5 bg-light border-bottom px-lg-5">

        <!-- Step Buttons START -->
        <div class="bs-stepper-header" role="tablist">

            <!-- Check if there are navigation items -->
            @if(is_array($nav_items) && count($nav_items))

                <!-- Loop through navigation items -->
                @foreach($nav_items as $route => $nav_item)

                    <!-- Step -->
                    <div class="step {{ array_get($nav_item, 'is_active') ? 'active' : '' }}" data-target="#step-{{$step_number}}">
                        <div class="d-grid text-center align-items-center">
                            <a type="button" href="{{ route($route, $course->id) }}" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger{{$step_number}}" aria-controls="step-{{$step_number}}">
                                <span class="bs-stepper-circle">{{$step_number}}</span> <!-- Use the step number -->
                            </a>
                            <h6 class="bs-stepper-label d-none d-md-block">{{ array_get($nav_item, 'name') }}</h6>
                        </div>
                    </div>
                    <div class="line d-none d-md-block"></div>

                    @php
                        $step_number++; // Increment the step number
                    @endphp

                @endforeach

            @endif

            <!-- Step 7 -->
            <div class="step{{ request()->is('dashboard/courses/*/publish') ? ' active' : '' }}">
                <div class="d-grid text-center align-items-center">
                    <a type="button" href="{{ route('publish_course', $course->id) }}" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger{{$step_number}}" aria-controls="step-{{$step_number}}">
                        <span class="bs-stepper-circle">{{$step_number}}</span> <!-- Use the step number -->
                    </a>
                    <h6 class="bs-stepper-label d-none d-md-block">{{ tr('Publish')}}</h6>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- For small devices, switch to list style -->
<div class="course-edit-header d-flex justify-content-between align-items-center m-3 d-md-none">
    <ul class="list-unstyled mb-0">
        @php
            $step_number = 1; // Reset step number for small device
        @endphp
        @if(is_array($nav_items) && count($nav_items))
            @foreach($nav_items as $route => $nav_item)
                <li>
                    <a href="{{ route($route, $course->id) }}" class="btn btn-link step-trigger mb-0" role="tab">
                        <span class="bs-stepper-circle">{{$step_number}}</span> <!-- Use the step number -->
                        {{ array_get($nav_item, 'name') }}
                    </a>
                </li>
                @php
                    $step_number++; // Increment the step number
                @endphp
            @endforeach
        @endif
        <li>
            <a href="{{ route('publish_course', $course->id) }}" class="btn btn-link step-trigger mb-0" role="tab">
                <span class="bs-stepper-circle">{{$step_number}}</span> <!-- Use the step number -->
                {{ tr('Publish')}}
            </a>
        </li>
    </ul>
</div>

<div class="course-edit-header d-none d-md-flex justify-content-between align-items-center m-3">
    <div>
        <a href="{{ route('my_courses') }}" class="back-link"><i class="la la-angle-left"></i> {{ tr('Back to courses') }}</a>
        <span class="separator">|</span>
        <strong class="header-course-title ellipsis">{{ $course->title }}</strong>
        <span class="separator">|</span>
        {!! $course->status_html(false) !!}
    </div>
    <div>
        @if($course->status == 1)
            <a href="{{ route('course', $course->slug) }}" class="btn btn-sm btn-primary" target="_blank"><i class="la la-eye"></i> {{ __t('view') }}</a>
        @else
            <a href="{{ route('course', $course->slug) }}" class="btn btn-sm btn-purple" target="_blank"><i class="la la-eye"></i> {{ __t('preview') }}</a>
        @endif
    </div>
</div>

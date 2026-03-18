@php
    $previous = $content->previous;
    $next = $content->next;
    $is_completed = false;
    if ($auth_user && $content->is_completed) {
        $is_completed = true;
    }
@endphp

<div class="piksera-course-player-navigation ">
    <div class="piksera-course-player-navigation__wrapper">

        <div class="piksera-course-player-navigation__prev">
            @if ($previous)
                <a href="{{ route('single_' . $previous->item_type, [$course->slug, $previous->id]) }}" data-id=""
                    id="lecture_previous_button"
                    class="piksera-nav-button piksera-nav-button_type-prev piksera-nav-button_style-secondary"
                    data-query="[]">
                    <span class="piksera-course-player-header__discussions-toggler__title">{{ __t('previous') }}
                        {{ __t($previous->item_type) }}</span>
                </a>
            @endif
        </div>

        @if ($is_completed)
            <div class="piksera-course-player-navigation__status">{{ __t('completed') }}</div>
        @endif


        <div class="piksera-course-player-navigation__next">
            @if ($next)
                @if ($content->item_type === 'lecture')
                    <a href="{{ route('content_complete', $content->id) }}" id="lecture_complete_button"
                        class="piksera-nav-button piksera-nav-button_type-next piksera-nav-button_style-primary"
                        data-query="{&quot;course&quot;:1000,&quot;lesson&quot;:47955}">
                        <span class="piksera-nav-button__title">
                            @if ($is_completed)
                                {{ __t('next') }} {{ $next ? __t($next->item_type) : '' }}
                            @else
                                {{ __t('complete_continue') }}
                            @endif
                        </span>
                    </a>
                @else
                    <a href="{{ route('single_' . $next->item_type, [$course->slug, $next->id]) }}" id="lecture_complete_button"
                        class="piksera-nav-button piksera-nav-button_type-next piksera-nav-button_style-primary"
                        data-query="{&quot;course&quot;:1000,&quot;lesson&quot;:47955}">
                        <span class="piksera-nav-button__title">{{ __t('next') }}
                            {{ $next ? __t($next->item_type) : '' }} </span>
                    </a>
                @endif
            @else
                @if ($content->item_type === 'lecture')
                    @if ($is_completed)
                        <a id="lecture_complete_button"
                            class="piksera-nav-button piksera-nav-button_type-next piksera-nav-button_style-primary"
                            data-query="{&quot;course&quot;:1000,&quot;lesson&quot;:47955}">
                            <span class="piksera-nav-button__title">{{ __t('complete') }}</span>
                        </a>
                    @else
                        <a href="{{ route('content_complete', $content->id) }}" id="lecture_complete_button"
                            class="piksera-nav-button piksera-nav-button_type-next piksera-nav-button_style-primary"
                            data-query="{&quot;course&quot;:1000,&quot;lesson&quot;:47955}">
                            <span class="piksera-nav-button__title">{{ __t('complete') }}</span>
                        </a>
                    @endif
                @else
                    <!-- <a id="lecture_complete_button"
                        class="piksera-nav-button piksera-nav-button_type-next piksera-nav-button_style-primary"
                        data-query="{&quot;course&quot;:1000,&quot;lesson&quot;:47955}">
                        <span class="piksera-nav-button__title">{{ __t('next') }}</span>
                    </a> -->
                @endif
            @endif
        </div>

        @if ($auth_user && $is_completed && !$content->next)
            <form action="{{ route('course_complete', $course->id) }}" method="post" class="ml-auto">
                @csrf
                <button type="submit" href="javascript:;" class="nav-icon-complete-course btn btn-success ml-auto"
                    data-toggle="tooltip" title="{{ __t('complete_course') }}">
                    <i class="la la-check-circle"></i>
                    @if ($auth_user && $auth_user->is_completed_course($course->id))
                        {{ __t('dashboard') }}
                    @else
                        {{ __t('complete_course') }}
                    @endif
                </button>
            </form>
        @endif
    </div>
</div>
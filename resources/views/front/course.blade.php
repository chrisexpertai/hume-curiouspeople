@php
    $courseLayouts = [
        'advace' => 'front.course.advance',
        'classic' => 'front.course.classic',
        'minimal' => 'front.course.minimal',
        'module' => 'front.course.module',
    ];

    $selectedCourseType = get_option("course_settings.course_type");
    $selectedCourseLayout = isset($courseLayouts[$selectedCourseType]) ? $courseLayouts[$selectedCourseType] : $courseLayouts['advace'];
@endphp

@include($selectedCourseLayout)

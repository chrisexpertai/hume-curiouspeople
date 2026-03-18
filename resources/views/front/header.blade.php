@php
    $platformHeaderLayouts = [
        'defaultlms' => 'front.default.header',
        'educationlms' => 'front.education.header',
        'academylms' => 'front.academy.header',
        'courselms' => 'front.course.header',
        // 'universitylms' => 'front.university.header',
        // 'kindergartenlms' => 'front.kindergarten.header',
        // 'landinglms' => 'front.landing.header',
        // 'tutorlms' => 'front.tutor.header',
        // 'schoollms' => 'front.school.header',
        // 'abroadlms' => 'front.abroad.header',
        // 'workshoplms' => 'front.workshop.header',
    ];

    $selectedPlatformHeader = get_option("lms_settings.platform_type");
    $selectedHeaderLayout = isset($platformHeaderLayouts[$selectedPlatformHeader]) ? $platformHeaderLayouts[$selectedPlatformHeader] : $platformHeaderLayouts['defaultlms'];
@endphp

@include($selectedHeaderLayout)

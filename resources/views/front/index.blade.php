@php
    $platformLayouts = [
        'defaultlms' => 'front.default.index',
        'educationlms' => 'front.education.index',
        'academylms' => 'front.academy.index',
        'courselms' => 'front.course.index',
        'universitylms' => 'front.university.index',
        'kindergartenlms' => 'front.kindergarten.index',
        'landinglms' => 'front.landing.index',
        'tutorlms' => 'front.tutor.index',
        'schoollms' => 'front.school.index',
        'abroadlms' => 'front.abroad.index',
        'workshoplms' => 'front.workshop.index',
    ];

    $selectedPlatform = get_option("lms_settings.platform_type");
    $selectedLayout = isset($platformLayouts[$selectedPlatform]) ? $platformLayouts[$selectedPlatform] : $platformLayouts['defaultlms'];
@endphp

@include($selectedLayout)

<?php

function dashboard_menu(){
    $menu = [];

    //$menu['route_name'] = 'value';


    $user = \Illuminate\Support\Facades\Auth::user();



        $menu = apply_filters('dashboard_menu_for_all', [


            'dashboard' => [
                'name' => __t('dashboard'),
                'icon' => 'bi bi-ui-checks-grid fa-fw',
                'is_active' => request()->is('dashboard'),
            ],



        ]);




    if ($user->is_admin){
        $menu['admin'] = [
            'name' => __t('go_to_admin'),
            'icon' => 'bi bi-shield-lock',
        ];
    }



    return apply_filters('dashboard_menu_items', $menu);
}



function instructor_menu() {
    $menu = [];
    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->isInstructor()) {
        $pendingDiscusionBadge = '';
        $pendingDiscussionCount = $user->instructor_discussions->where('replied', 0)->count();

        if ($pendingDiscussionCount) {
            $pendingDiscusionBadge = "<span class='badge badge-warning float-right'> {$pendingDiscussionCount} </span>";
        }

        $menu = apply_filters('dashboard_menu_for_instructor', [
            'create_document' => [
                'name' => __t('create_document'),
                'icon' => 'bi bi-card-heading',
                'is_active' => request()->is('dashboard/create-document'),
            ],
            'create_course' => [
                'name' => __t('create_new_course'),
                'icon' => 'bi bi-calendar2-plus',
                'is_active' => request()->is('dashboard/courses/new'),
            ],
            'my_courses' => [
                'name' => __t('my_courses'),
                'icon' => 'bi bi-collection',
                'is_active' => request()->is('dashboard/my-courses'),
            ],
            // 'earning' => [
            //     'name' => __t('earnings'),
            //     'icon' => 'bi bi-graph-up fa-fw',
            //     'is_active' => request()->is('dashboard/earning*')
            // ],
            // 'withdraw' => [
            //     'name' => __t('withdraw'),
            //     'icon' => 'bi bi-folder-check fa-fw',
            //     'is_active' => request()->is('dashboard/withdraw*'),
            // ],
            'my_courses_reviews' => [
                'name' => __t('my_courses_reviews'),
                'icon' => 'bi bi-stars',
                'is_active' => request()->is('dashboard/my-courses-reviews*'),
            ],
            'courses_has_quiz' => [
                'name' => __t('quiz_attempts'),
                'icon' => 'lbi bi-hr',
                'is_active' => request()->is('dashboard/courses-has-quiz*'),
            ],
            'courses_has_assignments' => [
                'name' => __t('assignments'),
                'icon' => 'bi bi-file-earmark-text',
                'is_active' => request()->is('dashboard/assignments*'),
            ],
            'user.tickets.index' => [
                'name' => __t('my_tickets'),
                'icon' => '<i class=bi bi-chat-square-text fa-fw',
                'is_active' => request()->is('dashboard/tickets'),
            ],
            'instructor_discussions' => [
                'name' => __t('discussions'),
                'icon' => 'bi bi-chat-square-quote',
                'is_active' => request()->is('dashboard/discussions*'),
            ]
        ]);

        // Check if StudentsProgress plugin is active
        $active_plugins = json_decode(get_option('active_plugins'), true);
        if (is_array($active_plugins) && in_array('StudentsProgress', $active_plugins)) {
            // Insert my_students route after my_courses route
            $new_menu = [];
            foreach ($menu as $key => $item) {
                $new_menu[$key] = $item;
                if ($key === 'my_courses') {
                    $new_menu['my_students'] = [
                        'name' => __t('students'),
                        'icon' => 'bi bi-people fa-fw',
                        'is_active' => request()->is('dashboard/students-progress'),
                    ];
                }
            }
            $menu = $new_menu;
        }
    }

    return apply_filters('dashboard_menu_items', $menu);
}



function student_menu(){
    $menu = [];





    $menu = $menu + apply_filters('dashboard_menu_for_users', [



        'enrolled_courses' => [
            'name' => __t('enrolled_courses'),
            'icon' => 'bi bi-basket fa-fw',
            'is_active' => request()->is('dashboard/enrolled-courses*'),
        ],
        // 'my_subscriptions' => [
        //     'name' => __t('my_subscriptions'),
        //     'icon' => '<i class=bi bi-card-checklist fa-fw',
        //     'is_active' => request()->is('dashboard/my-subscriptions'),
        // ],
        // 'wishlist' => [
        //     'name' => __t('wishlist'),
        //     'icon' => 'bi bi-heart',
        //     'is_active' => request()->is('dashboard/wishlist*'),
        // ],
        'reviews_i_wrote' => [
            'name' => __t('reviews'),
            'icon' => 'bi bi-star',
            'is_active' => request()->is('dashboard/reviews-i-wrote*'),
        ],
        'my_quiz_attempts' => [
            'name' => __t('my_quiz_attempts'),
            'icon' => 'bi bi-question-diamond fa-fw',
            'is_active' => request()->is('dashboard/my-quiz-attempts*'),
        ],
        // 'purchase_history' => [
        //     'name' => __t('purchase_history'),
        //     'icon' => 'bi bi-folder-check fa-fw',
        //     'is_active' => request()->is('dashboard/purchases*'),
        // ],
        'profile_settings' => [
            'name' => __t('settings'),
            'icon' => 'bi bi-gear fa-fw',
            'is_active' => request()->is('dashboard/settings*'),
        ],
        // 'delete.profile' => [
        //     'name' => __t('delete_profile'),
        //     'icon' => '<i class=bi bi-trash fa-fw',
        //     'is_active' => request()->is('dashboard/settings/delete-profile'),
        // ],
    ]);






    return apply_filters('dashboard_menu_items', $menu);
}


function course_edit_navs() {
    $nav_items = apply_filters('course_edit_nav_items', [
        'edit_course_information' => [
            'name' => __t('information'),
            'step' => 1,
            'icon' => 'la la-info-circle',
            'is_active' => request()->is('dashboard/courses/*/information'),
        ],
        'edit_course_curriculum' => [
            'name' => __t('curriculum'),
            'step' => 2,
            'icon' => 'la la-th-list',
            'is_active' => request()->is('dashboard/courses/*/curriculum'),
        ],
        'edit_course_pricing' => [
            'name' => __t('pricing'),
            'step' => 4,
            'icon' => 'la la-cart-arrow-down',
            'is_active' => request()->is('dashboard/courses/*/pricing'),
        ],
        'edit_course_drip' => [
            'name' => __t('drip'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('dashboard/courses/*/drip'),
        ],
        'edit_course_additional' => [
            'name' => __t('additional'),
            'step' => 6,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('dashboard/courses/*/additional'),
        ],

        
    ]);

    // Check if MultiInstructor plugin is active
    $active_plugins = json_decode(get_option('active_plugins'), true);
    if (is_array($active_plugins) && in_array('MultiInstructor', $active_plugins)) {
        // Insert edit_course_instructor route after edit_course_curriculum route
        $new_menu = [];
        foreach ($nav_items as $key => $item) {
            $new_menu[$key] = $item;
            if ($key === 'edit_course_curriculum') {
                $new_menu['edit_course_instructor'] = [
                    'name' => __t('instructors'),
                    'step' => 3,
                    'icon' => 'la la-users',
                    'is_active' => request()->is('dashboard/courses/*/instructors'),
                ];
            }
        }
        $nav_items = $new_menu;
    }

    return $nav_items;
}



function piksera_settings(){

    $nav_items = apply_filters('admin_settngs_side_menu', [

        'lms_settings' => [
            'name' => __a('lms_settings'),
            'step' => 2,
            'icon' => 'la la-th-list',
            'is_active' => request()->is('admin/settings/lms-settings'),
            'link' => '/admin/settings/lms-settings',

        ],

        'header_settings' => [
            'name' => __a('menu'),
            'step' => 4,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/menus'),
            'link' => '/admin/settings/menus',

        ],

        'course_settings' => [
            'name' => __a('course_settings'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/course-settings'),
            'link' => '/admin/settings/course-settings',

        ],
        'image_settings' => [
            'name' => __a('image_settings'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/image-settings'),
            'link' => '/admin/settings/image-settings',

        ],
        'marketing_settings' => [
            'name' => __a('marketing_settings'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/marketing-settings'),
            'link' => '/admin/settings/marketing-settings',

        ],
        'maintenance_settings' => [
            'name' => __a('maintenance_settings'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/maintenance-settings'),
            'link' => '/admin/settings/maintenance-settings',

        ],
        'contact_settings' => [
            'name' => __a('social_share'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/social-share'),
            'link' => '/admin/settings/social-share',

        ],
        'social_settings' => [
            'name' => __a('social_login'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/social'),
            'link' => '/admin/settings/social',

        ],
    ]);

    return $nav_items;
}

function admin_settngs_menu(){

    $nav_items = apply_filters('admin_settngs_side_menu', [
        'general_settings' => [
            'name' => __a('general_settings'),
            'step' =>   1,
            'icon' => 'la la-info-circle',
            'is_active' => request()->is('admin/settings/general'),
            'link' => '/admin/settings/general',

        ],
        'payment_settings' => [
            'name' => __a('payment_settings'),
            'step' => 2,
            'icon' => 'la la-th-list',
            'is_active' => request()->is('admin/settings/payment'),
            'link' => '/admin/settings/payment',

        ],
        'payment_gateways' => [
            'name' => __a('payment_gateways'),
            'step' => 3,
            'icon' => 'la la-cart-arrow-down',
            'is_active' => request()->is('admin/gateways'),
            'link' => '/admin/gateways',

        ],
        'withdraw_settings' => [
            'name' => __a('withdraw'),
            'step' => 4,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/withdraw'),
            'link' => '/admin/withdraw',

        ],
        'email_settings' => [
            'name' => __a('mailer'),
            'step' => 4,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/email'),
            'link' => '/admin/email',

        ],
        'invoice_settings' => [
            'name' => __a('invoice_settings'),
            'step' => 5,
            'icon' => 'la la-fill-drip',
            'is_active' => request()->is('admin/settings/invoice-settings'),
            'link' => '/admin/settings/invoice-settings',

        ],

    ]);

    return $nav_items;
}

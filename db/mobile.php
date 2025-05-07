<?php

$addons = [
    'mod_ilsccheckmarket' => [
        'handlers' => [
            'view' => [
                'displaydata' => [
                    'icon' => $CFG->wwwroot . '/mod/ilsccheckmarket/pix/icon.png',
                    'class' => '',
                ],
                'delegate' => 'CoreCourseModuleDelegate',
                'method' => 'mobile_course_page',
            ],
        ],
    ]
];

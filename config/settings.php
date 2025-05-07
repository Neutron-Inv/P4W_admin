<?php

return [
    'user' => [
        ['name' => 'gender', 'value' => '', 'context' => 'user'],
        ['name' => 'contact', 'value' => '', 'context' => 'user'],
        ['name' => 'address', 'value' => '', 'context' => 'user'],
        ['name' => 'date_of_birth', 'value' => '', 'context' => 'user'],
    ],
    'app' => [
        ['name' => 'company_name', 'value' => env('APP_NAME', 'Jobpoint'), 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_logo', 'value' => '/images/logo.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_icon', 'value' => '/images/icon.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_banner', 'value' => '/images/banner.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'job_post_cover', 'value' => '/images/job_post_cover.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'language', 'value' => 'en', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'layout', 'value' => 'ltr', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'date_format', 'value' => 'd-m-Y', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_format', 'value' => 'h', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_zone', 'value' => 'Asia/Dhaka', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_symbol', 'value' => '$', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'decimal_separator', 'value' => '.', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'thousand_separator', 'value' => ',', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'number_of_decimal', 'value' => '2', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_position', 'value' => 'prefix_with_space', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        [
            'name' => 'application_form',
            'value' => '[{"id":"64adc53a-4cdf-4c4e-82e5-b3bfc6b990f9","is_visible":true,"title":"Basic Information","items":[{"id":"dac64396-e2a6-4c1b-bf30-ad864f3c7ff0","fields":[{"title":"First name","type":"text","options":[],"required":true,"is_visible":true,"actions":{"edit":false,"delete":false,"move":false}},{"title":"Last name","type":"text","options":[],"required":true,"is_visible":true,"actions":{"edit":false,"delete":false,"move":false}},{"title":"Email","type":"email","options":[],"required":true,"is_visible":true,"actions":{"edit":false,"delete":false,"move":false}},{"title":"Gender","type":"radio","options":["Male","Female"],"required":false,"is_visible":true,"actions":{"edit":false,"delete":false,"move":false}},{"title":"Date of birth","type":"date","options":[],"required":false,"is_visible":true,"actions":{"edit":false,"delete":false,"move":false}},{"title":"Phone","type":"tel-input","options":[],"required":false,"is_visible":true,"actions":{"edit":false,"delete":false,"move":false}}],"actions":{"edit":true,"delete":false,"move":false}}],"actions":{"edit":true,"delete":false,"move":false,"fixed":true},"key":"basic_information"},{"id":"d6e5f546-73f8-45d4-96b5-fdd6e912ecad","is_visible":true,"title":"Contact Details","items":[{"id":"40cf036c-6e70-4a5b-943c-e9df43cdfd46","fields":[{"title":"Address","type":"textarea","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true}},{"title":"Linkedin","type":"text","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true}},{"title":"Twitter","type":"text","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}},{"id":"7d3e3733-15e0-440e-9e90-794f1e98b45c","is_visible":true,"title":"Education & Experience","items":[{"id":"9a3f0038-a6f5-4575-8c53-adc5b280cb22","fields":[{"title":"Education","type":"custom-form","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true},"fields":[{"title":"Level","type":"text","options":[],"required":false},{"title":"Institute","type":"text","options":[],"required":false},{"title":"CGPA","type":"text","options":[],"required":false}],"duplicate":true},{"title":"Work Experience","type":"custom-form","options":[],"required":false,"is_visible":true,"duplicate":true,"actions":{"edit":true,"delete":true,"move":true},"fields":[{"title":"Start","type":"date","options":[],"required":false},{"title":"End","type":"date","options":[],"required":false},{"title":"Designation","type":"text","options":[],"required":false}]}],"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}},{"id":"c4e419a6-b77e-4bc7-ae9a-a33d3363589c","is_visible":true,"title":"Question","items":[{"id":"7b01452c-9e48-4f81-a270-8f17f609d48e","fields":[{"title":"Write something about you...","type":"textarea","options":[],"required":true,"actions":{"edit":true,"delete":true,"move":true},"is_visible":true}],"actions":{"edit":true,"delete":true,"move":true}},{"id":"d76873c5-d2d0-4182-a062-ddb82b56a57e","fields":[{"title":"Why you think you are good for this job?","type":"textarea","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}},{"id":"42c8b816-0bd2-49a5-aa6b-d41f5cb05bc5","is_visible":true,"title":"Assignment","items":[{"id":"a83f1a25-e9eb-4f0b-9414-5af7449adc5f","fields":[{"title":"Write your assignment question","type":"textarea","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}},{"id":"8b6ed18d-f67f-4cc5-bdf5-5a151a1f1e57","is_visible":true,"title":"Resume","items":[{"id":"48de9f8e-f3b2-4f13-83e2-06d4e69a3e78","fields":[{"title":"Upload your resume here","type":"dropzone","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}}]',
            'context' => 'app',
            'autoload' => 0,
            'public' => 1
        ],
        [
            'name' => 'career_page',
            'value' => '{
                "job_post_settings": {
                    "content": {
                        "title": "Come join with us",
                        "subtitle": "A fast growing software company build web apps",
                        "details": "Software Company - Dhaka",
                        "bodySection": [
                            {
                                "headings": "About Us",
                                "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
                            },
                            {
                                "headings": "Service we provide",
                                "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
                            }
                        ],
                        "hero": true,
                        "job_post_cover": "/images/job_post_cover.png"
                    },
                    "pageBlocks": {
                        "defaultView": {
                            "header": true,
                            "body": true,
                            "footer": true,
                            "logo": true
                        },
                        "mobileView": {
                            "header": true,
                            "body": true,
                            "footer": true,
                            "logo": true
                        }
                    },
                    "pageStyle": {
                        "defaultView": [
                            {
                                "name": "Title",
                                "key": "title",
                                "fontSize": 50,
                                "fontWeight": 700,
                                "letterSpacing": 1,
                                "color": "#313131"
                            },
                            {
                                "name": "Subtitle",
                                "key": "sub-title",
                                "fontSize": 30,
                                "fontWeight": 300,
                                "letterSpacing": 1,
                                "color": "#afb1b6"
                            },
                            {
                                "name": "Details",
                                "key": "details",
                                "fontSize": 20,
                                "fontWeight": 300,
                                "letterSpacing": 1,
                                "color": "#3758b3"
                            },
                            {
                                "name": "Headings",
                                "key": "headings",
                                "fontSize": 27,
                                "fontWeight": 600,
                                "letterSpacing": 0,
                                "color": "#313131"
                            },
                            {
                                "name": "Description",
                                "key": "description",
                                "fontSize": 19,
                                "fontWeight": 300,
                                "letterSpacing": 0,
                                "color": "#313131"
                            }
                        ],
                        "mobileView": [
                            {
                                "name": "Title",
                                "key": "title",
                                "fontSize": 40,
                                "fontWeight": 700,
                                "letterSpacing": 1,
                                "color": "#313131"
                            },
                            {
                                "name": "Subtitle",
                                "key": "sub-title",
                                "fontSize": 25,
                                "fontWeight": 300,
                                "letterSpacing": 1,
                                "color": "#afb1b6"
                            },
                            {
                                "name": "Details",
                                "key": "details",
                                "fontSize": 16,
                                "fontWeight": 300,
                                "letterSpacing": 1,
                                "color": "#3758b3"
                            },
                            {
                                "name": "Headings",
                                "key": "headings",
                                "fontSize": 20,
                                "fontWeight": 600,
                                "letterSpacing": 0,
                                "color": "#313131"
                            },
                            {
                                "name": "Description",
                                "key": "description",
                                "fontSize": 18,
                                "fontWeight": 300,
                                "letterSpacing": 0,
                                "color": "#313131"
                            }
                        ]
                    }
                }
            }',
            'context' => 'app',
            'autoload' => 0,
            'public' => 1
        ]
    ],
    'brand' => [
        ['name' => 'avatar', 'value' => null, 'context' => 'brand'],
        ['name' => 'address', 'value' => '', 'context' => 'brand'],
    ],
    'context' => [
        'app',
        'campaign',
        'list',
        'user',
        'segment',
        'subscriber',
        'brand',
        'role',
        'template'
    ],
    'time_format' => [
        'h',
        'H'
    ],
    'layouts' => [
        'ltr',
        'rtl'
    ],
    'currency_position' => [
        'prefix_only',
        'prefix_with_space',
        'suffix_only',
        'suffix_with_space'
    ],
    'amazon_ses' => [
        'hostname' => '',
        'access_key_id' => '',
        'secret_access_key' => '',
    ],
    'mailgun' => [
        'domain_name' => '',
        'api_key' => '',
        'webhook_key' => ''
    ],
    'mail_configs' => [
        'context' => '',
        'from_email' => '',
        'from_name' => ''
    ],
    'date_format' => [
        'd-m-Y',
        'm-d-Y',
        'Y-m-d',
        'm/d/Y',
        'd/m/Y',
        'Y/m/d',
        'm.d.Y',
        'd.m.Y',
        'Y.m.d'
    ],

    'decimal_separator' => [
        '.',
        ','
    ],

    'thousand_separator' => [
        '.',
        ',',
        ' '
    ],
    'number_of_decimal' => [
        '0',
        '2'
    ],
    'supported_mail_services' => [
        'amazon_ses' => 'Amazon SES',
        'mailgun' => 'Mailgun'
    ],
    'corn-job-context' => 'corn-job',
    'brand_default_prefix' => [
        'amazon_ses' => 'brand_default_amazon_ses',
        'mailgun' => 'brand_default_mailgun',
        'privacy' => 'brand_default_privacy',
        'notification' => 'brand_default_notification',
    ],
];

<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => 'plugins://jscomments/jscomments.yaml',
    'modified' => 1456005280,
    'data' => [
        'enabled' => true,
        'providers' => [
            'disqus' => [
                'shortname' => '',
                'default_lang' => 'en'
            ],
            'intensedebate' => [
                'acct' => ''
            ],
            'facebook' => [
                'appId' => '',
                'lang' => 'en_US',
                'num_posts' => 5,
                'colorscheme' => 'light',
                'order_by' => 'social',
                'width' => '100%'
            ],
            'muut' => [
                'forum' => '',
                'channel' => 'General',
                'show_online' => false,
                'show_title' => false,
                'upload' => false,
                'share' => true,
                'widget' => false,
                'lang' => 'en'
            ]
        ]
    ]
];

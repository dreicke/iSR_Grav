<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => '/Applications/MAMP/htdocs/iSR_Grav/grav-admin/user/config/plugins/email.yaml',
    'modified' => 1456026302,
    'data' => [
        'enabled' => true,
        'from' => 'deicke2@gmail.com',
        'from_name' => 'iSchool Review',
        'to' => 'deicke2@gmail.com',
        'to_name' => 'David',
        'mailer' => [
            'engine' => 'mail',
            'smtp' => [
                'server' => 'localhost',
                'port' => 25,
                'encryption' => 'none'
            ],
            'sendmail' => [
                'bin' => '/usr/sbin/sendmail'
            ]
        ],
        'content_type' => 'text/html'
    ]
];

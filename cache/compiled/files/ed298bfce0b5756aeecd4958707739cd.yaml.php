<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => '/Applications/MAMP/htdocs/iSR_Grav/grav-admin/user/plugins/blog-injector/blueprints.yaml',
    'modified' => 1456533236,
    'data' => [
        'name' => 'BlogInjector',
        'version' => '1.1.8',
        'description' => 'BlogInjector is a Grav plugin that makes available the functionalities to add a blog to each Grav theme',
        'icon' => 'file-text-o',
        'author' => [
            'name' => 'Giansimon Diblas',
            'email' => 'info@diblas.net',
            'url' => 'http://diblas.net'
        ],
        'homepage' => 'http://diblas.net/plugins/blog-grav-cms-plugin-adds-blog-functionalities-to-each-grav-theme',
        'demo' => '-',
        'keywords' => 'blog, theme',
        'bugs' => 'https://github.com/giansi/grav-plugin-blog/issues',
        'license' => 'MIT',
        'dependencies' => [
            0 => 'simplesearch',
            1 => 'feed',
            2 => 'relatedpages',
            3 => 'pagination',
            4 => 'taxonomylist',
            5 => 'archives',
            6 => 'breadcrumbs'
        ],
        'form' => [
            'validation' => 'strict',
            'fields' => [
                'enabled' => [
                    'type' => 'toggle',
                    'label' => 'Plugin status',
                    'highlight' => 1,
                    'default' => 0,
                    'options' => [
                        1 => 'Enabled',
                        0 => 'Disabled'
                    ],
                    'validate' => [
                        'type' => 'bool'
                    ]
                ],
                'framework' => [
                    'type' => 'select',
                    'label' => 'Framework',
                    'size' => 'small',
                    'default' => 'pure',
                    'options' => [
                        'pure' => 'pure',
                        'bootstrap' => 'bootstrap'
                    ],
                    'help' => 'Choose the framework to use'
                ],
                'add_default_css' => [
                    'type' => 'toggle',
                    'label' => 'Add default stylesheet',
                    'highlight' => 1,
                    'default' => 1,
                    'options' => [
                        1 => 'Enabled',
                        0 => 'Disabled'
                    ],
                    'validate' => [
                        'type' => 'bool'
                    ],
                    'help' => 'Automatically adds the included blog stylesheet according to the selected framework, when true'
                ],
                'add_framework_assets' => [
                    'type' => 'toggle',
                    'label' => 'Add framework assets',
                    'highlight' => 1,
                    'default' => 1,
                    'options' => [
                        1 => 'Enabled',
                        0 => 'Disabled'
                    ],
                    'validate' => [
                        'type' => 'bool'
                    ],
                    'help' => 'Automatically adds selected framework assets, when true'
                ]
            ]
        ]
    ]
];

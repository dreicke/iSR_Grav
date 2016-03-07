<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => '/Applications/MAMP/htdocs/iSR_Grav/user/config/site.yaml',
    'modified' => 1456604903,
    'data' => [
        'title' => 'iSchool Review',
        'author' => [
            'name' => 'iSchool Review',
            'email' => 'ischoolreview@ischool.berkeley.edu'
        ],
        'taxonomies' => [
            0 => 'category',
            1 => 'tag',
            2 => 'author'
        ],
        'metadata' => [
            'description' => 'An online publication showcasing the best information-centric work from professional graduate programs worldwide'
        ],
        'summary' => [
            'enabled' => true,
            'format' => 'short',
            'size' => 300,
            'delimiter' => '==='
        ],
        'blog' => [
            'route' => '/blog'
        ],
        'visualization' => [
            'd3js' => true,
            'topojson' => false
        ],
        'logo' => 'user/images/isrlogo-wide.png'
    ]
];

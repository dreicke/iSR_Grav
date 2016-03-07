<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => '/Applications/MAMP/htdocs/iSR_Grav/user/plugins/jscomments/blueprints/jscomments.yaml',
    'modified' => 1456005280,
    'data' => [
        'form' => [
            'fields' => [
                'tabs' => [
                    'fields' => [
                        'options' => [
                            'type' => 'tab',
                            'fields' => [
                                'jscomments' => [
                                    'type' => 'section',
                                    'title' => 'PLUGIN_JSCOMMENTS.ADMIN.BLUEPRINTS.TAB.TITLE',
                                    'underline' => true,
                                    'fields' => [
                                        'header.jscomments.provider' => [
                                            'type' => 'select',
                                            'label' => 'PLUGIN_JSCOMMENTS.ADMIN.BLUEPRINTS.TAB.FIELDS.PROVIDER.LABEL',
                                            'default' => '',
                                            'options' => [
                                                '' => 'PLUGIN_JSCOMMENTS.ADMIN.BLUEPRINTS.GLOBAL.DISABLED',
                                                'disqus' => 'Disqus',
                                                'intensedebate' => 'IntenseDebate',
                                                'facebook' => 'Facebook',
                                                'muut' => 'Muut'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

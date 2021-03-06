<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => '/Applications/MAMP/htdocs/iSR_Grav/user/plugins/simple_form/blueprints/simple_form.yaml',
    'modified' => 1456022573,
    'data' => [
        'form' => [
            'fields' => [
                'tabs' => [
                    'fields' => [
                        'options' => [
                            'type' => 'tab',
                            'fields' => [
                                'simple_form' => [
                                    'type' => 'section',
                                    'title' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.TITLE',
                                    'underline' => true,
                                    'fields' => [
                                        'header.simple_form.token' => [
                                            'type' => 'text',
                                            'label' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.TOKEN.LABEL',
                                            'placeholder' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.TOKEN.PLACEHOLDER',
                                            'help' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.TOKEN.HELP'
                                        ],
                                        'header.simple_form.template_file' => [
                                            'type' => 'select',
                                            'classes' => 'fancy',
                                            'label' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.TEMPLATE_FILE.LABEL',
                                            '@data-options' => '\\Grav\\Plugin\\Simple_FormPlugin::getTemplatesList',
                                            'default' => '',
                                            'options' => [
                                                '' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.TEMPLATE_FILE.PLACEHOLDER'
                                            ]
                                        ],
                                        'header.simple_form.redirect_to' => [
                                            'type' => 'select',
                                            'label' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.REDIRECT_TO.LABEL',
                                            'classes' => 'fancy',
                                            '@data-options' => '\\Grav\\Plugin\\Simple_FormPlugin::getPagesList',
                                            'default' => '',
                                            'options' => [
                                                '' => 'PLUGIN_SIMPLE_FORM.ADMIN.BLUEPRINTS.TAB.FIELDS.REDIRECT_TO.PLACEHOLDER'
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
